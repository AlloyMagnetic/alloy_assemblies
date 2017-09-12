(function ($, hbspt, window, Drupal, drupalSettings) {

Drupal.behaviors.alloyAssembliesHsForms = {
  attach: function(context, settings) {
    $('[data-hs-form-target]').once('alloyAssembliesHsForms').each(function() {
      var $this = $(this),
        portal = $this.data('hs-portal-id'),
        formid = $this.data('hs-form-id'),
        target = $this.data('hs-form-target')
        ;

      hbspt.forms.create({
        css: '',
        portalId: portal,
        formId: formid,
        target: '[data-hs-form-target="' + target + '"]'
      });

    });
  }
}
})(jQuery, hbspt, window, Drupal, drupalSettings);
