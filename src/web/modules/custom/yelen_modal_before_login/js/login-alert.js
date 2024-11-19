(function ($, Drupal, drupalSettings, once) {
    'use strict';

    Drupal.behaviors.yelenModalBeforeLogin = {
        attach: function (context, settings) {
            console.log('Modal JS loaded');
            const modalsize = {
              'fullscreen':'fullscreen_ui',
              'large':'large_ui',
              'medium':'medium_ui',
              'small':'small_ui'
            }

            once('yelenModalBeforeLogin', 'body', context).forEach(function () {
                if (settings.yelenModalBeforeLogin && settings.yelenModalBeforeLogin.mediaUrl) {
                    console.log('Media URL found:', settings.yelenModalBeforeLogin.mediaUrl);

                    var modalContent = $('<div>');

                    // Vérifier le type de média pour afficher l'image ou la vidéo
                    if (settings.yelenModalBeforeLogin.mediaType === 'image') {
                        modalContent.append(
                            $('<div>', { class: 'modal-media' }).append(
                                $('<img>', {
                                    src: settings.yelenModalBeforeLogin.mediaUrl,
                                    alt: 'Image popup'
                                })
                            )
                        );
                    } else if (settings.yelenModalBeforeLogin.mediaType === 'video') {
                        modalContent.append(
                            $('<div>', { class: 'modal-media' }).append(
                                $('<video>', {
                                    src: settings.yelenModalBeforeLogin.mediaUrl,
                                    controls: true,
                                    autoplay: true
                                })
                            )
                        );
                    }

                    // Créer et afficher la modale avec un délai
                    setTimeout(function() {
                        Drupal.dialog(modalContent, {
                            title: settings.yelenModalBeforeLogin.titleModal.toUpperCase(),
                            width: 'auto',
                            buttons: [{
                                text: settings.yelenModalBeforeLogin.textbuton.toUpperCase(),
                                click: function() {
                                    $(this).dialog('close');
                                }
                            }],
                            closeOnEscape: true,
                            modal: true,
                            autoOpen: true,
                            /*classes: [
                              "ui-dialog", "yelen-modal-dialog",'papa',
                              modalsize[settings.yelenModalBeforeLogin.size]
                            ]*/
                          classes:{
                            "ui-dialog-titlebar":'bg-orange',
                            "ui-dialog-buttonset":"popup_btn",
                            "ui-dialog-content":"no_padding_container"
                          }
                        }).showModal();
                    }, 500);
                } else {
                    console.log('No media found in settings');
                }
            });
        }
    };
})(jQuery, Drupal, drupalSettings, once);
