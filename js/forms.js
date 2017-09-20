(function ($, hbspt, window, Drupal, drupalSettings) {

Drupal.behaviors.alloyAssembliesHsForms = {
  attach: function(context, settings) {
    $('[data-hs-form]').once('alloyAssembliesHsForms').each(function() {
      var $this = $(this),
        portal = $this.data('hs-portal-id'),
        formid = $this.data('hs-form-id')
        ;

      var id = $this.attr('id') || 'hsform' + Math.random().toString(36).substr(2, 10);
      $this.attr('id', id);
      hbspt.forms.create({
        css: '',
        portalId: portal,
        formId: formid,
        target: '#' + id
      });

    });
  }
}

})(jQuery, hbspt, window, Drupal, drupalSettings);
