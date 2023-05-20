$(document).ready(function() {
    $(".dropdown > span").click(function() {
      $(this).parent().toggleClass("show");
    });
  });