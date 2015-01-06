(function() {
    tinymce.create('tinymce.plugins.features', {
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


            ed.on('BeforeSetcontent', function(o) {
                 o.content = t._do_featurebox(o.content);
            });

            ed.on('LoadContent', function(o) {
                o.content = t._do_featurebox(o.content); 
            });

            ed.on('BeforeExecCommand', function(e) {
              console.log('BeforeExecCommand event', e);
          });


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
                // var contentDOM = parser.parse(o.content);
                      

                $elements = ed.$('div.feature');

                ed.$.each($elements, function(index, value) {
                   
                    $element = ed.$(value);

                    restored = t._restore_shortcode($element);
                    o.content = o.content.replace($element[0].outerHTML, restored);

                });               
                


            });

            ed.onClick.add(function(ed, e) {
                console.debug('Editor was clicked: ', ed.$(e.target));

                

                if (ed.$(e.target).hasClass('feature')) {
                    // console.log("Clicked Feature Box");
                    // popup window and stuff.  
                    
                    pa_title = $(e.target).find("header h2").html();
                    pa_content = $(e.target).find("div.innerContent").html();

                    popup = ed.windowManager.open({
                       url: ajaxurl + '?action=custom_mce',
                       width: 800,
                       height: 900
                    }, {
                        // specific to shortcode here
                        title : pa_title,
                        content: pa_content,
                        element: e.target,
                       }
                    );

                    var endId = tinymce.DOM.uniqueId();
                    var bookmark = tinymce.DOM.create('span', { id : endId }, "");
                    ed.dom.insertAfter(bookmark, e.target);

                    //select that span
                    var newNode = ed.dom.select('span#' + endId);
                    ed.selection.select(newNode[0]);








                    
                    

                    //.setCursorLocation(ed.selection.getNode(), 1);




                }
                
            });
 
        },


        _process_popup_form: function(a, b, win, el) {
            

            $(el).find("header h2").html(a);
            $(el).find("div.innerContent").html(b);
            win.close();
        },

        _restore_shortcode: function(element) {
            // textarea#content.wp-editor-area
            //content = "TEXT EDITORRRRRR";

            // should put some logic here about which shortcode we're replacing.  
            // it'll be a container div that gives it all away, eg which class name will be the 'id' of the shortcode.  
            // if you break from that rule logic here becomes harder.
            return '[feature-box]' + element.html() + '[/feature-box]';
        },

        _do_featurebox: function(content) {
            
            // perhaps add more logic about the shortcode names here, perhaps a swtich wit fucntions, or do it above
            var scontent;
            
            scontent = wp.shortcode.replace( 'feature-box', content, function( obj ) {
                

                output = '<div class="feature mceNonEditable">';
                if (obj.attrs.named.title) {
                    // do stuff
                    output += '<header><h2>' + obj.attrs.named.title + '</h2></header>';
                }
                
                output += '<div class="innerContent">' + obj.content + '</div>';

                output += '</div>';    
                
                return output;

            } );

            console.log(scontent);
            return scontent;
            //return content.replace(/\[feature-box([^\]]*)\](.?)\[\/feature-box\]/g, function(a,b,c){
              //  return '<div class="feature">\n\t<header>\n\t<h2>' + b + '</h2></header>\n\t<div class="content">\n\t' + b + '</div>';
            //});
        },
 
        /**
         * Creates control instances based in the incomming name. This method is normally not
         * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
         * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
         * method can be used to create those.
         *
         * @param {String} n Name of the control to create.
         * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
         * @return {tinymce.ui.Control} New control instance or null if no control was created.
         */
        createControl : function(n, cm) {
            return null;
        },
 
        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Insert Feature',
                author : 'Nathan',
                authorurl : 'http://cowfields.co.uk',
                version : "0.1"
            };
        }
    });
 
    // Register plugin
    tinymce.PluginManager.add( 'features', tinymce.plugins.features );
})();



