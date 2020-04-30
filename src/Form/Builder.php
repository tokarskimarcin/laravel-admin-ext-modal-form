<?php


namespace Encore\ModalForm\Form;


use Encore\Admin\Facades\Admin;

class Builder extends \Encore\Admin\Form\Builder
{
    protected $view = 'modal-form::form';

    /**
     * @return string
     */
    public function render():string
    {
        return json_encode([
            'content' => parent::render(),
            'script' => Admin::script()
        ]);
    }

    /**
     * Do initialize.
     */
    public function init()
    {
        parent::init();
        $this->footer = new Footer($this);
    }
}
