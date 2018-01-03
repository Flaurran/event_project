document.addEventListener("DOMContentLoaded", function(event) {
    document.querySelector("#menu-dropdown").onclick = function() {
        if(document.querySelector(".header__connexion").classList.contains("header__activated")) {
            document.querySelector(".header__connexion").classList.remove("header__activated");
        } else {
            document.querySelector(".header__connexion").classList.add("header__activated");
        }
    };
});