(function() {
    tinymce.create('tinymce.plugins.featureBox', {

        init : function(ed, url) {
            var t = this;

            t.url = url;
            t.editor = ed;
            t._createButtons();

            // Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('...');
            ed.addCommand('WP_Gallery', function() {
                if ( tinymce.isIE )
                    ed.selection.moveToBookmark( ed.wpGalleryBookmark );

                var el = ed.selection.getNode(),
                    gallery = wp.media.gallery,
                    frame;

                // Check if the `wp.media.gallery` API exists.
                if ( typeof wp === 'undefined' || ! wp.media || ! wp.media.gallery )
                    return;

                // Make sure we've selected a gallery node.
                if ( el.nodeName != 'IMG' || ed.dom.getAttrib(el, 'class').indexOf('wp-gallery') == -1 )
                    return;

                frame = gallery.edit( '[' + ed.dom.getAttrib( el, 'title' ) + ']' );

                frame.state('gallery-edit').on( 'update', function( selection ) {
                    var shortcode = gallery.shortcode( selection ).string().slice( 1, -1 );
                    ed.dom.setAttrib( el, 'title', shortcode );
                });
            });

            ed.onInit.add(function(ed) {
                // iOS6 doesn't show the buttons properly on click, show them on 'touchstart'
                if ( 'ontouchstart' in window ) {
                    ed.dom.events.add(ed.getBody(), 'touchstart', function(e){
                        var target = e.target;

                        if ( target.nodeName == 'IMG' && ed.dom.hasClass(target, 'wp-gallery') ) {
                            ed.selection.select(target);
                            ed.dom.events.cancel(e);
                            ed.plugins.wordpress._hideButtons();
                            ed.plugins.wordpress._showButtons(target, 'wp_gallerybtns');
                        }
                    });
                }
            });

            ed.onMouseDown.add(function(ed, e) {
                if ( e.target.nodeName == 'IMG' && ed.dom.hasClass(e.target, 'wp-gallery') ) {
                    ed.plugins.wordpress._hideButtons();
                    ed.plugins.wordpress._showButtons(e.target, 'wp_gallerybtns');
                }
            });

            ed.onBeforeSetContent.add(function(ed, o) {
                o.content = t._do_gallery(o.content);
            });

            ed.onPostProcess.add(function(ed, o) {
                if (o.get)
                    o.content = t._get_gallery(o.content);
            });
        },

        _do_gallery : function(co) {
            return co.replace(/\[gallery([^\]]*)\]/g, function(a,b){
                return '<img src="'+tinymce.baseURL+'/plugins/wpgallery/img/t.gif" class="wp-gallery mceItem" title="gallery'+tinymce.DOM.encode(b)+'" />';
            });
        },

        _get_gallery : function(co) {

            function getAttr(s, n) {
                n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
                return n ? tinymce.DOM.decode(n[1]) : '';
            };

            return co.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g, function(a,im) {
                var cls = getAttr(im, 'class');

                if ( cls.indexOf('wp-gallery') != -1 )
                    return '<p>['+tinymce.trim(getAttr(im, 'title'))+']</p>';

                return a;
            });
        },

        _createButtons : function() {
            var t = this, ed = tinymce.activeEditor, DOM = tinymce.DOM, editButton, dellButton, isRetina;

            if ( DOM.get('wp_gallerybtns') )
                return;

            isRetina = ( window.devicePixelRatio && window.devicePixelRatio > 1 ) || // WebKit, Opera
                ( window.matchMedia && window.matchMedia('(min-resolution:130dpi)').matches ); // Firefox, IE10, Opera

            DOM.add(document.body, 'div', {
                id : 'wp_gallerybtns',
                style : 'display:none;'
            });

            editButton = DOM.add('wp_gallerybtns', 'img', {
                src : isRetina ? t.url+'/img/edit-2x.png' : t.url+'/img/edit.png',
                id : 'wp_editgallery',
                width : '24',
                height : '24',
                title : ed.getLang('wordpress.editgallery')
            });

            tinymce.dom.Event.add(editButton, 'mousedown', function(e) {
                var ed = tinymce.activeEditor;
                ed.wpGalleryBookmark = ed.selection.getBookmark('simple');
                ed.execCommand("WP_Gallery");
                ed.plugins.wordpress._hideButtons();
            });

            dellButton = DOM.add('wp_gallerybtns', 'img', {
                src : isRetina ? t.url+'/img/delete-2x.png' : t.url+'/img/delete.png',
                id : 'wp_delgallery',
                width : '24',
                height : '24',
                title : ed.getLang('wordpress.delgallery')
            });

            tinymce.dom.Event.add(dellButton, 'mousedown', function(e) {
                var ed = tinymce.activeEditor, el = ed.selection.getNode();

                if ( el.nodeName == 'IMG' && ed.dom.hasClass(el, 'wp-gallery') ) {
                    ed.dom.remove(el);

                    ed.execCommand('mceRepaint');
                    ed.dom.events.cancel(e);
                }

                ed.plugins.wordpress._hideButtons();
            });
        },

        getInfo : function() {
            return {
                longname : 'Gallery Settings',
                author : 'WordPress',
                authorurl : 'http://wordpress.org',
                infourl : '',
                version : "1.0"
            };
        }
    });

    tinymce.PluginManager.add('wpgallery', tinymce.plugins.wpGallery);
})();
