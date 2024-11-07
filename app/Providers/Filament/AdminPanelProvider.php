<?php

namespace App\Providers\Filament;

use App\Filament\Resources\NeedResource\Pages\NeedsByInstance;
use App\Filament\Resources\NeedResource\Pages\NeedsByToon;
use App\Models\User;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
//use Filament\Support\Assets\Css;
//use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel_config = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->navigationItems([
                NavigationItem::make('Add New Item')
                    ->url('/admin/items/create')
                    ->group('Items')
                    ->sort(0),
                NavigationItem::make('Create Need')
                    ->url('/admin/needs/create')
                    ->group('Item Needs')
                    ->sort(0),
                NavigationItem::make('Needs By Toon')
                    ->url('/admin/needs-by-toon')
                    ->group('Item Needs')
                    ->sort(9),
                NavigationItem::make('Needs By Instance')
                    ->url('/admin/needs-by-instance')
                    ->group('Item Needs')
                    ->sort(29)
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                NeedsByInstance::class,
                NeedsByToon::class
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Items')
                    ->collapsed(true)
                    ->icon('heroicon-o-rectangle-stack'),
                NavigationGroup::make('Toons')
                    ->collapsed(true)
                    ->icon('heroicon-o-user'),
                NavigationGroup::make('Item Needs')
                    ->collapsed(true)
                    ->icon('heroicon-o-rectangle-stack'),
                NavigationGroup::make('Needs By Toon')
                    ->collapsed(true)
                    ->icon('heroicon-o-user-group'),
                NavigationGroup::make('Needs By Instance')
                    ->collapsed(true)
                    ->icon('heroicon-o-globe-alt')
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->assets([
                //Css::make('custom-stylesheet', resource_path('css/app.css')),
                //Js::make('custom-script', resource_path('js/app.js')),
                //Js::make('wow-tooltip', "https://wow.zamimg.com/js/tooltips.js") // in custom header now
            ])
            ->plugins([
                \BondarDe\FilamentRouteList\FilamentRouteListPlugin::make(),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
            ]);
        return $panel_config;
    }
}
