let modals = [];

$(document).on('pjax:start', function () {
    if (modals.length) {
        modals.forEach(( modal, key)=>{
            modal.dismiss();
        });
    }
});
