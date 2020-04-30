let pullingModal = false;
let modal = null;

$('a[data-form="modal"]').click(function (e) {
    e.preventDefault();
    var modalButton = $(e.target);
    if(!pullingModal){
        pullingModal = true;
        $.ajax({
            url: modalButton.attr('href'),
            method: 'GET'
        }).then(function (result) {
            result = JSON.parse(result);
            if(result && validate(result)){
                modal = $(result.content);
                modal.modal();
                eval(result.script);
                modal.on('hidden.bs.modal', function () {
                    modal.remove();
                    modal = null;
                });
            }
        }).always(function () {
            pullingModal = false;
        });
    }
});

function validate(result) {
    var valid = true;
    if(!result.hasOwnProperty('content')){
        valid = false;
        console.error("Result has no 'content' property.");
    }
    if(!result.hasOwnProperty('script')){
        valid = false;
        console.error("Result has no 'script' property.");
    }
    return valid;
}
