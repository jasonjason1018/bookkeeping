(function($){ 

    //漢堡選單
    $(document).ready(function(){
        $(".menu-button a").click(function(){
            $(".overlay").fadeToggle(200);
        });
    });
    $('.overlay').on('click', function(){
        $(".overlay").fadeToggle(200);
        open = false;
    });
    
    //fancybox 套用
    $('.fancybox').fancybox({
        padding:0,
          helpers: {
            overlay: {
              locked: false
            }
          }
    });
    $('.btn-close').click(function(){
        $(this).fancybox.close();
    });    
    

})(jQuery);