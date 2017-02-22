<?php

namespace App\Controllers\Admin;

use App\Controllers\ContextController;
use App\Data\Models\Feature;
use App\Data\Models\LotNumber;
use App\Data\Models\Page;
use App\Data\Models\Role;
use App\Data\Models\Site;
use App\Data\Models\User;
use App\Data\Models\VendorClient;
use App\Helpers\StringHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Input;
use Mail;
use Validator;

class AccountController extends ContextController
{
    const RESULTS_PER_PAGE = 50;

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

    protected function getLotNumbers(Site $site)
    {
        $lotNumbers = [];
        if ($site->hasFeature(Feature::LOT_NUMBER_PREFIX_ACCESS_RESTRICTED) && $site->lotNumbers()->count() > 0) {
            $lotNumbers = $site->lotNumbers()->pluck('prefix', 'id');
            $lotNumbers = $lotNumbers->sort();
        }

        return $lotNumbers;
    }

    protected function getVendorClients(Site $site)
    {
        $vendorClients = [];
        if ($site->hasFeature(Feature::VENDOR_CLIENT_CODE_ACCESS_RESTRICTED) && $site->vendorClients()->count() > 0) {
            $vendorClients = $site->vendorClients()->pluck('name', 'id');
            $vendorClients = $vendorClients->sort();
        }

        return $vendorClients;
    }

    protected function getPages(Site $site)
    {
        $pages = [];
        if ($site->hasFeature(Feature::HAS_PAGES)) {
            foreach ($site->pages()->getEager() as $page) {
                if ($page->user_restricted) {
                    $pages[$page->id] = $page->name;
                }
            }
        }

        return $pages;
    }

    public function getList()
    {
        $query = User::query();

        if (!Auth::user()->hasRole(Role::SUPERADMIN)) {
            $query->whereDoesntHave('roles', function ($subquery) {
                $subquery->whereIn('name', [Role::SUPERADMIN, Role::ADMIN]);
            });
        }

        if (!empty(Input::get('name_email'))) {
            $query->where(function ($subquery) {
                $subquery->where('name', 'like', '%' . StringHelper::addSlashes(trim(Input::get('name_email'))) . '%');
                $subquery->orWhere('email', 'like', '%' . StringHelper::addSlashes(trim(Input::get('name_email'))) . '%');
            });
        }

        if (!empty(Input::get('role'))) {
            $role = trim(Input::get('role'));

            if ($role == 'all_users') {
                $query->whereHas('roles', function ($subquery) {
                    $subquery->whereIn('name', [Role::USER, Role::SUPERUSER]);
                });
            } else if ($role == 'all_admins') {
                $query->whereHas('roles', function ($subquery) {
                    $subquery->whereIn('name', [Role::ADMIN, Role::SUPERADMIN]);
                });
            } else if ($role != 'all') {
                $query->whereHas('roles', function ($subquery) use ($role) {
                    $subquery->where('name', $role);
                });
            }
        }

        if (!empty(Input::get('site'))) {
            $site = trim(Input::get('site'));

            if ($site != 'all') {
                $query->whereHas('site', function ($subquery) use ($site) {
                    $subquery->where('id', $site);
                });
            }
        }

        if (!empty(Input::get('status')) || Input::get('status') == '0') {
            $status = trim(Input::get('status'));

            if ($status == '1') {
                $query->where('disabled', false);
            } else if ($status == '0') {
                $query->where('disabled', true);
            }
        }

        $query = $query->sortable(['id' => 'asc']);
        $accounts = $query->paginate(self::RESULTS_PER_PAGE);

        if (Auth::user()->hasRole(Role::SUPERADMIN)) {
            $rolesArray = Role::orderBy('id', 'asc')->pluck('name', 'name')->toArray();
            $rolesArray = ['all' => Lang::get('common.all'), 'all_users' => Lang::get('admin.accounts.user.all_users'), 'all_admins' => Lang::get('admin.accounts.user.all_admins')] + $rolesArray;
        } else {
            $rolesArray = Role::whereIn('name', [Role::USER, Role::SUPERUSER])->orderBy('id', 'asc')->pluck('name', 'name')->toArray();
            $rolesArray = ['all' => Lang::get('common.all'), 'all_users' => Lang::get('admin.accounts.user.all_users')] + $rolesArray;
        }

        $allSites = Site::orderBy('title', 'asc')->get();
        $allSitesArray = [];
        foreach ($allSites as $site) {
            $allSitesArray[$site->id] = ($site->title ? $site->title : '-') . ' (' . ($site->code ? $site->code : '-') . ')';
        }

        return view('admin.accountList', [
            'accounts' => $accounts,
            'roles' => $rolesArray,
            'sites' => $allSitesArray,
            'order' => $query->getQuery()->orders
        ]);
    }

