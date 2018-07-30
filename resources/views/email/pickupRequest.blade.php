@extends('email.emailLayout')

@section('content')
    <h1>{{ $title }}</h1>

    <br><hr><br>

    @if($pickupRequestData['use_contact_section_title'])
    <h4>{{ $pickupRequestData['contact_section_title'] }}:</h4>
    <br>
    @endif

    <table>
        <tr>
            <td>@lang('pickup_request.company_name'):</td>
            <td>@if($pickupRequest->company_name) {{ $pickupRequest->company_name }} @endif</td>
        </tr>
        <tr>
            <td>@lang('pickup_request.contact_name'):</td>
            <td>@if($pickupRequest->contact_name) {{ $pickupRequest->contact_name }} @endif</td>
        </tr>
        <tr>
            <td>@lang('pickup_request.phone_number'):</td>
            <td>@if($pickupRequest->contact_phone_number) {{ $pickupRequest->contact_phone_number }} @endif</td>
        </tr>
        <tr>
            <td>@lang('pickup_request.contact_address'):</td>
            <td>
            @if($pickupRequest->contact_address_1) {{ $pickupRequest->contact_address_1 }} @endif
            @if($pickupRequest->contact_address_2) <br>{{ $pickupRequest->contact_address_2 }} @endif
            </td>
        </tr>
        <tr>
            <td>@lang('pickup_request.city'):</td>
            <td>@if($pickupRequest->contact_city) {{ $pickupRequest->contact_city }} @endif</td>
        </tr>
        <tr>
            <td>@lang('pickup_request.state'):</td>
            <td>@if($pickupRequest->contact_state) {{ $pickupRequest->contact_state }} @endif</td>
        </tr>
        <tr>
            <td>@lang('pickup_request.zip_code'):</td>
			<td>@if($pickupRequest->contact_zip) {{ $pickupRequest->contact_zip }} @endif</td>
        </tr>
        @if($pickupRequestData['use_country'])
        <tr>
            <td>@lang('pickup_request.country'):</td>
			<td>@if($pickupRequest->contact_country) {{ $pickupRequest->contact_country }} @endif</td>
        </tr>
        @endif
        @if($pickupRequestData['use_company_division'])
        <tr>
            <td>@lang('pickup_request.division'):</td>
            <td>@if($pickupRequest->company_division) {{ $pickupRequest->company_division }} @endif</td>
        </tr>
        @endif
        <tr>
            <td>@lang('pickup_request.cell_number'):</td>
			<td>@if($pickupRequest->contact_cell_number) {{ $pickupRequest->contact_cell_number }} @endif</td>
        </tr>
        <tr>
            <td>@lang('pickup_request.email_address'):</td>
			<td>@if($pickupRequest->contact_email_address) <a href="mailto:{{ $pickupRequest->contact_email_address }}">{{ $pickupRequest->contact_email_address }}</a> @endif</td>
        </tr>
        @if($pickupRequestData['use_reference_number'])
        <tr>
            <td>{{ $pickupRequestData['reference_number_label'] }}:</td>
            <td>@if($pickupRequest->reference_number) {{ $pickupRequest->reference_number }} @endif</td>
        <tr>
        @endif
    </table>

    <br><hr><br>

    <table>
        @if($pickupRequestData['use_preferred_pickup_date'])
        <tr>
            <td>@lang('pickup_request.preferred_date_pickup')</td>
            <td>@if($pickupRequest->preferred_pickup_date) <span class="bold">{{ $pickupRequest->preferred_pickup_date }}</span> @endif</td>
        </tr>
        @endif
        @if($pickupRequestData['use_preferred_pickup_date_information'])
            <tr>
                <td>@lang('pickup_request.preferred_date_pickup')</td>
                <td>@if($pickupRequest->preferred_pickup_date_information) <span class="bold">{{ $pickupRequest->preferred_pickup_date_information }}</span> @endif</td>
            </tr>
        @endif
        <tr>
            <td>@lang('pickup_request.is_loading_dock_present')</td>
            <td><span class="bold">{{ $pickupRequest->is_loading_dock_present ? 'Yes' : 'No' }}</span></td>
        </tr>
        <tr>
            <td>@lang('pickup_request.dock_appointment_required')</td>
            <td><span class="bold">{{ $pickupRequest->dock_appointment_required ? 'Yes' : 'No' }}</span></td>
        </tr>
        <tr>
            <td>@lang('pickup_request.units_located_near_dock')</td>
            <td><span class="bold">{{ $pickupRequest->units_located_near_dock ? 'Yes' : 'No' }}</span></td>
        </tr>
        <tr>
            <td>@lang('pickup_request.units_on_single_floor')</td>
            <td><span class="bold">{{ $pickupRequest->units_on_single_floor ? 'Yes' : 'No' }}</span></td>
        </tr>
        <tr>
            <td>@lang('pickup_request.assets_need_packaging')</td>
            <td><span class="bold">{{ $pickupRequest->assets_need_packaging ? 'Yes' : 'No' }}</span></td>
        </tr>
        @if($pickupRequestData['use_hardware_on_skids'])
            <tr>
                <td>@lang('pickup_request.hardware_on_skids')</td>
                <td><span class="bold">{{ $pickupRequest->hardware_on_skids ? 'Yes' : 'No' }}</span></td>
            </tr>
            @if($pickupRequest->hardware_on_skids)
            <tr>
                <td><span style="margin-left:30px">@lang('pickup_request.how_many_skids')</span></td>
                <td>@if($pickupRequest->num_skids) <span class="bold">{{ $pickupRequest->num_skids }}</span> @endif</td>
            </tr>
            @endif
        @endif
    </table>

    <br><hr><br>

    <h4>@lang('pickup_request.product_type_quantities'):</h4>
    <br>

    <table>
        <tr>
            <td>
                <table class="table-striped">
                    <?php $total = 0; ?>
                    @if($pickupRequestData['use_alternative_piece_count_form'])
                        <tr>
                            <td>@lang('pickup_request.internal_hard_drives'):</td>
                            <td align="right">@if($pickupRequest->num_internal_hard_drives) {{ $pickupRequest->num_internal_hard_drives }} <?php $total += $pickupRequest->num_internal_hard_drives; ?>@else 0 @endif</td>
                        </tr>
                    @endif
                    @if($pickupRequestData['use_alternative_piece_count_form'])
                        <tr>
                            <td>@lang('pickup_request.encrypted'):</td>
                            <td>{{ $pickupRequest->internal_hard_drive_encrypted ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endif
                    @if($pickupRequestData['use_alternative_piece_count_form'])
                        <tr>
                            <td>@lang('pickup_request.hard_drive_wiped'):</td>
                            <td>{{ $pickupRequest->internal_hard_drive_wiped ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>@lang('pickup_request.desktop'):</td>
                        <td align="right">@if($pickupRequest->num_desktops) {{ $pickupRequest->num_desktops }} <?php $total += $pickupRequest->num_desktops; ?>@else 0 @endif</td>
                    </tr>
                    @if($pickupRequestData['use_alternative_piece_count_form'])
                        <tr>
                            <td>@lang('pickup_request.encrypted'):</td>
                            <td>{{ $pickupRequest->desktop_encrypted ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endif
                    @if($pickupRequestData['use_alternative_piece_count_form'])
                        <tr>
                            <td>@lang('pickup_request.hard_drive_wiped'):</td>
                            <td>{{ $pickupRequest->desktop_hard_drive_wiped ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>@lang('pickup_request.laptop'):</td>
                        <td align="right">@if($pickupRequest->num_laptops) {{ $pickupRequest->num_laptops }} <?php $total += $pickupRequest->num_laptops; ?>@else 0 @endif</td>
                    </tr>
                    @if($pickupRequestData['use_alternative_piece_count_form'])
                        <tr>
                            <td>@lang('pickup_request.encrypted'):</td>
                            <td>{{ $pickupRequest->laptop_encrypted ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endif
                    @if($pickupRequestData['use_alternative_piece_count_form'])
                        <tr>
                            <td>@lang('pickup_request.hard_drive_wiped'):</td>
                            <td>{{ $pickupRequest->laptop_hard_drive_wiped ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endif
                    @if(isset($pickupRequestData['use_crt_and_lcd_monitors']) && $pickupRequestData['use_crt_and_lcd_monitors'])
                        <tr>
                            <td>@lang('pickup_request.crt_monitor'):</td>
                            <td align="right">@if($pickupRequest->num_crt_monitors) {{ $pickupRequest->num_crt_monitors }} <?php $total += $pickupRequest->num_crt_monitors; ?>@else 0 @endif</td>
                        </tr>
                        <tr>
                            <td>@lang('pickup_request.lcd_monitor'):</td>
                            <td align="right">@if($pickupRequest->num_lcd_monitors) {{ $pickupRequest->num_lcd_monitors }} <?php $total += $pickupRequest->num_lcd_monitors; ?>@else 0 @endif</td>
                        </tr>
                    @else
                        <tr>
                            <td>@lang('pickup_request.monitor'):</td>
                            <td align="right">@if($pickupRequest->num_monitors) {{ $pickupRequest->num_monitors }} <?php $total += $pickupRequest->num_monitors; ?>@else 0 @endif</td>
                        </tr>
                    @endif
                    <tr>
                        <td>@lang('pickup_request.printer'):</td>
                        <td align="right">@if($pickupRequest->num_printers) {{ $pickupRequest->num_printers }} <?php $total += $pickupRequest->num_printers; ?>@else 0 @endif</td>
                    </tr>
                    <tr>
                        <td>@lang('pickup_request.server'):</td>
                        <td align="right">@if($pickupRequest->num_servers) {{ $pickupRequest->num_servers }} <?php $total += $pickupRequest->num_servers; ?>@else 0 @endif</td>
                    </tr>
                    @if($pickupRequestData['use_alternative_piece_count_form'])
                        <tr>
                            <td>@lang('pickup_request.encrypted'):</td>
                            <td>{{ $pickupRequest->server_encrypted ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endif
                    @if($pickupRequestData['use_alternative_piece_count_form'])
                        <tr>
                            <td>@lang('pickup_request.hard_drive_wiped'):</td>
                            <td>{{ $pickupRequest->server_hard_drive_wiped ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td>@lang('pickup_request.networking'):</td>
                        <td align="right">@if($pickupRequest->num_networking) {{ $pickupRequest->num_networking }} <?php $total += $pickupRequest->num_networking; ?>@else 0 @endif</td>
                    </tr>
                    <tr>
                        <td>@lang('pickup_request.storage_system'):</td>
                        <td align="right">@if($pickupRequest->num_storage_systems) {{ $pickupRequest->num_storage_systems }} <?php $total += $pickupRequest->num_storage_systems; ?>@else 0 @endif</td>
                    </tr>
                    <tr>
                        <td>@lang('pickup_request.ups'):</td>
                        <td align="right">@if($pickupRequest->num_ups) {{ $pickupRequest->num_ups }} <?php $total += $pickupRequest->num_ups; ?>@else 0 @endif</td>
                    </tr>
                    @if(!$pickupRequestData['use_alternative_piece_count_form'])
                    <tr>
                        <td>@lang('pickup_request.racks'):</td>
                        <td align="right">@if($pickupRequest->num_racks) {{ $pickupRequest->num_racks }} <?php $total += $pickupRequest->num_racks; ?>@else 0 @endif</td>
                    </tr>
                    @endif
                    <tr>
                        <td>@lang('pickup_request.mobile_phones'):</td>
                        <td align="right">@if($pickupRequest->num_mobile_phones) {{ $pickupRequest->num_mobile_phones }} <?php $total += $pickupRequest->num_mobile_phones; ?>@else 0 @endif</td>
                    </tr>
                    <tr>
                        <td>@lang('pickup_request.other'):</td>
                        <td align="right">@if($pickupRequest->num_other) {{ $pickupRequest->num_other }} <?php $total += $pickupRequest->num_other; ?>@else 0 @endif</td>
                    </tr>
                    <tr>
                        <td>@lang('pickup_request.misc'):</td>
                        <td align="right">@if($pickupRequest->num_misc) {{ $pickupRequest->num_misc }} <?php $total += $pickupRequest->num_misc; ?>@else 0 @endif</td>
                    </tr>
                    <tr>
                        <td><span class="bold">@lang('pickup_request.total')</span></td>
                        <td align="right"><span class="bold">{{ $total }}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    @if($pickupRequest->bm_company_name && $pickupRequest->bm_contact_name)
        <br><hr><br>
        <h4>@lang('pickup_request.building_manager_info_email'):</h4>
        <br>
        <table>
            <tr>
                <td>@lang('pickup_request.company_name'):</td>
                <td> {{ $pickupRequest->bm_company_name }} </td>
            </tr>
            <tr>
                <td>@lang('pickup_request.contact_name'):</td>
                <td> {{ $pickupRequest->bm_contact_name }} </td>
            </tr>
            <tr>
                <td>@lang('pickup_request.contact_address'):</td>
                <td>
                    @if($pickupRequest->bm_address_1) {{ $pickupRequest->bm_address_1 }} @endif
                    @if($pickupRequest->bm_address_2) <br> {{ $pickupRequest->bm_address_2 }} @endif
                </td>
            </tr>
            <tr>
                <td>@lang('pickup_request.city'):</td>
                <td>@if($pickupRequest->bm_city) {{ $pickupRequest->bm_city }} @endif</td>
            </tr>
            <tr>
                <td>@lang('pickup_request.state'):</td>
                <td>@if($pickupRequest->bm_state) {{ $pickupRequest->bm_state }} @endif</td>
            </tr>
            <tr>
                <td>@lang('pickup_request.zip_postal_code'):</td>
                <td>@if($pickupRequest->bm_zip) {{ $pickupRequest->bm_zip }} @endif</td>
            </tr>
            @if($pickupRequestData['use_country'])
            <tr>
                <td>@lang('pickup_request.country'):</td>
                <td>@if($pickupRequest->bm_country) {{ $pickupRequest->bm_country }} @endif</td>
            </tr>
            @endif
            <tr>
                <td>@lang('pickup_request.cell_number'):</td>
                <td>@if($pickupRequest->bm_cell_number) {{ $pickupRequest->bm_cell_number }} @endif</td>
            </tr>
            <tr>
                <td>@lang('pickup_request.email_address'):</td>
                <td>@if($pickupRequest->bm_email_address) <a href="mailto:{{ $pickupRequest->bm_email_address }}">{{ $pickupRequest->bm_email_address }}</a> @endif</td>
            </tr>
        </table>
    @endif

    @if($pickupRequest->special_instructions)
        <br><hr><br>
        <h4>@lang('pickup_request.special_instructions'):</h4>
        <p>{{ $pickupRequest->special_instructions }}</p>
    @endif

@endsection
