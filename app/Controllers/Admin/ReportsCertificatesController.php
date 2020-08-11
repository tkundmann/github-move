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
use App\Helpers\CsvHelper;
use App\Helpers\StringHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Builder;
use Storage;
use Validator;

class ReportsCertificatesController extends ContextController
{

  protected $certReportColumns = [
    'portal'                   => ['sortable' => true, 'sort_column' => 'site.code', 'sort_fa_icon' => 'fa-sort-alpha'],
    'vendor_client'            => '',
    'lot_date'                 => ['sortable' => true, 'sort_column' => 'shipment.lot_date', 'sort_fa_icon' => 'fa-sort-amount'],
    'lot_number'               => '',
    'audit_completed'          => ['sortable' => true, 'sort_column' => 'shipment.audit_completed', 'sort_fa_icon' => 'fa-sort-amount'],
    'has_certificate_of_data_wipe' => '',
    'has_certificate_of_recycling' => ''
  ];

  protected $certReportExportColumns = [
    'portalName'                => 'portal_name',
    'portalURL'                 => 'portal_url',
    'vendorClient'              => 'vendor_client',
    'lotDate'                   => 'lot_date',
    'lotNumber'                 => 'lot_number',
    'auditCompletedDate'        => 'audit_completed',
    'hasCertificateOfDataWipe'  => 'has_certificate_of_data_wipe',
    'fileDataWipeName'          => 'certificate_of_data_wipe_file',
    'hasCertificateOfRecycling' => 'has_certificate_of_recycling',
    'fileRecyclingName'         => 'certificate_of_recycling_file'
  ];

  const ALL_RESULTS      = 10;
  const RESULTS_PER_PAGE = 40;

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

  public function getCertificates()
  {
    $reportViewData = $this->assembleViewData();
    return $this->certificatesView($reportViewData);
  }

  protected function certificatesView($reportViewData = null) {

    $allSites = Site::orderBy('title', 'asc')->get();
    $filterSitesArray[0] = 'All Sites';
    foreach ($allSites as $site) {
      if ($site->code != 'allsites') {
        $filterSitesArray[$site->id] = ($site->title ? $site->title : '-') . ' (' . ($site->code ? $site->code : '-') . ')';
      }
    }
    $auditCompletedPickerStartDate = date("m/d/Y", strtotime("-2 year", time()));

    $viewData = [
      'sites' => $filterSitesArray,
      'auditCompletedPickerStartDate' => $auditCompletedPickerStartDate
    ];

    if (isset($reportViewData)) {
      $viewData = array_merge($viewData,$reportViewData);
    }

    return view('admin.reportsCertificates', $viewData);
  }

  public function postCertificates()
  {

    $reportViewData = $this->assembleViewData();
    return $this->certificatesView($reportViewData);
  }

  private function assembleViewData() {

    $fields = null;
    $query = null;
    $certificates = null;

    if (Input::get('generate-report')) {

      $fields = $this->validatePrepareReportFilters();
      $query = $this->prepareQuery($fields);

      // Set Query order by defaults
      $sortBy = 'site.code';
      $sortOrder = 'asc';
      if (Input::get('sort_by')) {
        $sort_by = trim(Input::get('sort_by'));
        if ($sort_by != '') {
          $sortBy = $sort_by;
          $sortOrder = trim(Input::get('order'));
        }
      }
      $query->orderBy($sortBy, $sortOrder);

      $certificates = $query->distinct()->paginate(self::RESULTS_PER_PAGE);

    }

    $reportViewData = [
      'certReportColumns' => $this->certReportColumns,
      'certificates'      => $certificates,
      'paginationParams'  => $fields
    ];

    if (isset($query)) {
      $reportViewData['order'] = $query->orders;
    }
    return $reportViewData;
  }

  private function validatePrepareReportFilters()
  {

  	$certsReportFile = Input::file('certs_report_file');

    if (isset($certsReportFile)) {

      // We are generating the report via the data contained in the uploaded Certs Report CSV file.
      // Prepare the data for the query and ignore all other submitted filtering fields.
      $csv = file_get_contents($certsReportFile);
      $fh = fopen($certsReportFile->path(), 'r+');
      $lotNumbers = array();
      $lineNumber = 0;
      while( ($row = fgetcsv($fh, 50000)) !== FALSE ) {
        if ($lineNumber != 0) {
          $lotNumbers[] = $row[0];
        }
        $lineNumber++;
      }
      $fields = array(
        'generate-report' => Input::get('generate-report'),
        'lotNumbers'      => implode(",", $lotNumbers)
      );

      $this->request->request->add(['lotNumbers' => implode(",", $lotNumbers)]);
      $this->request->request->remove('site');
      $this->request->request->remove('audit_completed_from');
      $this->request->request->remove('audit_completed_to');
    }
    else {

      $fields = Input::all();

      $rules = [
        'audit_completed_from' => 'required_with:audit_completed_to',
        'audit_completed_to' => 'required_with:audit_completed_from'
      ];

      $validator = Validator::make($fields, $rules);
      if ($validator->fails()) {
        return redirect()->route('admin.reports.certificates')->withErrors($validator)->withInput();
      }
    }
    return $fields;
  }

