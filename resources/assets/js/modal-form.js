class Modal {
    constructor(result) {
        this.$el = $(result);
        this.id = this.$el.attr('id');
        this.init();
    }

    init() {
        this._handleSubmit();
        this._handleReset();
        this._show();
        this._handleHide();
    }

    setButton(modalButton){
        this.$modalButton = modalButton;
        return this;
    }

    _handleSubmit() {
        var form = this.$el.find('form');
        var that = this;
        form.on('submit', function (e) {
            that.$modalButton.trigger('modelCreating');
            e.preventDefault();
            that._clearErrors();
            that._disableButtons();
            that.loading();
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize()
            }).error((jqXHR, textStatus, errorThrown) => {
                this.$modalButton.trigger('modelFailed');
                swal(jqXHR.status.toString(), errorThrown, 'error');
            }).success(function (result) {
                if(result.status){
                    toastr.success(result.message);
                    that.$modalButton.data('model-id', result.modelId);
                    that.$modalButton.trigger('modelCreated');
                    that.dismiss();
                }else{
                    that.$modalButton.trigger('modelValidationFailed');
                    that._handleErrors(result);
                }
            }).always(function () {
                that._enableButtons();
                that.loading(false);
            });

        });
    }

    loading(isLoading = true){
        if(isLoading){
            this.$loading = $('<div>').addClass('modal-backdrop in').append($("<div>").addClass('editableform-loading').css({
                'position': 'absolute',
                'top': '50%',
                'left': '50%',
                'z-index': '10000'
            }));
            this.$el.find('.modal-dialog').append(this.$loading);
        }else{
            if(this.$loading){
                this.$loading.remove();
            }
        }

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
            var index = modals.findIndex((element)=>{
                return element.id === that.id;
            });
            if(index > -1){
                modals.slice(index, 1);
            }
            that.modal.remove();
        });
    }

    _show() {
        this._appendModal();
        this.modal = this.$el.modal({
            backdrop: 'static',
            keyboard: false
        });
    }
    _appendModal(){
        $(document).find('header.main-header').before(this.$el);
    }

    dismiss() {
        this.modal.modal('hide');
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

$('a[data-form="modal"]:not([data-form-event-attached])').each((key, element)=>{
    var $modalButton = $(element);
    $modalButton.attr('data-form-event-attached', true);
}).click(function (e) {
    e.preventDefault();
    var modalButton = $(e.target);
    if(!modalButton.attr('disabled')){
        $.ajax({
            url: modalButton.attr('href'),
            method: 'GET'
        }).success(function (result) {
            var modal = new Modal(result);
            modals.push(modal);
            modal.setButton(modalButton);
        });
    }
});
