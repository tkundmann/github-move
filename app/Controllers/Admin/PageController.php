<?php

namespace App\Controllers\Admin;

use App\Controllers\ContextController;
use App\Data\Constants;
use App\Data\Models\Feature;
use App\Data\Models\File;
use App\Data\Models\LotNumber;
use App\Data\Models\Page;
use App\Data\Models\Role;
use App\Data\Models\Shipment;
use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Input;
use Storage;
use Validator;

class PageController extends ContextController
{
    const RESULTS_PER_PAGE = 50;
    const STRING_LIMIT = 50;

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

    protected function pageValidationRules()
    {
        return [
            'name' => 'required'
        ];
    }

    public function getList()
    {
        $pages = null;

        $query = Page::query();

        if (Input::get('site')) {
            $query->where('site_id', '=', trim(Input::get('site')));
            $query->whereNotIn('type', ['Certificates of Data Wipe','Certificates of Recycling','Settlements']);
            $query = $query->sortable(['id' => 'asc']);
            $pages = $query->paginate(self::RESULTS_PER_PAGE);
        }

        $allSitesWithPages = Site::whereHas('features', function ($query) {
            $query->where('name', Feature::HAS_PAGES);
        })->orderBy('title', 'asc')->get();

        $allSitesWithPagesArray = [];

        foreach ($allSitesWithPages as $site) {
            $allSitesWithPagesArray[$site->id] = ($site->title ? $site->title : '-') . ' (' . ($site->code ? $site->code : '-') . ')';
        }

        return view('admin.pageList', [
            'pages' => $pages,
            'sites' => $allSitesWithPagesArray,
            'order' => $query->getQuery()->orders,
            'limit' => self::STRING_LIMIT
        ]);
    }

    public function getCreate()
    {
        $siteId = Input::get('site') ? trim(Input::get('site')) : old('site');
        $site = Site::find($siteId);

        return $this->createView($site);
    }

    protected function createView($site = null) {
        $allSitesWithPages = Site::whereHas('features', function ($query) {
            $query->where('name', Feature::HAS_PAGES);
        })->orderBy('title', 'asc')->get();

        $allSitesWithPagesArray = [];

        foreach ($allSitesWithPages as $siteWithPages) {
            $allSitesWithPagesArray[$siteWithPages->id] = ($siteWithPages->title ? $siteWithPages->title : '-') . ' (' . ($siteWithPages->code ? $siteWithPages->code : '-') . ')';
        }

        $types = [];

        if ($site) {
            if ($site->hasFeature(Feature::HAS_PAGES)) {
                $types['Standard'] = 'Standard';
            }
            if ($site->hasFeature(Feature::HAS_CERTIFICATES)) {
                $certOfDataWipePagePresent = false;
                $certOfRecyclingPagePresent = false;

                foreach ($site->pages as $page) {
                    if ($page->type == 'Certificates of Data Wipe') {
                        $certOfDataWipePagePresent = true;
                    }
                    if ($page->type == 'Certificates of Recycling') {
                        $certOfRecyclingPagePresent = true;
                    }
                }

                if (!$certOfDataWipePagePresent) {
                    $types['Certificates of Data Wipe'] = 'Certificates of Data Wipe';
                }
                if (!$certOfRecyclingPagePresent) {
                    $types['Certificates of Recycling'] = 'Certificates of Recycling';
                }
            }
            if ($site->hasFeature(Feature::HAS_SETTLEMENTS)) {
                $settlementPagePresent = false;

                foreach ($site->pages as $page) {
                    if ($page->type == 'Settlements') {
                        $settlementPagePresent = true;
                    }
                }

                if (!$settlementPagePresent) {
                    $types['Settlements'] = 'Settlements';
                }
            }
        }

        return view('admin.pageCreate')->with([
            'sites' => $allSitesWithPagesArray,
            'types' => $types
        ]);
    }

