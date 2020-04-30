<?php


namespace Encore\ModalForm\Form;


use Encore\Admin\Facades\Admin;
use Illuminate\Contracts\Support\Renderable;

class ModalButton implements Renderable
{
    use HasHtmlAttributes;

    const DEFAULT_ATTRIBUTES = [
        'class' => 'btn btn-default',
        'data-form' => 'modal'
    ];

    /**
     * @var string
     */
    protected $label;

    public function __construct(string $label, string $href)
    {
        $this->label = empty($label)?'Modal':$label;
        $this->setAttribute('href', $href);
        $this->initHtmlAttributes();
    }

    public function render()
    {
        Admin::script(file_get_contents(public_path('/vendor/laravel-admin-ext/modal-form/js/pull-form.js')));
        $attr=$this->formatAttributes();
        return "<a $attr>$this->label</a>";
    }

    /**
     * @param string $href
     * @return $this
     */
    public function setHref(string $href){
        $this->setAttribute('href', $href);
        return $this;
    }

    /**
     * @return string
     */
    public function getHref(){
        return $this->getAttribute('href');
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setClass(string $class){
        $this->setAttribute('class', $class);
        return $this;
    }

    /**
     * @param string $class
     * @return $this
     */
    public function addClass(string $class){
        $this->addAttribute('class', $class);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getClasses(){
        return $this->getAttribute('class');
    }
}
