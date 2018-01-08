$(document).ready(function() {
    /* Confirm deletion */
    $('.modalclickevent').on('click', function(e) {
        var message = $(e.currentTarget).data('message');
        if (message === undefined) {
            console.log('modalclickevent is set on element but no data-message found');
            e.preventDefault();
        } else {
            if (confirm(message) !== true) {
                e.preventDefault();
            }
        }
    });
    /* Navigation */
    $("#menu-dropdown").click(function() {
        $(".header__connexion").toggleClass('header__activated');
    });

    $('.dash_project_filter').on('click', function(e) {
        var hiddenClassName = 'dashbord__project--hidden';
        var className = $(e.currentTarget).data('target');
        if (className === '') {
            $(".a_project").removeClass(hiddenClassName);
        } else {
            showProject('.' + className, hiddenClassName);
        }
    });
    var showProject = function(className, hiddenClassName) {
        if (hiddenClassName === undefined) {
            hiddenClassName = 'dashbord__project--hidden';
        }
        var my_project = $(".a_project:not(" + className + ")");
        if(my_project.length > 0) {
            my_project.addClass(hiddenClassName);
        }
        $(className).removeClass(hiddenClassName);
    };

    /* Project */
    $(document).on('input', 'textarea', function () {
        $(this).outerHeight(38).outerHeight(this.scrollHeight);
    });

    $(".message__close").click(function() {
        $(".message__wrapper").toggleClass('message__closed');
    });

});

