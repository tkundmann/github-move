<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    |
    |
    */

    'remove' => [
        'remove_assets' => [
            'remove_assets' => 'Remove Asset(s)',
            'info' => 'Please use the form below to permanently remove one or more assets from the Assets database table. In the text area below, enter the Bar Code number(s) for the Asset(s) you want to remove, and click the \'Remove Asset(s)" button. Please separate multiple Bar Code Numbers by either a comma, semi-colon, space, or by listing them one per line.',
            'warning' => 'Doing this will permanently remove the Assets from the database table. Do not do this unless you are certain you want it deleted from the site.',
            'barcode_numbers' => 'Bar Code Number(s)',
            'confirm_remove' => 'Are you sure you want to remove Asset(s) with these Bar Code numbers?',
            'barcode' => '{0}barcodes|{1}barcode|[2,Inf]barcodes',
            'asset' => '{0}assets|{1}asset|[2,Inf]assets',
            'successful_remove' => 'Successfully removed :quantity :asset.',
            'unsuccessful_remove' => 'Asset remove error!',
            'barcodes_not_found' => 'Records with these Bar Codes were not found:'
        ],
        'remove_by_lot_number' => [
            'remove_by_lot_number' => 'Remove by Lot Number',
            'info' => 'Please use the form below to permanently remove a given Lot Number from the Assets and/or Shipments database tables. Enter the exact Lot Number, select the record type (Assets, Shipments, or Both) from the dropdown and click on the \'Remove" button.',
            'warning' => 'Doing this will permanently remove this Lot Number from the chosen database table(s). Do not do this unless you are certain you want it deleted from the site.',
            'select_table_type' => 'Select Record Type',
            'confirm_remove' => 'Are you sure you want to remove :record_type with this Lot Number?',
            'asset' => '{0}assets|{1}asset|[2,Inf]assets',
            'shipment' => '{0}shipments|{1}shipment|[2,Inf]shipments',
            'successful_remove' => 'Successfully removed :quantity_shipment :shipment, :quantity_asset :asset.',
            'unsuccessful_remove' => 'Lot Number remove error! No records with Lot Number :lot_number found.',
            'assets_only' => 'Assets Only',
            'shipments_only' => 'Shipments Only',
            'both' => 'Both Assets and Shipments',
            'lot_number' => 'Lot Number',
            'record_type_assets' => 'Asset(s)',
            'record_type_shipments' => 'Shipment(s)',
            'record_type_both' => 'Shipment(s) and Asset(s)'
        ],
    ],
    'accounts' => [
        'account_list' => 'Account list',
        'record' => '{0}records|{1}record|[2,Inf]records',
        'user' => [
            'name' => 'Name',
            'name_email' => 'Name/Email',
            'email' => 'Email',
            'confirmed' => 'Confirmed',
            'enabled' => 'Enabled',
            'status' => 'Status',
            'password' => 'Password',
            'password_confirmation' => 'Confirm password',
            'role' => 'Role',
            'roles' => 'Roles',
            'all_users' => 'All Users',
            'all_admins' => 'All Admins',
            'site' => 'Site',
            'created_at' => 'Created At',
            'lot_numbers' => 'Lot Number Access',
            'pages' => 'Restricted Page Access',
            'vendor_clients' => 'Vendor Client Access'
        ],
        'edit' => [
            'editing_user' => 'Editing User: :user',
            'user_saved' => 'User saved.',
            'not_exist' => 'User does not exist.',
            'confirm_remove' => 'Are you sure you want to remove this User?'
        ],
        'create' => [
            'create' => 'Create User',
            'user_created' => 'User created',
            'site_reload_warning' => 'Change will reload page!',
        ],
        'remove' => [
            'user_removed' => 'User removed.',
            'not_exist' => 'User does not exist.',
        ]
    ],
    'reports' => [
        'go_to_reporting_tool' => 'Go to Reporting Tool',
        'go_to_exporting_tool' => 'Go to Exporting Tool',
        'home' => [
            'title' => 'Reports Home',
            'message' => 'To get started, select one of the report options listed below.',
        ],
        'certificates' => [
            'title' => 'Certificate Status Report',
            'description' => 'Use this tool to assemble a report of the current status (ie. exists/does not exist) of a certificate file on a per Lot/Shipment record basic.  Reports can be made for all portals, a specific portal and for a specific Audit Completed Date Range. Report allows for only Audit Completed Dates ocurring within the past two years.',
            // 'description' => 'Use this tool to assemble a report of the current status (ie. exists/does not exist) of a certificate file on a per Lot/Shipment record basic.  Reports can be made for all portals, a specific portal and for a specific Audit Completed Date Range. A report can also be generate per uploading a valid <strong>Certs Report</strong> CSV File.  The Certs Report CSV file is considered valid if it contains the following columns: Lot No., Vendor No. and Vendor Client No.<br/><br/><strong>Please Note:</strong> Report allows for only Audit Completed Dates ocurring within the past two years.',
            'record' => '{0}records|{1}record|[2,Inf]records',
            'report_filter_form' => [
                'generate_per_certs_report' => 'Generate per Uploaded Certs Report CSV File',
                'site' => 'Site',
                'audit_completed_range' => 'Audit Completed Range',
                'display_report_in_browser' => 'Display Report in Browser',
                'generate_report' => 'Generate Report',
                'export_report' => 'Export Report to CSV',
                'has_data_wipe_cert' => "Has Certificate of Data Wipe?",
                'has_recycling_cert' => "Has Certificate of Recycling?",
                'complete_date_range_required' => 'A complete Date range is required. Incomplete Audit Completed Date ranges are ignored.'
            ],
            'report_headers' => [
                'portal' => 'Portal',
                'portal_name' => 'Portal Name',
                'portal_url' => 'Portal URL',
                'vendor_client' => 'Vendor Client',
                'lot_date' => 'Lot Date',
                'lot_number' => 'Lot Number',
                'audit_completed' => 'Audit Completed',
                'has_certificate_of_data_wipe' => 'Has Certificate of Data Wipe?',
                'certificate_of_data_wipe_file' => 'Certificate of Data Wipe File',
                'has_certificate_of_recycling' => 'Has Certificate of Recycling?',
                'certificate_of_recycling_file' => 'Certificate of Recycling File'
            ],
        ],
        'pickuprequests' => [
            'title' => 'Pickup Request Exporting Tool',
            'description' => 'Use this tool to assemble and export a report (in CSV format) of Pickup Request submissions.  Reports can be made for all applicable portals (i.e. those that have an associated Pickup Request form), a specific applicable portal and for a specific Pickup Request Submission Date Range.',
            'record' => '{0}records|{1}record|[2,Inf]records',
            'report_filter_form' => [
                'pickuprequest_site_portal' => 'Pickup Request Applicable Site/Portal',
                'pickuprequest_submission_date_range' => 'Pickup Request Submission Date Range',
                'display_report_in_browser' => 'Display Report in Browser',
                'generate_report' => 'Generate Report',
                'export_report' => 'Export Report to CSV',
                'complete_pickuprequest_submission_date_required' => 'A complete Date range is required. Incomplete Pickup Request Submission Date ranges are ignored.'
            ],
            'report_headers' => [
                'id'                                         => 'Pickup Request ID',
                'created_at'                                 => 'Pickup Request Submission Date',
                'portal_name'                                => 'Portal Name',
                'portal_url'                                 => 'Portal URL',
                'company_name'                               => 'Company Name',
                'company_division'                           => 'Company Division',
                'contact_name'                               => 'Contact Name',
                'contact_phone_number'                       => 'Contact Phone Number',
                'contact_address_1'                          => 'Contact Address1',
                'contact_address_2'                          => 'Contact Address2',
                'contact_city'                               => 'Contact City',
                'contact_state'                              => 'Contact State',
                'contact_zip'                                => 'Contact Zip',
                'contact_country'                            => 'Contact Country',
                'contact_cell_number'                        => 'Contact Cell Number',
                'contact_email_address'                      => 'Contact Email Address',
                'additional_request_recipient_email_address' => 'Additional Request Recipient Email Address(es)',
                'reference_number'                           => 'Reference Number',
                'num_internal_hard_drives'                   => 'Number Internal Hard Drives',
                'num_desktops'                               => 'Number Desktops',
                'num_laptops'                                => 'Number Laptops',
                'num_monitors'                               => 'Number Monitors',
                'num_crt_monitors'                           => 'Number CRT Monitors',
                'num_lcd_monitors'                           => 'Number LCD Monitors',
                'num_printers'                               => 'Number Printers',
                'num_servers'                                => 'Number Servers',
                'num_networking'                             => 'Number Networking',
                'num_storage_systems'                        => 'Number Storage Systems',
                'num_ups'                                    => 'Number UPS',
                'num_racks'                                  => 'Number Racks',
                'num_mobile_phones'                          => 'Number Mobile Phones',
                'num_other'                                  => 'Number Other',
                'num_misc'                                   => 'Number Misc',
                'total_num_assets'                           => 'Total Number Assets',
                'desktop_encrypted'                          => 'Desktop Encrypted',
                'laptop_encrypted'                           => 'Laptop Encrypted',
                'server_encrypted'                           => 'Server Encrypted',
                'preferred_pickup_date'                      => 'Preferred Pickup Date',
                'preferred_pickup_date_information'          => 'Preferred Pickup Date Information',
                'units_located_near_dock'                    => 'Units Located Near Dock',
                'units_on_single_floor'                      => 'Units On Single Floor',
                'is_lift_gate_needed'                        => 'Is Lift Gate Needed',
                'is_loading_dock_present'                    => 'Is Loading Dock Present',
                'dock_appointment_required'                  => 'Dock Appointment Required',
                'assets_need_packaging'                      => 'Assets Need Packaging',
                'hardware_on_skids'                          => 'Hardware On Skids',
                'num_skids'                                  => 'Num Skids',
                'bm_company_name'                            => 'Building Manager Company Name',
                'bm_contact_name'                            => 'Building Manager Contact Name',
                'bm_phone_number'                            => 'Building Manager Phone Number',
                'bm_address_1'                               => 'Building Manager Address1',
                'bm_address_2'                               => 'Building Manager Address2',
                'bm_city'                                    => 'Building Manager City',
                'bm_state'                                   => 'Building Manager State',
                'bm_zip'                                     => 'Building Manager Zip',
                'bm_country'                                 => 'Building Manager Country',
                'bm_cell_number'                             => 'Building Manager Cell Number',
                'bm_email_address'                           => 'Building Manager Email Address',
                'special_instructions'                       => 'Special Instructions'
            ],
        ],
    ],
    'site' => [
        'site_list' => 'Site list',
        'record' => '{0}records|{1}record|[2,Inf]records',
        'site' => [
            'title' => 'Title',
            'code' => 'Code',
            'type' => 'Type',
            'logo' => 'Logo',
            'color' => 'Color',
            'account_password_expiry_days' => 'Account Password Expiry Days',
            'account_password_expiry_days_tagline' => 'Value applied upon creation of all applicable user accounts.',
            'prepopulated_with_portal_default' => 'Prepopulated with portal default.',
            'account_vendor_client_restriction' => 'Account Vendor Client Restriction Enabled',
        ],
        'edit' => [
            'editing_site' => 'Editing Site: :site',
            'site_saved' => 'Site saved.',
            'not_exist' => 'Site does not exist.',
            'change' => 'Change',
            'view_vendor_clients' => 'View Vendor Clients',
            'view_lot_numbers' => 'View Lot Numbers',
            'confirm_remove' => 'Are you sure you want to remove this Site?'
        ],
        'create' => [
            'create' => 'Create Site',
            'custom_logo' => 'Custom Logo',
            'custom_logo_required' => 'The Custom Logo field is required.',
            'site_created' => 'Site created'
        ],
        'remove' => [
            'site_removed' => 'Site removed.',
            'not_exist' => 'Site does not exist.'
        ],
        'vendor_client' => [
            'vendor_client_list' => ':site - Vendor Client list',
            'record' => '{0}records|{1}record|[2,Inf]records',
            'assign' => 'Assign',
            'unassign' => 'Unassign',
            'confirm_remove' => 'Are you sure you want to unassign this Vendor Client?',
            'vendor_client' => [
                'name' => 'Name'
            ],
            'create' => [
                'vendor_client' => 'Vendor Client(s)',
                'create' => 'Assign Vendor Client(s) to :site',
                'info' => 'Please separate multiple Vendor Clients by either a semi-colon or by listing them one per line.',
                'vendor_client_created' => 'Vendor Client(s) assigned.'
            ],
            'remove' => [
                'vendor_client_removed' => 'Vendor Client unassigned.',
                'not_exist' => 'Vendor Client does not exist.'
            ],
        ],
        'lot_number' => [
            'lot_number_list' => ':site - Lot Number list',
            'record' => '{0}records|{1}record|[2,Inf]records',
            'assign' => 'Assign',
            'unassign' => 'Unassign',
            'confirm_remove' => 'Are you sure you want to unassign this Lot Number?',
            'lot_number' => [
                'prefix' => 'Prefix'
            ],
            'create' => [
                'lot_number' => 'Lot Number(s)',
                'create' => 'Assign Lot Number(s) to :site',
                'info' => 'Please separate multiple Lot Numbers by either a comma, semi-colon or by listing them one per line.',
                'lot_number_created' => 'Lot Number(s) assigned.'
            ],
            'remove' => [
                'lot_number_removed' => 'Lot Number unassigned.',
                'not_exist' => 'Lot Number does not exist.'
            ],
        ]
    ],
    'page' => [
        'page' => [
            'type' => 'Type',
            'name' => 'Name',
            'code' => 'Code',
            'text' => 'Text',
            'description' => 'Description',
            'site' => 'Site',
            'user_restricted' => 'Restrict access per account',
            'lot_number_restricted' => 'Restrict access per lot number prefix',
            'view_files' => 'View files'
        ],
        'list' => [
            'page_list' => 'Page list',
            'site' => 'Site',
            'page' => '{0}pages|{1}page|[2,Inf]pages',
            'choose_site' => 'Choose a Site.',
            'confirm_remove' => 'Are you sure you want to remove this Page?'
        ],
        'edit' => [
            'editing_page' => 'Editing page: :page',
            'page_saved' => 'Page saved.',
            'not_exist' => 'Page does not exist.',
            'details' => 'Page Details',
            'files' => 'Page Files',
        ],
        'create' => [
            'create_page' => 'Create Page',
            'page_created' => 'Page created.',
            'no_type_warning' => 'Select Site first.',
            'site_reload_warning' => 'Change will reload page!'
        ],
        'remove' => [
            'page_removed' => 'Page removed.',
            'not_exist' => 'Page does not exist.',
        ],
        'file' => [
            'list' => [
                'file_list' => 'Page File list: :page',
                'confirm_remove' => 'Are you sure you want to remove this File?',
                'file' => '{0}files|{1}file|[2,Inf]files',
            ],
            'create' => [
                'upload_file_to_page' => 'Upload file to Page: :page',
                'file' => 'File',
				'additional_file' => 'Additional File',
                'file_name' => 'Name',
                'file_date' => 'File Date',
                'file_date_optional' => 'Optional',
                'shipment' => 'Shipment Lot Number',
                'lot_numbers' => 'Related Lot Numbers',
                'lot_numbers_optional' => 'Optional',
                'file_created' => 'File created.'
            ],
            'edit' => [
                'edit_file' => 'Edit file: :file',
                'filename' => 'Filename',
                'name' => 'Name',
                'size' => 'Size',
                'shipment' => 'Shipment Lot Number',
                'file_date' => 'Date',
                'file_date_optional' => 'Optional',
                'lot_numbers' => 'Related Lot Numbers',
                'lot_numbers_optional' => 'Optional',
                'not_exist' => 'File does not exist.',
                'file_saved' => 'File saved.',
            ],
            'remove' => [
                'not_exist' => 'File does not exist.',
                'file_removed' => 'File removed.'
            ]
        ]
    ],
    'file' => [
        'list' => [
            'file_list' => 'File list',
            'site' => 'Site',
            'confirm_remove' => 'Are you sure you want to remove this File?',
            'file' => '{0}files|{1}file|[2,Inf]files',
            'choose_site' => 'Choose a Site.',
            'filename_name' => 'Filename/Name'
        ],
        'create' => [
            'upload_file' => 'Upload file',
            'file' => 'File',
            'files' => 'File(s)',
            'site' => 'Site',
            'type' => 'Type',
            'file_name' => 'Name',
            'file_date' => 'File Date',
            'file_date_optional' => 'Optional',
            'shipment' => 'Shipment Lot Number',
            'file_created' => 'File created.',
            'success_file_upload' => 'The following file(s) were successfully uploaded.<br/>Click on the file name to verify successful upload via the applicable portal.',
            'not_valid_for_file_upload' => 'The following file(s) were not uploaded per one of the reasons listed below: ',
            'reasons_why_file_not_uploaded' => 'Either the file type is not supported by the applicable portal, or a Shipment record was not found in the database per the Lot Number specified in the file name. <br>A portal MUST support the upload of a given file type, or a Shipment record for the Lot Number specified in the file name MUST exist before any files can be uploaded for that shipment.<br><strong>PLEASE NOTE:</strong> All files being uploaded MUST conform with the agreed upon naming conventions.  Those files that do not conform, are rejected and not uploaded.',
            'site_reload_warning' => 'Change will reload page!'
        ],
        'edit' => [
            'edit_file' => 'Edit file: :file',
            'site' => 'Site',
            'type' => 'Type',
            'filename' => 'Filename',
            'name' => 'Name',
            'size' => 'Size',
            'file_date' => 'Date',
            'file_date_optional' => 'Optional',
            'shipment' => 'Shipment Lot Number',
            'not_exist' => 'File does not exist.',
            'file_saved' => 'File saved.',
        ],
        'remove' => [
            'not_exist' => 'File does not exist.',
            'file_removed' => 'File removed.'
        ]
    ]

];
