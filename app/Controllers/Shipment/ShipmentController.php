<?php

namespace App\Controllers\Shipment;

use App\Controllers\ContextController;
use App\Data\Constants;
use App\Data\Models\Feature;
use App\Data\Models\Role;
use App\Data\Models\Shipment;
use App\Helpers\CsvHelper;
use App\Helpers\StringHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShipmentController extends ContextController
{
    const RESULTS_PER_PAGE = 50;
    const USE_SELECT_EXACT_VALUES = true;
    const STRING_LIMIT = 50;

    protected $defaultSearchFields = [
        'vendor_client'           => 'vendor_client',
        'lot_date'                => 'lot_date',
        'lot_number'              => 'lot_number',
        'po_number'               => 'po_number',
        'vendor_shipment_number'  => 'vendor_shipment_number',
        'site_coordinator'        => 'site_coordinator',
        'vendor'                  => 'vendor',
        'bill_of_lading'          => 'bill_of_lading',
        'city_of_origin'          => 'city_of_origin',
        'schedule_pickup_date'    => 'schedule_pickup_date',
        'freight_carrier'         => 'freight_carrier',
        'pickup_address'          => 'pickup_address',
        'pickup_address_2'        => 'pickup_address_2',
        'pickup_city'             => 'pickup_city',
        'pickup_state'            => 'pickup_state',
        'pickup_zip_code'         => 'pickup_zip_code',
        'date_received'           => 'date_received',
        'total_weight_received'   => 'total_weight_received',
        'number_of_skids'         => 'number_of_skids',
        'number_of_pieces'        => 'number_of_pieces',
        'audit_completed'         => 'audit_completed',
        'cert_of_data_wipe_num'   => 'cert_of_data_wipe_num',
        'cert_of_destruction_num' => 'cert_of_destruction_num'
    ];

    protected $defaultSimpleSearchFields = [
        'vendor_client'           => 'vendor_client',
        'lot_date'                => 'lot_date',
        'lot_number'              => 'lot_number',
        'vendor'                  => 'vendor',
        'cert_of_data_wipe_num'   => 'cert_of_data_wipe_num',
        'cert_of_destruction_num' => 'cert_of_destruction_num'
    ];

    protected $defaultSearchResultFields = [
        'lot_date'                => 'lot_date',
        'lot_number'              => 'lot_number',
        'status'                  => 'status',
        'po_number'               => 'po_number',
        'vendor_shipment_number'  => 'vendor_shipment_number',
        'site_coordinator'        => 'site_coordinator',
        'vendor'                  => 'vendor',
        'vendor_client'           => 'vendor_client',
        'bill_of_lading'          => 'bill_of_lading',
        'city_of_origin'          => 'city_of_origin',
        'schedule_pickup_date'    => 'schedule_pickup_date',
        'freight_carrier'         => 'freight_carrier',
        'inbound_tracking'        => 'inbound_tracking',
        'outbound_tracking'       => 'outbound_tracking',
        'pickup_address'          => 'pickup_address',
        'pickup_address_2'        => 'pickup_address_2',
        'pickup_city'             => 'pickup_city',
        'pickup_state'            => 'pickup_state',
        'pickup_zip_code'         => 'pickup_zip_code',
        'date_received'           => 'date_received',
        'total_weight_received'   => 'total_weight_received',
        'number_of_skids'         => 'number_of_skids',
        'number_of_pieces'        => 'number_of_pieces',
        'audit_completed'         => 'audit_completed',
        'cert_of_data_wipe_num'   => 'cert_of_data_wipe_num',
        'cert_of_destruction_num' => 'cert_of_destruction_num'
    ];

    protected $defaultExportFields = [
        'lot_date'                => 'lot_date',
        'lot_number'              => 'lot_number',
        'status'                  => 'status',
        'po_number'               => 'po_number',
        'vendor_shipment_number'  => 'vendor_shipment_number',
        'site_coordinator'        => 'site_coordinator',
        'vendor'                  => 'vendor',
        'vendor_client'           => 'vendor_client',
        'bill_of_lading'          => 'bill_of_lading',
        'city_of_origin'          => 'city_of_origin',
        'schedule_pickup_date'    => 'schedule_pickup_date',
        'freight_carrier'         => 'freight_carrier',
        'inbound_tracking'        => 'inbound_tracking',
        'outbound_tracking'       => 'outbound_tracking',
        'pickup_address'          => 'pickup_address',
        'pickup_address_2'        => 'pickup_address_2',
        'pickup_city'             => 'pickup_city',
        'pickup_state'            => 'pickup_state',
        'pickup_zip_code'         => 'pickup_zip_code',
        'date_received'           => 'date_received',
        'total_weight_received'   => 'total_weight_received',
        'number_of_skids'         => 'number_of_skids',
        'number_of_pieces'        => 'number_of_pieces',
        'audit_completed'         => 'audit_completed',
        'cert_of_data_wipe_num'   => 'cert_of_data_wipe_num',
        //'has_cert_of_data_wipe'   => 'has_cert_of_data_wipe',
        'cert_of_destruction_num' => 'cert_of_destruction_num',
        //'has_cert_of_destruction' => 'has_cert_of_destruction'
    ];

    protected $modelSearchFields = [];
    protected $modelSimpleSearchFields = [];
    protected $modelSearchResultFields = [];
    protected $modelExportFields = [];

    protected $fieldCategories = [];

    protected $vendorClients = [];
    protected $lotNumberPrefixes = [];

    /**
     * Create a new controller instance.
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->fieldCategories = [
            'exact' => [],
            'string_like' => ['status', 'freight_carrier', 'site_coordinator', 'city_of_origin', 'po_number', 'vendor_shipment_number', 'cost_center', 'vendor', 'bill_of_lading', 'freight_invoice_number',
                'pickup_address', 'pickup_address_2', 'pickup_city', 'pickup_state', 'pickup_zip_code', 'nota_fiscal_transfer', 'nota_fiscal_transfer_2',
                'nota_fiscal_transfer_3', 'nota_fiscal_transfer_4', 'nota_fiscal_transfer_5', 'equipment_summary', 'cert_of_data_wipe_num', 'cert_of_destruction_num'],
            'string_multi' => ['lot_number'],
            'date_from_to' => ['lot_date', 'lot_approved_date', 'schedule_pickup_date', 'pickup_request_date', 'actual_pickup_date', 'date_received', 'nf_received_date',
                'pre_audit_approved', 'audit_completed'],
            'int_less_greater' => ['number_of_skids', 'number_of_pieces'],
            'float_less_greater' => ['freight_charge', 'total_weight_received'],
            'custom' => ['vendor_client','has_cert_of_data_wipe','has_cert_of_destruction'],
            'not_sortable' => ['inbound_tracking', 'outbound_tracking'],
        ];

        // Turn on USE SELECT EXACT VALUES on a per field basis.
        // if (self::USE_SELECT_EXACT_VALUES) {

        //     // Applicable Shipment fields: freight_carrier, site_coordinator, city_of_origin

        //     // Turn on for the Freight Carrier
        //     $this->fieldCategories['exact'][] = 'freight_carrier';
        //     if (($index = array_search('freight_carrier', $this->fieldCategories['string_like'])) !== false) {
        //         unset($this->fieldCategories['string_like'][$index]);
        //     }
        // }

        $this->middleware('auth');
        $this->middleware('context.permissions:' . $this->context);
        $this->middleware('role:' . Role::USER . '|' . Role::SUPERUSER);

        $this->modelSearchFields = $this->site->hasFeature(Feature::SHIPMENT_CUSTOM_SEARCH_FIELDS) ? $this->site->getFeature(Feature::SHIPMENT_CUSTOM_SEARCH_FIELDS)->pivot->data : $this->defaultSearchFields;
        $this->modelSimpleSearchFields = $this->site->hasFeature(Feature::SHIPMENT_CUSTOM_SIMPLE_SEARCH_FIELDS) ? $this->site->getFeature(Feature::SHIPMENT_CUSTOM_SIMPLE_SEARCH_FIELDS)->pivot->data : $this->defaultSimpleSearchFields;
        $this->modelSearchResultFields = $this->site->hasFeature(Feature::SHIPMENT_CUSTOM_SEARCH_RESULT_FIELDS) ? $this->site->getFeature(Feature::SHIPMENT_CUSTOM_SEARCH_RESULT_FIELDS)->pivot->data : $this->defaultSearchResultFields;
        $this->modelExportFields = $this->site->hasFeature(Feature::SHIPMENT_CUSTOM_EXPORT_FIELDS) ? $this->site->getFeature(Feature::SHIPMENT_CUSTOM_EXPORT_FIELDS)->pivot->data : $this->defaultExportFields;

        // Initialize the list of searchable Vendor Clients to all those applicable to the site.
        $this->vendorClients = $this->site->vendorClients->lists('name', 'name')->toArray();
        if (Auth::user() && !Auth::user()->hasRole(Role::SUPERUSER) && $this->site->hasFeature(Feature::VENDOR_CLIENT_CODE_ACCESS_RESTRICTED)) {
            $userRestrictedVendorClients = Auth::user()->vendorClients()->lists('name', 'name')->toArray();
            if (count($userRestrictedVendorClients) > 0) {
                // The user has restricted vendor client access.  Reset the list of
                // searchable vendor clients to only those to which the client has access.
                $this->vendorClients = $userRestrictedVendorClients;
            }
        }

        // Sort Vendor Client Array
        asort($this->vendorClients);
        if (Auth::user() && $this->site->hasFeature(Feature::LOT_NUMBER_PREFIX_ACCESS_RESTRICTED) && !Auth::user()->hasRole(Role::SUPERUSER)) {
            $this->lotNumberPrefixes = Auth::user()->lotNumbers()->lists('prefix')->toArray();
            asort($this->lotNumberPrefixes);
        }
        else {
            // probably not
            // $this->lotNumberPrefixes = $this->site->lotNumbers->lists('prefix', 'prefix')->toArray();
        }
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getSearch()
    {
        $uniqueFreightCarriers = [];
        $uniqueSiteCoordinators = [];
        $uniqueCitiesOfOrigin = [];

        if (self::USE_SELECT_EXACT_VALUES) {
            if (array_key_exists('freight_carrier', $this->modelSearchFields) && in_array('freight_carrier', $this->fieldCategories['exact'])) {
                $uniqueFreightCarriersQuery = DB::table('shipment')->distinct()->select('freight_carrier')->whereNotNull('freight_carrier')->orderBy('freight_carrier','asc');
                $this->applyVendorClientQueryRestrictions($uniqueFreightCarriersQuery);
                $this->applyLotNumberPrefixRestrictions($uniqueFreightCarriersQuery, 'shipment');
                $uniqueFreightCarriers = array_pluck($uniqueFreightCarriersQuery->get(), 'freight_carrier');
            }
            if (array_key_exists('site_coordinator', $this->modelSearchFields) && in_array('site_coordinator', $this->fieldCategories['exact'])) {
                $uniqueSiteCoordinatorsQuery = DB::table('shipment')->distinct()->select('site_coordinator')->whereNotNull('site_coordinator')->orderBy('site_coordinator','asc');
                $this->applyVendorClientQueryRestrictions($uniqueSiteCoordinatorsQuery);
                $this->applyLotNumberPrefixRestrictions($uniqueSiteCoordinatorsQuery, 'shipment');
                $uniqueSiteCoordinators = array_pluck($uniqueSiteCoordinatorsQuery->get(), 'site_coordinator');
            }
            if (array_key_exists('city_of_origin', $this->modelSearchFields) && in_array('city_of_origin', $this->fieldCategories['exact'])) {
                $uniqueCitiesOfOriginQuery = DB::table('shipment')->distinct()->select('city_of_origin')->whereNotNull('city_of_origin')->orderBy('city_of_origin','asc');
                $this->applyVendorClientQueryRestrictions($uniqueCitiesOfOriginQuery);
                $this->applyLotNumberPrefixRestrictions($uniqueCitiesOfOriginQuery, 'shipment');
                $uniqueCitiesOfOrigin = array_pluck($uniqueCitiesOfOriginQuery->get(), 'city_of_origin');
            }
        }

        return view('shipment.shipmentSearch', [
            'fields' => $this->modelSearchFields,
            'simpleFields' => $this->modelSimpleSearchFields,
            'advancedFields' => array_diff($this->modelSearchFields, $this->modelSimpleSearchFields),
            'fieldCategories' => $this->fieldCategories,
            'vendorClients' => $this->vendorClients,
            'freight_carrier_values' => $uniqueFreightCarriers,
            'site_coordinator_values' => $uniqueSiteCoordinators,
            'city_of_origin_values' => $uniqueCitiesOfOrigin
        ]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getSearchResult()
    {
        $query = $this->prepareQuery();

        if (array_key_exists('lot_date', $this->modelSearchResultFields)) {
            $query = $query->sortable(['lot_date' => 'desc']);
        }
        else {
            $query = $query->sortable([]);
        }

        $shipments = $query->withCount('assets')->paginate(self::RESULTS_PER_PAGE);

        foreach ($shipments as $key => $shipment) {
            if ($shipment->inbound_tracking != '') {
                $tempTrackingInfo = null;
                $inboundTrackingArr = explode(',', $shipment->inbound_tracking);
                if (count($inboundTrackingArr) > 1) {
                    foreach ($inboundTrackingArr as $key => $inboundTrackingStr) {
                        $tempTrackingInfo[] = explode('-COL-', $inboundTrackingStr);
                    }
                }
                else {
                    $tempTrackingInfo = $inboundTrackingArr[0];
                }
                $shipment->inbound_tracking = $tempTrackingInfo;
            }
            if ($shipment->outbound_tracking != '') {
                $tempTrackingInfo = null;
                $outboundTrackingArr = explode(',', $shipment->outbound_tracking);
                if (count($outboundTrackingArr) > 1) {
                    foreach ($outboundTrackingArr as $key => $outboundTrackingStr) {
                        $tempTrackingInfo[] = explode('-COL-', $outboundTrackingStr);
                    }
                }
                else {
                    $tempTrackingInfo = $outboundTrackingArr[0];
                }
                $shipment->outbound_tracking = $tempTrackingInfo;
            }
        }

        return view('shipment.shipmentSearchResult', [
            'fields' => $this->modelSearchResultFields,
            'fieldCategories' => $this->fieldCategories,
            'shipments' => $shipments,
            'order' => $query->getQuery()->orders,
            'limit' => self::STRING_LIMIT
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function prepareQuery($id=null)
    {

        $query = Shipment::query()
          ->leftJoin('tracking_number as tracking_number_inbound', function ($joinInbound) {
            $joinInbound->on('shipment.lot_number', '=', 'tracking_number_inbound.lot_number')->where('tracking_number_inbound.package_tracking_type', '=', 'Return');
          })
          ->leftJoin('tracking_number as tracking_number_outbound', function ($joinOutbound) {
            $joinOutbound->on('shipment.lot_number', '=', 'tracking_number_outbound.lot_number')->where('tracking_number_outbound.package_tracking_type', '=', 'Sent');
          })
          ->select(
            'shipment.*',
            DB::raw(
                'GROUP_CONCAT(DISTINCT CONCAT(tracking_number_inbound.package_tracking_number, \'-COL-\', tracking_number_inbound.tracking_number_url) ORDER BY tracking_number_inbound.package_tracking_number SEPARATOR \',\') as inbound_tracking'
            ),
            DB::raw(
                'GROUP_CONCAT(DISTINCT CONCAT(tracking_number_outbound.package_tracking_number, \'-COL-\', tracking_number_outbound.tracking_number_url) ORDER BY tracking_number_outbound.package_tracking_number SEPARATOR \',\') as outbound_tracking'
            )
          )
        ;

        if (isset($id)) {
            $query->where('shipment.id', $id);
        }
        else {
            $this->applyVendorClientQueryRestrictions($query);
            $this->applyLotNumberPrefixRestrictions($query, 'shipment');

            $input = Input::all();

            foreach($input as $key => $value) {
                $input[$key] = trim($value);
            }

            foreach ($this->modelSearchFields as $field => $label) {
                if (in_array($field, $this->fieldCategories['exact'], true) && array_key_exists($field, $input) && !empty($input[$field])) {
                    $query->where($field, StringHelper::addSlashes($input[$field]));
                }

                if (in_array($field, $this->fieldCategories['string_like'], true) && array_key_exists($field, $input) && !empty($input[$field])) {
                    $query->where($field, 'like', '%' . StringHelper::addSlashes($input[$field]) . '%');
                }

                if (in_array($field, $this->fieldCategories['string_multi'], true) && array_key_exists($field, $input) && !empty($input[$field . '_select']) && !empty($input[$field])) {
                    switch ($input[$field . '_select']) {
                        case 'equals':
                            $phrase = StringHelper::addSlashes($input[$field]);
                            break;
                        case 'begins_with':
                            $phrase = StringHelper::addSlashes($input[$field]) . '%';
                            break;
                        case 'contains':
                            $phrase = '%' . StringHelper::addSlashes($input[$field]) . '%';
                            break;
                        case 'ends_in':
                            $phrase = '%' . StringHelper::addSlashes($input[$field]);
                            break;
                        default:
                            throw new \InvalidArgumentException('Invalid value');
                            break;
                    }
                    $query->where('shipment'. '.' . $field, 'like', $phrase);
                }

                if (in_array($field, $this->fieldCategories['date_from_to'], true)) {
                    if (array_key_exists($field . '_from', $input) && !empty($input[$field . '_from'])) {
                        $query->where($field, '>=', Carbon::createFromFormat(Constants::DATE_FORMAT, $input[$field . '_from'])->startOfDay());
                    }
                    if (array_key_exists($field . '_to', $input) && !empty($input[$field . '_to'])) {
                        $query->where($field, '<=', Carbon::createFromFormat(Constants::DATE_FORMAT, $input[$field . '_to'])->endOfDay());
                    }
                }

                if ((in_array($field, $this->fieldCategories['int_less_greater'], true) || in_array($field, $this->fieldCategories['float_less_greater'], true))) {
                    if (array_key_exists($field . '_greater_than', $input) && !empty($input[$field . '_greater_than'])) {
                        $query->where($field, '>=', $input[$field . '_greater_than']);
                    }
                    if (array_key_exists($field . '_less_than', $input) && !empty($input[$field . '_less_than'])) {
                        $query->where($field, '=<', $input[$field . '_less_than']);
                    }
                }

                if (in_array($field, $this->fieldCategories['custom'], true) && array_key_exists($field, $input)) {
                    if ($field === 'vendor_client') {
                        if (!empty($input[$field])) {
                            if ($input[$field] === 'all') {
                                $query->whereIn($field, $this->vendorClients);
                            }
                            else {
                                $query->where($field, StringHelper::addSlashes($input[$field]));
                            }
                        }
                    }
                }
            }

            $query->groupBy('shipment.lot_number');
        }

        return $query;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getSearchExport()
    {
        $query = $this->prepareQuery();

        /* not needed after all
        if (Input::get('page')) {
            $query->paginate(self::RESULTS_PER_PAGE, null, null, Input::get('page'));
        }
        */

        $csv = new CsvHelper();

        $header = [];

        foreach ($this->modelExportFields as $field => $label) {
            array_push($header, Lang::has('shipment.' . $label) ? Lang::trans('shipment.' . $label) : $label);
        }

        $csv->initialize($header);

        $query = $this->prepareQuery();
        $query = $query->sortable(['lot_date' => 'desc']);

        $resultCheck = $query->paginate(10000);
        $numberOfIterations = $resultCheck->lastPage();
        $currentIteration = 1;

        for ($i = $currentIteration; $i <= $numberOfIterations; $i++) {
            $query = $this->prepareQuery();
            $query = $query->sortable(['lot_date' => 'desc']);

            $paginator = $query->paginate(10000, ['*'], 'page', $i);

            $shipments = $paginator->items();
            $shipmentsCsvArray = [];

            foreach ($shipments as $shipment) {
                $shipmentElement = $shipment->toArray();
                //$shipmentElement['has_cert_of_data_wipe'] = '';
                //$shipmentElement['has_cert_of_destruction'] = '';

                $row = [];
                foreach ($this->modelExportFields as $field => $label) {
                    if (str_contains($field, 'hardcoded-')) {
                        $row[$field] = str_replace('_', ' ', str_replace('hardcoded-', '', $field));
                    }
                    else if (array_key_exists($field, $shipmentElement)) {
                        if (in_array($field, $this->fieldCategories['exact'], true) ||
                            in_array($field, $this->fieldCategories['string_like'], true) ||
                            in_array($field, $this->fieldCategories['string_multi'], true) ||
                            in_array($field, $this->fieldCategories['int_less_greater'], true) ||
                            in_array($field, $this->fieldCategories['float_less_greater'], true) ||
                            in_array($field, $this->fieldCategories['custom'], true) ||
                            in_array($field, $this->fieldCategories['not_sortable'], true)
                        ) {
                            if ($field === 'freight_charge') {
                                $row[$field] = Constants::CURRENCY_SYMBOL . $shipmentElement[$field];
                            }
                            else {
                                $row[$field] = $shipmentElement[$field];
                            }

                            if ($field === 'inbound_tracking' || $field === 'outbound_tracking') {
                                $row[$field] = '';
                                if ($shipmentElement[$field] != '') {
                                    $tempTrackingNumbers = null;
                                    $trackingInfoArr = explode(',', $shipmentElement[$field]);
                                    foreach ($trackingInfoArr as $key => $trackingInfoStr) {
                                        $trackingInfo = explode('-COL-', $trackingInfoStr);
                                        $tempTrackingNumbers[] = $trackingInfo[0];
                                    }
                                    $row[$field] = implode(PHP_EOL, $tempTrackingNumbers);
                                }
                            }

                            if ($field === 'has_cert_of_data_wipe') {
								$row[$field] = 'No';
                                if (isset($shipment->certOfDataWipeNum)) {
                                    if ($this->site->hasFeature(Feature::HAS_CERTIFICATES)) {
                                        $certOfDataWipePage = $this->site->pages->where('type', 'Certificates of Data Wipe')->first();
                                        $certOfDataWipe = $certOfDataWipePage ? $shipment->files->where('page_id', $certOfDataWipePage->id)->first() : null;
                                        if ($certOfDataWipe) {
                                            if ($this->site->hasFeature(Feature::CERTIFICATE_OF_DATA_WIPE_NUMBER_AS_FILE) && isset($shipment->auditCompleted)) {
                                                $row[$field] = 'Yes (' . $certOfDataWipe->filename . ')';
                                            }
                                        }
                                    }
                                }
                            }

							if ($field === 'has_cert_of_destruction') {
								$row[$field] = 'No';
								if (isset($shipment->certOfDestructionNum)) {
									if ($this->site->hasFeature(Feature::HAS_CERTIFICATES)) {
										$certOfDestructionPage = $this->site->pages->where('type', 'Certificates of Recycling')->first();
										$certOfDestruction = $certOfDestructionPage ? $shipment->files->where('page_id', $certOfDestructionPage->id)->first() : null;
										if ($certOfDestruction) {
											if ($this->site->hasFeature(Feature::CERTIFICATE_OF_DESTRUCTION_NUMBER_AS_FILE) && isset($shipment->auditCompleted)) {
												$row[$field] = 'Yes (' . $certOfDestruction->filename . ')';
											}
										}
									}
								}
							}
                        }
                        else if (in_array($field, $this->fieldCategories['date_from_to'], true)) {
                            try {
                                $row[$field] = !$shipmentElement[$field] ? null : Carbon::createFromFormat('Y-m-d H:i:s', $shipmentElement[$field])->format(Constants::DATE_FORMAT);
                            } catch (\InvalidArgumentException $e) {
                                $row[$field] = !$shipmentElement[$field] ? null : Carbon::createFromFormat('Y-m-d', $shipmentElement[$field])->format(Constants::DATE_FORMAT);
                            }
                        }
                        else {
                            $row[$field] = '';
                        }
                    }
                    else {
                        $row[$field] = '';
                    }
                }

                array_push($shipmentsCsvArray, $row);
            }

            $csv->addRows($shipmentsCsvArray);
        }

        $csv->finalize();

        $filename = $this->site->code . '_shipments_' . Carbon::now()->format('mdY') . '.csv';

        return $csv->download($filename);
    }

    /**
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getDetails($context = null, $id)
    {
        //$shipment = Shipment::where('id', $id)->first();

        // $shipment = Shipment::where('shipment.id', $id)
        //   ->leftJoin('tracking_number as tracking_number_inbound', function ($joinInbound) {
        //     $joinInbound->on('shipment.lot_number', '=', 'tracking_number_inbound.lot_number')->where('tracking_number_inbound.package_tracking_type', '=', 'Return');
        //   })
        //   ->leftJoin('tracking_number as tracking_number_outbound', function ($joinOutbound) {
        //     $joinOutbound->on('shipment.lot_number', '=', 'tracking_number_outbound.lot_number')->where('tracking_number_outbound.package_tracking_type', '=', 'Sent');
        //   })
        //   ->select(
        //     'shipment.*',
        //     DB::raw(
        //         'GROUP_CONCAT(DISTINCT CONCAT(tracking_number_inbound.package_tracking_number, \'-COL-\', tracking_number_inbound.tracking_number_url) ORDER BY tracking_number_inbound.package_tracking_number SEPARATOR \',\') as inbound_tracking'
        //     ),
        //     DB::raw(
        //         'GROUP_CONCAT(DISTINCT CONCAT(tracking_number_outbound.package_tracking_number, \'-COL-\', tracking_number_outbound.tracking_number_url) ORDER BY tracking_number_outbound.package_tracking_number SEPARATOR \',\') as outbound_tracking'
        //     )
        //   )
        //   ->first();
        // ;

        $query = $this->prepareQuery($id);
        $shipment = $query->first();

        if (!$shipment) {
            throw new NotFoundHttpException();
        }

        if ($this->site->hasFeature(Feature::VENDOR_CLIENT_CODE_ACCESS_RESTRICTED) && !Auth::user()->hasRole(Role::SUPERUSER)) {
            if (!in_array($shipment->vendorClient, $this->vendorClients)) {
                throw new NotFoundHttpException();
            }
        }

        if ($this->site->hasFeature(Feature::LOT_NUMBER_PREFIX_ACCESS_RESTRICTED) && !Auth::user()->hasRole(Role::SUPERUSER)) {
            if (count($this->lotNumberPrefixes) > 0) {
                $error = true;

                foreach ($this->lotNumberPrefixes as $prefix) {
                    if (starts_with($shipment->lotNumber, $prefix)) {
                        $error = false;
                    }
                }

                if ($error) {
                    throw new NotFoundHttpException();
                }
            }
        }

        if ($shipment->inbound_tracking != '') {
            $tempTrackingInfo = null;
            $inboundTrackingArr = explode(',', $shipment->inbound_tracking);
            if (count($inboundTrackingArr) > 1) {
                foreach ($inboundTrackingArr as $key => $inboundTrackingStr) {
                    $tempTrackingInfo[] = explode('-COL-', $inboundTrackingStr);
                }
            }
            else {
                $tempTrackingInfo = $inboundTrackingArr[0];
            }
            $shipment->inbound_tracking = $tempTrackingInfo;
        }

        if ($shipment->outbound_tracking != '') {
            $tempTrackingInfo = null;
            $outboundTrackingArr = explode(',', $shipment->outbound_tracking);
            if (count($outboundTrackingArr) > 1) {
                foreach ($outboundTrackingArr as $key => $outboundTrackingStr) {
                    $tempTrackingInfo[] = explode('-COL-', $outboundTrackingStr);
                }
            }
            else {
                $tempTrackingInfo = $outboundTrackingArr[0];
            }
            $shipment->outbound_tracking = $tempTrackingInfo;
        }

        return view('shipment.shipmentDetails', [
            'fields' => $this->modelSearchResultFields,
            'shipment' => $shipment
        ]);
    }

    /**
     * @return \Redirect
     */
    public function postModifySearch()
    {
        return redirect()->route('shipment.search')->withInput();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyVendorClientQueryRestrictions($query, $queryPrefix = null) {
        if ($queryPrefix) {
            $query->whereIn($queryPrefix . '.' . 'vendor_client', $this->vendorClients);
        }
        else {
            $query->whereIn('vendor_client', $this->vendorClients);
        }

        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyLotNumberPrefixRestrictions($query, $queryPrefix = null) {
        if (count($this->lotNumberPrefixes) > 0) {
            $query->where(function($subquery) use ($queryPrefix) {
                foreach ($this->lotNumberPrefixes as $prefix) {
                    if ($prefix == head($this->lotNumberPrefixes)) {
                        if ($queryPrefix) {
                            $subquery->where($queryPrefix . '.' . 'lot_number', 'like', $prefix . '%');
                        }
                        else {
                            $subquery->where('lot_number', 'like', $prefix . '%');
                        }
                    }
                    else {
                        if ($queryPrefix) {
                            $subquery->orWhere($queryPrefix . '.' . 'lot_number', 'like', $prefix . '%');
                        }
                        else {
                            $subquery->orWhere('lot_number', 'like', $prefix . '%');
                        }
                    }
                }
            });
        }

        return $query;
    }
}
