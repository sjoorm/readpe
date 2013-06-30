jQuery(document).ready(function(){
    jQuery('button.treeTitle').click(function(){
        var ptrContent = jQuery(this).parent().children('div.treeContent').first();
        var ptrIcon = jQuery(this).children('i').first();
        if(jQuery(ptrContent).is(':visible')) {
            jQuery(ptrContent).slideUp();
            if(jQuery(ptrIcon).hasClass('icon-folder-open')) {
                jQuery(ptrIcon).attr('class', 'icon-folder-close');
            } else {
                jQuery(ptrIcon).attr('class', 'icon-plus');
            }
        } else {
            jQuery(ptrContent).slideDown();
            if(jQuery(ptrIcon).hasClass('icon-folder-close')) {
                jQuery(ptrIcon).attr('class', 'icon-folder-open');
            } else {
                jQuery(ptrIcon).attr('class', 'icon-minus');
            }
        }
    });
});