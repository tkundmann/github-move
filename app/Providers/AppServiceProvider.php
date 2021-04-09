<?php

namespace App\Providers;

use App\Data\Models\Page;
use App\Data\Models\User;
use App\Data\Models\Role;
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

        $this->app['request']->server->set('HTTPS', true);

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

            $role = Role::SUPERUSER;
            $query->orWhereHas('roles', function ($subquery) use ($role) {
                $subquery->where('name', $role);
            });

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

        Validator::extend("emails", function($attribute, $value, $parameters) {
            // $parameters[0] - email list string delimiter
            $rules = [
                'email' => 'email',
            ];
            foreach ($value as $email) {
                $data = [
                    'email' => trim($email)
                ];
                $validator = Validator::make($data, $rules);
                if ($validator->fails()) {
                    return false;
                }
            }
            return true;
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
