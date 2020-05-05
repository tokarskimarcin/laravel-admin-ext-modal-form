<?php


namespace Encore\ModalForm\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class Modal
 * @method static \Encore\ModalForm\Form\ModalForm form($model, \Closure $callable)
 * @method static \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void script($script = '')
 * @method static \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void style($style = '')
 * @package Encore\ModalForm\Facades
 */
class Modal extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Encore\ModalForm\Modal::class;
    }
}
