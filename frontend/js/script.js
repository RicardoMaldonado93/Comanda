$(document).ready(function(){

    var fixHeigth = function(){
        $(".navbar-nav").css('max-height', document.documentElement.clientHeight - 150);
    }

    fixHeigth();

    $(window).resize( function(){
        fixHeigth();
    });

    $('.navbar .navbar-toggler').on("click", function(){
        fixHeigth();
    });

    $(".navbar-toggler, .overlay").on("click", function(){
        $(".mobileMenu, .overlay").toggleClass("open");
    });

  })

  function saludar(){

  }

  