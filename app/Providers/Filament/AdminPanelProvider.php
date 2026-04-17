<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#1F3A5F'),
                'success' => Color::hex('#2E7D32'), 
                //'gray' => Color::hex('#1F3A5F'),
            ])
            ->brandName('') //
            ->renderHook(
                'panels::topbar.start',
                fn () => '
                <div style="display:flex; align-items:center; gap:10px;">
                    <img src="/images/logo.png" style="height:35px;">
                    <div>
                        <div style="color:white; font-weight:bold;">
                            BEKASWIT
                        </div>
                        <div style="color:#cbd5e1; font-size:11px;">
                            Bekas Jadi Duwit
                        </div>
                    </div>
                </div>
            '
            )
            ->renderHook(
                'panels::body.start',
                fn () => '
                <style>

                    // header {
                    //     background-color: #1F3A5F !important;
                    // }
                        
                    .fi-topbar {
                        background-color: #1F3A5F !important;
                    }

                    .fi-page-heading {
                        color: white !important;
                    }
                    
                    fi-topbar * { 
                        color: white !important; 
                    }
                   
                    .dark .fi-topbar {
                        background-color: #1F3A5F !important;
                    }

                    .dark .fi-topbar-heading {
                        color: white !important;
                    }

                    .dark .fi-page-heading {
                        color: white !important;
                    }
                    
                    .dark .fi-sidebar-item {
                        background-color: white !important;
                        border-radius: 7px !important;
                    }
                        

                    .fi-sidebar {
                        background-color: #1F3A5F !important;
                    }
                    
                    .fi-sidebar-item-active .fi-sidebar-item-label {
                        color: black !important;
                    }

                    .fi-sidebar-item-active .fi-sidebar-item-icon {
                        color: black !important;
                    }

                </style>
            '
            )
            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
