jQuery(document).ready(function(){
    jQuery('li.treeTitle').click(function(){
        var ptrContent = jQuery(this).parent().children('ul.treeContent').first();
        console.log(ptrContent);
        var ptrIcon = jQuery(this).children('i').first();
        if(jQuery(ptrContent).is(':visible')) {
            jQuery(ptrContent).slideUp('fast');
            if(jQuery(ptrIcon).hasClass('icon-folder-open')) {
                jQuery(ptrIcon).attr('class', 'icon-folder-close');
            } else {
                jQuery(ptrIcon).attr('class', 'icon-plus');
            }
        } else {
            jQuery(ptrContent).slideDown('fast');
            if(jQuery(ptrIcon).hasClass('icon-folder-close')) {
                jQuery(ptrIcon).attr('class', 'icon-folder-open');
            } else {
                jQuery(ptrIcon).attr('class', 'icon-minus');
            }
        }
    });
});