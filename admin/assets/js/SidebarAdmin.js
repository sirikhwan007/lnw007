$(document).ready(function () {
  // Toggle Sub Menu
  $(".sb-ul li").click(function () {
    $(".sb-sub-ul").slideUp();

    if ($(this).children(".sb-sub-ul").is(":visible")) {
      $(this).children(".sb-sub-ul").slideUp();
      $(".chev-pos").removeClass("chev-rotate");
    } else {
      $(this).children(".sb-sub-ul").slideDown();
      $(this).find(".chev-pos").toggleClass("chev-rotate");
    }
  });

  // Active Menu
  $(".sb-ul li a").click(function () {
    $(".sb-ul li a").removeClass("sb-ul-active");
    $(this).addClass("sb-ul-active");
  });

  // Hamburger (Responsive)
  $(".btn-hamburger").click(function () {
    $(".sidebar").toggleClass("sidebar-active");
  });

  // Show sidebar on wider screen
  $(window).resize(function () {
    var width = $(window).width();
    if (width > 500) {
      $(".sidebar").show();
    }
  });
});
