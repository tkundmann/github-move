<?php

namespace App\Controllers\Admin;

use App\Controllers\ContextController;
use App\Controllers\Helpers\FileUploadHelper;
use App\Data\Constants;
use App\Data\Models\Feature;
use App\Data\Models\File;
use App\Data\Models\Page;
use App\Data\Models\Role;
use App\Data\Models\Shipment;
use App\Data\Models\Site;
use App\Helpers\CsvHelper;
use App\Helpers\StringHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Builder;
use Storage;
use Validator;

class ReportsController extends ContextController
{

  /**
   * Create a new controller instance.
   * @param  \Illuminate\Http\Request $request
   * @return void
   */
  public function __construct(Request $request)
  {
      parent::__construct($request);
      $this->middleware('auth');
      $this->middleware('context.permissions:' . $this->context);
      $this->middleware('role:' . Role::ADMIN . '|' . Role::SUPERADMIN);
  }


  /**
   * Show the Reports Home/Dashboard page
   *
   * @return \Illuminate\Http\Response
   */
  public function home()
  {
      return view('admin.reportsHome');
  }

}
