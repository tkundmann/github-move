<?php

namespace App\Controllers\Admin;

use App\Controllers\ContextController;
use App\Data\Constants;
use App\Data\Models\Feature;
use App\Data\Models\File;
use App\Data\Models\Page;
use App\Data\Models\Role;
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

class ReportsPickupRequestsController extends ContextController
{

  protected $pickupRequestReportColumns = [
    'id'                       => ['sortable' => true, 'sort_column' => 'pickuprequest.id', 'sort_fa_icon' => 'fa-sort-alpha'],
    'portal'                   => ['sortable' => true, 'sort_column' => 'site.code', 'sort_fa_icon' => 'fa-sort-alpha'],
    'pickup_request_date'      => ['sortable' => true, 'sort_column' => 'pickuprequest.created_at', 'sort_fa_icon' => 'fa-sort-amount'],
  ];

  protected $pickupRequestReportExportColumns = [
    'id'                                         => 'id',
    'created_at'                                 => 'created_at',
    'portal_name'                                => 'portal_name',
    'portal_url'                                 => 'portal_url',
    'company_name'                               => 'company_name',
    'company_division'                           => 'company_division',
    'contact_name'                               => 'contact_name',
    'contact_phone_number'                       => 'contact_phone_number',
    'contact_address_1'                          => 'contact_address_1',
    'contact_address_2'                          => 'contact_address_2',
    'contact_city'                               => 'contact_city',
    'contact_state'                              => 'contact_state',
    'contact_zip'                                => 'contact_zip',
    'contact_country'                            => 'contact_country',
    'contact_cell_number'                        => 'contact_cell_number',
    'contact_email_address'                      => 'contact_email_address',
    'additional_request_recipient_email_address' => 'additional_request_recipient_email_address',
    'reference_number'                           => 'reference_number',
    'num_internal_hard_drives'                   => 'num_internal_hard_drives',
    'num_desktops'                               => 'num_desktops',
    'num_laptops'                                => 'num_laptops',
    'num_monitors'                               => 'num_monitors',
    'num_crt_monitors'                           => 'num_crt_monitors',
    'num_lcd_monitors'                           => 'num_lcd_monitors',
    'num_printers'                               => 'num_printers',
    'num_servers'                                => 'num_servers',
    'num_networking'                             => 'num_networking',
    'num_storage_systems'                        => 'num_storage_systems',
    'num_ups'                                    => 'num_ups',
    'num_racks'                                  => 'num_racks',
    'num_mobile_phones'                          => 'num_mobile_phones',
    'num_other'                                  => 'num_other',
    'num_misc'                                   => 'num_misc',
    'total_num_assets'                           => 'total_num_assets',
    'desktop_encrypted'                          => 'desktop_encrypted',
    'laptop_encrypted'                           => 'laptop_encrypted',
    'server_encrypted'                           => 'server_encrypted',
    'preferred_pickup_date'                      => 'preferred_pickup_date',
    'preferred_pickup_date_information'          => 'preferred_pickup_date_information',
    'units_located_near_dock'                    => 'units_located_near_dock',
    'units_on_single_floor'                      => 'units_on_single_floor',
    'is_lift_gate_needed'                        => 'is_lift_gate_needed',
    'is_loading_dock_present'                    => 'is_loading_dock_present',
    'dock_appointment_required'                  => 'dock_appointment_required',
    'assets_need_packaging'                      => 'assets_need_packaging',
    'hardware_on_skids'                          => 'hardware_on_skids',
    'num_skids'                                  => 'num_skids',
    'bm_company_name'                            => 'bm_company_name',
    'bm_contact_name'                            => 'bm_contact_name',
    'bm_phone_number'                            => 'bm_phone_number',
    'bm_address_1'                               => 'bm_address_1',
    'bm_address_2'                               => 'bm_address_2',
    'bm_city'                                    => 'bm_city',
    'bm_state'                                   => 'bm_state',
    'bm_zip'                                     => 'bm_zip',
    'bm_country'                                 => 'bm_country',
    'bm_cell_number'                             => 'bm_cell_number',
    'bm_email_address'                           => 'bm_email_address',
    'special_instructions'                       => 'special_instructions'
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

  public function getPickupRequests()
  {
    //$reportViewData = $this->assembleViewData();
    return $this->pickupRequestsView();
  }

  protected function pickupRequestsView($reportViewData = null) {

    $allSites = Site::orderBy('title', 'asc')->get();
    $filterSitesArray[0] = 'All Pickup Request Sites/Portals';
    foreach ($allSites as $site) {
      if ($site->hasFeature(Feature::HAS_PICKUP_REQUEST)) {
        $filterSitesArray[$site->id] = ($site->title ? $site->title : '-') . ' (' . ($site->code ? $site->code : '-') . ')';
      }
    }

    $viewData = [
      'pickupRequestSites'                     => $filterSitesArray,
      'pickupRequestSubmissionPickerStartDate' => '01/01/2017',
      'pickupRequestSubmissionPickerEndDate'   => date("m/d/Y")
    ];

    if (isset($reportViewData)) {
      $viewData = array_merge($viewData,$reportViewData);
    }

    return view('admin.reportsPickupRequests', $viewData);
  }

  public function postCertificates()
  {

    $reportViewData = $this->assembleViewData();
    return $this->pickupRequestsView($reportViewData);
  }

  private function assembleViewData() {

    $fields = null;
    $query = null;
    $pickupRequests = null;

    if (Input::get('generate-report')) {

      $fields = $this->validatePrepareReportFilters();
      $query = $this->prepareQuery($fields);

      // Set Query order by defaults
      $sortBy = 'pickuprequest.id';
      $sortOrder = 'asc';
      if (Input::get('sort_by')) {
        $sort_by = trim(Input::get('sort_by'));
        if ($sort_by != '') {
          $sortBy = $sort_by;
          $sortOrder = trim(Input::get('order'));
        }
      }
      $query->orderBy($sortBy, $sortOrder);

      $pickupRequests = $query->distinct()->paginate(self::RESULTS_PER_PAGE);

    }

    $reportViewData = [
      'pickupRequestReportColumns' => $this->pickupRequestReportColumns,
      'pickupRequests'             => $pickupRequests,
      'paginationParams'           => $fields
    ];

    if (isset($query)) {
      $reportViewData['order'] = $query->orders;
    }
    return $reportViewData;
  }

  private function validatePrepareReportFilters()
  {
    $fields = Input::all();

    $rules = [
      'pickuprequest_submission_from' => 'required_with:pickuprequest_submission_to',
      'pickuprequest_submission_to'   => 'required_with:pickuprequest_submission_from'
    ];

    $validator = Validator::make($fields, $rules);
    if ($validator->fails()) {
      return redirect()->route('admin.reports.getPickupRequests')->withErrors($validator)->withInput();
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

    $minimumPickupRequestCreatedDate = '2017-01-01';

    $query = DB::table('pickup_request')
      ->join('site', 'site.id', '=', 'pickup_request.site_id')
      ->select(
        'pickup_request.id',
        'pickup_request.created_at',
        'site.title as portal_name',
        'site.code as portal_url',
        'pickup_request.company_name',
        'pickup_request.company_division',
        'pickup_request.contact_name',
        'pickup_request.contact_phone_number',
        'pickup_request.contact_address_1',
        'pickup_request.contact_address_2',
        'pickup_request.contact_city',
        'pickup_request.contact_state',
        'pickup_request.contact_zip',
        'pickup_request.contact_country',
        'pickup_request.contact_cell_number',
        'pickup_request.contact_email_address',
        'pickup_request.additional_request_recipient_email_address',
        'pickup_request.reference_number',
        'pickup_request.num_internal_hard_drives',
        'pickup_request.num_desktops',
        'pickup_request.num_laptops',
        'pickup_request.num_monitors',
        'pickup_request.num_crt_monitors',
        'pickup_request.num_lcd_monitors',
        'pickup_request.num_printers',
        'pickup_request.num_servers',
        'pickup_request.num_networking',
        'pickup_request.num_storage_systems',
        'pickup_request.num_ups',
        'pickup_request.num_racks',
        'pickup_request.num_mobile_phones',
        'pickup_request.num_other',
        'pickup_request.num_misc',
        'pickup_request.total_num_assets',
        'pickup_request.desktop_encrypted',
        'pickup_request.laptop_encrypted',
        'pickup_request.server_encrypted',
        'pickup_request.preferred_pickup_date',
        'pickup_request.preferred_pickup_date_information',
        'pickup_request.units_located_near_dock',
        'pickup_request.units_on_single_floor',
        'pickup_request.is_lift_gate_needed',
        'pickup_request.is_loading_dock_present',
        'pickup_request.dock_appointment_required',
        'pickup_request.assets_need_packaging',
        'pickup_request.hardware_on_skids',
        'pickup_request.num_skids',
        'pickup_request.bm_company_name',
        'pickup_request.bm_contact_name',
        'pickup_request.bm_phone_number',
        'pickup_request.bm_address_1',
        'pickup_request.bm_address_2',
        'pickup_request.bm_city',
        'pickup_request.bm_state',
        'pickup_request.bm_zip',
        'pickup_request.bm_country',
        'pickup_request.bm_cell_number',
        'pickup_request.bm_email_address',
        'pickup_request.special_instructions'
      )
      ->whereNotNull('pickup_request.created_at')
      ->where('pickup_request.created_at', '>=', $minimumPickupRequestCreatedDate)
      ;

    if (isset($fields['site'])) {
      $id = $fields['site'];
      if ($id != 0) {  // Id equals 0 for All Sites option.  Do not include site id in where clause
        $query->where('site.id', '=', trim(Input::get('site')));
      }
    }

    if ($fields['pickuprequest_submission_from'] != '' && $fields['pickuprequest_submission_to'] != '') {
      $query->where('pickup_request.created_at', '>=', Carbon::createFromFormat(Constants::DATE_FORMAT, $fields['pickuprequest_submission_from'], 'America/Chicago')->startOfDay()->setTimezone('UTC'));
      $query->where('pickup_request.created_at', '<=', Carbon::createFromFormat(Constants::DATE_FORMAT, $fields['pickuprequest_submission_to'], 'America/Chicago')->endOfDay()->setTimezone('UTC'));
    }

    return $query;
  }

  public function postPickupRequestsExport()
  {

    $csv = new CsvHelper();

    $header = [];

    foreach ($this->pickupRequestReportExportColumns as $column => $label) {
        array_push($header, Lang::has('admin.reports.pickuprequests.report_headers.' . $label) ? Lang::trans('admin.reports.pickuprequests.report_headers.' . $label) : $label);
    }

    $csv->initialize($header);

    $fields = $this->validatePrepareReportFilters();
    $query = $this->prepareQuery($fields);

    $resultCheck = $query->paginate(10000);
    $numberOfIterations = $resultCheck->lastPage();
    $currentIteration = 1;
    $portalURL = '';

    if ($resultCheck->total() == 0) {
      $pickupRequests = $query->distinct()->paginate(self::RESULTS_PER_PAGE);
      $reportViewData = [
        'pickupRequestReportColumns' => $this->pickupRequestReportColumns,
        'pickupRequests'             => $pickupRequests,
        'paginationParams'           => $fields
      ];
      return $this->pickupRequestsView($reportViewData);
    }

    for ($i = $currentIteration; $i <= $numberOfIterations; $i++) {

      $query = $this->prepareQuery($fields);
      $query->orderBy('site.code', 'asc')->orderBy('pickup_request.id', 'asc');

      $paginator = $query->paginate(10000, ['*'], 'page', $i);

      $pickupRequests = $paginator->items();
      $pickupRequestsCsvArray = [];

      foreach ($pickupRequests as $pickupRequest) {

        $row = [];
        foreach ($this->pickupRequestReportExportColumns as $column => $label) {

          $row[$column] = $pickupRequest->{$column};

          if ($column == 'created_at') {
            $createdAtAdjusted = Carbon::createFromFormat(Constants::TS_FORMAT, $row[$column], 'UTC')->setTimezone('America/Chicago');
            $row[$column] = $createdAtAdjusted->toDateTimeString();;
          }

          if ($column == 'portal_url') {

            if ($portalURL == '' && isset($fields['site']) && $fields['site'] != 0) {
              $portalURL = $row['portal_url'];
            }
            $row[$column] = '/' . $row[$column];
          }
        }
        array_push($pickupRequestsCsvArray, $row);
      }

      $csv->addRows($pickupRequestsCsvArray);
    }

    $csv->finalize();

    $fileNamePrefix = 'pickup_requests_';

    if ($portalURL != '') {
      $fileNamePrefix .= $portalURL . '_';
    }
    if ($fields['pickuprequest_submission_from'] != '' && $fields['pickuprequest_submission_to'] != '') {
      $fileNamePrefix .= Carbon::createFromFormat(Constants::DATE_FORMAT, $fields['pickuprequest_submission_from'])->format('Ymd');

      if ($fields['pickuprequest_submission_to'] != $fields['pickuprequest_submission_from']) {
        $fileNamePrefix .= '_' . Carbon::createFromFormat(Constants::DATE_FORMAT, $fields['pickuprequest_submission_to'])->format('Ymd');
      }
    }
    else {
      $fileNamePrefix .= 'as_of_' . Carbon::now()->format('mdY');
    }

    $filename = $fileNamePrefix . '.csv';

    return $csv->download($filename);
  }
}
