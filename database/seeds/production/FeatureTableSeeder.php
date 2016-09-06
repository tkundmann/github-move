<?php

namespace Database\Seeds\Production;

use App\Data\Models\Feature;
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
    }
}