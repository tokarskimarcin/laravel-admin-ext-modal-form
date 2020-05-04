<?php
/**
 * @author Marcin Tokarski <marcinxx1994@gmail.com>
 */

namespace Encore\ModalForm\Form;


use Closure;
use Encore\Admin\Form;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\MessageBag;

/**
 * Class ModalForm
 * @package Encore\ModalForm\Form
 */
class ModalForm extends Form implements ModalFormInterface
{
    use HasModalSize;

    /**
     * Size of modal
     * @var string
     */
    protected $size = '';

    /**
     * ModalForm constructor.
     * @param $model
     * @param Closure|null $callback
     */
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

    public function getSize()
    {
        return $this->size;
    }
}
