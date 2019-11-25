var openNavBarExpand = false;
$('.navbar-toggler').click(function(){
    openNavBarExpand = (openNavBarExpand !== true);
    $('#navBar').css('background', 'rgba(241, 248, 233, .' + (openNavBarExpand ? 99 : 8) + ')');
});