<?php

namespace App\Controllers\PickupRequest;

use App\Controllers\ContextController;
use App\Data\Constants;
use App\Data\Models\Feature;
use App\Data\Models\File;
use App\Data\Models\PickupRequest;
use App\Data\Models\PickupRequestAddress;
use Carbon\Carbon;
use Crypt;
use Hash;
use Illuminate\Http\Request;
use Input;
use Mail;
use Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Validator;

class PickupRequestController extends ContextController
{
    const STRING_LIMIT = 50;
    const RESULTS_PER_PAGE = 50;

    protected $pickupRequestData;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        if (!$this->site->hasFeature(Feature::HAS_PICKUP_REQUEST)) {
            throw new NotFoundHttpException('Not found.');
        }

        $this->pickupRequestData = $this->site->getFeature(Feature::HAS_PICKUP_REQUEST)->pivot->data;
    }

    public function getPickupRequestLogin()
    {
        return view('pickupRequest.pickupRequestLogin', [
            'data' => $this->pickupRequestData
        ]);
    }

    public function postPickupRequestLogin()
    {
        $rules = [
            'password' => 'required'
        ];

        $validator = Validator::make(Input::all(), $rules);

        if (!Input::get('password') || !Hash::check(trim(Input::get('password')), $this->pickupRequestData['password'])) {
            return redirect()->route('pickupRequest.login')->withInput()->withErrors(['password' => trans('auth.login.failed')]);
        }

        if ($validator->fails()) {
            return redirect()->route('pickupRequest.login')->withInput(Input::except('password'))->withErrors($validator);
        }

        return redirect()->route('pickupRequest', ['token' => base64_encode(Hash::make($this->pickupRequestData['password']))]);
    }

    public function getPickupRequestForm($context = null, $id = null)
    {
        if (!$this->checkToken()) {
            return redirect()->route('pickupRequest.login');
        }

        $pickupRequest = null;
        if (isset($id)) {
            $pickupRequest = PickupRequest::find($id);
        }

        $addressBook = [];
        if (!isset($pickupRequest)) {
            if ($this->site->hasFeature(Feature::PICKUP_REQUEST_ADDRESS_BOOK)) {
            $addressBook = $this->site->pickupRequestAddresses->pluck('name', 'id')->toArray();
            natcasesort($addressBook);
            }
        }
        return view('pickupRequest.pickupRequest', [
            'data'          => $this->pickupRequestData,
            'addressBook'   => $addressBook,
            'limit'         => self::STRING_LIMIT,
            'pickupRequest' => $pickupRequest
        ]);
    }

    protected function checkToken()
    {
        if (!$token = trim(Input::get('token'))) {
            return false;
        }

        return Hash::check($this->pickupRequestData['password'], base64_decode($token));
    }

    public function getPickupRequestAddress($context = null, $id)
    {
        if (!$this->checkToken()) {
            return redirect()->route('pickupRequest.login');
        }

        $address = $this->site->pickupRequestAddresses->where('id', intval($id))->first();

        if (!$address) {
            throw new NotFoundHttpException();
        } else {
            $addressArray = $address->toArray();

            unset($addressArray['id']);
            unset($addressArray['site_id']);
            unset($addressArray['created_at']);
            unset($addressArray['updated_at']);

            $addressArray['contact_name'] = $addressArray['contact_name'] ? Crypt::decrypt($addressArray['contact_name']) : null;
            $addressArray['contact_phone_number'] = $addressArray['contact_phone_number'] ? Crypt::decrypt($addressArray['contact_phone_number']) : null;
            $addressArray['contact_address_1'] = $addressArray['contact_address_1'] ? Crypt::decrypt($addressArray['contact_address_1']) : null;
            $addressArray['contact_address_2'] = $addressArray['contact_address_2'] ? Crypt::decrypt($addressArray['contact_address_2']) : null;
            $addressArray['contact_cell_number'] = $addressArray['contact_cell_number'] ? Crypt::decrypt($addressArray['contact_cell_number']) : null;
            $addressArray['contact_email_address'] = $addressArray['contact_email_address'] ? Crypt::decrypt($addressArray['contact_email_address']) : null;

            return response()->json($addressArray, 200);
        }
    }

    public function postPickupRequestForm()
    {
        if (!$this->checkToken()) {
            return redirect()->route('pickupRequest.login');
        }

        $existingPickupRequestId = trim(Input::get('pickup_request_id'));
        $rules = [];
        foreach ($this->pickupRequestData['required_fields'] as $field) {
            if ($field === 'contact_email_address' || $field === 'bm_email_address') {
                $rules[$field] = 'required|email';
            } else if($field === 'upload_equipment_list') {
                if ($existingPickupRequestId == '') {
                    $rules[$field] = 'required';
                }
            } else {
                $rules[$field] = 'required';
            }
        }

        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('pickupRequest', ['token' => Input::get('token')])->withInput()->withErrors($validator);
        }

        if ($existingPickupRequestId != '') {
            $pickupRequest = PickupRequest::find($existingPickupRequestId);
        }
        else {
            $pickupRequest = new PickupRequest();
            $pickupRequest->site_id = $this->site->id;
            $pickupRequest->save();
        }

        $this->buildPickupRequest($pickupRequest);

        if ($existingPickupRequestId == '') {

            $uploadedElectronicsDispositionFile = null;
            $electronicsDispositionFileName = null;
            if ($this->site->hasFeature(Feature::PICKUP_REQUEST_SAR_BOX_PROGRAM)) {
                $uploadedElectronicsDispositionFile = Input::file('upload_electronics_disposition_form');
                if ($uploadedElectronicsDispositionFile) {

                    // Do not rely on the original file name. File name should be based on the site code and the pick up request id.
                    // Retain the file extension of the upload file, however
                    $uploadedFileExt = $uploadedElectronicsDispositionFile->getClientOriginalExtension();
                    $electronicsDispositionFileName = $this->site->code . '_ElectronicsDispositionForm_' . $pickupRequest->id . '.' . $uploadedFileExt;

                    $file = new File();
                    $file->pickup_request_id = $pickupRequest->id;
                    $file->name = $electronicsDispositionFileName;
                    $file->filename = $electronicsDispositionFileName;
                    $file->size = $uploadedElectronicsDispositionFile->getSize();

                    if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $this->site->code)) {
                        Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $this->site->code);
                    }
                    if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $this->site->code . '/pickup_request')) {
                        Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $this->site->code . '/pickup_request');
                    }
                    if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $this->site->code . '/pickup_request/' . $pickupRequest->id)) {
                        Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $this->site->code . '/pickup_request/' . $pickupRequest->id);
                    }

                    Storage::cloud()->put(Constants::UPLOAD_DIRECTORY . $this->site->code . '/pickup_request/' . $pickupRequest->id . '/' . $electronicsDispositionFileName, file_get_contents($uploadedElectronicsDispositionFile));
                    $url = Storage::cloud()->url(Constants::UPLOAD_DIRECTORY . $this->site->code . '/pickup_request/' . $pickupRequest->id . '/' . $electronicsDispositionFileName);

                    $file->url = $url;
                    $file->save();
                }
            }

            $uploadedFile = null;
            $fileName = null;
            if ($this->site->hasFeature(Feature::PICKUP_REQUEST_EQUIPMENT_LIST)) {
                $uploadedFile = Input::file('upload_equipment_list');
                if ($uploadedFile) {

                    // Do not rely on the original file name. File name should be based on the site code and the pick up request id.
                    // Retain the file extension of the upload file, however
                    $uploadedFileExt = $uploadedFile->getClientOriginalExtension();
                    $fileName = $this->site->code . '_EquipmentList_' . $pickupRequest->id . '.' . $uploadedFileExt;

                    $file = new File();
                    $file->pickup_request_id = $pickupRequest->id;
                    $file->name = $fileName;
                    $file->filename = $fileName;
                    $file->size = $uploadedFile->getSize();

                    if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $this->site->code)) {
                        Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $this->site->code);
                    }
                    if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $this->site->code . '/pickup_request')) {
                        Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $this->site->code . '/pickup_request');
                    }
                    if (!Storage::cloud()->exists(Constants::UPLOAD_DIRECTORY . $this->site->code . '/pickup_request/' . $pickupRequest->id)) {
                        Storage::cloud()->makeDirectory(Constants::UPLOAD_DIRECTORY . $this->site->code . '/pickup_request/' . $pickupRequest->id);
                    }

                    Storage::cloud()->put(Constants::UPLOAD_DIRECTORY . $this->site->code . '/pickup_request/' . $pickupRequest->id . '/' . $fileName, file_get_contents($uploadedFile));
                    $url = Storage::cloud()->url(Constants::UPLOAD_DIRECTORY . $this->site->code . '/pickup_request/' . $pickupRequest->id . '/' . $fileName);

                    $file->url = $url;
                    $file->save();
                }
            }

            $pickUpSite = Input::get('site');
            if ($this->site->hasFeature(Feature::PICKUP_REQUEST_ADDRESS_BOOK)) {
                if ($this->site->getFeature(Feature::PICKUP_REQUEST_ADDRESS_BOOK)->pivot->data['allow_change'] && Input::get('allow_change')) {

                    if ($pickUpSite) {
                        $pickupRequestAddress = PickupRequestAddress::find($pickUpSite);
                    } else {
                        $pickupRequestAddress = new PickupRequestAddress();
                    }

                    $siteName = trim(Input::get('site_name'));

                    if ($siteName) {
                        $pickupRequestAddress->name = $siteName;
                    }

                    $pickupRequestAddress->company_name = $pickupRequest->company_name;
                    $pickupRequestAddress->contact_name = Crypt::encrypt($pickupRequest->contact_name);
                    $pickupRequestAddress->contact_address_1 = Crypt::encrypt($pickupRequest->contact_address_1);
                    $pickupRequestAddress->contact_address_2 = Crypt::encrypt($pickupRequest->contact_address_2);
                    $pickupRequestAddress->contact_city = $pickupRequest->contact_city;
                    $pickupRequestAddress->contact_state = $pickupRequest->contact_state;
                    $pickupRequestAddress->contact_zip = $pickupRequest->contact_zip;

                    if ($this->pickupRequestData['use_country']) {
                        $pickupRequestAddress->contact_country = $pickupRequest->contact_country;
                    }

                    if ($this->pickupRequestData['use_company_division']) {
                        $pickupRequestAddress->company_division = $pickupRequest->company_division;
                    }

                    $pickupRequestAddress->contact_phone_number = Crypt::encrypt($pickupRequest->contact_phone_number);
                    $pickupRequestAddress->contact_cell_number = Crypt::encrypt($pickupRequest->contact_cell_number);
                    $pickupRequestAddress->contact_email_address = Crypt::encrypt($pickupRequest->contact_email_address);

                    $pickupRequestAddress->site_id = $this->site->id;
                } else {
                    if ($pickUpSite && $this->site->getFeature(Feature::PICKUP_REQUEST_ADDRESS_BOOK)->pivot->data['save_dock_info']) {
                        $pickupRequestAddress = PickupRequestAddress::find($pickUpSite);
                    }
                }

                if (isset($pickupRequestAddress)) {

                    if ($this->site->getFeature(Feature::PICKUP_REQUEST_ADDRESS_BOOK)->pivot->data['save_dock_info']) {
                        $pickupRequestAddress->has_dock = $pickupRequest->is_loading_dock_present;
                        $pickupRequestAddress->dock_appointment_required = $pickupRequest->dock_appointment_required;
                        $pickupRequestAddress->units_located_near_dock = $pickupRequest->units_located_near_dock;
                    }
                    $pickupRequestAddress->save();
                }
            }
        }

        $pickupRequest->save();

        // email

        $emailFrom = $this->pickupRequestData['email_from'];
        $emailsBcc = explode(';', $this->pickupRequestData['email_bcc']);

        foreach ($this->pickupRequestData['email_additional_bcc'] as $additionalBcc) {
            $additionalBccMatch = true;

            foreach ($additionalBcc as $key => $value) {
                if ($key !== 'emails') {
                    if ($value !== trim(Input::get($key))) {
                        $additionalBccMatch = false;
                    }
                }
            }
            if ($additionalBccMatch) {
                $emailsBcc = array_merge($emailsBcc, explode(';', $additionalBcc['emails']));
            }
        }

        $pickupRequestData = $this->pickupRequestData;

        $title = $pickupRequestData['title'] . ' #' . $pickupRequest->id;
        if ($existingPickupRequestId != '') {
            $title = 'Updated ' . $title;
        }
        $siteCode = $this->site->code;

        Mail::queue('email.pickupRequest', ['title' => $title, 'pickupRequest' => $pickupRequest, 'pickupRequestData' => $pickupRequestData], function ($mail) use ($title, $siteCode, $fileName, $electronicsDispositionFileName, $pickupRequest, $emailFrom, $emailsBcc) {
            $mail->from($emailFrom, $emailFrom);
            $mail->to($pickupRequest->contact_email_address);
            $mail->bcc($emailsBcc);
            $mail->subject($title);

            if ($fileName) {
                $mail->attachData(Storage::cloud()->get(Constants::UPLOAD_DIRECTORY . $siteCode . '/pickup_request/' . $pickupRequest->id . '/' . $fileName), $fileName);
            }

            if ($electronicsDispositionFileName) {
                $mail->attachData(Storage::cloud()->get(Constants::UPLOAD_DIRECTORY . $siteCode . '/pickup_request/' . $pickupRequest->id . '/' . $electronicsDispositionFileName), $electronicsDispositionFileName);
            }

        });

        return redirect()->route('pickupRequest', ['token' => Input::get('token')])->with('success', trans('pickup_request.success', ['pickup_request_id' => $pickupRequest->id]));
    }

    public function buildPickupRequest(&$pickupRequest)
    {
        $pickupRequest->company_name = trim(Input::get('company_name'));
        $pickupRequest->contact_name = trim(Input::get('contact_name'));
        $pickupRequest->contact_address_1 = trim(Input::get('contact_address_1'));
        $pickupRequest->contact_address_2 = trim(Input::get('contact_address_2'));
        $pickupRequest->contact_city = trim(Input::get('contact_city'));
        $pickupRequest->contact_state = trim(Input::get('contact_state'));
        $pickupRequest->contact_zip = trim(Input::get('contact_zip'));
        if ($this->pickupRequestData['use_country']) {
            $pickupRequest->contact_country = trim(Input::get('contact_country'));
        }
        if ($this->pickupRequestData['use_company_division']) {
            $pickupRequest->company_division = trim(Input::get('company_division'));
        }
        $pickupRequest->contact_phone_number = trim(Input::get('contact_phone_number'));
        $pickupRequest->contact_cell_number = trim(Input::get('contact_cell_number'));
        $pickupRequest->contact_email_address = trim(Input::get('contact_email_address'));

        if ($this->pickupRequestData['use_preferred_pickup_date_information']) {
            $pickupRequest->preferred_pickup_date_information = trim(Input::get('preferred_pickup_date_information'));
        }

        $pickupRequest->is_loading_dock_present = trim(Input::get('is_loading_dock_present'));
        $pickupRequest->dock_appointment_required = trim(Input::get('dock_appointment_required'));
        $pickupRequest->units_located_near_dock = trim(Input::get('units_located_near_dock'));

        $pickupRequest->units_on_single_floor = trim(Input::get('units_on_single_floor'));
        $pickupRequest->assets_need_packaging = trim(Input::get('assets_need_packaging'));

        if ($this->pickupRequestData['use_lift_gate']) {
            $pickupRequest->is_lift_gate_needed = trim(Input::get('is_lift_gate_needed'));
        }

        if ($this->pickupRequestData['use_hardware_on_skids']) {
            $hardwareOnSkids = trim(Input::get('hardware_on_skids'));
            $pickupRequest->hardware_on_skids = $hardwareOnSkids;
            if ($hardwareOnSkids) {
                $pickupRequest->num_skids = trim(Input::get('num_skids'));
            }
        }

        if ($this->pickupRequestData['use_reference_number']) {
            $pickupRequest->reference_number = trim(Input::get('reference_number'));
        }

        if ($this->pickupRequestData['use_alternative_piece_count_form']) {
            $pickupRequest->num_internal_hard_drives = trim(Input::get('num_internal_hard_drives'));
            $pickupRequest->internal_hard_drive_encrypted = trim(Input::get('internal_hard_drive_encrypted'));
            $pickupRequest->internal_hard_drive_wiped = trim(Input::get('internal_hard_drive_wiped'));

            $pickupRequest->desktop_encrypted = trim(Input::get('desktop_encrypted'));
            $pickupRequest->desktop_hard_drive_wiped = trim(Input::get('desktop_hard_drive_wiped'));

            $pickupRequest->laptop_encrypted = trim(Input::get('laptop_encrypted'));
            $pickupRequest->laptop_hard_drive_wiped = trim(Input::get('laptop_hard_drive_wiped'));

            $pickupRequest->server_encrypted = trim(Input::get('server_encrypted'));
            $pickupRequest->server_hard_drive_wiped = trim(Input::get('server_hard_drive_wiped'));
        }

        $pickupRequest->num_desktops = trim(Input::get('num_desktops'));
        $pickupRequest->num_laptops = trim(Input::get('num_laptops'));

        if (isset($this->pickupRequestData['use_crt_and_lcd_monitors']) && $this->pickupRequestData['use_crt_and_lcd_monitors']) {
            $pickupRequest->num_crt_monitors = trim(Input::get('num_crt_monitors'));
            $pickupRequest->num_lcd_monitors = trim(Input::get('num_lcd_monitors'));
        }
        else {
            $pickupRequest->num_monitors = trim(Input::get('num_monitors'));
        }
        $pickupRequest->num_printers = trim(Input::get('num_printers'));
        $pickupRequest->num_servers = trim(Input::get('num_servers'));
        $pickupRequest->num_networking = trim(Input::get('num_networking'));
        $pickupRequest->num_storage_systems = trim(Input::get('num_storage_systems'));
        $pickupRequest->num_ups = trim(Input::get('num_ups'));
        $pickupRequest->num_racks = trim(Input::get('num_racks'));
        $pickupRequest->num_mobile_phones = trim(Input::get('num_mobile_phones'));
        $pickupRequest->num_other = trim(Input::get('num_other'));
        $pickupRequest->num_misc = trim(Input::get('num_misc'));

        if ($this->pickupRequestData['use_preferred_pickup_date']) {
            $preferredPickupDate = trim(Input::get('preferred_pickup_date'));
            $preferredPickupDateHour = trim(Input::get('preferred_pickup_date_hour'));
            $preferredPickupDateMin = trim(Input::get('preferred_pickup_date_min'));
            $preferredPickupDateAMPM = trim(Input::get('preferred_pickup_date_am_pm'));

            $date = Carbon::createFromFormat('m/d/Y', $preferredPickupDate);
            $date->hour = 0;
            $date->minute = 0;
            $date->second = 0;

            if ($preferredPickupDateHour) {
                $date->hour = $preferredPickupDateHour;
            }
            if ($preferredPickupDateMin) {
                $date->minute = $preferredPickupDateMin;
            }

            if ($preferredPickupDateAMPM) {
                $date = Carbon::createFromFormat('m/d/Y h:i A', $date->month . '/' . $date->day . '/' . $date->year . ' ' . str_pad($date->hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($date->minute, 2, '0', STR_PAD_LEFT) . ' ' . $preferredPickupDateAMPM);
            }

            $pickupRequest->preferred_pickup_date = $date;
        }

        $pickupRequest->bm_company_name = trim(Input::get('bm_company_name'));
        $pickupRequest->bm_contact_name = trim(Input::get('bm_contact_name'));
        $pickupRequest->bm_address_1 = trim(Input::get('bm_address_1'));
        $pickupRequest->bm_address_2 = trim(Input::get('bm_address_2'));
        $pickupRequest->bm_city = trim(Input::get('bm_city'));
        $pickupRequest->bm_state = trim(Input::get('bm_state'));
        $pickupRequest->bm_zip = trim(Input::get('bm_zip'));

        if ($this->pickupRequestData['use_country']) {
            $pickupRequest->bm_country = trim(Input::get('bm_country'));
        }

        $pickupRequest->bm_cell_number = trim(Input::get('bm_cell_number'));
        $pickupRequest->bm_email_address = trim(Input::get('bm_email_address'));

        $pickupRequest->special_instructions = Input::get('special_instructions');
    }

    public function getList()
    {

        if (!$this->checkToken()) {
            return redirect()->route('pickupRequest.login');
        }

        $query = PickupRequest::query();

        $query->where('site_id', $this->site->id);

        if (Input::get('id') != '') {
            $query->where('id', trim(Input::get('id')));
        }

        $listableCreateDateTime = Carbon::now()->subYears(2);
        $query->where('created_at', '>=', $listableCreateDateTime);

        $validForEditingCreateDatetime = Carbon::now()->subHours(48);

        if (Input::get('status') != '') {
            $status = trim(Input::get('status'));
            if ($status == '1') {
                $query->where('created_at', '>=', $validForEditingCreateDatetime);
            } else if ($status == '0') {
                $query->where('created_at', '<', $validForEditingCreateDatetime);
            }
        }

        $query = $query->sortable(['id' => 'desc']);
        $pickupRequests = $query->paginate(self::RESULTS_PER_PAGE);

        return view('pickupRequest.pickupRequestList', [
            'pickupRequests'                => $pickupRequests,
            'data'                          => $this->pickupRequestData,
            'order'                         => $query->getQuery()->orders,
            'abs(number)' => $validForEditingCreateDatetime
        ]);
    }
}
