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
use App\Helpers\StringHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

                if (!empty(Input::get('filename_name'))) {
                    $query->where(function ($subquery) {
                        $subquery->where('filename', 'like', '%' . StringHelper::addSlashes(trim(Input::get('filename_name'))) . '%');
                        $subquery->orWhere('name', 'like', '%' . StringHelper::addSlashes(trim(Input::get('filename_name'))) . '%');
                    });
                }

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
        $site = Site::find(trim(Input::get('site')));
        if (Input::get('site_change')) {
            if (!$site) {
                throw new \Exception('Site does not exist.');
            } else {
                return $this->createView($site);
            }
        }

        $rules = [
            'site' => 'required|exists:site,id',
            'type' => 'required',
            'file' => 'required'
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('admin.file.create')->withErrors($validator)->withInput();
        }

        // If a page for the selected file type does exist create it and any necessary s3 directories
        $type = Input::get('type');
        $page = $site->pages->where('type', $type)->first();

        if (!$page) {
            $page = new Page();
            $page->type = $type;
            $page->name = $type;
            $page->site_id = $site->id;
            $page->save();

            $siteDirectory = Constants::UPLOAD_DIRECTORY . $page->site->code;

            if (!Storage::cloud()->exists($siteDirectory)) {
                Storage::cloud()->makeDirectory($siteDirectory);
            }

            $pageTypeDir = $this->getFilePageTypeDir($page->type);
            if ($pageTypeDir != '') {
                if (!Storage::cloud()->exists($siteDirectory . $pageTypeDir)) {
                    Storage::cloud()->makeDirectory($siteDirectory . $pageTypeDir);
                }
            }
        }

        // Parse the file name of the uploaded file to retrieve th Shipment Lot Number and then check to see if a shipment
        // record exists for the selected site per the parsed Lot Number.  This works because all 3 file types follow
        // agreed upon file naming conventions.
        $uploadedFile = Input::file('file');
        $uploadedFileName = $uploadedFile->getClientOriginalName();

        Log::info('uploadedFileName: ' . $uploadedFileName);

        $fileNamePrefix = $this->getFilePrefixPerType($type);
        $withoutExtension = substr($uploadedFileName, 0, strrpos($uploadedFileName, '.'));
        $shipmentLotNumber = strtoupper(str_replace($fileNamePrefix, null, $withoutExtension));

        Log::info('shipmentLotNumber: ' . $shipmentLotNumber);

        //$shipment = Shipment::where('lot_number', $shipmentLotNumber)->first();
        $shipment = Shipment::forLotNumberAndSiteId($shipmentLotNumber, $site->id);

        $validator->after(function($validator) use ($shipment) {
            if (!$shipment) {
                 $validator->errors()->add('file', 'A Shipment record was not found per the Lot Number specified in the uploaded filename for the selected site. Files can only be uploaded to sites to which the Lot Number is associated.');
            }
        });

        if ($validator->fails()) {
            return redirect()->route('admin.file.create')->withErrors($validator)->withInput();
        }

        // Check if another file for the lot number was previously uploaded for this site/file type
        // If so, delete file record from DB and remove from S3.  File overwriting is allowed.
         $existingFile = $page->files->where('shipment_id', $shipment->id)->first();
        if ($existingFile) {
             $this->removeFile($existingFile);
        }

        $file = new File();
        $file->pageId = $page->id;
        $file->size = $uploadedFile->getSize();
        $file->shipmentId = $shipment->id;
        $file->save();

        $url = null;
        $siteDirectory = Constants::UPLOAD_DIRECTORY . $site->code;
        $pageTypeDir = $this->getFilePageTypeDir($type);

        if ($pageTypeDir != '') {

            $uploadedFileExt = $uploadedFile->getClientOriginalExtension();
            $fileNamePrefix = $this->getFilePrefixPerType($type);
            $fileName = $fileNamePrefix . strtoupper($shipment->lot_number) . '.' . $uploadedFileExt;
            Storage::cloud()->put($siteDirectory . $pageTypeDir . '/' . $fileName, file_get_contents($uploadedFile));
            $url = Storage::cloud()->url($siteDirectory . $pageTypeDir . '/' . $fileName);

            $file->filename = $file->name = $fileName;
            $file->url = $url;
            $file->save();

        }
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

        $this->removeFile($file);
        return redirect()->route('admin.file.list',  ['site' => $file->page->site_id])->with('success', trans('admin.file.remove.file_removed'));
    }

    public function getFilePageTypeDir($type)
    {

        $pageTypeDir = '';
        if ($type == 'Certificates of Data Wipe') {
            $pageTypeDir = '/certificate_of_data_wipe';
        }
        else if ($type == 'Certificates of Recycling') {
            $pageTypeDir = '/certificate_of_destruction';
        }
        else if ($type == 'Settlements') {
            $pageTypeDir = '/settlement';
        }
        return $pageTypeDir;
    }

    public function getFilePrefixPerType($type)
    {

        $fileNamePrefix = '';
        if ($type == 'Certificates of Data Wipe') {
            $fileNamePrefix = 'DATA';
        }
        else if ($type == 'Certificates of Recycling') {
            $fileNamePrefix = 'DEST';
        }
        else if ($type == 'Settlements') {
            $fileNamePrefix = 'settlement';
        }
        return $fileNamePrefix;
    }

    public function removeFile($file)
    {

        $pageTypeDir = $this->getFilePageTypeDir($file->page->type);
        if ($pageTypeDir != '') {
            if (Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $file->page->site->code . $pageTypeDir . '/' . $file->filename)) {
                Storage::cloud()->delete(Constants::UPLOAD_DIRECTORY . $file->page->site->code . $pageTypeDir . '/' . $file->filename);
            }
        }
        $file->delete();
    }
}
