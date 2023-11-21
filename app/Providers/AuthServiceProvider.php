<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Hub;
use App\Models\Complain;
use App\Models\Rider;
use App\Models\Notification;
use App\Policies\UserPolicy;
use App\Policies\HubPolicy;
use App\Policies\InventryPolicy;
use App\Policies\ComplaintPolicy;
use App\Policies\WalletPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\RefundPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Hub::class => HubPolicy::class,
        Rider::class => CustomerPolicy::class,
        Complain::class => ComplaintPolicy::class,
        Notification::class => NotificationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //User Policies
        Gate::define('view_user', 'App\Policies\UserPolicy@view_user');
        Gate::define('add_user', 'App\Policies\UserPolicy@add_user');
        Gate::define('edit_user', 'App\Policies\UserPolicy@edit_user');
        Gate::define('delete_user', 'App\Policies\UserPolicy@delete_user');
        Gate::define('user_status', 'App\Policies\UserPolicy@user_status');
        Gate::define('view_role', 'App\Policies\UserPolicy@view_role');
        Gate::define('add_role', 'App\Policies\UserPolicy@add_role');
        Gate::define('edit_role', 'App\Policies\UserPolicy@edit_role');
        Gate::define('delete_delete', 'App\Policies\UserPolicy@delete_delete');
        Gate::define('allow_permission', 'App\Policies\UserPolicy@allow_permission');

        //Hub Policies
        Gate::define('hub_list', 'App\Policies\HubPolicy@hub_list');
        Gate::define('hub_view', 'App\Policies\HubPolicy@hub_view');
        Gate::define('add_hub', 'App\Policies\HubPolicy@add_hub');
        Gate::define('edit_hub', 'App\Policies\HubPolicy@edit_hub');
        Gate::define('delete_hub', 'App\Policies\HubPolicy@delete_hub');
        Gate::define('view_ev_mapped_hub', 'App\Policies\HubPolicy@view_ev_mapped_hub');
        Gate::define('track_refund_complaint', 'App\Policies\HubPolicy@track_refund_complaint');
        Gate::define('generate_send_refund_report', 'App\Policies\HubPolicy@generate_send_refund_report');

        //Rider Policies
        Gate::define('enable_disable_customer', 'App\Policies\CustomerPolicy@enable_disable_customer');

        //Cpmplain Policies
        Gate::define('view_complaint', 'App\Policies\ComplaintPolicy@view_complaint');
        Gate::define('change_complaint_status', 'App\Policies\ComplaintPolicy@change_complaint_status');
        Gate::define('change_assignment', 'App\Policies\ComplaintPolicy@change_assignment');

        //Notification Policies
        Gate::define('view_notification', 'App\Policies\NotificationPolicy@view_notification');
        Gate::define('set_automatic_notification', 'App\Policies\NotificationPolicy@set_automatic_notification');
        Gate::define('send_push_notification', 'App\Policies\NotificationPolicy@send_push_notification');

        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(7));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
