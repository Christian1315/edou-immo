<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'CheckSuperAdmin' => \App\Http\Middleware\CheckSuperAdmin::class,
        'ChechSuperAdminOrSimpleAdmin' => \App\Http\Middleware\ChechSuperAdminOrSimpleAdmin::class,

        'CheckIfUserIsAdminOrLogistique' => \App\Http\Middleware\CheckIfUserIsAdminOrLogistique::class,
        'CheckIfUserIsAdminOrExploitation' => \App\Http\Middleware\CheckIfUserIsAdminOrExploitation::class,
        'CheckIfUserIsAdminOrMarketeur' => \App\Http\Middleware\CheckIfUserIsAdminOrMarketeur::class,

        'CheckIfUserIsMarketeur' => \App\Http\Middleware\CheckIfUserIsMarketeur::class,
        'CheckIfUserIsLogistique' => \App\Http\Middleware\CheckIfUserIsLogistique::class,
        'CheckIfUserIsExploitation' => \App\Http\Middleware\CheckIfUserIsExploitation::class,

        'Check_If_User_Has_A_Master_Role' => \App\Http\Middleware\Check_If_User_Has_A_Master_Role::class,
        'Check_If_User_Has_A_Chief_Accountant_Role' => \App\Http\Middleware\Check_If_User_Has_A_Chief_Accountant_Role::class,
        'Check_If_User_Has_A_Supervisor_Role' => \App\Http\Middleware\Check_If_User_Has_A_Supervisor_Role::class,
        'Check_If_User_Has_An_Agent_Accountant_Role' => \App\Http\Middleware\Check_If_User_Has_An_Agent_Accountant_Role::class,



        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
        'scope' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
    ];
}
