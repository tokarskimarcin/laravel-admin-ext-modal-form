<?php
/**
 * @author Marcin Tokarski <marcinxx1994@gmail.com>
 */

namespace Encore\ModalForm\Form;


use Closure;
use Encore\Admin\Form;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ModalForm
 * @package Encore\ModalForm\Form
 */
class ModalForm extends Form implements ModalFormInterface
{
    public function __construct($model, Closure $callback = null)
    {
        $this->model = $model;

        $this->builder = new Builder($this);

        $this->initLayout();

        if ($callback instanceof Closure) {
            $callback($this);
        }

        $this->isSoftDeletes = in_array(SoftDeletes::class, class_uses_deep($this->model), true);

        $this->callInitCallbacks();
    }
}
