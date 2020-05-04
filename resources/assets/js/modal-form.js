let pullingModal = false;
let modal = null;

class Modal {
    constructor(result) {
        this.$el = $(result.content);
        this.init(result);
    }

    init(result) {
        this._handleSubmit();
        this._handleReset();
        this._show(result);
        this._handleHide();
    }

    _handleSubmit() {
        var form = this.$el.find('form');
        var that = this;
        form.on('submit', function (e) {
            e.preventDefault();
            that._clearErrors();
            that._disableButtons();
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize()
            }).error((jqXHR, textStatus, errorThrown) => {
                swal(jqXHR.status.toString(), errorThrown, 'error');
            }).success(function (result) {
                if(result.status){

                }else{
                    that._handleErrors(result);
                }
            }).always(function () {
                that._enableButtons();
            });

        });
    }

    _handleReset(){
        this.$el.find('[type="reset"]').click(()=>{
            this._clearErrors();
        });
    }

    _getButtons() {
        return this.$el.find('.btn');
    }

    _enableButtons() {
        var buttons = this._getButtons();
        buttons.each(function (id, button) {
            let $button = $(button);
            switch ($button.prop('tagName')) {
                case 'A':
                    $button.attr('href', $button.data('data-href'));
                    $button.removeAttr('data-href');
                //intentional break statement missing
                case 'BUTTON':
                    $button.removeAttr('disabled');
                    break;
            }
        });
    }

    _disableButtons() {
        var buttons = this._getButtons();
        buttons.each(function (id, button) {
            let $button = $(button);
            switch ($button.prop('tagName')) {
                case 'A':
                    $button.data('data-href', $button.attr('href'));
                    $button.attr('href', 'javascript:void(0)');
                //intentional break statement missing
                case 'BUTTON':
                    $button.attr('disabled', true);
                    break;
            }
        });

    }

    _handleHide() {
        var that = this;
        this.modal.on('hidden.bs.modal', function () {
            that.remove();
            modal = null;
        });
    }

    _show(result) {
        $(document).find('.wrapper').prepend(this.$el);
        if (typeof result.script != 'object'){
            var script = $(result.script);
            this.$el.append(script);
            $.globalEval(script.prop('innerHTML'));
        }
        this.modal = this.$el.modal({
            allowOutsideClick: false
        });
    }

    remove() {
        this.modal.remove();
        $('.modal-backdrop').remove();
    }
    _clearErrors(){
        var formGroups = this.$el.find(".has-error");
        formGroups.each(function (id, formGroup) {
            formGroup = $(formGroup);
            formGroup.removeClass('has-error');
            formGroup.find('[for="inputError"]').each((key, inputError)=>{
                $(inputError).siblings('br').remove();
            }).remove();
        });
    }

    _handleErrors(result){
        var that = this;
        if(result.hasOwnProperty('validation')){
            var messages = result.validation;
            for (var [key, message] of Object.entries(messages)){
                var input = that.$el.find(`[name="${key}"]`);
                if(input){
                    var formInput = new FormInput(input);
                    formInput.showMessage(message);
                }
            }
        }
    }
}

class FormInput{
    constructor(input) {
        this.$el = input
        this.formGroup = this.$el.closest('.form-group');
        this.inputGroup = this.formGroup.find('.input-group');
    }

    showMessage(message){
        this.formGroup.addClass('has-error');
        if(Array.isArray(message)){
            message.forEach((error)=>{
                this.inputGroup.before(this._getErrorLabel(error))
            });
        }
    }

    _getErrorLabel(error){
        return `<label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i> ${error}</label><br/>`;
    }
}

$('a[data-form="modal"]').click(function (e) {
    e.preventDefault();
    var modalButton = $(e.target);
    if (!pullingModal) {
        pullingModal = true;
        modalForm(modalButton.attr('href'))
    }
});
$(document).on('pjax:start', function () {
    if (modal) {
        modal.remove();
    }
});

function modalForm(href) {
    $.ajax({
        url: href,
        method: 'GET'
    }).then(function (result) {
        makeModal(result);
    }).always(function () {
        pullingModal = false;
    });
}

function makeModal(result) {
    result = JSON.parse(result);
    if (result && validate(result)) {
        modal = new Modal(result);
    }
}

function validate(result) {
    var valid = true;
    if (!result.hasOwnProperty('content')) {
        valid = false;
        console.error("Result has no 'content' property.");
    }
    if (!result.hasOwnProperty('script')) {
        valid = false;
        console.error("Result has no 'script' property.");
    }
    return valid;
}
