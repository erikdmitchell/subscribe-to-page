/* global stpControlsObject, alert */

jQuery('#update-page-email-action a').click(function(e) {
    e.preventDefault();

    sendEmail();
});

function sendEmail() {
    loadSTPsendEmailLoader();

    var data = {
        'action': 'subscribe_to_page_send_email',
        'post_id': jQuery('#update-page-email-action #stp_post_id').val(),
        'security': stpControlsObject.nonce
    };

    jQuery.post(stpControlsObject.ajax_url, data, function(response) {
        closeSTPsendEmailLoader();

        alert(jQuery.parseJSON(response));
    });
}

function loadSTPsendEmailLoader() {
    jQuery('#stp-ajax-controls-loader-container').show();
}

function closeSTPsendEmailLoader() {
    jQuery('#stp-ajax-controls-loader-container').hide();
}