<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

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
        Grid::configureUsing(fn (Grid $grid) => $grid->columnSpanFull());

        Section::configureUsing(fn (Section $section) => $section->columnSpanFull());
    }
}
