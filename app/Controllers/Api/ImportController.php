<?php

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Jobs\ArchiveShipmentsAndAssetsJob;
use App\Jobs\PruneAssetsJob;
use App\Data\ApiResponse;
use App\Data\Models\Asset;
use App\Data\Models\Shipment;

use DateTime;
use Exception;
use SimpleXMLElement;

use Illuminate\Http\Request;

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
        $contentType = $request->headers->get('CONTENT_TYPE');

        if (($contentType != 'application/xml') && ($contentType != 'text/plain')) {
            return $this->returnError($contentType . ' ' . ApiResponse::DESCRIPTION_FORMAT_INCORRECT);
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
            $rootElementName = $xml->getName();

            switch ($rootElementName) {
                case 'Lot_Summary':
                    try {
                        $this->processLotSummary($xml);
                        return $this->returnSuccess();
                    } catch (Exception $e) {
                        return $this->returnError(ApiResponse::DESCRIPTION_IMPORT_UNSUCCESSFUL);
                    }
                    break;
                case 'Lot_Control':
                    try {
                        $this->processLotControl($xml);
                        $this->pruneAssets();
                        return $this->returnSuccess();
                    }
                    catch (Exception $e) {
                        return $this->returnError(ApiResponse::DESCRIPTION_IMPORT_UNSUCCESSFUL);
                    }
                    break;
                case 'Assets_Detail':
                    try {
                        $this->processAssetsDetail($xml);
                        $this->pruneAssets();
                        return $this->returnSuccess();
                    }
                    catch (Exception $e) {
                        return $this->returnError(ApiResponse::DESCRIPTION_IMPORT_UNSUCCESSFUL);
                    }
                    break;
                default:
                    return $this->returnError(ApiResponse::DESCRIPTION_FORMAT_INCORRECT);
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

    private function pruneAssets() {
        $this->dispatch(new PruneAssetsJob());
    }

    private function archiveShipmentsAndAssets() {
        $this->dispatch(new ArchiveShipmentsAndAssetsJob());
    }

    private function returnSuccess() {
        return response()->json(new ApiResponse(ApiResponse::STATUS_OK, null, new DateTime()), 200);
    }

    private function returnError(string $description) {
        return response()->json(new ApiResponse(ApiResponse::STATUS_ERROR, $description, new DateTime()), 400);
    }

}