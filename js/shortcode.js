/* global stpShortcodeObject */

jQuery('.subscribe-to-page-form-submit').click(function(e) {
    e.preventDefault();

    formSubmit();
});

function formSubmit() {
    var unsubscribe = 0;

    loadSTPajaxLoader();

    if (jQuery('#subscribe-to-page-form #unsubscribe').is(':checked')) {
        unsubscribe = 1;   
    }

    var data = {
        'email': jQuery('#subscribe-to-page-form #email').val(),
        'unsubscribe': unsubscribe,
        'action': 'subscribe_to_page_subscribe',
        'security': stpShortcodeObject.nonce
    };

    jQuery.post(stpShortcodeObject.ajax_url, data, function(response) {
        closeSTPajaxLoader();
        
        response = jQuery.parseJSON(response);
        var msgClass = '';
        
        if (response.status === 'error') {
            msgClass = 'error';    
        } else {
            msgClass = 'success';
        }
        
        var html = '<div class="'+msgClass+'">' + response.message + '</div>';
        
        jQuery('#subscribe-to-page-form-response').html(html);
        jQuery('#subscribe-to-page-form').find('input[type=email], input[type=checkbox]').val('');
    });

}

function loadSTPajaxLoader() {
    jQuery('#stp-ajax-loader-container').show();
}

function closeSTPajaxLoader() {
    jQuery('#stp-ajax-loader-container').hide();
}