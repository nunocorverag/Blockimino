//Js paara el boton de regresar al indice

var scrollToTopBtn = document.querySelector("#scroll-to-top-btn");

window.addEventListener("scroll", function () {
    var scrollHeight = window.pageYOffset;
    if (scrollHeight > 200) {
        scrollToTopBtn.style.display = "block";
    } else {
        scrollToTopBtn.style.display = "none";
    }
});

scrollToTopBtn.addEventListener("click", function () {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
});