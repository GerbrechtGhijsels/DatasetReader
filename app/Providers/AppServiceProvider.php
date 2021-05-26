<?php

namespace App\Providers;

use DateTime;
use Exception;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Throwable;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        /**
         * @value Date of birth
         * @parameters includes $minAge that is accepted
         * Checks with the date of birth of a person and minimum age if the person is old enough.
         */
        Validator::extend('olderThan', function($attribute, $value, $parameters)
        {
            $minAge = ( ! empty($parameters)) ? (int) $parameters[0] : 100;
            try {
                return (new DateTime)->diff(new DateTime($value))->y >= $minAge;
            } catch (Exception $e) {
                try {
                $date = str_replace('/', '-', $value);
                return (new DateTime)->diff(new DateTime($date))->y >= $minAge;
                } catch (Exception $e) {
                    return false;
                }
            }
        });

        /**
         * @value Date of birth
         * @parameters includes $maxAge that is accepted
         * Checks with the date of birth of a person and maximum age if the person is young enough.
         */
        Validator::extend('youngerThan', function($attribute, $value, $parameters)
        {
            $maxAge = ( ! empty($parameters)) ? (int) $parameters[0] : 100;
            try {
                return (new DateTime)->diff(new DateTime($value))->y <= $maxAge;
            } catch (Exception $e) {
                try {
                    $date = str_replace('/', '-', $value);
                    return (new DateTime)->diff(new DateTime($date))->y <= $maxAge;
                } catch (Exception $e) {
                    return false;
                }
            }
        });
    }
}
