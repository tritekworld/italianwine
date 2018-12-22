window.addEventListener("load", function () {
  if(gdpr_policy_page.gdpr_url){
    var layoutcheck = 'gdpr-cool-layout';
  }else{
    var layoutcheck = 'gdpr-cool-layout-wlink';
  }
  window.cookieconsent.initialise({    
    layout:layoutcheck,
    layouts: {
      'gdpr-cool-layout': '{{header}}{{message}}{{link}}{{compliance}}<span aria-label="dismiss cookie message" role="button" tabindex="0" class="gdpr-close"><img style="width: 13px !important; top: 0;right: 5px;" draggable="false" class="emoji cc-close" alt="❌" src="https://s.w.org/images/core/emoji/11/svg/274c.svg"></span>',
      'gdpr-cool-layout-wlink': '{{header}}{{message}}{{compliance}}<span aria-label="dismiss cookie message" role="button" tabindex="0" class="gdpr-close"><img style="width: 13px !important; top: 0;right: 5px;" draggable="false" class="emoji cc-close" alt="❌" src="https://s.w.org/images/core/emoji/11/svg/274c.svg"></span>',
    },
    "palette": {
      "popup": {
        "background": gdpr_policy_page.gdpr_popup_background,
        "text": gdpr_policy_page.gdpr_popup_text
      },
      "button": {
        "background": gdpr_policy_page.gdpr_button_background,
        "text": gdpr_policy_page.gdpr_button_text,
        "border": gdpr_policy_page.gdpr_button_border
      }
    },
    "position": gdpr_policy_page.gdpr_popup_position,
    "static": gdpr_policy_page.gdpr_popup_static,
    "theme": gdpr_policy_page.gdpr_popup_theme,
    "type": gdpr_policy_page.gdpr_popup_type,
    "content": {      
      "header": gdpr_policy_page.gdpr_header, //"Cookies used on the website!"
      "message": gdpr_policy_page.gdpr_message,      
      "href": gdpr_policy_page.gdpr_url,      
      "link": gdpr_policy_page.gdpr_link,
      "deny": gdpr_policy_page.gdpr_dismiss,
      "allow": gdpr_policy_page.gdpr_allow,
      "target": '_blank',
    },
    onStatusChange: function (status, chosenBefore) {
      if(chosenBefore == 'false' || status=="allow")
      {
        jQuery(document).ready( function($){
         $.getJSON('https://api.ipify.org?format=json', function(data){
              $.ajax({
                url: ajax_object.ajaxurl, 
                type: 'POST',
                data:{ 
                  action: 'add_consent_accept_cookies',
                  userip: data.ip
                },
                success: function( data ){
                  $('.cc-close').click();
                 if(gdpr_policy_page.gdpr_hide){
                    $(".cc-revoke").hide();
                  }
                }
              });
          });
        
        });
      }else if(chosenBefore == 'false' || status=="deny"){
        jQuery(document).ready( function($){
          $.getJSON('https://api.ipify.org?format=json', function(data){
               $.ajax({
                 url: ajax_object.ajaxurl, 
                 type: 'POST',
                 data:{ 
                   action: 'add_consent_deny_cookies',
                   userip: data.ip
                 },
                 success: function( data ){
                   $('.cc-close').click();
                   if(gdpr_policy_page.gdpr_hide){
                    $(".cc-revoke").hide();
                  }
                 }
               });
           });
         
         });
      }
    }
  })
});


/**
 * add datatable frontend.
 */
jQuery(document).ready( function () {
  jQuery('#classiDocs_dataTable').DataTable( {
    "autoWidth": false,
    "scrollX": true
  });
} );