<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use App\Models\StockHistory;
use App\Models\Stock;
use App\Models\DistributionPayment;
use App\Models\Expense;
use Illuminate\Support\ServiceProvider;
use DB;
use Auth;

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
        // $data = [];

        // $general_settings =  GeneralSetting::first();
        
        
        // $data = compact('general_settings');
        // view()->composer('*', function ($view) use ($data) {

        //     try {
        //         $view->with($data);

        //     } catch (\Exception $e) {

        //     }

        // });
    }
}
