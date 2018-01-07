$(document).ready(function() {

    /* Navigation */
    $("#menu-dropdown").click(function() {
        $(".header__connexion").toggleClass('header__activated');
    });

    /* Dashbord */
    $("#dash_all").click(function() {
        $(".dashbord__project").removeClass("dashbord__project--hidden");
    });

    $("#dash_mine").click(function() {
        var my_project = $(".a_project:not(.my_project)");
        if(my_project.length > 0) {
            my_project.addClass("dashbord__project--hidden");
        }
    });

    $("#dash_going").click(function() {
        var project_going = $(".a_project:not(.project_going)");
        if(project_going.length > 0) {
            project_going.addClass("dashbord__project--hidden");
        }
    });

    /* Project */
    $(document).on('input', 'textarea', function () {
        $(this).outerHeight(38).outerHeight(this.scrollHeight);
    });

    $(".message__close").click(function() {
        $(".message__wrapper").toggleClass('message__closed');
    });

});

