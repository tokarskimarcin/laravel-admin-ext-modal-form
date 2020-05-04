<?php


namespace Encore\ModalForm\Form;


trait HasModalSize
{
    public function large()
    {
        $this->size = 'modal-lg';
        return $this;
    }

    public function medium()
    {
        $this->size = '';
        return $this;
    }

    public function small()
    {
        $this->size = 'modal-sm';
        return $this;
    }

}