    public function postCreate()
    {
        if (Input::get('site_change')) {
            if (!$site = Site::find(trim(Input::get('site')))) {
                throw new \Exception('Site does not exist.');
            } else {
                return $this->createView($site);
            }
        }

        $rules = $this->pageValidationRules();
        $rules['type'] = 'required';
        $rules['site'] = 'required|exists:site,id';

        $type = trim(Input::get('type'));

        if ($type == 'Standard') {
            $rules['code'] = 'required|regex:/^[A-Za-z0-9\-\_\']+$/|unique_page_code:site_id,' . trim(Input::get('site'));
        }

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('admin.page.create')->withErrors($validator)->withInput();
        }
        else {
            $page = new Page();
            $page->type = trim(Input::get('type'));
            $page->name = trim(Input::get('name'));
            $page->description = trim(Input::get('description'));
            $page->site_id = trim(Input::get('site'));

            if ($page->type == 'Standard') {
                $page->code = trim(Input::get('code'));
                $page->text = trim(Input::get('text'));
                $page->userRestricted = trim(Input::get('user_restricted'));
                $page->lotNumberRestricted = trim(Input::get('lot_number_restricted'));
            }

            $page->save();

            if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $page->site->code)) {
                Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $page->site->code);
            }

            if ($page->type == 'Standard') {
                if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $page->site->code . '/page')) {
                    Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $page->site->code . '/page');
                }

                Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $page->site->code . '/page/' . $page->code);
            }
            else if ($page->type == 'Certificates of Data Wipe') {
                if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $page->site->code . '/certificate_of_data_wipe')) {
                    Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $page->site->code . '/certificate_of_data_wipe');
                }
            }
            else if ($page->type == 'Certificates of Recycling') {
                if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $page->site->code . '/certificate_of_destruction')) {
                    Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $page->site->code . '/certificate_of_destruction');
                }
            }
            else if ($page->type == 'Settlements') {
                if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $page->site->code . '/settlement')) {
                    Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $page->site->code . '/settlement');
                }
            }

            return redirect()->route('admin.page.list', ['site' => $page->site_id])->with('success', trans('admin.page.create.page_created'));
        }
    }

    public function getEdit($context = null, $id)
    {
        $page = Page::find($id);

        if (!$page) {
            return redirect()->route('admin.page.list')->with('fail', trans('admin.page.edit.not_exist'));
        }

        $allSitesWithPages = Site::whereHas('features', function ($query) {
            $query->where('name', Feature::HAS_PAGES);
        })->orderBy('title', 'asc')->get();

        $allSitesWithPagesArray = [];

        foreach ($allSitesWithPages as $site) {
            $allSitesWithPagesArray[$site->id] = ($site->title ? $site->title : '-') . ' (' . ($site->code ? $site->code : '-') . ')';
        }

        return view('admin.pageEdit')->with(['page' => $page, 'sites' => $allSitesWithPagesArray, 'limit' => self::STRING_LIMIT]);
    }

    public function postEdit($context = null, $id)
    {
        $validator = Validator::make(Input::all(), $this->pageValidationRules());

        if ($validator->fails()) {
            return redirect()->route('admin.page.edit', ['id' => $id])->withErrors($validator)->withInput();
        }
        else {
            $page = Page::find($id);

            if (!$page) {
                return redirect()->route('admin.page.list')->with('fail', trans('admin.page.edit.not_exist'));
            }

            $page->name = trim(Input::get('name'));
            $page->text = trim(Input::get('text'));
            $page->description = trim(Input::get('description'));

            if ($page->type == 'Standard') {
                $page->userRestricted = trim(Input::get('user_restricted'));
                $page->lotNumberRestricted = trim(Input::get('lot_number_restricted'));
            }

            $page->save();

            return redirect()->route('admin.page.list', ['site' => $page->site_id])->with('success', trans('admin.page.edit.page_saved'));
        }
    }

    public function getRemove($context = null, $id)
    {
        $page = Page::find($id);

        if (!$page) {
            return redirect()->route('admin.page.list')->with('fail', trans('admin.page.remove.not_exist'));
        }

        $siteId = $page->siteId;

        foreach ($page->files as $file) {
            $file->delete();
        }

        if ($page->type == 'Standard') {
            if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $page->site->code . '/page/' . $page->code)) {
                Storage::cloud()->deleteDirectory(Constants::UPLOAD_DIRECTORY . $page->site->code . '/page/' . $page->code);
            }
        }
        else if ($page->type == 'Certificates of Data Wipe') {
            if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $page->site->code . '/certificate_of_data_wipe')) {
                Storage::cloud()->deleteDirectory(Constants::UPLOAD_DIRECTORY . $page->site->code . '/certificate_of_data_wipe');
            }
        }
        else if ($page->type == 'Certificates of Recycling') {
            if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $page->site->code . '/certificate_of_destruction')) {
                Storage::cloud()->deleteDirectory(Constants::UPLOAD_DIRECTORY . $page->site->code . '/certificate_of_destruction');
            }
        }
        else if ($page->type == 'Settlements') {
            if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $page->site->code . '/settlement')) {
                Storage::cloud()->deleteDirectory(Constants::UPLOAD_DIRECTORY . $page->site->code . '/settlement');
            }
        }

        $page->delete();

        return redirect()->route('admin.page.list', ['site' => $siteId])->with('success', trans('admin.page.remove.page_removed'));
    }

    // Files

    public function getFileList($context = null, $id) {
        $page = Page::find($id);

        if (!$page) {
            return redirect()->route('admin.page.list')->with('fail', trans('admin.page.edit.not_exist'));
        }

        $files = null;

        $query = File::query();

        $query->where('page_id', '=', $page->id);
        $query = $query->sortable(['id' => 'asc']);
        $files = $query->paginate(self::RESULTS_PER_PAGE);

        return view('admin.pageFileList')->with([
            'page' => $page,
            'files' => $files,
            'order' => $query->getQuery()->orders,
            'limit' => self::STRING_LIMIT
        ]);
    }

    public function getFileCreate($context = null, $id)
    {
        $page = Page::find($id);

        if (!$page) {
            return redirect()->route('admin.page.list')->with('fail', trans('admin.page.edit.not_exist'));
        }

        $siteLotNumbers = [];

        if ($page->lotNumberRestricted) {
            $siteLotNumbers = $page->site->lotNumbers->pluck('prefix', 'id')->toArray();
        }

        return view('admin.pageFileCreate')->with(['page' => $page, 'lotNumbers' => $siteLotNumbers, 'limit' => self::STRING_LIMIT]);
    }

    public function postFileCreate($context = null, $id)
    {
        $page = Page::find($id);

        if (!$page) {
            return redirect()->route('admin.page.list')->with('fail', trans('admin.page.edit.not_exist'));
        }

        $rules = [
            'name' => 'required',
            'file' => 'required'
        ];

        if (in_array($page->type, ['Certificates of Data Wipe','Certificates of Recycling','Settlements'], true)) {
            $rules['shipment'] = 'required|exists:shipment,lot_number';
        }

        $validator = Validator::make(Input::all(), $rules);
        $validator->after(function($validator) use ($page) {
            if (in_array($page->type, ['Certificates of Data Wipe','Certificates of Recycling','Settlements'], true)) {
                // check if prefix is valid (within this site and only if site actually has restrictions)
                if ($page->site->hasFeature(Feature::LOT_NUMBER_PREFIX_ACCESS_RESTRICTED)) {

                    $prefixes = $page->site->lotNumbers->pluck('prefix')->toArray();
                    $shipment = trim(Input::get('shipment'));

                    $isPrefixValid = false;
                    foreach ($prefixes as $prefix) {
                        if (starts_with($shipment, $prefix)) {
                            $isPrefixValid = true;
                        }
                    }

                    if (!$isPrefixValid) {
                        $validator->errors()->add('shipment', 'This Shipment Lot Number doesn\'t belong to this Site.');
                    }
                }

                // check if no other file for that lot number was uploaded for this page

                $shipment = Shipment::where('lot_number', trim(Input::get('shipment')))->first();
                if ($shipment) {
                    $existingFile = $page->files->where('shipment_id', $shipment->id)->first();

                    if ($existingFile) {
                        $validator->errors()->add('shipment', 'This page already has a file uploaded for this Shipment Lot Number.');
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->route('admin.page.file.create', ['id' => $id])->withErrors($validator)->withInput();
        }

        $uploadedFile = Input::file('file');
        $fileName = $uploadedFile->getClientOriginalName();

        $file = new File();
        $file->pageId = $page->id;
        $file->name = trim(Input::get('name'));
        $file->size = $uploadedFile->getSize();
        $file->filename = $fileName;
        $file->save();

        if (($page->type == 'Standard') && Input::get('file_date')) {
            $file->fileDate = Carbon::createFromFormat('m/d/Y' , trim(Input::get('file_date')));
        }

        if (($page->type == 'Standard') && $page->lotNumberRestricted) {
            if (is_array(Input::get('lot_numbers'))) {
                foreach (Input::get('lot_numbers') as $lotNumberId) {
                    if (!$lotNumber = LotNumber::find($lotNumberId)) {
                        throw new \Exception('Lot Number does not exist.');
                    }

                    if (!$page->site->lotNumbers->contains($lotNumber)) {
                        throw new \Exception('Lot Number does not belong to site.');
                    }

                    $file->lotNumbers()->attach($lotNumber);
                }
            }
        }

        if (in_array($page->type, ['Certificates of Data Wipe','Certificates of Recycling','Settlements'], true) && Input::get('shipment')) {
            $shipment = Shipment::where('lot_number', trim(Input::get('shipment')))->first();
            if ($shipment) {
                $file->shipmentId = $shipment->id;
            }
        }

        $url = null;

        if ($page->type == 'Standard') {
            Storage::cloud()->put(Constants::UPLOAD_DIRECTORY . $page->site->code . '/page/' . $page->code . '/' . $fileName, file_get_contents($uploadedFile));
            $url = Storage::cloud()->url(Constants::UPLOAD_DIRECTORY . $page->site->code . '/page/' . $page->code . '/' . $fileName);
        }
        else if ($page->type == 'Certificates of Data Wipe') {
            Storage::cloud()->put(Constants::UPLOAD_DIRECTORY . $page->site->code . '/certificate_of_data_wipe/' . $fileName, file_get_contents($uploadedFile));
            $url = Storage::cloud()->url(Constants::UPLOAD_DIRECTORY . $page->site->code . '/certificate_of_data_wipe/' . $fileName);
        }
        else if ($page->type == 'Certificates of Recycling') {
            Storage::cloud()->put(Constants::UPLOAD_DIRECTORY . $page->site->code . '/certificate_of_destruction/' . $fileName, file_get_contents($uploadedFile));
            $url = Storage::cloud()->url(Constants::UPLOAD_DIRECTORY . $page->site->code . '/certificate_of_destruction/' . $fileName);
        }
        else if ($page->type == 'Settlements') {
            Storage::cloud()->put(Constants::UPLOAD_DIRECTORY . $page->site->code . '/settlement/' . $fileName, file_get_contents($uploadedFile));
            $url = Storage::cloud()->url(Constants::UPLOAD_DIRECTORY . $page->site->code . '/settlement/' . $fileName);
        }

        $file->url = $url;
        $file->save();

        return redirect()->route('admin.page.file.list', ['id' => $page->id])->with('success', trans('admin.page.file.create.file_created'));
    }

    public function getFileEdit($context = null, $pageId, $fileId)
    {
        $file = File::find($fileId);

        if (!$file) {
            return redirect()->route('admin.page.list')->with('fail', trans('admin.page.file.edit.not_exist'));
        }

        $page = Page::find($pageId);

        if (!$page) {
            return redirect()->route('admin.page.list')->with('fail', trans('admin.page.edit.not_exist'));
        }

        $siteLotNumbers = [];

        if ($page->lotNumberRestricted) {
            $siteLotNumbers = $page->site->lotNumbers->pluck('prefix', 'id')->toArray();
        }

        return view('admin.pageFileEdit')->with([ 'file' => $file , 'page' => $page, 'lotNumbers' => $siteLotNumbers, 'limit' => self::STRING_LIMIT]);
    }

    public function postFileEdit($context = null, $pageId, $fileId)
    {
        $page = Page::find($pageId);

        if (!$page) {
            return redirect()->route('admin.page.list')->with('fail', trans('admin.page.edit.not_exist'));
        }

        $rules = [
            'name' => 'required'
        ];

        if (in_array($page->type, ['Certificates of Data Wipe','Certificates of Recycling','Settlements'], true)) {
            $rules['shipment'] = 'required|exists:shipment,lot_number';
        }

        $validator = Validator::make(Input::all(), $rules);
        $validator->after(function($validator) use ($page, $fileId) {
            if (in_array($page->type, ['Certificates of Data Wipe','Certificates of Recycling','Settlements'], true)) {
                // check if prefix is valid (within this site and only if site actually has restrictions)
                if ($page->site->hasFeature(Feature::LOT_NUMBER_PREFIX_ACCESS_RESTRICTED)) {

                    $prefixes = $page->site->lotNumbers->pluck('prefix')->toArray();
                    $shipment = trim(Input::get('shipment'));

                    $isPrefixValid = false;
                    foreach ($prefixes as $prefix) {
                        if (starts_with($shipment, $prefix)) {
                            $isPrefixValid = true;
                        }
                    }

                    if (!$isPrefixValid) {
                        $validator->errors()->add('shipment', 'This Shipment Lot Number doesn\'t belong to this Site.');
                    }
                }

                // check if no other file for that lot number was uploaded for this page

                $shipment = Shipment::where('lot_number', trim(Input::get('shipment')))->first();
                if ($shipment) {
                    $existingFile = $page->files->where('shipment_id', $shipment->id)->first();
                    $currentFile = File::find($fileId);

                    if ($existingFile && ($existingFile->id != $currentFile->id)) {
                        $validator->errors()->add('shipment', 'This page already has a file uploaded for this Shipment Lot Number.');
                    }
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->route('admin.page.file.edit', ['fileId' => $fileId, 'pageId' => $pageId])->withErrors($validator)->withInput();
        }
        else {
            $file = File::find($fileId);

            if (!$file) {
                return redirect()->route('admin.page.list')->with('fail', trans('admin.page.file.edit.not_exist'));
            }

            $file->name = trim(Input::get('name'));

            if (($page->type == 'Standard') && Input::get('file_date')) {
                $file->fileDate = Carbon::createFromFormat('m/Y' , trim(Input::get('file_date')));
            }

            if (($page->type == 'Standard') && $page->lotNumberRestricted) {
                if (is_array(Input::get('lot_numbers'))) {
                    $file->lotNumbers()->detach();

                    foreach (Input::get('lot_numbers') as $lotNumberId) {
                        if (!$lotNumber = LotNumber::find($lotNumberId)) {
                            throw new \Exception('Lot Number does not exist.');
                        }

                        if (!$page->site->lotNumbers->contains($lotNumber)) {
                            throw new \Exception('Lot Number does not belong to site.');
                        }

                        $file->lotNumbers()->attach($lotNumber);
                    }
                }
            }

            if (in_array($page->type, ['Certificates of Data Wipe','Certificates of Recycling','Settlements'], true) && Input::get('shipment')) {
                $shipment = Shipment::where('lot_number', trim(Input::get('shipment')))->first();
                if ($shipment) {
                    $file->shipmentId = $shipment->id;
                }
            }

            $file->save();

            return redirect()->route('admin.page.file.list', ['id' => $file->page_id])->with('success', trans('admin.page.file.edit.file_saved'));
        }
    }

    public function getFileRemove($context = null, $pageId, $fileId)
    {
        $file = File::find($fileId);

        if (!$file) {
            return redirect()->route('admin.page.file.list', ['id' => $pageId])->with('fail', trans('admin.page.file.remove.not_exist'));
        }

        $pageId = $file->pageId;

        if ($file->page->type == 'Standard') {
            if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $file->page->site->code . '/page/' . $file->page->code . '/' . $file->filename)) {
                Storage::cloud()->delete(Constants::UPLOAD_DIRECTORY . $file->page->site->code . '/page/' . $file->page->code . '/' . $file->filename);
            }
        }
        else if ($file->page->type == 'Certificates of Data Wipe') {
            if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $file->page->site->code . '/certificate_of_data_wipe/' . $file->filename)) {
                Storage::cloud()->delete(Constants::UPLOAD_DIRECTORY . $file->page->site->code . '/certificate_of_data_wipe/' . $file->filename);
            }
        }
        else if ($file->page->type == 'Certificates of Recycling') {
            if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $file->page->site->code . '/certificate_of_destruction/' . $file->filename)) {
                Storage::cloud()->delete(Constants::UPLOAD_DIRECTORY . $file->page->site->code . '/certificate_of_destruction/' . $file->filename);
            }
        }
        else if ($file->page->type == 'Settlements') {
            if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $file->page->site->code . '/settlement/' . $file->filename)) {
                Storage::cloud()->delete(Constants::UPLOAD_DIRECTORY . $file->page->site->code . '/settlement/' . $file->filename);
            }
        }

        if (count($file->lotNumbers) > 0) {
            $file->lotNumbers()->detach();
        }

        $file->delete();

        return redirect()->route('admin.page.file.list', ['id' => $pageId])->with('success', trans('admin.page.file.remove.file_removed'));
    }

}
