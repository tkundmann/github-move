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

    const MAX_NUM_FILE_UPLOADS = 100;
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
            'sites'                => $allSitesWithPagesArray,
            'types'                => $types,
            'limit'                => self::STRING_LIMIT,
            'max_num_file_uploads' => self::MAX_NUM_FILE_UPLOADS
        ]);
    }

    public function postCreate()
    {

        $filesBeingUploaded = Input::file('files');
        if (!isset($filesBeingUploaded[0])) {
            $filesBeingUploaded = null;
        }
        $fields = [
            'files' => $filesBeingUploaded,
        ];

        $rules = [
            'files' => 'required'
        ];

        $validator = Validator::make($fields, $rules);
        if ($validator->fails()) {
            return redirect()->route('admin.file.create')->withErrors($validator)->withInput();
        }

        // A maximum of 100 files may be uploaded for a given form submit.  If user selected more than 100 files,
        // truncate the $fields['files'] array to 100 files.
        if (count($fields['files']) > self::MAX_NUM_FILE_UPLOADS) {
            $fields['files'] = array_slice($fields['files'], 0, self::MAX_NUM_FILE_UPLOADS);
        }

        // Parse the file name of the uploaded file(s) to retrieve the Shipment Lot Number and then check to see if a shipment
        // record exists per the parsed Lot Number.  This works because all 3 file types follow agreed upon file naming conventions.
        // Also, obtaining data for the site to which the shipment applies.
        $shipmentNotFoundError = false;
        $filesValidForUpload = array();
        $filesNotValidForUpload = array();
        foreach ($fields['files'] as $key => $currentFile) {

            if (isset($currentFile)) {

                $uploadedFileName = $currentFile->getClientOriginalName();
                $fileTypeData = FileUploadHelper::getFileTypeDataPerFileName($uploadedFileName);

                $shipment = null;
                if (is_array($fileTypeData)) {

                    $shipment = $this->getShipmentDataPerFile($uploadedFileName, $fileTypeData['prefix']);

                    if ($shipment) {

                        // We have a applicable shipment, but we need to make sure that the applicable
                        // site has the applicable "file type" feature enabled.  If not, file is not
                        // valid for upload.  Nullify shipment variable so file is not uploaded.
                        $site = Site::find($shipment->site_id);
                        if (! $site->hasFeature($fileTypeData['applicableFeature'])) {
                            $shipment = null;
                        }
                    }
                }

                if ($shipment) {
                    $filesValidForUpload[$shipment->site_id][] = array(
                        'upload'       => $currentFile,
                        'shipment'     => $shipment,
                        'fileTypeData' => $fileTypeData
                    );
                }
                else {
                    $filesNotValidForUpload[] = $uploadedFileName;
                }
            }
        }

        $previousSiteId = '';
        $previousFileType = '';
        $successfullyUploadedFiles = array(
            'numFilesUploaded' => 0,
            'sites'            => array(),
        );

        foreach ($filesValidForUpload as $siteId => $siteFilesToUploadData) {

            $site = Site::find($siteId);

            foreach ($siteFilesToUploadData as $key => $fileToUploadData) {

                $fileType = $fileToUploadData['fileTypeData']['type'];

                if ($siteId != $previousSiteId || $fileType != $previousFileType) {

                    // If for the current site, a page for the current file type does
                    // exist create it and any necessary s3 directories
                    $page = $site->pages->where('type', $fileType)->first();
                    if (!$page) {
                        $page = new Page();
                        $page->type = $fileType;
                        $page->name = $fileType;
                        $page->site_id = $site->id;
                        $page->save();

                        $siteDirectory = Constants::UPLOAD_DIRECTORY . $page->site->code;

                        if (!Storage::cloud()->exists($siteDirectory)) {
                            Storage::cloud()->makeDirectory($siteDirectory);
                        }

                        $pageTypeDir = $fileToUploadData['fileTypeData']['typeDir'];
                        if ($pageTypeDir != '') {
                            if (!Storage::cloud()->exists($siteDirectory . $pageTypeDir)) {
                                Storage::cloud()->makeDirectory($siteDirectory . $pageTypeDir);
                            }
                        }
                    }
                }

                // Check if another file for the lot number was previously uploaded for this site/file type
                // If so, delete file record from DB and remove from S3.  File overwriting is allowed.
                $existingFile = $page->files->where('shipment_id', $fileToUploadData['shipment']->id)->first();
                if ($existingFile) {
                    FileUploadHelper::removeFile($existingFile);
                }

                $file = new File();
                $file->pageId = $page->id;
                $file->size = $fileToUploadData['upload']->getSize();
                $file->shipmentId = $fileToUploadData['shipment']->id;
                $file->save();

                $url = null;
                $siteDirectory = Constants::UPLOAD_DIRECTORY . $site->code;
                $pageTypeDir = $fileToUploadData['fileTypeData']['typeDir'];

                if ($pageTypeDir != '') {

                    $uploadedFileExt = $fileToUploadData['upload']->getClientOriginalExtension();
                    $fileNamePrefix = $fileToUploadData['fileTypeData']['prefix'];
                    $fileName = $fileNamePrefix . strtoupper($fileToUploadData['shipment']->lot_number) . '.' . $uploadedFileExt;
                    Storage::cloud()->put($siteDirectory . $pageTypeDir . '/' . $fileName, file_get_contents($fileToUploadData['upload']));
                    $url = Storage::cloud()->url($siteDirectory . $pageTypeDir . '/' . $fileName);

                    $file->filename = $file->name = $fileName;
                    $file->url = $url;
                    $file->save();

                    if (! isset($successfullyUploadedFiles['sites'][$site->code])) {
                        $successfullyUploadedFiles['sites'][$site->code]['site-data'] = array(
                            'code' => $site->code,
                            'title' => $site->title
                        );
                    }

                    $successfullyUploadedFiles['sites'][$site->code]['files'][] = array(
                        'lotNumber' => $fileToUploadData['shipment']->lot_number,
                        'fileName'  => $fileName
                    );

                    $successfullyUploadedFiles['numFilesUploaded']++;
                }
                $previousFileType = $fileType;
                $previousSiteId = $siteId;
            }
        }

        $messages = array();
        if ($successfullyUploadedFiles['numFilesUploaded'] > 0) {

            $successMessage = trans('admin.file.create.success_file_upload');

            $messages['success'] = '<p class="file-upload-success-messsage">' . $successMessage . '</p>';

            foreach ($successfullyUploadedFiles['sites'] as $siteCode => $siteDataAndFiles) {

                $messages['success'] .= '<div class="site-uploaded-files-container">';
                $messages['success'] .= '<p class="site-title">' . $siteDataAndFiles['site-data']['title'] . ' (' . $siteDataAndFiles['site-data']['code'] . ')</p>';
                $messages['success'] .= '<p class="site-uploaded-files"><strong>';

                foreach ($siteDataAndFiles['files'] as $index => $uploadedFile) {
                    $messages['success'] .= '<span class="file"><a href="/' . $siteDataAndFiles['site-data']['code'] . '/shipment/search/result?lot_number_select=equals&lot_number=' . $uploadedFile['lotNumber'] . '" target="_blank">' . $uploadedFile['fileName'] . '</a></span>';
                }

                $messages['success'] .= '</strong></p></div>';
            }
        }

        if (count($filesNotValidForUpload) > 0) {

            $failMessage = trans('admin.file.create.not_valid_for_file_upload');

            $messages['fail'] = '<p>' . $failMessage . '</p><p><strong>';
            foreach ($filesNotValidForUpload as $index => $notValidFile) {
                $messages['fail'] .= '<span class="file">' . $notValidFile . '</span>';
            }
            $messages['fail'] .= '</strong></p><p>' . trans('admin.file.create.reasons_why_file_not_uploaded') . '</p>';
        }

        return redirect()->route('admin.file.list')->with($messages);
    }

    public function getShipmentDataPerFile($fileName, $fileNamePrefix)
    {
        $fileNamePrefixPattern = '/' . $fileNamePrefix . '/';
        $withoutExtension = substr($fileName, 0, strrpos($fileName, '.'));
        $replacementLimit = 1;

        $shipmentLotNumber = strtoupper(preg_replace($fileNamePrefixPattern, null, $withoutExtension, $replacementLimit));

        $shipment = Shipment::forLotNumber($shipmentLotNumber);

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
