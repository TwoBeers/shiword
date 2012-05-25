jQuery(document).ready(function($){
    $('#widget-list').append('<p class="clear description" style="border-bottom: 1px solid #ccc;">Shiword widgets</p>');
    bz_widgets = $('#widget-list .widget[id*=_shi-]');
    $('#widget-list').append(bz_widgets);
});