<?php

namespace App\ViewComposers;

use App\Data\Constants;
use App\Data\Models\Feature;
use App\Data\Models\Role;
use App\Helpers\ContextHelper;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Input;

class MenuComposer
{
    protected $menu = [];

    private $currentRoute;
    private $context;

    public function __construct()
    {
        $this->currentRoute = Route::getCurrentRoute();

        if ($this->currentRoute) {
            $parameters = $this->currentRoute->parameters();
            if ($parameters && array_key_exists(Constants::CONTEXT_PARAMETER, $parameters)) {
                $this->context = $parameters[Constants::CONTEXT_PARAMETER];
            }
        }

        if (Auth::check()) {
                if (Auth::user()->hasRole([ Role::ADMIN, Role::SUPERADMIN ]) && ContextHelper::isAdminContext($this->context)) {
                    $this->menu = [
                        ['url' => route('admin.account.list'), 'label' => 'main.layout.menu.accounts', 'icon' => 'fa-users', 'active' => in_array($this->currentRoute->getName(), ['admin.account.list', 'admin.account.create', 'admin.account.edit'])],
                        ['url' => route('admin.site.list'), 'label' => 'main.layout.menu.sites', 'icon' => 'fa-university', 'active' => in_array($this->currentRoute->getName(), ['admin.site.list', 'admin.site.create', 'admin.site.edit', 'admin.site.vendorClient.list', 'admin.site.vendorClient.create', 'admin.site.lotNumber.list', 'admin.site.lotNumber.create'])],
                        ['url' => route('admin.remove'), 'label' => 'main.layout.menu.remove_from_site', 'icon' => 'fa-trash', 'active' => in_array($this->currentRoute->getName(), ['admin.remove'])],
                        ['url' => route('admin.page.list'), 'label' => 'main.layout.menu.pages', 'icon' => 'fa fa-files-o', 'active' => in_array($this->currentRoute->getName(), ['admin.page.list', 'admin.page.create', 'admin.page.edit', 'admin.page.file.list', 'admin.page.file.create', 'admin.page.file.edit'])],
                        ['url' => route('admin.file.list'), 'label' => 'main.layout.menu.files', 'icon' => 'fa fa-file-o', 'active' => in_array($this->currentRoute->getName(), ['admin.file.list', 'admin.file.create', 'admin.file.edit'])],
                        ['url' => route('admin.reports.home'), 'label' => 'main.layout.menu.reports', 'icon' => 'fa fa-file-text-o', 'active' => in_array($this->currentRoute->getName(), ['admin.reports.home', 'admin.reports.certificates'])]
                    ];
                } else if (Auth::user()->hasRole([ Role::USER, Role::SUPERUSER ]) && ContextHelper::isSiteContext($this->context) && (ContextHelper::doesContextMatchUserSite(Auth::user(), $this->context) || (Auth::user()->hasRole([Role::SUPERUSER])))) {
                    $this->menu = [
                        ['url' => route('shipment.search'), 'label' => 'main.layout.menu.search_shipments', 'icon' => 'fa-truck', 'active' => in_array($this->currentRoute->getName(), ['shipment.search', 'shipment.search.result', 'shipment.details'])],
                        ['url' => route('asset.search'), 'label' => 'main.layout.menu.search_assets', 'icon' => 'fa-laptop', 'active' => in_array($this->currentRoute->getName(), ['asset.search', 'asset.search.result', 'asset.details'])]
                    ];
                }
            }
            else {
                // nothing
            }
        }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $site = null;

        if (isset($view->getData()['site'])) {
            $site = $view->getData()['site'];
        }

        if ($site) {
            if (Auth::check() && Auth::user()->hasRole([ Role::USER, Role::SUPERUSER ]) && ContextHelper::isSiteContext($this->context) && (ContextHelper::doesContextMatchUserSite(Auth::user(), $this->context) || (Auth::user()->hasRole([Role::SUPERUSER])))) {
                if ($site->hasFeature(Feature::HAS_PAGES)) {
                    foreach ($site->pages as $page) {
                        if ($page->type == 'Standard') {
                            $canAccessPageUserRestricted = true;
                            $canAccessPageLotNumberRestricted = true;

                            if (!Auth::user()->hasRole([ Role::SUPERUSER ])) {
                                if ($page->userRestricted) {
                                    $canAccessPageUserRestricted = false;

                                    if (Auth::user()->pages->where('id', $page->id)->first()) {
                                        $canAccessPageUserRestricted = true;
                                    }
                                }

                                if ($page->lotNumberRestricted) {
                                    $userLotNumbers = Auth::user()->lotNumbers->pluck('prefix')->toArray();

                                    if (count($userLotNumbers) > 0) {
                                        $canAccessPageLotNumberRestricted = false;

                                        $fileLotNumbers = [];

                                        foreach ($page->files as $file) {
                                            $fileLotNumbers = array_merge($fileLotNumbers, $file->lotNumbers->pluck('prefix')->toArray());
                                        }

                                        $fileLotNumbers = array_unique($fileLotNumbers);

                                        $intersection = array_intersect($fileLotNumbers, $userLotNumbers);

                                        if (count($intersection) > 0) {
                                            $canAccessPageLotNumberRestricted = true;
                                        }
                                    }
                                }
                            }

                            if ($canAccessPageUserRestricted && $canAccessPageLotNumberRestricted) {
                                array_push($this->menu, ['url' => route('page', ['page' => $page->code]), 'label' => $page->name, 'icon' => 'fa fa-files-o', 'active' => (($this->currentRoute->getName() == 'page') && ($this->currentRoute->getParameter('page') == $page->code))]);
                            }
                        }
                    }
                }
                if ($site->hasFeature(Feature::HAS_PICKUP_REQUEST)) {
                    // apparently not needed
                    // array_push($this->menu, ['url' => route('pickupRequest'), 'label' => 'main.layout.menu.pickup_request', 'icon' => 'fa-envelope-o', 'active' => in_array($this->currentRoute->getName(), ['pickupRequest'])]);
                }
            }
            else if (($this->currentRoute->getName() == 'pickupRequest')) {
                if ($site->hasFeature(Feature::HAS_PICKUP_REQUEST)) {
                    array_push($this->menu, ['label' => 'main.layout.menu.pickup_request_form', 'icon' => 'fa-envelope-o', 'active' => ($this->currentRoute->getName() == 'pickupRequest')]);
                }
            }
        }

        // put menu only if there's no exception on current response
        if (!isset($view->getData()['exception'])) {
            $view->with('menu', $this->menu);
        }

    }
}