  private function prepareQuery($fields)
  {
    foreach($fields as $key => $value) {
        if (is_string($value)) {
          $fields[$key] = trim($value);
        }
    }

    $minimumAuditCompletedDate = date("Y-m-d", strtotime("-2 year", time()));

    $query = DB::table('site')
      ->join('site_vendor_client', 'site.id', '=', 'site_vendor_client.site_id')
      ->join('vendor_client', 'site_vendor_client.vendor_client_id', '=', 'vendor_client.id')
      ->join('shipment', 'vendor_client.name', '=', 'shipment.vendor_client')
      ->leftJoin('file as file_dataWipe', function ($joinData) {
        $joinData->on('shipment.id', '=', 'file_dataWipe.shipment_id')->where('file_dataWipe.filename', 'like', 'DATA%')->where(DB::raw('LOCATE(CONCAT(\'/\',site.code,\'/\'), file_dataWipe.url)'), '>', 0);
      })
      ->leftJoin('file as file_recycling', function ($joinDest) {
        $joinDest->on('shipment.id', '=', 'file_recycling.shipment_id')->where('file_recycling.filename', 'like', 'DEST%')->where(DB::raw('LOCATE(CONCAT(\'/\',site.code,\'/\'), file_recycling.url)'), '>', 0);
      })
      ->select(
        'site.code as portalURL',
        'site.title as portalName',
        'vendor_client.name as vendorClient',
        'shipment.lot_date as lotDate',
        'shipment.lot_number as lotNumber',
        'shipment.audit_completed as auditCompletedDate',
        DB::raw('IF(file_dataWipe.filename IS NOT NULL AND LOCATE(CONCAT(\'/\',site.code,\'/\'), file_dataWipe.url) > 0,\'Yes\', \'No\') as hasCertificateOfDataWipe'),
        'file_dataWipe.filename as fileDataWipeName',
        'file_dataWipe.url as fileDataWipeURL',
        DB::raw('IF(file_recycling.filename IS NOT NULL AND LOCATE(CONCAT(\'/\',site.code,\'/\'), file_recycling.url) > 0,\'Yes\', \'No\') as hasCertificateOfRecycling'),
        'file_recycling.filename as fileRecyclingName',
        'file_recycling.url as fileRecyclingURL'
      )
      ->whereNotNull('shipment.audit_completed')
      ->where('shipment.audit_completed', '>=', $minimumAuditCompletedDate)
      ->groupBy('shipment.lot_number')
    ;

    if (isset($fields['lotNumbers'])) {
      $query->whereIn('shipment.lot_number', explode(",", $fields['lotNumbers']));
    }
    else {
      if (isset($fields['site'])) {
        $id = $fields['site'];
        if ($id != 0) {  // Id equals 0 for All Sites option.  Do not include site id in where clause
          $query->where('site.id', '=', trim(Input::get('site')));
        }
      }

      if ($fields['audit_completed_from'] != '' && $fields['audit_completed_to'] != '') {
        $query->where('shipment.audit_completed', '>=', Carbon::createFromFormat(Constants::DATE_FORMAT, $fields['audit_completed_from'])->startOfDay());
        $query->where('shipment.audit_completed', '<=', Carbon::createFromFormat(Constants::DATE_FORMAT, $fields['audit_completed_to'])->endOfDay());
      }
    }
    return $query;
  }

  public function postCertificatesExport()
  {
    $csv = new CsvHelper();

    $header = [];

    foreach ($this->certReportExportColumns as $column => $label) {
        array_push($header, Lang::has('admin.reports.certificates.report_headers.' . $label) ? Lang::trans('admin.reports.certificates.report_headers.' . $label) : $label);
    }

    $csv->initialize($header);

    $fields = $this->validatePrepareReportFilters();
    $query = $this->prepareQuery($fields);

    $resultCheck = $query->paginate(10000);
    $numberOfIterations = $resultCheck->lastPage();
    $currentIteration = 1;
    $portalURL = '';

    if ($resultCheck->total() == 0) {
      $certificates = $query->distinct()->paginate(self::RESULTS_PER_PAGE);
      $reportViewData = [
        'certReportColumns' => $this->certReportColumns,
        'certificates'      => $certificates,
        'paginationParams'  => $fields
      ];
      return $this->certificatesView($reportViewData);
    }

    for ($i = $currentIteration; $i <= $numberOfIterations; $i++) {

      $query = $this->prepareQuery($fields);
      $query->orderBy('site.code', 'asc')->orderBy('shipment.lot_date', 'asc');

      $paginator = $query->paginate(10000, ['*'], 'page', $i);

      $certificates = $paginator->items();
      $certificatesCsvArray = [];

      foreach ($certificates as $certificate) {

        $row = [];
        foreach ($this->certReportExportColumns as $column => $label) {
          $row[$column] = $certificate->{$column};
        }
        array_push($certificatesCsvArray, $row);
      }

      if ($portalURL == '' && isset($fields['site']) && $fields['site'] != 0) {
        $portalURL = $row['portalURL'];
      }

      $csv->addRows($certificatesCsvArray);
    }

    $csv->finalize();

    $fileNamePrefix = 'certificate_file_status_';

    if (isset($fields['lotNumbers'])) {
      $fileNamePrefix .= 'per_sipiar_cert_report';
    }
    else {
      if ($portalURL != '') {
        $fileNamePrefix .= $portalURL . '_';
      }
      if ($fields['audit_completed_from'] != '' && $fields['audit_completed_to'] != '') {
        $fileNamePrefix .= Carbon::createFromFormat(Constants::DATE_FORMAT, $fields['audit_completed_from'])->format('Ymd') . '_';
        $fileNamePrefix .= Carbon::createFromFormat(Constants::DATE_FORMAT, $fields['audit_completed_to'])->format('Ymd');
      }
      else {
        $fileNamePrefix .= 'as_of_' . Carbon::now()->format('mdY');
      }
    }
    $filename = $fileNamePrefix . '.csv';

    return $csv->download($filename);
  }

}