    public function getCreate()
    {
        $siteId = Input::get('site') ? trim(Input::get('site')) : old('site');
        $site = Site::find($siteId);

        $lotNumbers = $site ? $this->getLotNumbers($site) : null;
        $vendorClients = $site ? $this->getVendorClients($site) : null;
        $pages = $site ? $this->getPages($site) : null;

        return $this->createView($lotNumbers, $vendorClients, $pages);
    }

    protected function createView($lotNumbers = [], $vendorClients = [], $pages = [])
    {
        $allSites = Site::orderBy('title', 'asc')->get();
        $allSitesArray = [];
        foreach ($allSites as $site) {
            $allSitesArray[$site->id] = ($site->title ? $site->title : '-') . ' (' . ($site->code ? $site->code : '-') . ')';
        }

        if (Auth::user()->hasRole(Role::SUPERADMIN)) {
            $rolesArray = Role::orderBy('id', 'asc')->pluck('name', 'name')->toArray();
        } else {
            $rolesArray = Role::whereNotIn('name', [Role::ADMIN, Role::SUPERADMIN])->orderBy('id', 'asc')->pluck('name', 'name')->toArray();
        }

        return view('admin.accountCreate')->with([
            'sites' => $allSitesArray,
            'roles' => $rolesArray,
            'lotNumbers' => $lotNumbers,
            'vendorClients' => $vendorClients,
            'pages' => $pages
        ]);
    }

    public function postCreate()
    {
        if (Input::get('site_change')) {
            if (!$site = Site::find(trim(Input::get('site')))) {
                throw new \Exception('Site does not exist.');
            } else {
                return $this->createView($this->getLotNumbers($site), $this->getVendorClients($site), $this->getPages($site));
            }
        }

        $rules = array(
            'name' => 'required',
            'email' => 'required|email|unique_email_context:site_id,' . trim(Input::get('site')),
            'password' => 'required|min:8|symbols|confirmed',
            'password_confirmation' => 'required',
            'disabled' => 'required',
        );

        if (Input::get('roles') && !in_array(Input::get('roles'), [Role::SUPERUSER, Role::ADMIN, Role::SUPERADMIN], true)) {
            $rules['site'] = 'required|exists:site,id';
        }

        if (Auth::user()->hasRole(Role::SUPERADMIN)) {
            $rolesArray = Role::all()->pluck('name')->toArray();
        } else {
            $rolesArray = Role::whereNotIn('name', [Role::ADMIN, Role::SUPERADMIN])->pluck('name')->toArray();
        }

        $rules['roles'] = 'required|in:' . implode(',', $rolesArray);
        $validator = Validator::make(Input::all(), $rules);

        $account = new User();

        if ($validator->fails()) {
            return redirect()->route('admin.account.create')->withInput(Input::except('password'))->withErrors($validator);
        } else {
            $account->name = trim(Input::get('name'));
            $account->email = trim(Input::get('email'));
            $account->password = Hash::make(trim(Input::get('password')));
            $account->disabled = trim(Input::get('disabled'));
            $account->confirmed = false;

            $account->save();

            if (!in_array(Input::get('roles'), [Role::SUPERUSER, Role::ADMIN, Role::SUPERADMIN], true)) {
                if (!$site = Site::find(trim(Input::get('site')))) {
                    throw new \Exception('Site does not exist.');
                }
                $account->site()->associate($site);

                if (is_array(Input::get('lot_numbers'))) {
                    foreach (Input::get('lot_numbers') as $lotNumberId) {
                        if (!$lotNumber = LotNumber::find($lotNumberId)) {
                            throw new \Exception('Lot Number does not exist.');
                        }

                        if (!$site->lotNumbers->contains($lotNumber)) {
                            throw new \Exception('Lot Number does not belong to site.');
                        }

                        $account->lotNumbers()->attach($lotNumber);
                    }
                }

                if (is_array(Input::get('vendor_clients'))) {
                    foreach (Input::get('vendor_clients') as $vendorClientId) {
                        if (!$vendorClient = VendorClient::find($vendorClientId)) {
                            throw new \Exception('Vendor Client does not exist.');
                        }

                        if (!$site->vendorClients->contains($vendorClient)) {
                            throw new \Exception('Vendor Client does not belong to site.');
                        }

                        $account->vendorClients()->attach($vendorClient);
                    }
                }

                if ($site->hasFeature(Feature::HAS_PAGES) && is_array(Input::get('pages'))) {
                    foreach (Input::get('pages') as $pageId) {
                        if (!$page = Page::find($pageId)) {
                            throw new \Exception('Page does not exist.');
                        }

                        if (!$site->pages->contains($page)) {
                            throw new \Exception('Page does not belong to site.');
                        }

                        if (!$page->user_restricted) {
                            throw new \Exception('Page not restricted.');
                        }

                        $account->pages()->attach($page);
                    }
                }
            }

            if (Input::get('roles')) {
                $account->attachRole(Role::where('name', Input::get('roles'))->first());
            }

            $account->save();

            $title = trans('email.account_created.email_title');

            Mail::queue('email.accountCreated', ['title' => $title, 'account' => $account], function ($mail) use ($account, $title) {
                $mail->from(env('MAIL_SENDER_EMAIL'), env('MAIL_SENDER_NAME'));
                $mail->to($account->email, $account->name)->subject($title);
            });

            return redirect()->route('admin.account.list')->with('success', trans('admin.accounts.create.user_created'));
        }
    }

