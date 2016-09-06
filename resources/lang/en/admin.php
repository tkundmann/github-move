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
    'page' => [
        'page' => [
            'type' => 'Type',
            'name' => 'Name',
            'code' => 'Code',
            'text' => 'Text',
            'description' => 'Description',
            'site' => 'Site',
            'user_restricted' => 'Restrict access per account',
            'lot_number_restricted' => 'Restrict access per lot number prefix'
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
            'confirm_remove' => 'Are you sure you want to remove this File?'
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
            'create' => [
                'upload_file_to_page' => 'Upload file to Page: :page',
                'file' => 'File',
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
    ]

];
