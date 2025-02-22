<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- <link rel="stylesheet" href="../assets/css/navbar.css"> -->
</head>
<div class="hero-anime">
    <div class="navigation-wrap bg-light start-header start-style">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="navbar navbar-expand-md navbar-light">

                        <a class="navbar-brand" href="https://front.codes/" target="_blank"><img src="https://assets.codepen.io/1462889/fcy.png" alt=""></a>

                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ml-auto py-4 py-md-0">
                                <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 active">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Home</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                    </div>
                                </li>
                                <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                    <a class="nav-link" href="#">Portfolio</a>
                                </li>
                                <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                    <a class="nav-link" href="#">Agency</a>
                                </li>
                                <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Services</a>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                    </div>
                                </li>
                                <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                    <a class="nav-link" href="#">Journal</a>
                                </li>
                                <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                    <a class="nav-link" href="#">Contact</a>
                                </li>
                            </ul>
                        </div>

                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Link to page
================================================== -->
    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <script>
        /* Please ❤ this if you like it! */

        (function($) {
            "use strict";

            $(function() {
                var header = $(".start-style");
                $(window).scroll(function() {
                    var scroll = $(window).scrollTop();

                    if (scroll >= 10) {
                        header.removeClass('start-style').addClass("scroll-on");
                    } else {
                        header.removeClass("scroll-on").addClass('start-style');
                    }
                });
            });

            //Animation

            $(document).ready(function() {
                $('body.hero-anime').removeClass('hero-anime');
            });

            //Menu On Hover

            $('body').on('mouseenter mouseleave', '.nav-item', function(e) {
                if ($(window).width() > 750) {
                    var _d = $(e.target).closest('.nav-item');
                    _d.addClass('show');
                    setTimeout(function() {
                        _d[_d.is(':hover') ? 'addClass' : 'removeClass']('show');
                    }, 1);
                }
            });

            //Switch light/dark

            $("#switch").on('click', function() {
                if ($("body").hasClass("dark")) {
                    $("body").removeClass("dark");
                    $("#switch").removeClass("switched");
                } else {
                    $("body").addClass("dark");
                    $("#switch").addClass("switched");
                }
            });

        })(jQuery);
    </script>
</div>