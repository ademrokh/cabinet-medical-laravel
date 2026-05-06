<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\MedicalDocument;
use App\Policies\AppointmentPolicy;
use App\Policies\MedicalDocumentPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::policy(Appointment::class, AppointmentPolicy::class);
        Gate::policy(MedicalDocument::class, MedicalDocumentPolicy::class);
    }
}