    public function getEdit($context = null, $id)
    {
        $account = User::find($id);
        if (!$account) {
            return redirect()->route('admin.account.list')->with('fail', trans('admin.accounts.edit.not_exist'));
        }

        if (!$siteId = Input::get('site') ? trim(Input::get('site')) : old('site')) {
            $siteId = $account->site_id;
        }

        $site = Site::find($siteId);

        $lotNumbers = $site ? $this->getLotNumbers($site) : null;
        $vendorClients = $site ? $this->getVendorClients($site) : null;
        $pages = $site ? $this->getPages($site) : null;

        return $this->editView($account, $lotNumbers, $vendorClients, $pages);
    }

    protected function editView(User $account, $lotNumbers = [], $vendorClients = [], $pages = [])
    {
        $allSites = Site::orderBy('title', 'asc')->get();
        $allSitesArray = [];
        foreach ($allSites as $site) {
            $allSitesArray[$site->id] = ($site->title ? $site->title : '-') . ' (' . ($site->code ? $site->code : '-') . ')';
        }

        if (Auth::user()->hasRole(Role::SUPERADMIN)) {
            $rolesArray = Role::orderBy('id', 'asc')->pluck('name', 'name')->toArray();
        }
        else if (Auth::user()->hasRole(Role::ADMIN) && (Auth::user()->id == $account->id)) {
            $rolesArray = Role::whereNotIn('name', [Role::SUPERADMIN])->orderBy('id', 'asc')->orderBy('id', 'asc')->pluck('name', 'name')->toArray();
        }
        else {
            $rolesArray = Role::whereNotIn('name', [Role::ADMIN, Role::SUPERADMIN])->orderBy('id', 'asc')->orderBy('id', 'asc')->pluck('name', 'name')->toArray();
        }

        return view('admin.accountEdit')->with([
            'account' => $account,
            'sites' => $allSitesArray,
            'roles' => $rolesArray,
            'lotNumbers' => $lotNumbers,
            'vendorClients' => $vendorClients,
            'pages' => $pages
        ]);
    }

