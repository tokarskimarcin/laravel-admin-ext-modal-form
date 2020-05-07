<?php
/**
 * @author Marcin Tokarski <marcinxx1994@gmail.com>
 */

namespace Encore\ModalForm\Form;


use Closure;
use Encore\Admin\Form;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

/**
 * Class ModalForm
 * @package Encore\ModalForm\Form
 */
class ModalForm extends Form implements ModalFormInterface
{
    use HasModalSize;

    /**
     * @var string
     */
    protected $id;

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

        $this->setId(\Illuminate\Support\Str::uuid());

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

    /**
     * Get ajax response.
     *
     * @param string $message
     *
     * @return bool|\Illuminate\Http\JsonResponse
     */
    protected function ajaxResponse($message)
    {
        $request = Request::capture();

        // ajax but not pjax
        if ($request->ajax() && !$request->pjax()) {
            return $this->ajaxResponseBody($message);
        }

        return false;
    }

    protected function ajaxResponseBody($message){
        return response()->json([
            'status'  => true,
            'modelId' => $this->model->getKey(),
            'message' => $message,
        ]);
    }



    /**
     * @param string $id
     * @return $this
     */
    protected function setId(string $id)
    {
        $this->id = "$id-modal";
        return $this;
    }

    /**
     * @return string
     */
    public function getId(){
        return $this->id;
    }
}
