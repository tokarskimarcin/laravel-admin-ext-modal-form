<?php


namespace Encore\ModalForm;

use Closure;
use Encore\Admin\Facades\Admin;

/**
 * Class Modal
 * @package Encore\ModalForm
 */
class Modal
{
    /**
     * @param string $script
     * @param bool $deferred
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string|string[]|void
     */
    public function script($script = '', $deferred = false)
    {
        $script = Admin::script($script, $deferred);
        switch (true){
            case $script instanceof \Illuminate\Contracts\View\Factory:
            case $script instanceof \Illuminate\View\View:
                return str_replace('data-exec-on-popstate', 'data-exec-on-modal-load', $script);
            default: return $script;
        }
    }

    /**
     * @param string $style
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function style($style = ''){
        return Admin::style($style);
    }

    /**
     * @param $model
     * @param Closure $callable
     * @return \Encore\Admin\Form|Form\ModalForm
     */
    public function form($model, Closure $callable)
    {
        return new Form\ModalForm($this->getModel($model), $callable);
    }
}
