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
                    
                    templates.render("local_smf/course_iframe", response).then(function(html, js) {
                        templates.prependNodeContents("#user-notifications", html, js);
                        $('.chat_smf').click(function(){
                            //llamado al chat
                            var promises_chat = ajax.call([
                                { methodname: 'local_smf_load_chats',
                                    args: {

                                    }
                                }
                            ]);
                            promises_chat[0].done(function(response_chat) {
//                                console.log("response_chat");
//                                console.log(response_chat);
                            }).fail(function(ex) {
                            // Deal with this exception (I recommend core/notify exception function for this).
                            });
                            
                        });
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