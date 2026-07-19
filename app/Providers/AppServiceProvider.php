<?php

namespace App\Providers;

use App\Models\Member;
use App\Models\Payment;
use App\Observers\MemberObserver;
use App\Observers\PaymentObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Settings\GeneralSettings;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Member::observe(MemberObserver::class);
        Payment::observe(PaymentObserver::class);
        view::share('settings', $this->app->make(GeneralSettings::class));
    }
}
