<?php

use App\Data\Constants;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Api
Route::post('api/import', 'Api\ImportController@process')->name('api.import');

// Context-Related
Route::group(['prefix' => '{'.Constants::CONTEXT_PARAMETER.'}'], function() {

    // Authentication
    Route::get('login', 'Auth\AuthController@showLoginForm')->name('login');
    Route::post('login', 'Auth\AuthController@login')->name('login');
    Route::get('logout', 'Auth\AuthController@logout')->name('logout');

    // Password Reset
    Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\PasswordController@reset')->name('password.reset.reset');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail')->name('password.reset.email');

    // Change password
    Route::get('password/change', 'Auth\ChangePasswordController@getChangePassword')->name('password.change');
    Route::post('password/change', 'Auth\ChangePasswordController@postChangePassword')->name('password.change');

    // Main
    Route::get('', function() {
        return redirect()->route('main.home');
    });
    Route::get('home', 'Main\HomeController@home')->name('main.home');
    Route::get('terms-and-notices', 'Main\TermsAndNoticesController@terms')->name('main.termsAndNotices');
    Route::get('back', 'Controller@goBack')->name('main.back');

    // Shipment
    Route::get('shipment/search', 'Shipment\ShipmentController@getSearch')->name('shipment.search');
    Route::get('shipment/search/result', 'Shipment\ShipmentController@getSearchResult')->name('shipment.search.result');
    Route::get('shipment/search/export', 'Shipment\ShipmentController@getSearchExport')->name('shipment.search.export');
    Route::post('shipment/search/modify', 'Shipment\ShipmentController@postModifySearch')->name('shipment.search.modify');
    Route::get('shipment/{id}', 'Shipment\ShipmentController@getDetails')->name('shipment.details');

    // Asset
    Route::get('asset/search', 'Asset\AssetController@getSearch')->name('asset.search');
    Route::get('asset/search/result', 'Asset\AssetController@getSearchResult')->name('asset.search.result');
    Route::get('asset/search/export', 'Asset\AssetController@getSearchExport')->name('asset.search.export');
    Route::post('asset/search/modify', 'Asset\AssetController@postModifySearch')->name('asset.search.modify');
    Route::get('asset/{id}', 'Asset\AssetController@getDetails')->name('asset.details');

    // Admin
    Route::get('account/list', 'Admin\AccountController@getList')->name('admin.account.list');
    Route::get('account/create', 'Admin\AccountController@getCreate')->name('admin.account.create');
    Route::post('account/create', 'Admin\AccountController@postCreate')->name('admin.account.create');
    Route::get('account/{id}/edit', 'Admin\AccountController@getEdit')->name('admin.account.edit');
    Route::post('account/{id}/edit', 'Admin\AccountController@postEdit')->name('admin.account.edit');
    Route::get('account/{id}/reset', 'Admin\AccountController@postResetPassword')->name('admin.account.reset');
    Route::get('account/{id}/remove', 'Admin\AccountController@getRemove')->name('admin.account.remove');
    Route::get('site/list', 'Admin\SiteController@getList')->name('admin.site.list');
    Route::get('site/create', 'Admin\SiteController@getCreate')->name('admin.site.create');
    Route::post('site/create', 'Admin\SiteController@postCreate')->name('admin.site.create');
    Route::get('site/{id}/edit', 'Admin\SiteController@getEdit')->name('admin.site.edit');
    Route::post('site/{id}/edit', 'Admin\SiteController@postEdit')->name('admin.site.edit');
    Route::get('site/{id}/remove', 'Admin\SiteController@getRemove')->name('admin.site.remove');
    Route::get('site/{siteId}/vendor-client/list', 'Admin\SiteController@getVendorClientList')->name('admin.site.vendorClient.list');
    Route::get('site/{siteId}/vendor-client/create', 'Admin\SiteController@getVendorClientCreate')->name('admin.site.vendorClient.create');
    Route::post('site/{siteId}/vendor-client/create', 'Admin\SiteController@postVendorClientCreate')->name('admin.site.vendorClient.create');
    Route::get('site/{siteId}/vendor-client/{vendorClientId}/remove', 'Admin\SiteController@getVendorClientRemove')->name('admin.site.vendorClient.remove');
    Route::get('site/{siteId}/lot-number/list', 'Admin\SiteController@getLotNumberList')->name('admin.site.lotNumber.list');
    Route::get('site/{siteId}/lot-number/create', 'Admin\SiteController@getLotNumberCreate')->name('admin.site.lotNumber.create');
    Route::post('site/{siteId}/lot-number/create', 'Admin\SiteController@postLotNumberCreate')->name('admin.site.lotNumber.create');
    Route::get('site/{siteId}/lot-number/{lotNumberId}/remove', 'Admin\SiteController@getLotNumberRemove')->name('admin.site.lotNumber.remove');
    Route::get('remove', 'Admin\RemoveFromSiteController@getRemove')->name('admin.remove');
    Route::post('remove/assets', 'Admin\RemoveFromSiteController@postRemoveAssets')->name('admin.remove.assets');
    Route::post('remove/by-lot-number', 'Admin\RemoveFromSiteController@postRemoveByLotNumber')->name('admin.remove.byLotNumber');
    Route::get('page/list', 'Admin\PageController@getList')->name('admin.page.list');
    Route::get('page/create', 'Admin\PageController@getCreate')->name('admin.page.create');
    Route::post('page/create', 'Admin\PageController@postCreate')->name('admin.page.create');
    Route::get('page/{id}/edit', 'Admin\PageController@getEdit')->name('admin.page.edit');
    Route::post('page/{id}/edit', 'Admin\PageController@postEdit')->name('admin.page.edit');
    Route::get('page/{id}/remove', 'Admin\PageController@getRemove')->name('admin.page.remove');
    Route::get('page/{id}/file/list', 'Admin\PageController@getFileList')->name('admin.page.file.list');
    Route::get('page/{id}/file/create', 'Admin\PageController@getFileCreate')->name('admin.page.file.create');
    Route::post('page/{id}/file/create', 'Admin\PageController@postFileCreate')->name('admin.page.file.create');
    Route::get('page/{pageId}/file/{fileId}/edit', 'Admin\PageController@getFileEdit')->name('admin.page.file.edit');
    Route::post('page/{pageId}/file/{fileId}/edit', 'Admin\PageController@postFileEdit')->name('admin.page.file.edit');
    Route::get('page/{pageId}/file/{fileId}/remove', 'Admin\PageController@getFileRemove')->name('admin.page.file.remove');
    Route::get('file/list', 'Admin\FileController@getList')->name('admin.file.list');
    Route::get('file/create', 'Admin\FileController@getCreate')->name('admin.file.create');
    Route::post('file/create', 'Admin\FileController@postCreate')->name('admin.file.create');
    Route::get('file/{id}/edit', 'Admin\FileController@getEdit')->name('admin.file.edit');
    Route::post('file/{id}/edit', 'Admin\FileController@postEdit')->name('admin.file.edit');
    Route::get('file/{id}/remove', 'Admin\FileController@getRemove')->name('admin.file.remove');


    Route::get('reports', 'Admin\ReportsController@home')->name('admin.reports.home');
    Route::get('reports/home', 'Admin\ReportsController@home')->name('admin.reports.home');

    Route::get('reports/certificates', 'Admin\ReportsCertificatesController@getCertificates')->name('admin.reports.certificates');
    Route::post('reports/certificates', 'Admin\ReportsCertificatesController@postCertificates')->name('admin.reports.certificates');
    Route::post('reports/certificates/export', 'Admin\ReportsCertificatesController@postCertificatesExport')->name('admin.reports.certificates.export');

    Route::get('reports/pickuprequests', 'Admin\ReportsPickupRequestsController@getPickupRequests')->name('admin.reports.pickuprequests');
    Route::post('reports/pickuprequests', 'Admin\ReportsPickupRequestsController@postPickupRequests')->name('admin.reports.pickuprequests');
    Route::post('reports/pickuprequests/export', 'Admin\ReportsPickupRequestsController@postPickupRequestsExport')->name('admin.reports.pickuprequests.export');

    // Page
    Route::get('page/{page}', 'Page\PageController@getPage')->name('page');

    // Pickup Request
    Route::get('pickup-request/login', 'PickupRequest\PickupRequestController@getPickupRequestLogin')->name('pickupRequest.login');
    Route::post('pickup-request/login', 'PickupRequest\PickupRequestController@postPickupRequestLogin')->name('pickupRequest.login');
    Route::get('pickup-request', 'PickupRequest\PickupRequestController@getPickupRequestForm')->name('pickupRequest');
    Route::post('pickup-request', 'PickupRequest\PickupRequestController@postPickupRequestForm')->name('pickupRequest');
    Route::get('pickup-request/address/{id}/get', 'PickupRequest\PickupRequestController@getPickupRequestAddress')->name('pickupRequest.address.get');

    Route::get('pickup-request/list', 'PickupRequest\PickupRequestController@getList')->name('pickupRequest.list');
    Route::post('pickup-request/list', 'PickupRequest\PickupRequestController@getList')->name('pickupRequest.list');
    Route::get('pickup-request/{id}/edit', 'PickupRequest\PickupRequestController@getPickupRequestForm')->name('pickupRequest.edit');
    Route::put('pickup-request/{id}/edit', 'PickupRequest\PickupRequestController@postEdit')->name('pickupRequest.edit');
});

