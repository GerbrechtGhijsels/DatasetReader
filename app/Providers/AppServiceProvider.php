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
        Validator::extend('olderThan', function($attribute, $value, $parameters)
        {
            var_dump($value);
            $minAge = ( ! empty($parameters)) ? (int) $parameters[0] : 100;
            try {
                $test = (new DateTime)->diff(new DateTime($value))->y <= $minAge;
                return $test;
            } catch (Exception $e) {
                $date = str_replace('/', '-', $value);
                var_dump('test' .$date);
                return (new DateTime)->diff(new DateTime($date))->y >= $minAge;
            }
        });

        Validator::extend('youngerThan', function($attribute, $value, $parameters)
        {
            var_dump($value);
            $maxAge = ( ! empty($parameters)) ? (int) $parameters[0] : 100;
            try {
                $test = (new DateTime)->diff(new DateTime($value))->y <= $maxAge;
                return $test;
            } catch (Exception $e) {
                $date = str_replace('/', '-', $value);
                var_dump('test' .$date);
                return (new DateTime)->diff(new DateTime($date))->y <= $maxAge;
            }
        });
    }
}
