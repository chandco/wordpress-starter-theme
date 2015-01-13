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


            function my_cleanup_callback(type,value) {
              switch (type) {
                case 'get_from_editor':
                  // Remove &nbsp; characters
                  value = value.replace(/&nbsp;/ig, ' ');
                  break;
                case 'insert_to_editor':
                case 'submit_content':
                case 'get_from_editor_dom':
                case 'insert_to_editor_dom':
                case 'setup_content_dom':
                case 'submit_content_dom':
                default:
                  break;
              }
              return value;
            }

            ed.on

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

            

            // Just the columns, start with two, add more if needed.  in the future we can add a feature to add predefined layouts with less functionality but
            // for the most part it should be enough for uniformity.  We could also maybe add in a class to set the width dynamically.

            // eg "col-smart' but :first-of-type { width 66% } for wider etc"

            ed.addButton('columns', {
                title: 'Insert Columns',
                cmd: 'columns',
                image : url + '/../img/halves.png'
            });

            ed.addCommand('columns', function() {
                var selected_text = ed.selection.getContent();


                var button_text = "<button class='col-control-add mceNonEditable'>Add Column</button><button class='col-control-remove mceNonEditable'>Remove Column</button>";

                return_text =   '<div class="cf_columns">';
                return_text +=  '<div class="col-smart">' + button_text + selected_text + '</div>';
                return_text +=  '<div class="col-smart">' + button_text + '</div>';
                return_text +=  '</div> &nbsp;';

                ed.selection.setContent(return_text);
                
            });

            ed.on('setContent', function(o) {
                
                // add controls to the element for adjustments


                
                ed.$.each( ed.$('.cf_columns > div'), function(index, element) {
                    
                        ed.$(element)
                            .prepend("<button class='col-control-add mceNonEditable'>Add Column</button>")
                            .prepend("<button class='col-control-remove mceNonEditable'>Remove Column</button>");  

                       
                });



            });

            ed.on('SaveContent', function(o) {

                // find any columns and remove the buttons

                // get content in temp element

               $element = ed.$("<div>" + o.content + "</div>"); // wrap it so jquery can make something of it
               $element.find('button[class^="col-control-"]').remove();

                o.content = $element.html();

            });


            ed.onClick.add(function(ed, e) {
                
                //console.debug('Editor was clicked: ', ed.$(e.target));

                $element = ed.$(e.target);

                

                // add a column
                if ($element.hasClass('col-control-add')) {
                    $('<div class="col-smart"></div>')
                            .prepend("<button class='col-control-add mceNonEditable'>Add Column</button>")
                            .prepend("<button class='col-control-remove mceNonEditable'>Remove Column</button>")
                            .insertAfter( $element.closest('div[class^="col-"]') );
                    
                    
                    

                } 

                // remove a column
                if ($element.hasClass('col-control-remove')) {

                    // get column
                    $contents = $element.closest('div[class^="col-"]');

                    // remove the buttons we added temporarily

                    $contents.find('button[class^="col-control-"]').remove();



                    // get the column row
                    $wrapper = $contents.parent()

                    



                    // put the contents afterwards, outside of the container
                    $wrapper.after( $contents.html() ); // inner HTML not the container as well

                    // now get rid of this column
                    $contents.remove(); 
                    

                    if ($wrapper.find('div[class^="col-"]').length < 1) {
                        console.log('Nothing Left in columns');
                        $wrapper.remove();
                    }
                }
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


// Views - this is for the feature box
 (function($){
                var media = wp.media, shortcode_string = 'feature-box', wpshortcode = wp.shortcode;

                wp.mce = wp.mce || {};
                wp.mce.feature_box = {

                    update: function( data ) {

                        // s = the new content.  Will replace everything in teh shortcode with this
                        



                        var s = '[' + shortcode_string;

                            
                            //s += ' img="' + data.img + '" ';
                            s += ' imgid="' + data.imgid + '" ';
                            s += ' link="' + data.link + '" ';
                            s += ' title="' + data.title + '" ';
                            s += ' linktitle="' + data.linktitle + '" ';

                                
                            s += ']';
                            s += data.innercontent;
                            s += '[/' + shortcode_string + ']';


                        tinyMCE.activeEditor.insertContent( s );    


                    },
                    shortcode_data: {},
                    View: {

                        template: media.template( 'editor-feature-box' ),
                        postID: $('#post_ID').val(),
                        initialize: function( options, node ) {

                            
                            
                            this.shortcode = options.shortcode;
                            wp.mce.feature_box.shortcode_data = this.shortcode;

                            


                        },
                        getHtml: function() {

                            var options = this.shortcode.attrs.named;
                            options['innercontent'] = this.shortcode.content;

                            
                            //console.log(this.shortcode);

                            // format the template
                            output = this.template(options);

                            // get HTML as Jquery object
                            $content = $(output);

                           
                            // add in the image from the id

                            // get image SRC


                            if (this.shortcode.attrs.named.imgid) {


                                imgid = this.shortcode.attrs.named.imgid;   

                                idURL = mcedata.apiURL + 'posts/' + imgid;

                                
                                // fix the ID for this particular element so that AJAX can find it later.
                                current = this; // so ajax doesn't get confused
                                current.uid =  tinyMCE.activeEditor.dom.uniqueId('mce_fb_');
                                $content.attr("id",current.uid);

                                // load the images later when we have them
                                $.ajax(idURL,{
                                    url : idURL,
                                    type : 'GET',
                                    //data : dataArray,
                                    cache : false,
                                    element : current
                                }).done( function( response ) {

                                    // find teh original one
                                    $content = $( tinyMCE.activeEditor.dom.get( this.element.uid ) );

                                    ext = response.guid.substr(response.guid.length - 4);
                                    thumb = response.guid.replace(ext, mcedata.imgSuffix + ext);
                                    
                                    $content
                                        .find('header')
                                        .prepend( $('<img src="' + thumb + '" />') )
                                        .prepend( $("<p>Image ID " + this.element.shortcode.attrs.named.imgid + "</p>") );

                                    

                                });
                            } // else no id for image set so no image to find


                            // replace the image content;

                            
                
                            return $content[0].outerHTML;
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