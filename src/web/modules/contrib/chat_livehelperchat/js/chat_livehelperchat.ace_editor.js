/**
 * @file
 * Live Helper CHat applies Ace Editor to simplify work.
 */

(function ($, Drupal, once) {
  'use strict';
  Drupal.behaviors.livehelperchatAceEditor = {
    attach: function (context, settings) {
      if (typeof ace == 'undefined' || typeof ace.edit != 'function') {
        return;
      }

      $('body').addClass('ace-editor-chat');
      $(once('ace-editor-chat', '.ace-editor')).each(function () {
        var textarea = $(this).parent().siblings().find('textarea');
        var mode = $(textarea).attr('data-ace-mode');

        if (mode) {
          $(textarea).css('position', 'absolute')
            .css('width', "1px")
            .css('height', "1px")
            .css('opacity', 0)
            .attr('tabindex', -1);

          ace.config.set('basePath', '//cdnjs.cloudflare.com/ajax/libs/ace/1.8.1');
          ace.config.set('modePath', '//cdnjs.cloudflare.com/ajax/libs/ace/1.8.1');
          ace.config.set('themePath', '//cdnjs.cloudflare.com/ajax/libs/ace/1.8.1');

          var editor = ace.edit(this);
          editor.getSession().setMode('ace/mode/' + mode);
          editor.getSession().setTabSize(2);
          editor.getSession().setUseSoftTabs();

          editor.getSession().on('change', function () {
            textarea.val(editor.getSession().getValue());
          });

          editor.setValue(textarea.val());

          textarea.on('focus', function () {
            editor.focus()
          })
        }
      });
    }
  };
})(jQuery, Drupal, once);
