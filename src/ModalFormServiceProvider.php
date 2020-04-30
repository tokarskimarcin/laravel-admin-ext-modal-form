<?php

namespace Encore\\ModalForm;

use Illuminate\Support\ServiceProvider;

class ModalFormServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(ModalForm $extension)
    {
        if (! ModalForm::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'modal-form');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/modal-form')],
                'modal-form'
            );
        }

        $this->app->booted(function () {
            ModalForm::routes(__DIR__.'/../routes/web.php');
        });
    }
}