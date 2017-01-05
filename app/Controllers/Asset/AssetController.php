<?php

namespace App\Controllers\Asset;

use App\Controllers\ContextController;
use App\Data\Constants;
use App\Data\Models\Asset;
use App\Data\Models\Feature;
use App\Data\Models\Role;
use App\Helpers\CsvHelper;
use App\Helpers\StringHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Lang;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AssetController extends ContextController
{
    const RESULTS_PER_PAGE = 50;
    const USE_SELECT_EXACT_VALUES = false;
    const STRING_LIMIT = 50;

    protected $defaultSearchFields = [
        'vendor_client' => 'vendor_client',
        'lot_date' => 'lot_date',
        'lot_number' => 'lot_number',
        'bill_of_lading' => 'bill_of_lading',
        'city_of_origin' => 'city_of_origin',
        'freight_carrier' => 'freight_carrier',
        'po_number' => 'po_number',
        'vendor_shipment_number' => 'vendor_shipment_number',
        'vendor' => 'vendor',
        'date_arrived' => 'date_arrived',
        'barcode_number' => 'barcode_number',
        'product_family' => 'product_family',
        'manufacturer' => 'manufacturer',
        'manufacturer_model_num' => 'manufacturer_model_num',
        'manufacturer_part_num' => 'manufacturer_part_num',
        'manufacturer_serial_num' => 'manufacturer_serial_num',
        'parent_serial_num' => 'parent_serial_num',
        'item_number' => 'item_number',
        'form_factor' => 'form_factor',
        'speed' => 'speed',
        'memory' => 'memory',
        'storage_capacity' => 'storage_capacity',
        'optical_1' => 'optical_1',
        'optical_2' => 'optical_2',
        'nic' => 'nic',
        'video' => 'video',
        'color' => 'color',
        'adapter' => 'adapter',
        'screen_size' => 'screen_size',
        'battery' => 'battery',
        'wifi' => 'wifi',
        'docking_station' => 'docking_station',
        'stylus' => 'stylus',
        'firewire' => 'firewire',
        'keyboard' => 'keyboard',
        'mouse' => 'mouse',
        'cartridge' => 'cartridge',
        'coa' => 'coa',
        'osx_description' => 'osx_description',
        'condition' => 'condition',
        'date_code' => 'date_code',
        'comments' => 'comments',
        'additional_comments' => 'additional_comments',
        'asset_tag' => 'asset_tag',
        'status' => 'status',
        'audit_completed' => 'audit_completed',
        'cert_of_data_wipe_num' => 'cert_of_data_wipe_num',
        'cert_of_destruction_num' => 'cert_of_destruction_num'
    ];

    protected $defaultSimpleSearchFields = [
        'vendor_client' => 'vendor_client',
        'lot_date' => 'lot_date',
        'lot_number' => 'lot_number',
        'barcode_number' => 'barcode_number',
        'cert_of_data_wipe_num' => 'cert_of_data_wipe_num',
        'cert_of_destruction_num' => 'cert_of_destruction_num'
    ];

    protected $defaultSearchResultFields = [
        'lot_date' => 'lot_date',
        'lot_number' => 'lot_number',
        'bill_of_lading' => 'bill_of_lading',
        'city_of_origin' => 'city_of_origin',
        'freight_carrier' => 'freight_carrier',
        'po_number' => 'po_number',
        'vendor_shipment_number' => 'vendor_shipment_number',
        'vendor' => 'vendor',
        'vendor_client' => 'vendor_client',
        'date_arrived' => 'date_arrived',
        'barcode_number' => 'barcode_number',
        'product_family' => 'product_family',
        'manufacturer' => 'manufacturer',
        'manufacturer_model_num' => 'manufacturer_model_num',
        'manufacturer_part_num' => 'manufacturer_part_num',
        'manufacturer_serial_num' => 'manufacturer_serial_num',
        'parent_serial_num' => 'parent_serial_num',
        'item_number' => 'item_number',
        'form_factor' => 'form_factor',
        'speed' => 'speed',
        'memory' => 'memory',
        'storage_capacity' => 'storage_capacity',
        'optical_1' => 'optical_1',
        'optical_2' => 'optical_2',
        'nic' => 'nic',
        'video' => 'video',
        'color' => 'color',
        'adapter' => 'adapter',
        'screen_size' => 'screen_size',
        'battery' => 'battery',
        'wifi' => 'wifi',
        'docking_station' => 'docking_station',
        'stylus' => 'stylus',
        'firewire' => 'firewire',
        'keyboard' => 'keyboard',
        'mouse' => 'mouse',
        'cartridge' => 'cartridge',
        'coa' => 'coa',
        'osx_description' => 'osx_description',
        'condition' => 'condition',
        'date_code' => 'date_code',
        'comments' => 'comments',
        'additional_comments' => 'additional_comments',
        'asset_tag' => 'asset_tag',
        'status' => 'status',
        'audit_completed' => 'audit_completed',
        'cert_of_data_wipe_num' => 'cert_of_data_wipe_num',
        'cert_of_destruction_num' => 'cert_of_destruction_num'
    ];

    protected $defaultExportFields = [
        'lot_date' => 'lot_date',
        'lot_number' => 'lot_number',
        'bill_of_lading' => 'bill_of_lading',
        'city_of_origin' => 'city_of_origin',
        'freight_carrier' => 'freight_carrier',
        'po_number' => 'po_number',
        'vendor_shipment_number' => 'vendor_shipment_number',
        'vendor' => 'vendor',
        'vendor_client' => 'vendor_client',
        'date_arrived' => 'date_arrived',
        'barcode_number' => 'barcode_number',
        'product_family' => 'product_family',
        'manufacturer' => 'manufacturer',
        'manufacturer_model_num' => 'manufacturer_model_num',
        'manufacturer_part_num' => 'manufacturer_part_num',
        'manufacturer_serial_num' => 'manufacturer_serial_num',
        'parent_serial_num' => 'parent_serial_num',
        'item_number' => 'item_number',
        'form_factor' => 'form_factor',
        'speed' => 'speed',
        'memory' => 'memory',
        'storage_capacity' => 'storage_capacity',
        'optical_1' => 'optical_1',
        'optical_2' => 'optical_2',
        'nic' => 'nic',
        'video' => 'video',
        'color' => 'color',
        'adapter' => 'adapter',
        'screen_size' => 'screen_size',
        'battery' => 'battery',
        'wifi' => 'wifi',
        'docking_station' => 'docking_station',
        'stylus' => 'stylus',
        'firewire' => 'firewire',
        'keyboard' => 'keyboard',
        'mouse' => 'mouse',
        'cartridge' => 'cartridge',
        'coa' => 'coa',
        'osx_description' => 'osx_description',
        'condition' => 'condition',
        'date_code' => 'date_code',
        'comments' => 'comments',
        'additional_comments' => 'additional_comments',
        'asset_tag' => 'asset_tag',
        'status' => 'status',
        'audit_completed' => 'audit_completed',
        'cert_of_data_wipe_num' => 'cert_of_data_wipe_num',
        'cert_of_destruction_num' => 'cert_of_destruction_num'
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

        $this->fieldCategories = self::USE_SELECT_EXACT_VALUES ?
        [
            'exact' => ['carrier', 'manufacturer', 'product_family', 'condition', 'date_code', 'status'],
            'string_like' => ['vendor_order_number', 'vendor', 'barcode_number', 'manufacturer_model_num', 'manufacturer_part_num',
                'parent_serial_num', 'item_number', 'form_factor', 'speed', 'memory', 'storage_capacity', 'dual', 'quad', 'optical_1', 'optical_2', 'nic', 'video', 'color',
                'adapter', 'screen_size', 'battery', 'wifi', 'docking_station', 'stylus', 'firewire', 'keyboard', 'mouse', 'cartridge', 'coa', 'osx_description',
                'comments', 'additional_comments', 'hard_drive_serial_num', 'asset_tag', 'cert_of_data_wipe_num', 'cert_of_destruction_num'],
            'string_multi' => ['lot_number', 'manufacturer_serial_num'],
            'date_from_to' => ['lot_date', 'date_arrived', 'shipment_date'],
            'int_less_greater' => [],
            'float_less_greater' => ['settlement_amount', 'net_settlement'],
            'custom' => ['vendor_client'],
            'shipment' => [
                'exact' => ['city_of_origin', 'freight_carrier'],
                'string_like' => ['bill_of_lading', 'cost_center', 'pickup_address', 'pickup_address_2', 'pickup_city', 'pickup_state', 'pickup_zip_code', 'po_number', 'vendor_shipment_number'],
                'string_multi' => [],
                'date_from_to' => ['date_received', 'pre_audit_approved', 'audit_completed'],
                'int_less_greater' => [],
                'float_less_greater' => [],
                'custom' => []
            ]
        ]
        :
        [
            'exact' => [],
            'string_like' => ['carrier', 'manufacturer', 'product_family', 'condition', 'date_code', 'status', 'vendor_order_number', 'vendor', 'barcode_number', 'manufacturer_model_num', 'manufacturer_part_num',
                'parent_serial_num', 'item_number', 'form_factor', 'speed', 'memory', 'storage_capacity', 'dual', 'quad', 'optical_1', 'optical_2', 'nic', 'video', 'color',
                'adapter', 'screen_size', 'battery', 'wifi', 'docking_station', 'stylus', 'firewire', 'keyboard', 'mouse', 'cartridge', 'coa', 'osx_description',
                'comments', 'additional_comments', 'hard_drive_serial_num', 'asset_tag', 'cert_of_data_wipe_num', 'cert_of_destruction_num'],
            'string_multi' => ['lot_number', 'manufacturer_serial_num'],
            'date_from_to' => ['lot_date', 'date_arrived', 'shipment_date'],
            'int_less_greater' => [],
            'float_less_greater' => ['settlement_amount', 'net_settlement'],
            'custom' => ['vendor_client'],
            'shipment' => [
                'exact' => [],
                'string_like' => ['city_of_origin', 'freight_carrier', 'bill_of_lading', 'cost_center', 'pickup_address', 'pickup_address_2', 'pickup_city', 'pickup_state', 'pickup_zip_code', 'po_number', 'vendor_shipment_number'],
                'string_multi' => [],
                'date_from_to' => ['date_received', 'pre_audit_approved', 'audit_completed'],
                'int_less_greater' => [],
                'float_less_greater' => [],
                'custom' => []
            ]
        ];

        $this->middleware('auth');
        $this->middleware('context.permissions:' . $this->context);
        $this->middleware('role:' . Role::USER . '|' . Role::SUPERUSER);

        $this->modelSearchFields = $this->site->hasFeature(Feature::ASSET_CUSTOM_SEARCH_FIELDS) ? $this->site->getFeature(Feature::ASSET_CUSTOM_SEARCH_FIELDS)->pivot->data : $this->defaultSearchFields;
        $this->modelSimpleSearchFields = $this->site->hasFeature(Feature::ASSET_CUSTOM_SIMPLE_SEARCH_FIELDS) ? $this->site->getFeature(Feature::ASSET_CUSTOM_SIMPLE_SEARCH_FIELDS)->pivot->data : $this->defaultSimpleSearchFields;
        $this->modelSearchResultFields = $this->site->hasFeature(Feature::ASSET_CUSTOM_SEARCH_RESULT_FIELDS) ? $this->site->getFeature(Feature::ASSET_CUSTOM_SEARCH_RESULT_FIELDS)->pivot->data : $this->defaultSearchResultFields;
        $this->modelExportFields = $this->site->hasFeature(Feature::ASSET_CUSTOM_EXPORT_FIELDS) ? $this->site->getFeature(Feature::ASSET_CUSTOM_EXPORT_FIELDS)->pivot->data : $this->defaultExportFields;

        if (Auth::user() && $this->site->hasFeature(Feature::VENDOR_CLIENT_CODE_ACCESS_RESTRICTED) && !Auth::user()->hasRole(Role::SUPERUSER)) {
            $this->vendorClients = Auth::user()->vendorClients()->lists('name', 'name')->toArray();
        }
        else {
            $this->vendorClients = $this->site->vendorClients->lists('name', 'name')->toArray();
        }

        if (Auth::user() && $this->site->hasFeature(Feature::LOT_NUMBER_PREFIX_ACCESS_RESTRICTED) && !Auth::user()->hasRole(Role::SUPERUSER)) {
            $this->lotNumberPrefixes = Auth::user()->lotNumbers()->lists('prefix')->toArray();
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
        $uniqueCarriers = [];
        $uniqueManufacturers = [];
        $uniqueProductFamilies = [];
        $uniqueConditions = [];
        $uniqueDateCodes = [];
        $uniqueStatuses = [];

        if (self::USE_SELECT_EXACT_VALUES) {
            if (array_key_exists('freight_carrier', $this->modelSearchFields)) {
                $uniqueFreightCarriersQuery = DB::table('shipment')->distinct()->select('freight_carrier')->whereNotNull('freight_carrier');
                $this->applyVendorClientQueryRestrictions($uniqueFreightCarriersQuery);
                $this->applyLotNumberPrefixRestrictions($uniqueFreightCarriersQuery);
                $uniqueFreightCarriers = array_pluck($uniqueFreightCarriersQuery->get(), 'freight_carrier');
            }
            if (array_key_exists('site_coordinator', $this->modelSearchFields)) {
                $uniqueSiteCoordinatorsQuery = DB::table('shipment')->distinct()->select('site_coordinator')->whereNotNull('site_coordinator');
                $this->applyVendorClientQueryRestrictions($uniqueSiteCoordinatorsQuery);
                $this->applyLotNumberPrefixRestrictions($uniqueSiteCoordinatorsQuery);
                $uniqueSiteCoordinators = array_pluck($uniqueSiteCoordinatorsQuery->get(), 'site_coordinator');
            }
            if (array_key_exists('city_of_origin', $this->modelSearchFields)) {
                $uniqueCitiesOfOriginQuery = DB::table('shipment')->distinct()->select('city_of_origin')->whereNotNull('city_of_origin');
                $this->applyVendorClientQueryRestrictions($uniqueCitiesOfOriginQuery);
                $this->applyLotNumberPrefixRestrictions($uniqueCitiesOfOriginQuery);
                $uniqueCitiesOfOrigin = array_pluck($uniqueCitiesOfOriginQuery->get(), 'city_of_origin');
            }
            if (array_key_exists('carrier', $this->modelSearchFields)) {
                $uniqueCarriersQuery = DB::table('asset')->distinct()->select('carrier')->whereNotNull('carrier');
                $this->applyVendorClientQueryRestrictions($uniqueCarriersQuery);
                $this->applyLotNumberPrefixRestrictions($uniqueCarriersQuery);
                $uniqueCarriers = array_pluck($uniqueCarriersQuery->get(), 'carrier');
            }
            if (array_key_exists('manufacturer', $this->modelSearchFields)) {
                $uniqueManufacturersQuery = DB::table('asset')->distinct()->select('manufacturer')->whereNotNull('manufacturer');
                $this->applyVendorClientQueryRestrictions($uniqueManufacturersQuery);
                $this->applyLotNumberPrefixRestrictions($uniqueManufacturersQuery);
                $uniqueManufacturers = array_pluck($uniqueManufacturersQuery->get(), 'manufacturer');
            }
            if (array_key_exists('product_family', $this->modelSearchFields)) {
                $uniqueProductFamiliesQuery = DB::table('asset')->distinct()->select('product_family')->whereNotNull('product_family');
                $this->applyVendorClientQueryRestrictions($uniqueProductFamiliesQuery);
                $this->applyLotNumberPrefixRestrictions($uniqueProductFamiliesQuery);
                $uniqueProductFamilies = array_pluck($uniqueProductFamiliesQuery->get(), 'product_family');
            }
            if (array_key_exists('condition', $this->modelSearchFields)) {
                $uniqueConditionsQuery = DB::table('asset')->distinct()->select('condition')->whereNotNull('condition');
                $this->applyVendorClientQueryRestrictions($uniqueConditionsQuery);
                $this->applyLotNumberPrefixRestrictions($uniqueConditionsQuery);
                $uniqueConditions = array_pluck($uniqueConditionsQuery->get(), 'condition');
            }
            if (array_key_exists('date_code', $this->modelSearchFields)) {
                $uniqueDateCodesQuery = DB::table('asset')->distinct()->select('date_code')->whereNotNull('date_code');
                $this->applyVendorClientQueryRestrictions($uniqueDateCodesQuery);
                $this->applyLotNumberPrefixRestrictions($uniqueDateCodesQuery);
                $uniqueDateCodes = array_pluck($uniqueDateCodesQuery->get(), 'date_code');
            }
            if (array_key_exists('status', $this->modelSearchFields)) {
                $uniqueStatusesQuery = DB::table('asset')->distinct()->select('status')->whereNotNull('status');
                $this->applyVendorClientQueryRestrictions($uniqueStatusesQuery);
                $this->applyLotNumberPrefixRestrictions($uniqueStatusesQuery);
                $uniqueStatuses = array_pluck($uniqueStatusesQuery->get(), 'status');
            }
        }

        return view('asset.assetSearch', [
            'fields' => $this->modelSearchFields,
            'simpleFields' => $this->modelSimpleSearchFields,
            'advancedFields' => array_diff($this->modelSearchFields, $this->modelSimpleSearchFields),
            'fieldCategories' => $this->fieldCategories,
            'vendorClients' => $this->vendorClients,
            'freight_carrier_values' => $uniqueFreightCarriers,
            'site_coordinator_values' => $uniqueSiteCoordinators,
            'carrier_values' => $uniqueCarriers,
            'manufacturer_values' => $uniqueManufacturers,
            'product_family_values' => $uniqueProductFamilies,
            'condition_values' => $uniqueConditions,
            'date_code_values' => $uniqueDateCodes,
            'status_values' => $uniqueStatuses,
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
            $query = $query->sortable(['asset.lot_date' => 'desc']);
        }
        else {
            $query = $query->sortable([]);
        }

        $assets = $query->paginate(self::RESULTS_PER_PAGE);

        return view('asset.assetSearchResult', [
            'fields' => $this->modelSearchResultFields,
            'fieldCategories' => $this->fieldCategories,
            'assets' => $assets,
            'order' => $query->getQuery()->orders,
            'limit' => self::STRING_LIMIT
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function prepareQuery()
    {
        $query = Asset::query()->select('asset.*')->leftjoin('shipment', 'asset.lot_number', '=', 'shipment.lot_number')->with('shipment');

        $this->applyVendorClientQueryRestrictions($query, 'asset');
        $this->applyLotNumberPrefixRestrictions($query, 'asset');

        $input = Input::all();

        foreach($input as $key => $value) {
            $input[$key] = trim($value);
        }

        foreach ($this->modelSearchFields as $field => $label) {
            // Asset
            if (in_array($field, $this->fieldCategories['exact'], true) && array_key_exists($field, $input) && !empty($input[$field])) {
                $query->where('asset.' . $field, StringHelper::addSlashes($input[$field]));
            }

            if (in_array($field, $this->fieldCategories['string_like'], true) && array_key_exists($field, $input) && !empty($input[$field])) {
                $query->where('asset.' . $field, 'like', '%' . StringHelper::addSlashes($input[$field]) . '%');
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
                $query->where('asset.' . $field, 'like', $phrase);
            }

            if (in_array($field, $this->fieldCategories['date_from_to'], true)) {
                if (array_key_exists($field . '_from', $input) && !empty($input[$field . '_from'])) {
                    $query->where('asset.' . $field, '>=', Carbon::createFromFormat(Constants::DATE_FORMAT, $input[$field . '_from'])->startOfDay());
                }
                if (array_key_exists($field . '_to', $input) && !empty($input[$field . '_to'])) {
                    $query->where('asset.' . $field, '<=', Carbon::createFromFormat(Constants::DATE_FORMAT, $input[$field . '_to'])->endOfDay());
                }
            }

            if ((in_array($field, $this->fieldCategories['int_less_greater'], true) || in_array($field, $this->fieldCategories['float_less_greater'], true))) {
                if (array_key_exists($field . '_greater_than', $input) && !empty($input[$field . '_greater_than'])) {
                    $query->where('asset.' . $field, '>=', $input[$field . '_greater_than']);
                }
                if (array_key_exists($field . '_less_than', $input) && !empty($input[$field . '_less_than'])) {
                    $query->where('asset.' . $field, '<=', $input[$field . '_less_than']);
                }
            }

            if (in_array($field, $this->fieldCategories['custom'], true) && array_key_exists($field, $input)) {
                if ($field === 'vendor_client') {
                    if (!empty($input[$field])) {
                        if ($input[$field] === 'all') {
                            $query->whereIn('asset.' . $field, $this->vendorClients);
                        }
                        else {
                            $query->where('asset.' . $field, StringHelper::addSlashes($input[$field]));
                        }
                    }
                }
            }

            // Shipment

            if (in_array($field, $this->fieldCategories['shipment']['exact'], true) && array_key_exists($field, $input) && !empty($input[$field])) {
                $query->where('shipment.' . $field, StringHelper::addSlashes($input[$field]));
            }

            if (in_array($field, $this->fieldCategories['shipment']['string_like'], true) && array_key_exists($field, $input) && !empty($input[$field])) {
                $query->where('shipment.' . $field, 'like', '%' . StringHelper::addSlashes($input[$field]) . '%');
            }

            if (in_array($field, $this->fieldCategories['shipment']['string_multi'], true) && array_key_exists($field, $input) && !empty($input[$field . '_select']) && !empty($input[$field])) {
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
                $query->where('shipment.' . $field, 'like', $phrase);
            }

            if (in_array($field, $this->fieldCategories['shipment']['date_from_to'], true)) {
                if ((array_key_exists($field . '_from', $input) && !empty($input[$field . '_from'])) || (array_key_exists($field . '_to', $input) && !empty($input[$field . '_to']))) {
                    if (array_key_exists($field . '_from', $input) && !empty($input[$field . '_from'])) {
                        $query->where('shipment.' . $field, '>=', Carbon::createFromFormat(Constants::DATE_FORMAT, $input[$field . '_from'])->startOfDay());
                    }
                    if (array_key_exists($field . '_to', $input) && !empty($input[$field . '_to'])) {
                        $query->where('shipment.' . $field, '<=', Carbon::createFromFormat(Constants::DATE_FORMAT, $input[$field . '_to'])->endOfDay());
                    }
                }
            }

            if (in_array($field, $this->fieldCategories['shipment']['int_less_greater'], true) || in_array($field, $this->fieldCategories['shipment']['float_less_greater'], true)) {
                if ((array_key_exists($field . '_greater_than', $input) && !empty($input[$field . '_greater_than'])) || (array_key_exists($field . '_less_than', $input) && !empty($input[$field . '_less_than']))) {
                    if (array_key_exists($field . '_greater_than', $input) && !empty($input[$field . '_greater_than'])) {
                        $query->where('shipment.' . $field, '>=', $input[$field . '_greater_than']);
                    }
                    if (array_key_exists($field . '_less_than', $input) && !empty($input[$field . '_less_than'])) {
                        $query->where('shipment.' . $field, '<=', $input[$field . '_less_than']);
                    }
                }
            }

            if (in_array($field, $this->fieldCategories['shipment']['custom'], true) && array_key_exists($field, $input)) {
                if ($field === 'vendor_client') {
                    if (!empty($input[$field])) {
                        if ($input[$field] === 'all') {
                            $query->whereIn('shipment.' . $field, $this->vendorClients);
                        }
                        else {
                            $query->where('shipment.' . $field, StringHelper::addSlashes($input[$field]));
                        }
                    }
                }
            }
        }

        return $query;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getSearchExport()
    {
        /* not needed after all
        if (Input::get('page')) {
           $query->paginate(self::RESULTS_PER_PAGE, null, null, Input::get('page'));
        }
        */

        $siteHasCustomEmptyStatus = $this->site->hasFeature(Feature::ASSET_CUSTOM_EMPTY_STATUS);
        $siteCustomEmptyStatus = null;
        if ($siteHasCustomEmptyStatus) {
            $siteCustomEmptyStatus = $this->site->getFeature(Feature::ASSET_CUSTOM_EMPTY_STATUS);
        }

        $siteHasCustomProductFamilyForCertificateOfDataWipeNumber = $this->site->hasFeature(Feature::CUSTOM_PRODUCT_FAMILY_FOR_CERTIFICATE_OF_DATA_WIPE_NUMBER);
        $siteCustomProductFamilyForCertificateOfDataWipeNumber = null;

        if ($siteHasCustomProductFamilyForCertificateOfDataWipeNumber) {
            $siteCustomProductFamilyForCertificateOfDataWipeNumber = $this->site->getFeature(Feature::CUSTOM_PRODUCT_FAMILY_FOR_CERTIFICATE_OF_DATA_WIPE_NUMBER);
        }

        $siteHasCustomStatusForCertificateOfDestructionNumber = $this->site->hasFeature(Feature::CUSTOM_STATUS_FOR_CERTIFICATE_OF_DESTRUCTION_NUMBER);
        $siteCustomStatusForCertificateOfDestructionNumber = null;

        if ($siteHasCustomStatusForCertificateOfDestructionNumber) {
            $siteCustomStatusForCertificateOfDestructionNumber = $this->site->getFeature(Feature::CUSTOM_STATUS_FOR_CERTIFICATE_OF_DESTRUCTION_NUMBER);
        }

        $csv = new CsvHelper();

        $header = [];

        foreach ($this->modelExportFields as $field => $label) {
            array_push($header, Lang::has('asset.' . $label) ? Lang::trans('asset.' . $label) : $label);
        }

        $csv->initialize($header);

        $query = $this->prepareQuery();
        $query = $query->sortable(['asset.lot_date' => 'desc']);

        $resultCheck = $query->paginate(10000);
        $numberOfIterations = $resultCheck->lastPage();
        $currentIteration = 1;

        for ($i = $currentIteration; $i <= $numberOfIterations; $i++) {
            $query = $this->prepareQuery();
            $query = $query->sortable(['asset.lot_date' => 'desc']);

            $paginator = $query->paginate(10000, ['*'], 'page', $i);

            $assets = $paginator->items();
            $assetsCsvArray = [];

            foreach ($assets as $asset) {
                $assetElement = $asset->toArray();

                $row = [];
                foreach ($this->modelExportFields as $field => $label) {
                    if (array_key_exists($field, $assetElement)) {
                        if (in_array($field, $this->fieldCategories['exact'], true) ||
                            in_array($field, $this->fieldCategories['string_like'], true) ||
                            in_array($field, $this->fieldCategories['string_multi'], true) ||
                            in_array($field, $this->fieldCategories['int_less_greater'], true) ||
                            in_array($field, $this->fieldCategories['float_less_greater'], true) ||
                            in_array($field, $this->fieldCategories['custom'], true)
                        ) {
                            if ($field === 'status' && empty($assetElement[$field]) && $siteHasCustomEmptyStatus) {
                                if (!$customStatus = $siteCustomEmptyStatus->pivot->data) {
                                    $customStatus = $siteCustomEmptyStatus->data;
                                }

                                $row[$field] = $customStatus;
                            }
                            else if (($field === 'net_settlement') || ($field === 'settlement_amount')) {
                                $row[$field] = '-';
                            }
                            else if ($field === 'cert_of_data_wipe_num' && $siteHasCustomProductFamilyForCertificateOfDataWipeNumber) {
                                if (!$productFamilyArray = $siteCustomProductFamilyForCertificateOfDataWipeNumber->pivot->data) {
                                    $productFamilyArray = $siteCustomProductFamilyForCertificateOfDataWipeNumber->data;
                                }

                                if (in_array($assetElement['product_family'], $productFamilyArray)) {
                                    $row[$field] = $assetElement[$field];
                                } else {
                                    $row[$field] = '-';
                                }

                            }
                            else if ($field === 'cert_of_destruction_num' && $siteHasCustomStatusForCertificateOfDestructionNumber) {
                                if (!$statusArray = $siteCustomStatusForCertificateOfDestructionNumber->pivot->data) {
                                    $statusArray = $siteCustomStatusForCertificateOfDestructionNumber->data;
                                }

                                if (in_array($assetElement['status'], $statusArray)) {
                                    $row[$field] = $assetElement[$field];
                                } else {
                                    $row[$field] = '-';
                                }

                            }
                            else {
                                $row[$field] = $assetElement[$field];
                            }
                        }
                        else if (in_array($field, $this->fieldCategories['date_from_to'], true)) {
                            try {
                                $row[$field] = !$assetElement[$field] ? null : Carbon::createFromFormat('Y-m-d H:i:s', $assetElement[$field])->format(Constants::DATE_FORMAT);
                            } catch (\InvalidArgumentException $e) {
                                $row[$field] = !$assetElement[$field] ? null : Carbon::createFromFormat('Y-m-d', $assetElement[$field])->format(Constants::DATE_FORMAT);
                            }
                        }
                        else {
                            $row[$field] = '-';
                        }
                    }
                    else if ($assetElement['shipment'] && array_key_exists($field, $assetElement['shipment'])) {
                        if (in_array($field, $this->fieldCategories['shipment']['exact'], true) ||
                            in_array($field, $this->fieldCategories['shipment']['string_like'], true) ||
                            in_array($field, $this->fieldCategories['shipment']['string_multi'], true) ||
                            in_array($field, $this->fieldCategories['shipment']['int_less_greater'], true) ||
                            in_array($field, $this->fieldCategories['shipment']['float_less_greater'], true) ||
                            in_array($field, $this->fieldCategories['shipment']['custom'], true)
                        ) {
                            if ($assetElement['shipment']) {
                                if ($field === 'freight_charge') {
                                    $row[$field] = Constants::CURRENCY_SYMBOL . $assetElement['shipment'][$field];
                                }
                                else {
                                    $row[$field] = $assetElement['shipment'][$field];
                                }
                            }
                            else {
                                $row[$field] = '-';
                            }
                        }
                        else if (in_array($field, $this->fieldCategories['shipment']['date_from_to'], true)) {
                            if ($assetElement['shipment']) {
                                try {
                                    $row[$field] = !$assetElement['shipment'][$field] ? null : Carbon::createFromFormat('Y-m-d H:i:s', $assetElement['shipment'][$field])->format(Constants::DATE_FORMAT);
                                }
                                catch (\InvalidArgumentException $e) {
                                    $row[$field] = !$assetElement['shipment'][$field] ? null : Carbon::createFromFormat('Y-m-d', $assetElement['shipment'][$field])->format(Constants::DATE_FORMAT);
                                }
                            }
                            else {
                                $row[$field] = '-';
                            }
                        }
                        else {
                            $row[$field] = '-';
                        }
                    }
                    else {
                        $row[$field] = '-';
                    }
                }

                array_push($assetsCsvArray, $row);
            }

            $csv->addRows($assetsCsvArray);
        }

        $csv->finalize();

        $filename = $this->site->code . '_assets';

        if (!empty(Input::get('lot_number')) && (Input::get('lot_number_select') == 'equals')) {
            $filename = $filename . '_lotnumber_' . Input::get('lot_number');
        }

        $filename = $filename . '_' . Carbon::now()->format('mdY') . '.csv';

        return $csv->download($filename);
    }

    /**
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getDetails($context = null, $id)
    {
        $asset = Asset::find($id);

        if (!$asset) {
            throw new NotFoundHttpException();
        }

        if ($this->site->hasFeature(Feature::VENDOR_CLIENT_CODE_ACCESS_RESTRICTED) && !Auth::user()->hasRole(Role::SUPERUSER)) {
            if (!in_array($asset->vendorClient, $this->vendorClients)) {
                throw new NotFoundHttpException();
            }
        }

        if ($this->site->hasFeature(Feature::LOT_NUMBER_PREFIX_ACCESS_RESTRICTED) && !Auth::user()->hasRole(Role::SUPERUSER)) {
            if (count($this->lotNumberPrefixes) > 0) {
                $error = true;

                foreach ($this->lotNumberPrefixes as $prefix) {
                    if (starts_with($asset->lotNumber, $prefix)) {
                        $error = false;
                    }
                }

                if ($error) {
                    throw new NotFoundHttpException();
                }
            }
        }

        return view('asset.assetDetails', [
            'asset' => $asset,
            'fields' => $this->modelSearchResultFields,
        ]);
    }

    /**
     * @return \Redirect
     */
    public function postModifySearch()
    {
        return redirect()->route('asset.search')->withInput();
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
