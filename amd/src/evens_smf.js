define(['jquery', 'core/templates', 'core/config', 'core/ajax'], function($, templates, config, ajax){
    return {
        init: function() {
            var promises = ajax.call([
                { methodname: 'local_smf_events',
                    args: {
                        
                    }
                }
            ]);
            //seteo la data al tabulator
            promises[0].done(function(response) {
                if(response != []){
                    //$('#user-notifications').append(response.html);
                    var context_elearning = {
                        
                    };
                    console.log("html");
                    templates.render("local_smf/template_course_iframe", context_elearning).then(function(html, js) {
                        console.log("html 2");
                        console.log(html);
                        templates.prependNodeContents("#user-notifications", 'ssssssssssssssssssss', js);
                    }).fail(function(ex) {
                    // Deal with this exception (I recommend core/notify exception function for this).
                    });
                }
            }).fail(function(ex) {
                console.log('errr');
                console.error(ex);
            });
        }
    }
})