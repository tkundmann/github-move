<?php

namespace App\Providers;

use App\Data\Models\Page;
use App\Data\Models\User;
use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('unique_email_context', function ($attribute, $value, $parameters, $validator) {
            $query = User::where('email', $value);
            // $parameters[0] - column name eg. site_id
            // $parameters[1] - value eq. site1
            if ($parameters[1]) {
                $query = $query->where($parameters[0], $parameters[1]);
            } else {
                $query = $query->where($parameters[0], null);
            }
    
            // $parameters[2] - exclude user id (so that it ignores itself)
            if (isset($parameters[2])) {
                $query = $query->where('id', '!=', $parameters[2]);
            }

            // if no records found, it means that provided email is unique within given context (site = value or site = null)
            return $query->count() == 0;
        });
    
        Validator::extend('unique_page_code', function ($attribute, $value, $parameters, $validator) {
            $query = Page::where('code', $value);
            // $parameters[0] - column name eg. site_id
            // $parameters[1] - value eq. site1
            if ($parameters[1]) {
                $query = $query->where($parameters[0], $parameters[1]);
            } else {
                $query = $query->where($parameters[0], null);
            }
    
            // $parameters[2] - exclude page id (so that it ignores itself)
            if (isset($parameters[2])) {
                $query = $query->where('id', '!=', $parameters[2]);
            }

            // if no records found, it means that provided code is unique within given context (site = value or site = null)
            return $query->count() == 0;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
