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
    'site' => [
        'site_list' => 'Site list',
        'record' => '{0}records|{1}record|[2,Inf]records',
        'site' => [
            'title' => 'Title',
            'code' => 'Code',
            'type' => 'Type',
            'logo' => 'Logo',
            'color' => 'Color',
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
            'site' => 'Site',
            'type' => 'Type',
            'file_name' => 'Name',
            'file_date' => 'File Date',
            'file_date_optional' => 'Optional',
            'shipment' => 'Shipment Lot Number',
            'file_created' => 'File created.',
            'success_file_upload' => 'The following file(s) were successfully uploaded for the selected Site (PLACEHOLDER_SITE_TITLE) and File Type (PLACEHOLDER_FILE_TYPE): ',
            'not_valid_for_file_upload' => 'The following file(s) were not uploaded for the selected Site (PLACEHOLDER_SITE_TITLE) and File Type (PLACEHOLDER_FILE_TYPE) per the reason listed below: ',
            'shipment_not_found_for_file' => 'A Shipment record was not found in the database per the Site selected and the Lot Number specified in the file name. <br>A Shipment record for the Lot Number specified in the file name MUST exist before any files can be uploaded for that shipment.',
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
