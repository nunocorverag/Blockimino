var indiceLinks = document.querySelectorAll(".indice a");

indiceLinks.forEach(function (link) {
    link.addEventListener("click", function (event) {
        event.preventDefault();

        var targetId = this.getAttribute("href");

        if (targetId.includes("#")) {
            targetId = targetId.split("#")[1];
        }

        var targetPosition = document.getElementById(targetId).offsetTop;

        // Resta 100 del valor de targetPosition
        targetPosition -= 100;

        window.scrollTo({
            top: targetPosition,
            behavior: "smooth"
        });
    });
});



