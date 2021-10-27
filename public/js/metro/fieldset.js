$(function(){
    $('fieldset .fieldsetcontent').hide();
    $('legend').click(function(){
        $(this).parent().find('.fieldsetcontent').slideToggle("slow");
    });
});