<?php

namespace App\Controllers\Admin;

use App\Controllers\ContextController;
use App\Data\Constants;
use App\Data\Models\Feature;
use App\Data\Models\File;
use App\Data\Models\Page;
use App\Data\Models\Role;
use App\Data\Models\Shipment;
use App\Data\Models\Site;
use Illuminate\Http\Request;
use Input;
use Storage;
use Validator;

class FileController extends ContextController
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

    public function getList()
    {
        $files = null;

        $query = File::query();

        if (Input::get('site')) {
            $site = Site::find(Input::get('site'));

            if ($site) {
                $query->whereHas('page', function ($subquery) use ($site) {
                    $types = [];

                    if ($site->hasFeature(Feature::HAS_CERTIFICATES)) {
                        array_push($types, 'Certificates of Data Wipe');
                        array_push($types, 'Certificates of Recycling');
                    }
                    if ($site->hasFeature(Feature::HAS_SETTLEMENTS)) {
                        array_push($types, 'Settlements');
                    }

                    $subquery->where('site_id', trim(Input::get('site')))->whereIn('type', $types);

                    if (!empty(Input::get('type'))) {
                        $type = trim(Input::get('type'));

                        if ($type != 'all') {
                            $subquery->where('type', $type);
                        }
                    }
                });
                $query = $query->sortable(['id' => 'asc']);
                $files = $query->paginate(self::RESULTS_PER_PAGE);
            }
        }

        $allSitesWithPages = Site::whereHas('features', function ($query) {
            $query->whereIn('name', [Feature::HAS_CERTIFICATES, Feature::HAS_SETTLEMENTS]);
        })->orderBy('title', 'asc')->get();

        $allSitesWithPagesArray = [];

        foreach ($allSitesWithPages as $site) {
            $allSitesWithPagesArray[$site->id] = ($site->title ? $site->title : '-') . ' (' . ($site->code ? $site->code : '-') . ')';
        }

        return view('admin.fileList', [
            'files' => $files,
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
        $types = [];

        if ($site) {
            if ($site->hasFeature(Feature::HAS_CERTIFICATES)) {
                $types['Certificates of Data Wipe'] = 'Certificates of Data Wipe';
                $types['Certificates of Recycling'] = 'Certificates of Recycling';

            }
            if ($site->hasFeature(Feature::HAS_SETTLEMENTS)) {
                $types['Settlements'] = 'Settlements';
            }
        }

        $allSitesWithPages = Site::whereHas('features', function ($query) {
            $query->whereIn('name', [Feature::HAS_CERTIFICATES, Feature::HAS_SETTLEMENTS]);
        })->orderBy('title', 'asc')->get();

        $allSitesWithPagesArray = [];

        foreach ($allSitesWithPages as $site) {
            $allSitesWithPagesArray[$site->id] = ($site->title ? $site->title : '-') . ' (' . ($site->code ? $site->code : '-') . ')';
        }

        return view('admin.fileCreate')->with([
            'sites' => $allSitesWithPagesArray,
            'types' => $types,
            'limit' => self::STRING_LIMIT
        ]);
    }

    public function postCreate()
    {
        $site = null;

        if (Input::get('site_change')) {
            if (!$site = Site::find(trim(Input::get('site')))) {
                throw new \Exception('Site does not exist.');
            } else {
                return $this->createView($site);
            }
        }
        else {
            $site = Site::find(trim(Input::get('site')));
        }

        $type = trim(Input::get('type'));

        $page = null;

        if ($type) {
            $page = $site->pages->where('type', $type)->first();
        }

        if (!$page && $type) {
            $page = new Page();
            $page->type = $type;
            $page->name = $type;
            $page->site_id = $site->id;
            $page->save();

            if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $page->site->code)) {
                Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $page->site->code);
            }
            if ($page->type == 'Certificates of Data Wipe') {
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
        }

        $rules = [
            'site' => 'required|exists:site,id',
            'type' => 'required',
            'name' => 'required',
            'file' => 'required',
            'shipment' => 'required|exists:shipment,lot_number'
        ];

        $validator = Validator::make(Input::all(), $rules);
        $validator->after(function($validator) use ($site, $type, $page) {
            // check if prefix is valid (within this site and only if site actually has restrictions)
            if ($site && $site->hasFeature(Feature::LOT_NUMBER_PREFIX_ACCESS_RESTRICTED)) {

                $prefixes = $site->lotNumbers->pluck('prefix')->toArray();
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

            // check if no other file for that lot number was uploaded for this site/file type

            $shipment = Shipment::where('lot_number', trim(Input::get('shipment')))->first();
            if ($shipment && $page) {
                $existingFile = $page->files->where('shipment_id', $shipment->id)->first();

                if ($existingFile) {
                    $validator->errors()->add('shipment', 'This site already has a file of type ' . $type . ' uploaded for this Shipment Lot Number.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->route('admin.file.create')->withErrors($validator)->withInput();
        }

        $uploadedFile = Input::file('file');
        $fileName = $uploadedFile->getClientOriginalName();

        $file = new File();
        $file->pageId = $page->id;
        $file->name = trim(Input::get('name'));
        $file->size = $uploadedFile->getSize();
        $file->filename = $fileName;
        $file->save();

        if (Input::get('shipment')) {
            $shipment = Shipment::where('lot_number', trim(Input::get('shipment')))->first();
            if ($shipment) {
                $file->shipmentId = $shipment->id;
            }
        }

        $url = null;

        if ($type == 'Certificates of Data Wipe') {
            Storage::cloud()->put(Constants::UPLOAD_DIRECTORY . $site->code . '/certificate_of_data_wipe/' . $fileName, file_get_contents($uploadedFile));
            $url = Storage::cloud()->url(Constants::UPLOAD_DIRECTORY . $site->code . '/certificate_of_data_wipe/' . $fileName);
        }
        else if ($type == 'Certificates of Recycling') {
            Storage::cloud()->put(Constants::UPLOAD_DIRECTORY . $site->code . '/certificate_of_destruction/' . $fileName, file_get_contents($uploadedFile));
            $url = Storage::cloud()->url(Constants::UPLOAD_DIRECTORY . $site->code . '/certificate_of_destruction/' . $fileName);
        }
        else if ($type == 'Settlements') {
            Storage::cloud()->put(Constants::UPLOAD_DIRECTORY . $site->code . '/settlement/' . $fileName, file_get_contents($uploadedFile));
            $url = Storage::cloud()->url(Constants::UPLOAD_DIRECTORY . $site->code . '/settlement/' . $fileName);
        }

        $file->url = $url;
        $file->save();

        return redirect()->route('admin.file.list', ['site' => $site->id])->with('success', trans('admin.page.file.create.file_created'));
    }

    public function getEdit($context = null, $id)
    {
        $file = File::find($id);

        if (!$file) {
            return redirect()->route('admin.page.list')->with('fail', trans('admin.file.edit.not_exist'));
        }

        return view('admin.fileEdit')->with(['file' => $file, 'limit' => self::STRING_LIMIT]);
    }

    public function postEdit($context = null, $id)
    {
        $file = File::find($id);

        if (!$file) {
            return redirect()->route('admin.file.list')->with('fail', trans('admin.file.edit.not_exist'));
        }

        $rules = [
            'name' => 'required',
            'shipment' => 'required|exists:shipment,lot_number'
        ];

        $page = $file->page;

        $validator = Validator::make(Input::all(), $rules);
        $validator->after(function($validator) use ($page, $file) {
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

                if ($existingFile && ($existingFile->id != $file->id)) {
                    $validator->errors()->add('shipment', 'This page already has a file of type ' . $file->page->type . ' uploaded for this Shipment Lot Number.');
                }
            }

        });

        if ($validator->fails()) {
            return redirect()->route('admin.file.edit', ['id' => $id])->withErrors($validator)->withInput();
        }
        else {
            $file->name = trim(Input::get('name'));

            if (Input::get('shipment')) {
                $shipment = Shipment::where('lot_number', trim(Input::get('shipment')))->first();
                if ($shipment) {
                    $file->shipmentId = $shipment->id;
                }
            }

            $file->save();

            return redirect()->route('admin.file.list', ['site' => $file->page->site->id])->with('success', trans('admin.file.edit.file_saved'));
        }
    }

    public function getRemove($context, $id)
    {
        $file = File::find($id);

        if (!$file) {
            return redirect()->route('admin.file.list')->with('fail', trans('admin.file.remove.not_exist'));
        }

        if ($file->page->type == 'Certificates of Data Wipe') {
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

        $file->delete();

        return redirect()->route('admin.file.list',  ['site' => $file->page->site_id])->with('success', trans('admin.file.remove.file_removed'));
    }
}