    public function postEdit($context, $id)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique_email_context:site_id,' . trim(Input::get('site')) . ',' . $id,
            'disabled' => 'required',
            'confirmed' => 'required',
            'roles' => 'required',
        ];

        if (Input::get('roles') && !in_array(Input::get('roles'), [Role::SUPERUSER, Role::ADMIN, Role::SUPERADMIN], true)) {
            $rules['site'] = 'required|exists:site,id';
        }

        if (Auth::user()->hasRole(Role::SUPERADMIN)) {
            $rolesArray = Role::all()->pluck('name')->toArray();
        } else {
            $rolesArray = Role::whereNotIn('name', [Role::ADMIN, Role::SUPERADMIN])->pluck('name')->toArray();
        }

        $rules['roles'] = 'required|in:' . implode(',', $rolesArray);

        $account = User::find($id);

        if (!$account) {
            return redirect()->route('admin.account.list')->with('fail', trans('admin.accounts.edit.not_exist'));
        }

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('admin.account.edit', ['id' => $id])->withErrors($validator)->withInput(Input::except('password'));
        } else {
            if (Input::get('site_change')) {
                if (!$site = Site::find(trim(Input::get('site')))) {
                    throw new \Exception('Site does not exist.');
                } else {
                    return $this->editView($account, $this->getLotNumbers($site), $this->getVendorClients($site), $this->getPages($site));
                }
            }

            $account->name = trim(Input::get('name'));
            $account->email = trim(Input::get('email'));
            $account->disabled = trim(Input::get('disabled'));
            $account->confirmed = trim(Input::get('confirmed'));

            $account->site()->dissociate();
            $account->lotNumbers()->detach();
            $account->vendorClients()->detach();
            $account->pages()->detach();
            if (!in_array(Input::get('roles'), [Role::SUPERUSER, Role::ADMIN, Role::SUPERADMIN], true)) {
                if (!$site = Site::find(trim(Input::get('site')))) {
                    throw new \Exception('Site does not exist.');
                }
                $account->site()->associate($site);

                if (is_array(Input::get('lot_numbers'))) {
                    foreach (Input::get('lot_numbers') as $lotNumberId) {
                        if (!$lotNumber = LotNumber::find($lotNumberId)) {
                            throw new \Exception('Lot Number does not exist.');
                        }

                        if (!$site->lotNumbers->contains($lotNumber)) {
                            throw new \Exception('Lot Number does not belong to site.');
                        }

                        $account->lotNumbers()->attach($lotNumber);
                    }
                }

                if (is_array(Input::get('vendor_clients'))) {
                    foreach (Input::get('vendor_clients') as $vendorClientId) {
                        if (!$vendorClient = VendorClient::find($vendorClientId)) {
                            throw new \Exception('Vendor Client does not exist.');
                        }

                        if (!$site->vendorClients->contains($vendorClient)) {
                            throw new \Exception('Vendor Client does not belong to site.');
                        }

                        $account->vendorClients()->attach($vendorClient);
                    }
                }

                if ($site->hasFeature(Feature::HAS_PAGES) && is_array(Input::get('pages'))) {
                    foreach (Input::get('pages') as $pageId) {
                        if (!$page = Page::find($pageId)) {
                            throw new \Exception('Page does not exist.');
                        }

                        if (!$site->pages->contains($page)) {
                            throw new \Exception('Page does not belong to site.');
                        }

                        if (!$page->user_restricted) {
                            throw new \Exception('Page is not user restricted.');
                        }

                        $account->pages()->attach($page);
                    }
                }
            }

            $account->detachRoles();
            if (Input::get('roles')) {
                $account->attachRole(Role::where('name', Input::get('roles'))->first());
            }

            $account->save();

            return redirect()->route('admin.account.list')->with('success', trans('admin.accounts.edit.user_saved'));
        }
    }

    public function getRemove($context, $id)
    {
        $account = User::find($id);

        if (!$account) {
            return redirect()->route('admin.account.list')->with('fail', trans('admin.accounts.remove.not_exist'));
        }

        $account->delete();

        return redirect()->route('admin.account.list')->with('success', trans('admin.accounts.remove.user_removed'));
    }
}
