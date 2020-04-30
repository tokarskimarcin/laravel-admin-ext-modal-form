<?php

namespace Encore\\ModalForm;

use Encore\Admin\Extension;

class ModalForm extends Extension
{
    public $name = 'modal-form';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => 'Modalform',
        'path'  => 'modal-form',
        'icon'  => 'fa-gears',
    ];
}