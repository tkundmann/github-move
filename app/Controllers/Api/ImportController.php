<?php

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Jobs\ArchiveShipmentsAndAssetsJob;
use App\Jobs\PruneAssetsJob;
use App\Data\ApiResponse;
use App\Data\Models\Asset;
use App\Data\Models\Shipment;
use App\Data\Models\TrackingNumber;

use DateTime;
use Exception;
use SimpleXMLElement;
use Storage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    /**
     * Create a new controller instance.
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function process(Request $request) {
        // #1 method - file from multipart/form-data, part named 'xml'
        // $file = Input::file('xml');
        // $splfile = $file->openFile();
        // $content = $splfile->fread($splfile->getSize());

        // #2 method - plain content from request (application/xml or text/plain)
        $content = $request->getContent();
        $contentType = strtolower($request->headers->get('CONTENT_TYPE'));

        Log::info('Content-Type: ' . $contentType);

        if (strpos($contentType,'text/plain') === false && strpos($contentType,'text/xml') === false && strpos($contentType,'application/xml') === false) {

            return $this->returnError(ApiResponse::DESCRIPTION_FORMAT_INCORRECT);
        }
        if (strlen($content) == 0) {
            return $this->returnError(ApiResponse::DESCRIPTION_FORMAT_INCORRECT);
        }

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);

        if ($xml == false) {
            return $this->returnError(ApiResponse::DESCRIPTION_FORMAT_INCORRECT);
        }
        else {
            $importSuccessful = false;
            $rootElementName = $xml->getName();

            switch ($rootElementName) {
                case 'Lot_Summary':
                    try {
                        $this->processLotSummary($xml);
                        $importSuccessful = true;
                    } catch (Exception $e) {
                        $importSuccessful = false;
                    }
                    break;
                case 'Lot_Control':
                    try {
                        $this->processLotControl($xml);
                        $this->pruneAssets();
                        $importSuccessful = true;
                    }
                    catch (Exception $e) {
                        $importSuccessful = false;
                    }
                    break;
                case 'Assets_Detail':
                    try {
                        $this->processAssetsDetail($xml);
                        $this->pruneAssets();
                        $importSuccessful = true;
                    }
                    catch (Exception $e) {
                        $importSuccessful = false;
                    }
                    break;
                case 'Tracking_Detail':
                    try {
                        $this->processTrackingDetail($xml);
                        $importSuccessful = true;
                    }
                    catch (Exception $e) {
                        $importSuccessful = false;
                    }
                    break;
                default:
                    return $this->returnError(ApiResponse::DESCRIPTION_FORMAT_INCORRECT);
            }

            // Store raw XML content in _importing_archive S3 directory
            $t = microtime(true);
            $micro = sprintf("%06d",($t - floor($t)) * 1000000);
            $d = new DateTime( date('Y-m-d H:i:s.'.$micro, $t) );
            $archiveFileName = $rootElementName . '_' . $d->format("Ymd_His_u") . '.xml';

            Storage::cloud()->put('/_importing_archive/' . $rootElementName . '/' . $archiveFileName, $content);

            if ($importSuccessful) {
                return $this->returnSuccess();
            }
            else {
                return $this->returnError(ApiResponse::DESCRIPTION_IMPORT_UNSUCCESSFUL);
            }
        }
    }

    private function processLotSummary(SimpleXMLElement $xml) {
        foreach ($xml->Lot as $lot) {
            $shipment = Shipment::createFromLotSummary($lot);
            $existingShipment = Shipment::where('lotNumber', $shipment->lotNumber)->first();

            if ($existingShipment) {
                $attributes = $shipment->getAttributes();
                $attributesUpdated = false;

                foreach ($attributes as $attribute => $value) {
                    if ($value != $existingShipment[$attribute]) {
                        $attributesUpdated = true;
                    }
                    $existingShipment[$attribute] = $value;
                }

                if ($attributesUpdated) {
                    $existingShipment->save();
                }
            }
            else {
                $shipment->save();
            }
        }
    }

    private function processLotControl(SimpleXMLElement $xml) {
        $shipment = Shipment::createFromLotControl($xml);
        $existingShipment = Shipment::where('lotNumber', $shipment->lotNumber)->first();

        if ($existingShipment) {
            $attributes = $shipment->getAttributes();
            $attributesUpdated = false;

            foreach ($attributes as $attribute => $value) {
                if ($value != $existingShipment[$attribute]) {
                    $attributesUpdated = true;
                }
                $existingShipment[$attribute] = $value;
            }

            if ($attributesUpdated) {
                $existingShipment->save();
            }
        }
        else {
            $shipment->save();
        }

        foreach ($xml->LOT_DETAIL as $assetDetail) {
            $asset = Asset::createFromLotControl($assetDetail, $xml);
            $existingAsset = Asset::where('barcodeNumber', $asset->barcodeNumber)->first();

            if ($existingAsset) {
                $attributes = $asset->getAttributes();
                $attributesUpdated = false;

                foreach ($attributes as $attribute => $value) {
                    if ($value != $existingAsset[$attribute]) {
                        $attributesUpdated = true;
                    }
                    $existingAsset[$attribute] = $value;
                }

                if ($attributesUpdated) {
                    $existingAsset->save();
                }
            }
            else {
                $asset->save();
            }
        }
    }

    private function processAssetsDetail(SimpleXMLElement $xml) {
        foreach ($xml->Assets as $assetDetail) {
            $asset = Asset::createFromAssetsDetail($assetDetail);
            $existingAsset = Asset::where('barcodeNumber', $asset->barcodeNumber)->first();

            if ($existingAsset) {
                $attributes = $asset->getAttributes();
                $attributesUpdated = false;

                foreach ($attributes as $attribute => $value) {
                    if ($value != $existingAsset[$attribute]) {
                        $attributesUpdated = true;
                    }
                    $existingAsset[$attribute] = $value;
                }

                if ($attributesUpdated) {
                    $existingAsset->save();
                }
            }
            else {
                $asset->save();
            }
        }
    }

    private function processTrackingDetail(SimpleXMLElement $xml) {
        foreach ($xml->Tracking as $trackingNumber) {
            $trackingNumber = TrackingNumber::createFromTrackingDetail($trackingNumber);
            $existingTrackingNumber = TrackingNumber::where('entryNumber', $trackingNumber->entryNumber)->first();

            if ($existingTrackingNumber) {
                $attributes = $trackingNumber->getAttributes();
                $attributesUpdated = false;

                foreach ($attributes as $attribute => $value) {
                    if ($value != $existingTrackingNumber[$attribute]) {
                        $attributesUpdated = true;
                    }
                    $existingTrackingNumber[$attribute] = $value;
                }

                if ($attributesUpdated) {
                    $existingTrackingNumber->save();
                }
            }
            else {
                $trackingNumber->save();
            }
        }
    }

    private function pruneAssets() {
        $this->dispatch(new PruneAssetsJob());
    }

    private function archiveShipmentsAndAssets() {
        $this->dispatch(new ArchiveShipmentsAndAssetsJob());
    }

    private function archiveTrackingNumbers() {
        $this->dispatch(new ArchiveTrackingNumbersJob());
    }

    private function returnSuccess() {
        return response()->json(new ApiResponse(ApiResponse::STATUS_OK, null, new DateTime()), 200);
    }

    private function returnError($description) {
        return response()->json(new ApiResponse(ApiResponse::STATUS_ERROR, $description, new DateTime()), 400);
    }

}