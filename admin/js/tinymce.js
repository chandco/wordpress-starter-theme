(function() {
    tinymce.create('tinymce.plugins.cf_features', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.


      



         */
        init : function(ed, url) {

            var t = this;

            
            ed.addButton('feature', {
                title: 'Insert Feature Box',
                cmd: 'feature',
                image : url + '/../img/feature.png'
            });

           /* ed.addCommand('feature', function() {
                var selected_text = ed.selection.getContent();
                var return_text = '';
                return_text = '<div class="feature">\n\t<header>\n\t<h2>Heading Here</h2>' + selected_text + '</header>\n\t<div class="content">\n\tPut your text here</div>';
                ed.execCommand('mceInsertContent', 0, return_text);
            });
            */

            ed.addCommand('feature', function() {
                var selected_text = ed.selection.getContent();
                var return_text = '';
                return_text = '[feature-box title="Feature Box (Edit Title By Clicking)"]' + selected_text + '[/feature-box]';
                ed.execCommand('mceInsertContent', 0, return_text);
            });

            //ed.onBeforeSetContent.add(function(ed, o) {
           //     o.content = t._do_featurebox(o.content);
           // });



            ed.addButton('halves', {
                title: 'Insert 2 columns',
                cmd: 'halves',
                image : url + '/../img/halves.png'
            });

            ed.addCommand('halves', function() {
                var selected_text = ed.selection.getContent();
                var return_text = '';
                return_text = '<div class="cf_columns">\n\t<div class="col-half">&nbsp;</div>\n\t<div class="col-half">&nbsp;</div></div>';
                ed.execCommand('mceInsertContent', 0, return_text);
            });

            ed.on('SaveContent', function(o) {
                //console.log('SaveContent event', e);
                // var parser = new tinymce.html.DomParser({validate: true});
                // var contentDOM = parser.parse(o.content   
                


            });

            ed.onClick.add(function(ed, e) {
                
                console.debug('Editor was clicked: ', ed.$(e.target));


                
                
            });
 
        },


 
         /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Nathans Wordpress Starter Theme Editor Features',
                author : 'Nathan',
                authorurl : 'http://cowfields.co.uk',
                version : "0.1"
            };
        }
    });
 
    // Register plugin
    tinymce.PluginManager.add( 'cf_features', tinymce.plugins.cf_features );
})();


// Views
 (function($){
                var media = wp.media, shortcode_string = 'feature-box', wpshortcode = wp.shortcode;

                console.log(media.template( 'editor-feature-box' ));

                wp.mce = wp.mce || {};
                wp.mce.feature_box = {

                    update: function( data ) {

                        // s = the new content.  Will replace everything in teh shortcode with this
                        //console.log(data);

                       // console.log(this.shortcode);


                        var s = '[' + shortcode_string;

                            
                            s += ' img="' + data.img + '" ';
                            s += ' link="' + data.link + '" ';
                            s += ' title="' + data.title + '" ';
                                
                            s += ']';
                            s += data.innercontent;
                            s += '[/' + shortcode_string + ']';


                        tinyMCE.activeEditor.insertContent( s );    


                    },
                    shortcode_data: {},
                    View: {
                        template: media.template( 'editor-feature-box' ),
                        postID: $('#post_ID').val(),
                        initialize: function( options ) {
                            
                            this.shortcode = options.shortcode;
                            wp.mce.feature_box.shortcode_data = this.shortcode;
                        },
                        getHtml: function() {
                            var options = this.shortcode.attrs.named;
                            options['innercontent'] = this.shortcode.content;
                            //console.log(this.shortcode);
                            return this.template(options);
                        }
                    },
                    edit: function( node ) {
                        var data = window.decodeURIComponent( $( node ).attr('data-wpview-text') );
                        

                        el = wpshortcode.next(shortcode_string, data);

                        
                        var values = el.shortcode.attrs.named;
                        //values['innercontent'] = this.shortcode_data.content;
                       

                        
                        wp.mce.feature_box.popupwindow(tinyMCE.activeEditor, values, node);
                        //$( node ).attr( 'data-wpview-text', window.encodeURIComponent( shortcode ) );
                    },
                    // this is called from our tinymce plugin, also can call from our "edit" function above
                    // wp.mce.feature_box.popupwindow(tinyMCE.activeEditor, "bird");
                    popupwindow: function(editor, values, node, onsubmit_callback){
                        
                        if(typeof onsubmit_callback != 'function'){
                            onsubmit_callback = function( e ) {
                                // not being called right now
                            };
                        }

                        editor.windowManager.open( {
                            title: "Edit Feature Box",
                            url: mcedata.adminurl + 'admin.php?page=feature-box-edit',
                            width: 800,
                            height: 500,
                        },

                        {
                            data : values
                        }
                            
                        );
                    }
                };
                wp.mce.views.register( shortcode_string, wp.mce.feature_box );



            }(jQuery));