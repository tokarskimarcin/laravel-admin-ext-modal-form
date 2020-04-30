<?php

use Encore\\ModalForm\Http\Controllers\ModalFormController;

Route::get('modal-form', ModalFormController::class.'@index');