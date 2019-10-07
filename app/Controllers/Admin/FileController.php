<?php

namespace App\Controllers\Admin;

use App\Controllers\ContextController;
use App\Controllers\Helpers\FileUploadHelper;
use App\Data\Constants;
use App\Data\Models\Feature;
use App\Data\Models\File;
use App\Data\Models\Page;
use App\Data\Models\Role;
use App\Data\Models\Shipment;
use App\Data\Models\Site;
use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Input;
use Storage;
use Validator;

class FileController extends ContextController
{
    const RESULTS_PER_PAGE = 50;
    const STRING_LIMIT = 50;

    const NUM_FILE_UPLOADS = 50;
    /**
     * FileController constructor.
     * @param Request $request
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
                    $query->where(function (Builder $subquery) {
                        $subquery->where('filename', 'like', '%' . StringHelper::addSlashes(trim(Input::get('filename_name'))) . '%');
                        $subquery->orWhere('name', 'like', '%' . StringHelper::addSlashes(trim(Input::get('filename_name'))) . '%');
                    });
                }

                $query->whereHas('page', function (Builder $subquery) use ($site) {
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
                $query = $query->sortable(['name' => 'asc']);
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
                $types['Certificates of Data Wipe'] = 'Certificates of Data Wipe (.pdf)';
                $types['Certificates of Recycling'] = 'Certificates of Recycling (.pdf)';

            }
            if ($site->hasFeature(Feature::HAS_SETTLEMENTS)) {
                $types['Settlements'] = 'Settlements (.xls, .xlsx)';
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
            'sites'             => $allSitesWithPagesArray,
            'types'             => $types,
            'limit'             => self::STRING_LIMIT,
            'num_upload_fields' => self::NUM_FILE_UPLOADS
        ]);
    }

    public function postCreate()
    {
        $fields = [
            'site' => Input::get('site'),
            'type' => Input::get('type')
        ];

        $site = null;
        $site = Site::find(trim($fields['site']));
        if (Input::get('site_change')) {
            if (!$site) {
                throw new \Exception('Site does not exist.');
            } else {
                return $this->createView($site);
            }
        }

        $numFilesUploaded = 0;
        $uploadedFiles = Input::file('files');
        foreach ($uploadedFiles as $key => $uploadedFile) {
            if (isset($uploadedFile)) {
                $numFilesUploaded++;
                $fields['file' . $numFilesUploaded] = $uploadedFile;
            }
        }

        // $numFilesUploaded = 0;
        // for ($i=1; $i <= self::NUM_FILE_UPLOADS ; $i++) {
        //     $uploadedFile = Input::file('file' . $i);
        //     if (isset($uploadedFile)) {
        //         $numFilesUploaded++;
        //         $fields['file' . $numFilesUploaded] = $uploadedFile;
        //     }
        // }

        $rules = [
            'site' => 'required|exists:site,id',
            'type' => 'required',
            'file1' => 'required'
        ];


        $validator = Validator::make($fields, $rules);
        if ($validator->fails()) {
            return redirect()->route('admin.file.create')->withErrors($validator)->withInput();
        }

        // If a page for the selected file type does exist create it and any necessary s3 directories
        $page = $site->pages->where('type', $fields['type'])->first();

        if (!$page) {
            $page = new Page();
            $page->type = $fields['type'];
            $page->name = $fields['type'];
            $page->site_id = $site->id;
            $page->save();

            $siteDirectory = Constants::UPLOAD_DIRECTORY . $page->site->code;

            if (!Storage::cloud()->exists($siteDirectory)) {
                Storage::cloud()->makeDirectory($siteDirectory);
            }

            $pageTypeDir = FileUploadHelper::getFilePageTypeDir($page->type);
            if ($pageTypeDir != '') {
                if (!Storage::cloud()->exists($siteDirectory . $pageTypeDir)) {
                    Storage::cloud()->makeDirectory($siteDirectory . $pageTypeDir);
                }
            }
        }

        // Parse the file name of the uploaded file(s) to retrieve the Shipment Lot Number and then check to see if a shipment
        // record exists for the selected site per the parsed Lot Number.  This works because all 3 file types follow
        // agreed upon file naming conventions.
        $shipmentNotFoundError = false;
        $filesValidForUpload = array();
        $filesNotValidForUpload = array();
        for ($i=1; $i <= $numFilesUploaded ; $i++) {

            $currentFile = $fields['file' . $i];
            $shipment = $this->getShipmentPerFile($currentFile, $fields['type'], $site);

            if ($shipment) {
                $filesValidForUpload[$i]['upload'] = $currentFile;
                $filesValidForUpload[$i]['shipment'] = $shipment;
            }
            else {
                $fileName = $currentFile->getClientOriginalName();
                $filesNotValidForUpload[$fileName] = $fileName;
            }
        }

        $successfullyUploadedFiles = array();
        foreach ($filesValidForUpload as $key => $fileToUpload) {

            // Check if another file for the lot number was previously uploaded for this site/file type
            // If so, delete file record from DB and remove from S3.  File overwriting is allowed.
             $existingFile = $page->files->where('shipment_id', $fileToUpload['shipment']->id)->first();
            if ($existingFile) {
                FileUploadHelper::removeFile($existingFile);
            }

            $file = new File();
            $file->pageId = $page->id;
            $file->size = $fileToUpload['upload']->getSize();
            $file->shipmentId = $fileToUpload['shipment']->id;
            $file->save();

            $url = null;
            $siteDirectory = Constants::UPLOAD_DIRECTORY . $site->code;
            $pageTypeDir = FileUploadHelper::getFilePageTypeDir($fields['type']);

            if ($pageTypeDir != '') {

                $uploadedFileExt = $fileToUpload['upload']->getClientOriginalExtension();
                $fileNamePrefix = FileUploadHelper::getFilePrefixPerType($fields['type']);
                $fileName = $fileNamePrefix . strtoupper($fileToUpload['shipment']->lot_number) . '.' . $uploadedFileExt;
                Storage::cloud()->put($siteDirectory . $pageTypeDir . '/' . $fileName, file_get_contents($fileToUpload['upload']));
                $url = Storage::cloud()->url($siteDirectory . $pageTypeDir . '/' . $fileName);

                $file->filename = $file->name = $fileName;
                $file->url = $url;
                $file->save();

                $successfullyUploadedFiles[] = array(
                    'lotNumber' => $fileToUpload['shipment']->lot_number,
                    'fileName'  => $fileName,
                    'siteCode'  => $site->code
                );
            }
        }

        $messages = array();
        $messageReplacePairs = array(
            'PLACEHOLDER_SITE_TITLE' => $site->title,
            'PLACEHOLDER_FILE_TYPE'  => $fields['type']
        );

        if (count($successfullyUploadedFiles) > 0) {

            $successMessage = trans('admin.file.create.success_file_upload');
            $successMessage = str_replace(array_keys($messageReplacePairs),array_values($messageReplacePairs),$successMessage);

            $messages['success'] = $successMessage . '<br/><strong>';
            foreach ($successfullyUploadedFiles as $index => $uploadedFile) {
                $messages['success'] .= '<span class="font-size-md margin-top-md margin-right-lg inline-block"><a href="/' . $uploadedFile['siteCode'] . '/shipment/search/result?lot_number_select=equals&lot_number=' . $uploadedFile['lotNumber'] . '" target="_blank">' . $uploadedFile['fileName'] . '</a></span>';
            }
            $messages['success'] .= '</strong>';
        }

        if (count($filesNotValidForUpload) > 0) {

            $failMessage = trans('admin.file.create.not_valid_for_file_upload');
            $failMessage = str_replace(array_keys($messageReplacePairs),array_values($messageReplacePairs),$failMessage);

            $messages['fail'] = $failMessage . '<br/><strong>';
            foreach ($filesNotValidForUpload as $index => $notValidFile) {
                $messages['fail'] .= '<span class="font-size-md margin-top-md margin-right-lg inline-block">' . $notValidFile . '</span>';
            }
            $messages['fail'] .= '</strong><br><br>' . trans('admin.file.create.shipment_not_found_for_file');
        }

        return redirect()->route('admin.file.list', ['site' => $site->id])->with($messages);
    }

    public function getShipmentPerFile($uploadedFile, $fileType, $site)
    {
        $uploadedFileName = $uploadedFile->getClientOriginalName();

        $fileNamePrefixPattern = '/' . FileUploadHelper::getFilePrefixPerType($fileType) . '/';
        $withoutExtension = substr($uploadedFileName, 0, strrpos($uploadedFileName, '.'));
        $replacementLimit = 1;

        $shipmentLotNumber = strtoupper(preg_replace($fileNamePrefixPattern, null, $withoutExtension, $replacementLimit));

        $shipment = Shipment::forLotNumberAndSiteId($shipmentLotNumber, $site->id);

        return $shipment;
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
                FileUploadHelper::removeFile($file);
        return redirect()->route('admin.file.list',  ['site' => $file->page->site_id])->with('success', trans('admin.file.remove.file_removed'));
    }
}
