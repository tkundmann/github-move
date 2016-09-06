<?php

use Illuminate\Database\Migrations\Migration;

class CreateViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW site_vendor_client_view 
        as SELECT site.code as `site_code`, vendor_client.name as `vendor_client` 
        FROM site 
        JOIN site_vendor_client ON site.id = site_vendor_client.site_id 
        JOIN vendor_client ON vendor_client.id = site_vendor_client.vendor_client_id");

        DB::statement("CREATE VIEW site_feature_view 
        as SELECT site.code as `site_code`, feature.name as `feature_name`, feature.description as `feature_description`, feature.data as `feature_data_global`, 
        sf.data as `feature_data_local` 
        FROM site 
        JOIN site_feature as sf ON site.id = sf.site_id 
        JOIN feature ON feature.id = sf.feature_id");

        DB::statement("CREATE VIEW site_lot_number_view 
        as SELECT site.code as `site_code`, lot_number.prefix as `lot_number_prefix` 
        FROM site 
        JOIN site_lot_number ON site.id = site_lot_number.site_id 
        JOIN lot_number ON lot_number.id = site_lot_number.lot_number_id");

        DB::statement("CREATE VIEW site_page_view 
        as SELECT site.code as `site_code`, page.type as `page_type`, page.name as `page_name`, page.code as `page_code`, page.description as `page_description`, 
        page.user_restricted as `page_user_restricted`, page.lot_number_restricted as `page_lot_number_restricted`
        FROM site 
        JOIN page ON page.site_id = site.id");

        DB::statement("CREATE VIEW site_page_file_view 
        as SELECT site.code as `site_code`, page.type as `page_type`, page.name as `page_name`, page.code as `page_code`, page.description as `page_description`, page.user_restricted as `page_user_restricted`, 
        page.lot_number_restricted as `page_lot_number_restricted`, file.filename as `file_filename`, file.name as `file_name`, 
        file.url as `file_url`, file.size as `file_size`, file.page_id as `file_page_id`, file.shipment_id as `file_shipment_id`, 
        file.pickup_request_id as `file_pickup_request_id`, file.file_date as `file_date`
        FROM page 
        JOIN file ON file.page_id = page.id 
        JOIN site on site.id = page.site_id");

        DB::statement("CREATE VIEW user_site_view 
        as SELECT user.name as `user_name`, user.email as `user_email`, site.code as `site_code` 
        FROM user 
        LEFT JOIN site ON user.site_id = site.id");

        DB::statement("CREATE VIEW user_vendor_client_view 
        as SELECT user.name as `user_name`, user.email as `user_email`, site.code as `site_code`, vendor_client.name as `vendor_client`
        FROM user 
        LEFT JOIN site ON user.site_id = site.id 
        JOIN user_vendor_client as uvc ON user.id = uvc.user_id 
        JOIN vendor_client ON vendor_client.id = uvc.vendor_client_id");

        DB::statement("CREATE VIEW user_lot_number_view 
        as SELECT user.name as `user_name`, user.email as `user_email`, site.code as `site_code`, lot_number.prefix as `lot_number_prefix`
        FROM user 
        LEFT JOIN site ON user.site_id = site.id 
        JOIN user_lot_number as uln ON user.id = uln.user_id 
        JOIN lot_number ON lot_number.id = uln.lot_number_id");

        DB::statement("CREATE VIEW user_role_view 
        as SELECT user.name as `user_name`, user.email as `user_email`, site.code as `site_code`, role.name as `role_name`, role.description as `role_description` 
        FROM user 
        LEFT JOIN site ON user.site_id = site.id 
        JOIN role_user ON user.id = role_user.user_id 
        JOIN role ON role.id = role_user.role_id");

        DB::statement("CREATE VIEW user_permission_view 
        as SELECT user.name as `user_name`, user.email as `user_email`, site.code as `site_code`, permission.name as `permission_name`, 
        permission.description as `permission_description` 
        FROM user
        LEFT JOIN site ON user.site_id = site.id 
        JOIN role_user ON user.id = role_user.user_id 
        JOIN permission_role ON permission_role.role_id = role_user.role_id 
        JOIN permission ON permission.id = permission_role.permission_id");

        DB::statement("CREATE VIEW user_page_view
        as SELECT user.name as `user_name`, user.email as `user_email`, site.code as `site_code`, page.type as `page_type`, page.code as `page_code`, 
        page.description as `page_description`, page.user_restricted as `page_user_restricted`, page.lot_number_restricted as `page_lot_number_restricted`
        FROM user 
        LEFT JOIN site ON user.site_id = site.id 
        JOIN user_page ON user.id = user_page.user_id 
        JOIN page ON page.id = user_page.page_id");

        DB::statement("CREATE VIEW permission_role_view 
        as SELECT role.name as `role_name`, role.description as `role_description`, permission.name as `permission_name`, 
        permission.description as `permission_description` 
        FROM permission 
        JOIN permission_role ON permission.id = permission_role.permission_id 
        JOIN role ON role.id = permission_role.role_id");

        DB::statement("CREATE VIEW site_pickup_request_view
        as SELECT site.code as `site_code`, pickup_request.company_name, pickup_request.company_division, pickup_request.contact_name, 
        pickup_request.contact_phone_number, pickup_request.contact_address_1, pickup_request.contact_address_2, pickup_request.contact_city, pickup_request.contact_state, 
        pickup_request.contact_zip, pickup_request.contact_country, pickup_request.contact_cell_number, pickup_request.contact_email_address, pickup_request.reference_number, 
        pickup_request.num_internal_hard_drives, pickup_request.num_desktops, pickup_request.num_laptops, pickup_request.num_monitors, pickup_request.num_printers, 
        pickup_request.num_servers, pickup_request.num_networking, pickup_request.num_storage_systems, pickup_request.num_ups, pickup_request.num_racks, pickup_request.num_other, 
        pickup_request.num_misc, pickup_request.total_num_assets, pickup_request.internal_hard_drive_encrypted, pickup_request.internal_hard_drive_wiped, 
        pickup_request.desktop_encrypted, pickup_request.desktop_hard_drive_wiped, pickup_request.laptop_encrypted, pickup_request.laptop_hard_drive_wiped, 
        pickup_request.server_encrypted, pickup_request.server_hard_drive_wiped, pickup_request.preferred_pickup_date, pickup_request.preferred_pickup_date_information, 
        pickup_request.units_located_near_dock, pickup_request.units_on_single_floor, pickup_request.is_lift_gate_needed, pickup_request.is_loading_dock_present, 
        pickup_request.dock_appointment_required, pickup_request.assets_need_packaging, pickup_request.hardware_on_skids, pickup_request.num_skids, 
        pickup_request.bm_company_name, pickup_request.bm_contact_name, pickup_request.bm_phone_number, pickup_request.bm_address_1, pickup_request.bm_address_2, 
        pickup_request.bm_city, pickup_request.bm_state, pickup_request.bm_zip, pickup_request.bm_country, pickup_request.bm_cell_number, pickup_request.bm_email_address, 
        pickup_request.special_instructions, pickup_request.created_at, pickup_request.updated_at 
        FROM site 
        JOIN pickup_request ON pickup_request.site_id = site.id");

        DB::statement("CREATE VIEW site_pickup_request_address_view
        as SELECT site.code as `site_code`, pickup_request_address.name, pickup_request_address.company_name, pickup_request_address.company_division, 
        pickup_request_address.contact_name, pickup_request_address.contact_phone_number, pickup_request_address.contact_address_1, pickup_request_address.contact_address_2, 
        pickup_request_address.contact_city, pickup_request_address.contact_state, pickup_request_address.contact_zip, pickup_request_address.contact_country, 
        pickup_request_address.contact_cell_number, pickup_request_address.contact_email_address, pickup_request_address.created_at, pickup_request_address.updated_at 
        FROM site 
        JOIN pickup_request_address ON pickup_request_address.site_id = site.id");

        DB::statement("CREATE VIEW file_lot_number_view
        as SELECT file.filename as `file_filename`, file.name as `file_name`, file.url as `file_url`, file.size as `file_size`, file.page_id as `file_page_id`, 
        file.shipment_id as `file_shipment_id`, file.pickup_request_id as `file_pickup_request_id`, file.file_date as `file_date`, lot_number.prefix as `lot_number_prefix`
        FROM file 
        JOIN file_lot_number ON file.id = file_lot_number.file_id 
        JOIN lot_number ON lot_number.id = file_lot_number.lot_number_id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET foreign_key_checks = 0;');
        DB::statement("DROP VIEW site_vendor_client_view");
        DB::statement("DROP VIEW site_feature_view");
        DB::statement("DROP VIEW site_lot_number_view");
        DB::statement("DROP VIEW site_page_view");
        DB::statement("DROP VIEW site_page_file_view");
        DB::statement("DROP VIEW user_site_view");
        DB::statement("DROP VIEW user_vendor_client_view");
        DB::statement("DROP VIEW user_lot_number_view");
        DB::statement("DROP VIEW user_role_view");
        DB::statement("DROP VIEW user_permission_view");
        DB::statement("DROP VIEW user_page_view");
        DB::statement("DROP VIEW permission_role_view");
        DB::statement("DROP VIEW site_pickup_request_view");
        DB::statement("DROP VIEW site_pickup_request_address_view");
        DB::statement("DROP VIEW file_lot_number_view");
        DB::statement('SET foreign_key_checks = 1;');
    }
}

