jQuery("document").ready(function () {
    elementor.hooks.addAction( 'panel/open_editor/widget/wds-elementor', function( panel, model, view ) {
        var wds_el = jQuery('select[data-setting="sliders"]',window.parent.document);
        wds_add_edit_link(wds_el);
    });
    jQuery('body').on('change', 'select[data-setting="sliders"]',window.parent.document, function (){
        wds_add_edit_link(jQuery(this));
    });
});

function wds_add_edit_link(el) {
        var wds_el = el;
        var wds_id = wds_el.val();
        var a_link = wds_el.closest('.elementor-control-content').find('.elementor-control-field-description').find('a');
        var new_link = 'admin.php?page=sliders_wds';
        if(wds_id !== '0'){
            new_link = 'admin.php?page=sliders_wds&task=edit&current_id='+wds_el.val();
        }
        a_link.attr( 'href', new_link);
}