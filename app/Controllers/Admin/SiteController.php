<?php

namespace App\Controllers\Admin;

use App\Controllers\ContextController;
use App\Data\Constants;
use App\Data\Models\Feature;
use App\Data\Models\LotNumber;
use App\Data\Models\Role;
use App\Data\Models\Site;
use App\Data\Models\VendorClient;
use App\Helpers\StringHelper;
use Illuminate\Http\Request;
use DB;
use Input;
use Storage;
use Validator;

class SiteController extends ContextController
{
    const RESULTS_PER_PAGE = 50;

    /**
     * Create a new controller instance.
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('auth');
        $this->middleware('context.permissions:' . $this->context);
        $this->middleware('role:' . Role::ADMIN . '|' . Role::SUPERADMIN);
    }

    public function getList()
    {
        $query = Site::query();

        if (!empty(Input::get('title'))) {
            $query->where('title', 'like', '%' . StringHelper::addSlashes(trim(Input::get('title'))) . '%');
        }

        if (!empty(Input::get('type'))) {
            $type = trim(Input::get('type'));

            if ($type != 'all') {
                $query->where('type', $type);
            }
        }

        $query = $query->sortable(['title' => 'asc']);
        $sites = $query->paginate(self::RESULTS_PER_PAGE);

        return view('admin.siteList', [
            'sites' => $sites,
            'order' => $query->getQuery()->orders
        ]);
    }

    public function getCreate()
    {
        return view('admin.siteCreate');
    }

    public function postCreate()
    {
        $rules = array(
            'type' => 'required',
            'title' => 'required',
            'code' => 'required|regex:/^[A-Za-z0-9\-\_\']+$/|unique:site,code',
            'logo' => 'required',
        );

        if (Input::get('logo') == 'custom') {
            $rules['file'] = 'required';
        }

        $messages = array(
            'file.required' => trans('admin.site.create.custom_logo_required')
        );

        $validator = Validator::make(Input::all(), $rules, $messages);
        $validator->after(function($validator) {
            if (Input::get('logo')) {
                $logo = trim(Input::get('logo'));

                if ($logo == 'custom') {
                    if (Input::file('file')) {
                        $uploadedFile = Input::file('file');

                        imagepng(imagecreatefromstring(file_get_contents($uploadedFile)), storage_path('app/public/logo.png'));
                        $imageSize = getimagesize(storage_path('app/public/logo.png'));

                        $imageWidth = $imageSize[0];
                        $imageHeight = $imageSize[1];

                        if (($imageWidth < 0) || ($imageWidth > 400) || ($imageHeight < 0) || ($imageHeight > 90)) {
                            $validator->errors()->add('file', 'Custom Logo maximum dimension is 400 x 90.');
                        }
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->route('admin.site.create')->withInput(Input::all())->withErrors($validator);
        }
        else {
            $site = new Site();
            $site->type = trim(Input::get('type'));
            $site->title = trim(Input::get('title'));
            $site->code = trim(Input::get('code'));

            if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $site->code)) {
                Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $site->code);
            }

            $logo = trim(Input::get('logo'));

            if ($logo == 'Insight') {
                $site->logoUrl = '/img/logo/logo-insight.png';
            }
            else if ($logo == 'Sipi') {
                $site->logoUrl = '/img/logo/logo-sipi.png';
            }
            else if ($logo == 'custom') {
                Storage::cloud()->put(Constants::UPLOAD_DIRECTORY . $site->code . '/' . 'logo.png', file_get_contents(storage_path('app/public/logo.png')));
                unlink(storage_path('app/public/logo.png'));

                $url = Storage::cloud()->url(Constants::UPLOAD_DIRECTORY . $site->code . '/' . 'logo.png');
                $site->logoUrl = $url;
            }

            if (Input::get('color')) {
                $site->color = trim(Input::get('color'));
            }

            $site->save();

            $featureHasPages = Feature::where('name', '=', Feature::HAS_PAGES)->first();
            $featureHasSettlements = Feature::where('name', '=', Feature::HAS_SETTLEMENTS)->first();
            $featureHasCertificates = Feature::where('name', '=', Feature::HAS_CERTIFICATES)->first();
            $featureSettlementAsFile = Feature::where('name', '=', Feature::SETTLEMENT_AS_FILE)->first();
            $featureCertificateOfDataWipeNumberAsFile = Feature::where('name', '=', Feature::CERTIFICATE_OF_DATA_WIPE_NUMBER_AS_FILE)->first();
            $featureCertificateOfDestructionAsFile = Feature::where('name', '=', Feature::CERTIFICATE_OF_DESTRUCTION_NUMBER_AS_FILE)->first();
            $featureCustomProductFamilyForCertificateOfDataWipeNumber = Feature::where('name', '=', Feature::CUSTOM_PRODUCT_FAMILY_FOR_CERTIFICATE_OF_DATA_WIPE_NUMBER)->first();
            $featureCustomStatusForCertificateOfDestructionNumber = Feature::where('name', '=', Feature::CUSTOM_STATUS_FOR_CERTIFICATE_OF_DESTRUCTION_NUMBER)->first();

            $site->features()->attach([
                $featureHasPages->id,
                $featureHasSettlements->id,
                $featureHasCertificates->id,
                $featureSettlementAsFile->id,
                $featureCertificateOfDataWipeNumberAsFile->id,
                $featureCertificateOfDestructionAsFile->id,
                $featureCustomProductFamilyForCertificateOfDataWipeNumber->id,
                $featureCustomStatusForCertificateOfDestructionNumber->id
            ]);

            return redirect()->route('admin.site.list')->with('success', trans('admin.site.create.site_created'));
        }
    }

    public function getEdit($context = null, $id)
    {
        $site = Site::find($id);

        if (!$site) {
            return redirect()->route('admin.site.list')->with('fail', trans('admin.site.edit.not_exist'));
        }

        return view('admin.siteEdit')->with([
            'currentSite' => $site
        ]);
    }

    public function postEdit($context, $id)
    {
        $site = Site::find($id);

        if (!$site) {
            return redirect()->route('admin.site.list')->with('fail', trans('admin.site.edit.not_exist'));
        }

        $rules = array(
            'type' => 'required',
            'title' => 'required',
            'code' => 'required|regex:/^[A-Za-z0-9\-\_\']+$/|unique:site,code,' . $site->id
        );

        if (Input::get('logo_change')) {
            $rules['logo'] = 'required';

            if (Input::get('logo') == 'custom') {
                $rules['file'] = 'required';
            }
        }

        $messages = array(
            'file.required' => trans('admin.site.create.custom_logo_required')
        );

        $validator = Validator::make(Input::all(), $rules, $messages);
        $validator->after(function($validator) {
            if (Input::get('logo_change') && Input::get('logo')) {
                $logo = trim(Input::get('logo'));

                if ($logo == 'custom') {
                    if (Input::file('file')) {
                        $uploadedFile = Input::file('file');

                        imagepng(imagecreatefromstring(file_get_contents($uploadedFile)), storage_path('app/public/logo.png'));
                        $imageSize = getimagesize(storage_path('app/public/logo.png'));

                        $imageWidth = $imageSize[0];
                        $imageHeight = $imageSize[1];

                        if (($imageWidth < 0) || ($imageWidth > 400) || ($imageHeight < 0) || ($imageHeight > 90)) {
                            $validator->errors()->add('file', 'Custom Logo maximum dimension is 400 x 90.');
                        }
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->route('admin.site.edit', ['id' => $id])->withInput(Input::all())->withErrors($validator);
        }
        else {
            $site->type = trim(Input::get('type'));
            $site->title = trim(Input::get('title'));

            $oldCode = $site->code;
            $newCode = trim(Input::get('code'));

            if ($oldCode != $newCode) {
                if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $newCode)) {
                    Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $newCode);
                }

                $files = Storage::cloud()->allFiles(Constants::UPLOAD_DIRECTORY . $oldCode);

                foreach ($files as $file) {
                    $target = str_replace(Constants::UPLOAD_DIRECTORY . $oldCode, '', $file);
                    Storage::cloud()->move($file, Constants::UPLOAD_DIRECTORY . $newCode . $target);
                }

                if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $oldCode)) {
                    Storage::cloud()->deleteDirectory(Constants::UPLOAD_DIRECTORY . $oldCode);
                }

                // rewrite

                $site->logoUrl = str_replace($oldCode, $newCode, $site->logoUrl);

                foreach ($site->pages as $page) {
                    foreach ($page->files as $file) {
                        $file->url = str_replace($oldCode, $newCode, $file->url);
                        $file->save();
                    }
                }
            }

            $site->code = $newCode;

            if (Input::get('logo_change') && Input::get('logo')) {
                $logo = trim(Input::get('logo'));

                if ($logo == 'Insight') {
                    $site->logoUrl = '/img/logo/logo-insight.png';

                    if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $site->code . '/' . 'logo.png')) {
                        Storage::cloud()->delete(Constants::UPLOAD_DIRECTORY . $site->code . '/' . 'logo.png');
                    }
                }
                else if ($logo == 'Sipi') {
                    $site->logoUrl = '/img/logo/logo-sipi.png';

                    if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $site->code . '/' . 'logo.png')) {
                        Storage::cloud()->delete(Constants::UPLOAD_DIRECTORY . $site->code . '/' . 'logo.png');
                    }
                }
                else if ($logo == 'custom') {
                    Storage::cloud()->put(Constants::UPLOAD_DIRECTORY . $site->code . '/' . 'logo.png', file_get_contents(storage_path('app/public/logo.png')));
                    unlink(storage_path('app/public/logo.png'));

                    $url = Storage::cloud()->url(Constants::UPLOAD_DIRECTORY . $site->code . '/' . 'logo.png');
                    $site->logoUrl = $url;
                }
            }

            if (Input::get('color')) {
                $site->color = trim(Input::get('color'));
            }

            $site->save();

            return redirect()->route('admin.site.list')->with('success', trans('admin.site.edit.site_saved'));
        }
    }

    public function getRemove($context, $id)
    {
        $site = Site::find($id);

        if (!$site) {
            return redirect()->route('admin.site.list')->with('fail', trans('admin.site.remove.not_exist'));
        }

        if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $site->code)) {
            Storage::cloud()->deleteDirectory(Constants::UPLOAD_DIRECTORY . $site->code);
        }

        foreach ($site->pages as $page) {
            foreach ($page->files as $file) {
                $file->lotNumbers()->detach();
                $file->delete();
            }

            $page->delete();
        }

        foreach ($site->pickupRequests as $pickupRequest) {
            $pickupRequest->delete();
        }

        foreach ($site->pickupRequestAddresses as $pickupRequestAddress) {
            $pickupRequestAddress->delete();
        }

        foreach ($site->users as $user) {
            $user->disabled = true;
            $user->save();
        }

        $site->features()->detach();
        $site->lotNumbers()->detach();
        $site->vendorClients()->detach();

        $site->delete();

        return redirect()->route('admin.site.list')->with('success', trans('admin.site.remove.site_removed'));
    }

    public function getVendorClientList($context, $siteId) {
        $site = Site::find($siteId);

        if (!$site) {
            return redirect()->route('admin.site.list')->with('fail', trans('admin.site.remove.not_exist'));
        }

        $query = VendorClient::query()->select('vendor_client.*')->leftjoin('site_vendor_client', 'vendor_client.id', '=', 'site_vendor_client.vendor_client_id')->where('site_vendor_client.site_id', $site->id);

        if (!empty(Input::get('name'))) {
            $query->where('name', 'like', '%' . StringHelper::addSlashes(trim(Input::get('name'))) . '%');
        }

        $query = $query->sortable(['name' => 'asc']);
        $vendorClients = $query->paginate(self::RESULTS_PER_PAGE);

        return view('admin.siteVendorClientList', [
            'vendorClients' => $vendorClients,
            'currentSite' => $site,
            'order' => $query->getQuery()->orders
        ]);
    }

    public function getVendorClientCreate($context, $siteId) {
        $site = Site::find($siteId);

        if (!$site) {
            return redirect()->route('admin.site.list')->with('fail', trans('admin.site.remove.not_exist'));
        }

        return view('admin.siteVendorClientCreate', [
            'currentSite' => $site
        ]);
    }

    public function postVendorClientCreate($context, $siteId) {
        $site = Site::find($siteId);

        if (!$site) {
            return redirect()->route('admin.site.list')->with('fail', trans('admin.site.remove.not_exist'));
        }

        $rules = array(
            'vendor_client' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('admin.site.vendorClient.create', ['siteId' => $site->id])->withInput(Input::all())->withErrors($validator);
        }
        else {
            $input = htmlspecialchars(Input::get('vendor_client'));
            $input = str_replace([',', ';', "\r\n", "\r", "\n"], ',', $input);
            $vendorClients = explode(',', $input);

            foreach ($vendorClients as $vendorClient) {
                $existingRecordCount = VendorClient::where('name', $vendorClient)->count();

                if ($existingRecordCount > 0) {
                    $existingVendorClient =  VendorClient::where('name', $vendorClient)->first();

                    $site->vendorClients()->attach($existingVendorClient->id);
                }
                else {
                    $newVendorClient = new VendorClient();
                    $newVendorClient->name = $vendorClient;
                    $newVendorClient->save();

                    $site->vendorClients()->attach($newVendorClient->id);
                }
            }

            return redirect()->route('admin.site.vendorClient.list', ['siteId' => $site->id])->with('success', trans('admin.site.vendor_client.create.vendor_client_created'));
        }
    }

    public function getVendorClientRemove($context, $siteId, $vendorClientId) {
        $site = Site::find($siteId);

        if (!$site) {
            return redirect()->route('admin.site.list')->with('fail', trans('admin.site.remove.not_exist'));
        }

        $vendorClient = VendorClient::find($vendorClientId);

        if (!$vendorClient) {
            return redirect()->route('admin.site.vendorClient.list', ['siteId' => $site->id])->with('fail', trans('admin.site.vendor_client.remove.not_exist'));
        }

        $site->vendorClients()->detach($vendorClient->id);

        $existingSiteRelationCount = DB::table('site_vendor_client')->where('vendor_client_id', $vendorClient->id)->count();
        $existingUserRelationCount = DB::table('user_vendor_client')->where('vendor_client_id', $vendorClient->id)->count();

        if (($existingSiteRelationCount == 0) && ($existingUserRelationCount == 0)) {
            $vendorClient->delete();
        }

        return redirect()->route('admin.site.vendorClient.list', ['siteId' => $site->id])->with('success', trans('admin.site.vendor_client.remove.vendor_client_removed'));
    }

    public function getLotNumberList($context, $siteId) {
        $site = Site::find($siteId);

        if (!$site) {
            return redirect()->route('admin.site.list')->with('fail', trans('admin.site.remove.not_exist'));
        }

        $query = LotNumber::query()->select('lot_number.*')->leftjoin('site_lot_number', 'lot_number.id', '=', 'site_lot_number.lot_number_id')->where('site_lot_number.site_id', $site->id);

        if (!empty(Input::get('prefix'))) {
            $query->where('prefix', 'like', '%' . StringHelper::addSlashes(trim(Input::get('prefix'))) . '%');
        }

        $query = $query->sortable(['prefix' => 'asc']);
        $lotNumbers = $query->paginate(self::RESULTS_PER_PAGE);

        return view('admin.siteLotNumberList', [
            'lotNumbers' => $lotNumbers,
            'currentSite' => $site,
            'order' => $query->getQuery()->orders
        ]);
    }

    public function getLotNumberCreate($context, $siteId) {
        $site = Site::find($siteId);

        if (!$site) {
            return redirect()->route('admin.site.list')->with('fail', trans('admin.site.remove.not_exist'));
        }

        return view('admin.siteLotNumberCreate', [
            'currentSite' => $site
        ]);
    }

    public function postLotNumberCreate($context, $siteId) {
        $site = Site::find($siteId);

        if (!$site) {
            return redirect()->route('admin.site.list')->with('fail', trans('admin.site.remove.not_exist'));
        }

        $rules = array(
            'lot_number' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('admin.site.lotNumber.create', ['siteId' => $site->id])->withInput(Input::all())->withErrors($validator);
        }
        else {
            $input = htmlspecialchars(Input::get('lot_number'));
            $input = str_replace([',', ';', "\r\n", "\r", "\n"], ',', $input);
            $lotNumbers = explode(',', $input);

            foreach ($lotNumbers as $lotNumber) {
                $existingRecordCount = LotNumber::where('prefix', $lotNumber)->count();

                if ($existingRecordCount > 0) {
                    $existingLotNumber = LotNumber::where('prefix', $lotNumber)->first();

                    $site->lotNumbers()->attach($existingLotNumber->id);
                }
                else {
                    $newLotNumber= new LotNumber();
                    $newLotNumber->prefix = $lotNumber;
                    $newLotNumber->save();

                    $site->lotNumbers()->attach($newLotNumber->id);
                }
            }

            return redirect()->route('admin.site.lotNumber.list', ['siteId' => $site->id])->with('success', trans('admin.site.lot_number.create.lot_number_created'));
        }
    }

    public function getLotNumberRemove($context, $siteId, $lotNumberId) {
        $site = Site::find($siteId);

        if (!$site) {
            return redirect()->route('admin.site.list')->with('fail', trans('admin.site.remove.not_exist'));
        }

        $lotNumber = LotNumber::find($lotNumberId);

        if (!$lotNumber) {
            return redirect()->route('admin.site.lotNumber.list', ['siteId' => $site->id])->with('fail', trans('admin.site.lot_number.remove.not_exist'));
        }

        $site->lotNumbers()->detach($lotNumber->id);

        $existingSiteRelationCount = DB::table('site_lot_number')->where('lot_number_id', $lotNumber->id)->count();
        $existingUserRelationCount = DB::table('user_lot_number')->where('lot_number_id', $lotNumber->id)->count();
        $existingFileRelationCount = DB::table('file_lot_number')->where('lot_number_id', $lotNumber->id)->count();

        if (($existingSiteRelationCount == 0) && ($existingUserRelationCount == 0) && ($existingFileRelationCount == 0)) {
            $lotNumber->delete();
        }

        return redirect()->route('admin.site.lotNumber.list', ['siteId' => $site->id])->with('success', trans('admin.site.lot_number.remove.lot_number_removed'));
    }
}
