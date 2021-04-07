<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pickup
    |--------------------------------------------------------------------------
    |
    |
    */

    'login' => [
        'title'                    => ':title Login',
        'info'                     => 'Please use the form below to gain access to the Pickup Request Form.',
        'password'                 => 'Password',
        'access_code'              => 'Access Code',
        'login'                    => 'Login'
    ],

    'list' => [
        'title'                    => ':title List',
        'record'                   => '{0}records|{1}record|[2,Inf]records',
        'status'                   => 'Status',
        'editable'                 => 'Editable',
        'not_editable'             => 'Not Editable',
        'pickup_request_id'        => 'Pickup Request ID',
        'created_at'               => 'Created At',
    ],

    'title'                        => ':title Questionnaire',

    'site_address_book'            => 'Site Address Book',
    'site_name'                    => 'Site name',
    'change_address_book_checkbox' => 'Check here if you want to update and/or save this site to the Address Book.',
    'provide_a_one_off'            => 'Provide a one-off pick-up location, and fill out the Site Location information below.',

    'click_here'                   => 'Click here',
    'to_download'                  => 'to download the',
    'equipment_list'               => 'Equipment List',
    'equipment_list_info'          => 'Please download and fill out the Equipment List spreadsheet.',
    'upload_equipment_list'        => 'Upload Equipment List',

    'sar_box_program'                     => 'SipiAR Box Program',
    'sar_box_program_info'                => 'If you wish to use the box program, please download, fill out the Electronics Disposition Form and Upload it as part of this Pickup Request.',
    'upload_electronics_disposition_form' => 'Upload Electronics Disposition Form',

    'denotes_required_field'       => '* Denotes required field',

    'company_name'                 => 'Company Name',
    'contact_name'                 => 'Contact Name',
    'contact_address'              => 'Pick Up Address',
    'city'                         => 'City',
    'state_province'               => 'State/Province',
    'zip_postal_code'              => 'Zip/Postal Code',
    'zip_code'                     => 'Zip code',
    'state'                        => 'State',
    'country'                      => 'Country',
    'division'                     => 'Division',
    'phone_number'                 => 'Phone Number',
    'cell_number'                  => 'Cell Number',
    'email_address'                => 'Email Address',
    'additional_request_recipient_email_address' => 'Additional Request Recipient Email Address',

    'reference_number_note'        => 'If you do not have a :reference_number_label, please enter "N/A" in this field.',

    'provide_piece_counts'         => 'Please provide piece counts for what will be picked up at your facility:',
    'remove_all_passwords'         => '***All passwords must be removed from all devices prior to shipping***',

    'internal_hard_drives'         => 'Individual Hard Drive(s)',
    'desktop'                      => 'Desktop',
    'laptop'                       => 'Laptop',
    'monitor'                      => 'Monitor',
    'crt_monitor'                  => 'CRT Monitor',
    'lcd_monitor'                  => 'LCD Monitor',
    'printer'                      => 'Printer',
    'server'                       => 'Server',
    'networking'                   => 'Networking',
    'storage_system'               => 'Storage System',
    'ups'                          => 'UPS',
    'racks'                        => 'Racks',
    'mobile_phones'                => 'Mobile Phones',
    'other'                        => 'Other',
    'misc'                         => 'Misc.',
    'total'                        => 'Total Number of Assets',
    'encrypted'                    => 'Encrypted',
    'hard_drive_wiped'             => 'Hard Drives wiped',

    'preferred_date_pickup'        => 'What is the preferred Date and Time for pickup?',
    'preferred_date_pickup_note'   => 'Please allow at least 48 hours notice for the pick-up request',
    'units_located_near_dock'      => 'Are the units centrally located near loading dock?',
    'lift_gate_needed'             => 'Is a Lift Gate needed?',
    'units_on_single_floor'        => 'Are the units on single floor?',
    'is_loading_dock_present'      => 'Is there a loading dock?',
    'dock_appointment_required'    => 'Is an appointment for dock time required?',
    'assets_need_packaging'        => 'Do assets need to be packaged for shipment?',
    'hardware_on_skids'            => 'Is the hardware already on skids?',
    'how_many_skids'               => 'If "Yes", how many skids?',

    'building_manager_info'        => 'If building manager needs to be contacted prior to pick-up, please provide contact information',

    'special_instructions'         => 'Special Instructions',

    'product_type_quantities'      => 'Product Type Quantities',
    'building_manager_info_email'  => 'Building Manager Contact Information',

    'success'                      => 'Pickup Request #:pickup_request_id submitted successfully.',
    'error'                        => 'Pickup Request submit error! Please check for all the required fields.',

    'faqs' => [
        'title'                    => 'Box Program Pickup Request FAQs',
        'question1'                => 'What happens after I submit my work order or box request?',
        'answer1'                  => 'You will receive an automated response that your request was successfully submitted. Within one to two business days you will receive an email from the Sipi Customer Service Team providing SAR\'s LOT Number and logistics details.',
        'question2'                => 'When will I receive my boxes?',
        'answer2'                  => 'Shipping times vary, but the box will arrive at your location in approximately 3 to 4 days from the date the box shipment was requested. Boxes and return labels will be in the shipment that you receive with return instruction.',
        'question3'                => 'What happens if I need to change or cancel my work order after it being submitted?',
        'answer3'                  => 'Please respond back to the email you received from Sipi assigning the LOT Number that you would like to adjust and or cancel.',
        'question4'                => 'What happens if I do not receive my boxes and or my return labels are missing?',
        'answer4'                  => 'Please respond back to the email that you received from Sipi assigning the Lot Number with your inquiry.',
        'question5'                => 'How do I send the boxes back?',
        'answer5'                  => 'Please follow the instruction sheet that was in your box kit labeled “Do Not Throw away”.  If you are still unclear, please reply to the email Sipi sent you with the original work order request.',
    ],
];
