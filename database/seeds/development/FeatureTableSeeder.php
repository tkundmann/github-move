<?php

namespace Database\Seeds\Development;

use App\Data\Models\Feature;
use App\Data\Models\Site;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('feature')->insert([
            [
                'name' => Feature::VENDOR_CLIENT_CODE_ACCESS_RESTRICTED,
                'display_name' => 'Site uses Vendor Client code access restriction',
                'description' => 'Site uses Vendor Client code access restriction',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::LOT_NUMBER_PREFIX_ACCESS_RESTRICTED,
                'display_name' => 'Site uses Lot Number prefix access restriction',
                'description' => 'Site uses Lot Number prefix access restriction',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::HIDE_TITLE,
                'display_name' => 'Site title is not displayed in header',
                'description' => 'Site title is not displayed in header',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::HAS_PAGES,
                'display_name' => 'Site uses Page functionality',
                'description' => 'Site uses Page functionality',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::HAS_SETTLEMENTS,
                'display_name' => 'Site Shipment search displays Settlements column with a relevant Settlement file link',
                'description' => 'Site Shipment search displays Settlements column with a relevant Settlement file link',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::HAS_CERTIFICATES,
                'display_name' => 'Site Shipment search displays Certificates columns with a relevant file links',
                'description' => 'Site Shipment search displays Certificates columns with a relevant file links',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::HAS_PICKUP_REQUEST,
                'display_name' => 'Site has a Pickup Request form',
                'description' => 'Site has a Pickup Request form',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::IS_WINTHROP,
                'display_name' => 'This site belongs to Winthrop and has custom displaying logic',
                'description' => 'This site belongs to Winthrop and has custom displaying logic',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::SETTLEMENT_AS_FILE,
                'display_name' => 'Settlement displays as file link (if the file exists)',
                'description' => 'Settlement displays as file link (if the file exists)',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::CERTIFICATE_OF_DATA_WIPE_NUMBER_AS_FILE,
                'display_name' => 'Certificate of Data Wipe Number displays as file link (if the file exists)',
                'description' => 'Certificate of Data Wipe Number displays as file link (if the file exists)',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::CERTIFICATE_OF_DESTRUCTION_NUMBER_AS_FILE,
                'display_name' => 'Certificate of Destruction Number displays as file link (if the file exists)',
                'description' => 'Certificate of Destruction Number displays as file link (if the file exists)',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::CUSTOM_PRODUCT_FAMILY_FOR_CERTIFICATE_OF_DATA_WIPE_NUMBER,
                'display_name' => 'Certificate of Data Wipe Number is only displayed in the results for a given Asset if that Asset\'s Product Family is included in the customized set',
                'description' => 'Certificate of Data Wipe Number is only displayed in the results for a given Asset if that Asset\'s Product Family is included in the customized set',
                'created_at' => Carbon::now(),
                'data' => serialize(['COPY MACHINE','DIGITAL SENDER','HARD DRIVE','HDD','LAPTOP','MF PRINTERS','MOBIL PHONE', 'NETWORKING','OBS SYSTEM','PDA','PHONE','PHONE EQUIPMENT', 'PIN PAD','POS SYSTEM', 'PRINTERS', 'RUGGEDIZED LAPTOP', 'SCANNERS','SERVERS','STORAGE SYSTEMS', 'SYSTEM','TABLET LAPTOP', 'TERMINAL'])
            ],
            [
                'name' => Feature::CUSTOM_STATUS_FOR_CERTIFICATE_OF_DESTRUCTION_NUMBER,
                'display_name' => 'Certificate of Destruction Number is only displayed in the results for a given Asset if that Asset\'s Status is included in the customized set',
                'description' => 'Certificate of Destruction Number is only displayed in the results for a given Asset if that Asset\'s Status is included in the customized set',
                'created_at' => Carbon::now(),
                'data' => serialize(['SCRAP', 'TEARDOWN'])
            ],
            [
                'name' => Feature::SHIPMENT_CUSTOM_SEARCH_FIELDS,
                'display_name' => 'Site has own set of Shipment search fields',
                'description' => 'Site has own set of Shipment search fields',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::SHIPMENT_CUSTOM_SIMPLE_SEARCH_FIELDS,
                'display_name' => 'Site has own set of Shipment Simple search fields. This should be a subset of fields specified in SHIPMENT_CUSTOM_SEARCH_FIELDS.',
                'description' => 'Site has own set of Shipment Simple search fields. This should be a subset of fields specified in SHIPMENT_CUSTOM_SEARCH_FIELDS.',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::SHIPMENT_CUSTOM_SEARCH_RESULT_FIELDS,
                'display_name' => 'Site has own set of Shipment search result fields',
                'description' => 'Site has own set of Shipment search result fields',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::SHIPMENT_CUSTOM_EXPORT_FIELDS,
                'display_name' => 'Site has own set of Shipment export fields',
                'description' => 'Site has own set of Shipment export fields',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::ASSET_CUSTOM_SEARCH_FIELDS,
                'display_name' => 'Site has own set of Asset search fields',
                'description' => 'Site has own set of Asset search fields',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::ASSET_CUSTOM_SIMPLE_SEARCH_FIELDS,
                'display_name' => 'Site has own set of Asset Simple search fields. This should be a subset of fields specified in ASSET_CUSTOM_SEARCH_FIELDS.',
                'description' => 'Site has own set of Asset Simple search fields. This should be a subset of fields specified in ASSET_CUSTOM_SEARCH_FIELDS.',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::ASSET_CUSTOM_SEARCH_RESULT_FIELDS,
                'display_name' => 'Site has own set of Asset search result fields',
                'description' => 'Site has own set of Asset search result fields',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::ASSET_CUSTOM_EXPORT_FIELDS,
                'display_name' => 'Site has own set of Asset export fields',
                'description' => 'Site has own set of Asset export fields',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::ASSET_CUSTOM_EMPTY_STATUS,
                'display_name' => 'Site has own label for Asset\'s empty Status field',
                'description' => 'Site has own label for Asset\'s empty Status field',
                'created_at' => Carbon::now(),
                'data' => serialize('STATUS PENDING')
            ],
            [
                'name' => Feature::PICKUP_REQUEST_EQUIPMENT_LIST,
                'display_name' => 'Site has a set of Equipment List files for Pickup Request',
                'description' => 'Site has a set of Equipment List files for Pickup Request',
                'created_at' => Carbon::now(),
                'data' => null
            ],
            [
                'name' => Feature::PICKUP_REQUEST_ADDRESS_BOOK,
                'display_name' => 'Site has an Address Book for Pickup Request',
                'description' => 'Site has an Address Book for Pickup Request',
                'created_at' => Carbon::now(),
                'data' => null
            ]
        ]);

        $featureVendorClientCodeAccessRestriction = Feature::where('name', '=', Feature::VENDOR_CLIENT_CODE_ACCESS_RESTRICTED)->first();
        $featureLotNumberPrefixAccessRestriction = Feature::where('name', '=', Feature::LOT_NUMBER_PREFIX_ACCESS_RESTRICTED)->first();

        $featureHideTitle = Feature::where('name', '=', Feature::HIDE_TITLE)->first();

        $featureHasPages = Feature::where('name', '=', Feature::HAS_PAGES)->first();
        $featureHasSettlements = Feature::where('name', '=', Feature::HAS_SETTLEMENTS)->first();
        $featureHasCertificates = Feature::where('name', '=', Feature::HAS_CERTIFICATES)->first();
        $featureHasPickupRequest = Feature::where('name', '=', Feature::HAS_PICKUP_REQUEST)->first();

        $featureIsWinthrop = Feature::where('name', '=', Feature::IS_WINTHROP)->first();

        $featureSettlementAsFile = Feature::where('name', '=', Feature::SETTLEMENT_AS_FILE)->first();
        $featureCertificateOfDataWipeNumberAsFile = Feature::where('name', '=', Feature::CERTIFICATE_OF_DATA_WIPE_NUMBER_AS_FILE)->first();
        $featureCertificateOfDestructionAsFile = Feature::where('name', '=', Feature::CERTIFICATE_OF_DESTRUCTION_NUMBER_AS_FILE)->first();

        $featureCustomProductFamilyForCertificateOfDataWipeNumber = Feature::where('name', '=', Feature::CUSTOM_PRODUCT_FAMILY_FOR_CERTIFICATE_OF_DATA_WIPE_NUMBER)->first();
        $featureCustomStatusForCertificateOfDestructionNumber = Feature::where('name', '=', Feature::CUSTOM_STATUS_FOR_CERTIFICATE_OF_DESTRUCTION_NUMBER)->first();

        $featureShipmentCustomSearchFields = Feature::where('name', '=', Feature::SHIPMENT_CUSTOM_SEARCH_FIELDS)->first();
        $featureShipmentCustomSimpleSearchFields = Feature::where('name', '=', Feature::SHIPMENT_CUSTOM_SIMPLE_SEARCH_FIELDS)->first();
        $featureShipmentCustomExportFields = Feature::where('name', '=', Feature::SHIPMENT_CUSTOM_EXPORT_FIELDS)->first();
        $featureShipmentCustomSearchResultFields = Feature::where('name', '=', Feature::SHIPMENT_CUSTOM_SEARCH_RESULT_FIELDS)->first();

        $featureAssetCustomSearchFields = Feature::where('name', '=', Feature::ASSET_CUSTOM_SEARCH_FIELDS)->first();
        $featureAssetCustomSimpleSearchFields = Feature::where('name', '=', Feature::ASSET_CUSTOM_SIMPLE_SEARCH_FIELDS)->first();
        $featureAssetCustomSearchResultFields = Feature::where('name', '=', Feature::ASSET_CUSTOM_SEARCH_RESULT_FIELDS)->first();
        $featureAssetCustomExportFields = Feature::where('name', '=', Feature::ASSET_CUSTOM_EXPORT_FIELDS)->first();
        $featureAssetCustomEmptyStatus = Feature::where('name', '=', Feature::ASSET_CUSTOM_EMPTY_STATUS)->first();

        $featurePickupRequestEquipmentList = Feature::where('name', '=', Feature::PICKUP_REQUEST_EQUIPMENT_LIST)->first();
        $featurePickupRequestAddressBook = Feature::where('name', '=', Feature::PICKUP_REQUEST_ADDRESS_BOOK)->first();

        $site1 = Site::where('code', '=', 'site1')->first();
        $site2 = Site::where('code', '=', 'site2')->first();

        $site1->features()->attach( [
            $featureVendorClientCodeAccessRestriction->id,
            $featureLotNumberPrefixAccessRestriction->id,
            $featureHasPages->id,
            $featureHasSettlements->id,
            $featureHasCertificates->id,
            $featureHasPickupRequest->id,
            $featureSettlementAsFile->id,
            $featureCertificateOfDataWipeNumberAsFile->id,
            $featureCertificateOfDestructionAsFile->id,
            $featureCustomProductFamilyForCertificateOfDataWipeNumber->id,
            $featureCustomStatusForCertificateOfDestructionNumber->id,
            $featureShipmentCustomSearchFields->id,
            $featureShipmentCustomSimpleSearchFields->id,
            $featureShipmentCustomSearchResultFields->id,
            $featureShipmentCustomExportFields->id,
            $featureAssetCustomSearchFields->id,
            $featureAssetCustomSimpleSearchFields->id,
            $featureAssetCustomSearchResultFields->id,
            $featureAssetCustomExportFields->id,
            $featureAssetCustomEmptyStatus->id,
            $featurePickupRequestEquipmentList->id
        ]);
    
        $site1->features()->updateExistingPivot($featureHasPickupRequest->id, ['data' => serialize([
            'password' => '$2y$10$/3dBspSYyO9xTc1vcjdzH.Zeg0a1lDSqkMV8kpgto/7ZHZg7wDt1q',
            'title' => 'Belmont Technology Remarketing/Yahoo Pickup Request',
            'use_company_division' => true,
            'company_division_label' => 'Yahoo Division',
            'company_divisions' => ['Operations' => 'Operations', 'Information Technology' => 'Information Technology'],
            'use_contact_section_title' => true,
            'contact_section_title' => 'Site Location Pick-up Information',
            'use_state_as_select' => false,
            'use_country' => true,
            'countries' => [
                'USA' => 'USA',
                'Argentina' => 'Argentina',
                'Australia' => 'Australia',
                'Brazil' => 'Brazil',
                'Canada' => 'Canada',
                'Denmark' => 'Denmark',
                'France' => 'France',
                'Germany' => 'Germany',
                'Hong Kong' => 'Hong Kong',
                'India' => 'India',
                'Ireland' => 'Ireland',
                'Italy' => 'Italy',
                'Mexico' => 'Mexico',
                'Netherlands' => 'Netherlands',
                'Norway' => 'Norway',
                'Singapore' => 'Singapore',
                'South Korea' => 'South Korea',
                'Spain' => 'Spain',
                'Sweden' => 'Sweden',
                'Switzerland' => 'Switzerland',
                'United Kingdom' => 'United Kingdom'
            ],
            'use_reference_number' => true,
            'reference_number_label' => 'Customer Reference Number',
            'use_alternative_piece_count_form' => false,
            'use_preferred_pickup_date' => false,
            'use_preferred_pickup_date_information' => true,
            'use_lift_gate' => false,
            'use_hardware_on_skids' => true,
            'required_fields' => [
                'contact_name',
                'contact_address_1',
                'contact_city',
                'contact_state',
                'contact_zip',
                'contact_country',
                'company_division',
                'contact_phone_number',
                'contact_email_address',
                'reference_number',
                'preferred_pickup_date_information',
                'units_located_near_dock',
                'units_on_single_floor',
                'is_loading_dock_present',
                'dock_appointment_required',
                'assets_need_packaging'
            ],
            'email_from' => 'example@example.com',
            'email_bcc' => 'test1@example.com;test2@example.com',
            'email_additional_bcc' => [
                ['company_division' => 'Information Technology', 'contact_country' => 'USA', 'emails' => 'test@example.com;test2@example.com'],
                ['company_division' => 'Information Technology', 'contact_country' => 'Italy', 'emails' => 'test3@example.com;test4@example.com'],
                ['company_division' => 'Operations', 'contact_country' => 'USA', 'emails' => 'test5@example.com']
            ]
        ])]);

        $site1->features()->updateExistingPivot($featurePickupRequestEquipmentList->id, ['data' => serialize([
            ['name' => 'US Equipment List Spreadsheet', 'filename' => 'BTR_EquipmentList_US.xls', 'url' => 'https://www.belmont-technology.com/workday/res/BTR_EquipmentList_US.xls'],
            ['name' => 'EMEA Equipment List Spreadsheet', 'filename' => 'BTR_EquipmentList_EMEA.xls', 'url' => 'https://www.belmont-technology.com/workday/res/BTR_EquipmentList_EMEA.xls'],
        ])]);
        
        $site1->features()->updateExistingPivot($featureShipmentCustomSearchFields->id, ['data' => serialize([
            'lot_date' => 'lot_date',
            'lot_number' => 'lot_number',
            'po_number' => 'po_number',
            'vendor_shipment_number' => 'vendor_shipment_number',
            'cost_center' => 'cost_center'
        ])]);
        $site1->features()->updateExistingPivot($featureShipmentCustomSimpleSearchFields->id, ['data' => serialize([
            'lot_date' => 'lot_date',
            'lot_number' => 'lot_number'
        ])]);
        $site1->features()->updateExistingPivot($featureShipmentCustomSearchResultFields->id, ['data' => serialize([
            'lot_date' => 'lot_date',
            'lot_number' => 'lot_number',
            'po_number' => 'po_number',
            'vendor_shipment_number' => 'vendor_shipment_number',
            'cost_center' => 'cost_center'
        ])]);
        $site1->features()->updateExistingPivot($featureShipmentCustomExportFields->id, ['data' => serialize([
            'lot_date' => 'lot_date',
            'lot_number' => 'lot_number',
            'po_number' => 'po_number',
            'vendor_shipment_number' => 'vendor_shipment_number',
            'cost_center' => 'cost_center'
        ])]);
        $site1->features()->updateExistingPivot($featureAssetCustomSearchFields->id, ['data' => serialize([
            'lot_date' => 'lot_date',
            'lot_number' => 'lot_number',
            'barcode_number' => 'barcode_number',
            'vendor_client' => 'vendor_client',
            'manufacturer_serial_num' => 'manufacturer_serial_num'
        ])]);
        $site1->features()->updateExistingPivot($featureAssetCustomSimpleSearchFields->id, ['data' => serialize([
            'lot_date' => 'lot_date',
            'lot_number' => 'lot_number',
        ])]);
        $site1->features()->updateExistingPivot($featureAssetCustomSearchResultFields->id, ['data' => serialize([
            'lot_date' => 'lot_date',
            'lot_number' => 'lot_number',
            'barcode_number' => 'barcode_number',
            'vendor_client' => 'vendor_client',
            'manufacturer_serial_num' => 'manufacturer_serial_num'
        ])]);
        $site1->features()->updateExistingPivot($featureAssetCustomExportFields->id, ['data' => serialize([
            'lot_date' => 'lot_date',
            'lot_number' => 'lot_number',
            'barcode_number' => 'barcode_number',
            'vendor_client' => 'vendor_client',
            'manufacturer_serial_num' => 'manufacturer_serial_num'
        ])]);

        $site2->features()->attach([
            $featureHideTitle->id,
            $featureHasPickupRequest->id,
            $featurePickupRequestAddressBook->id,
            $featureIsWinthrop->id,
            $featureAssetCustomSearchResultFields->id
        ]);
    
        $site2->features()->updateExistingPivot($featureHasPickupRequest->id, ['data' => serialize([
            'password' => '$2y$10$/3dBspSYyO9xTc1vcjdzH.Zeg0a1lDSqkMV8kpgto/7ZHZg7wDt1q',
            'title' => 'Belmont Technology Remarketing/Ebay Pickup Request',
            'use_company_division' => false,
            'use_contact_section_title' => false,
            'use_state_as_select' => false,
            'use_country' => false,
            'use_reference_number' => false,
            'use_alternative_piece_count_form' => true,
            'use_preferred_pickup_date' => true,
            'use_preferred_pickup_date_information' => false,
            'use_lift_gate' => false,
            'use_hardware_on_skids' => false,
            'required_fields' => ['company_name', 'contact_name', 'contact_email_address', 'num_desktops'],
            'email_from' => 'example@example.com',
            'email_bcc' => 'test1@example.com;test2@example.com'
        ])]);

        $site2->features()->updateExistingPivot($featurePickupRequestAddressBook->id, ['data' => serialize([
            'allow_change' => true,
            'change_text' => 'If you want to add this site to the address book, you must supply a new Site Name. Otherwise, the existing Site Name address record will be updated with the information submitted for this request.',
            'site_address_book_label' => 'Site Address Book',
            'new_site_text' => 'Provide to a new Site Name to create a new record.',
            'new_site_address_book_label' => 'Site Name',

        ])]);

        $site2->features()->updateExistingPivot($featureAssetCustomSearchResultFields->id, ['data' => serialize([
            'manufacturer_serial_num' => 'Serial #1',
            '!reserved_' . str_random(10) => 'Serial #2',
            '!reserved_' . str_random(10) => 'Serial #3',
            'manufacturer_part_num' => 'manufacturer_serial_num',
            '!reserved_' . str_random(10) => 'Part #3',
            'hard_drive_serial_num' => 'hard_drive_serial_num',
            '!reserved_' . str_random(10) => 'Reason (Test Fail)',
            'screen_size' => 'screen_size',
            '!reserved_' . str_random(10) => 'Screen Type',
            'lot_number' => 'lot_number',
            '!reserved_' . str_random(10) => 'Quantity'
        ])]);
    }
}
