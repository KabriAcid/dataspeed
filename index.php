<?php
session_start();
function set_title($title = null)
{
    $default = "DataSpeed";
    return htmlspecialchars($title ?: $default);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title is set dynamically -->
    <title><?= set_title($title ?? null) ?></title>
    <link rel="shortcut icon" href="/dataspeed/public/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">

    <!-- Font Awesome for icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet">

    <!-- Lottie Animations -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <!-- <script src="public/assets/js/lottie-player.js"></script> -->

    <!-- Toasted JS for notifications -->
    <link rel="stylesheet" href="public/assets/css/toasted.css" />
    <script src="public/assets/js/toasted.js"></script>

    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>


    <link rel="stylesheet" href="public/assets/css/soft-design-system-pro.min3f71.css">
    <link rel="stylesheet" href="public/assets/css/style.css">

</head>

<body>
    <nav class="bg-light navbar fixed-top">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div>
                <a href="index.php" class="d-block fw-bold"><img src="/dataspeed/public/favicon.png" alt="" class="favicon"></a>
            </div>
            <div class="d-flex align-items-center">
                <a href="public/pages/login.php" class="secondary-btn m-0 me-2">LOGIN</a>
                <a href="public/pages/register.php" class="btn primary-btn m-0">CREATE ACCOUNT</a>
            </div>
        </div>
    </nav>

    <main class="pt-5">
        <section class="jumbotoron" id="sectionI">
            <h1>The cheapest and most affordable <span class="primary">airtime</span> data plug.</h1>
            <p id="lead">Convert your airtime to cash, make & receive payments, instant airtime and data
                delivery, make bill payments with <span class="primary">DataSpeed</span>. The all-in-one payments app.
            </p>
            <a href="" class="btn primary-btn">Get Started</a>
        </section>
        <!--  -->
        <section id="sectionII">
            <div id="join-people">
                <h3>Join over <span class="primary">500</span> <br> people who use <span
                        class="primary">DataSpeed.</span></h3>
            </div>
            <div class="row">
                <!-- Card 1 -->
                <div class="col-md-6 col-lg-4 col-12 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="public/assets/img/icons/airtime-to-cash.png" alt="icon" class="card-icon">
                        </div>
                        <h6 class="primary">Instant airtime to cash</h6>
                        <p>Convert your airtime to cash in seconds using the new and improved Airtime to cash service.
                        </p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-6 col-lg-4 col-12 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="public/assets/img/icons/airtime.png" alt="icon" class="card-icon">
                        </div>
                        <h6 class="primary">Airtime top up</h6>
                        <p>Purchase airtime for MTN, Glo, 9mobile & Airtel at the best possible / discounted rates.</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-6 col-lg-4 col-12 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="public/assets/img/icons/data.png" alt="icon" class="card-icon">
                        </div>
                        <h6 class="primary">Data purchase</h6>
                        <p>We offer the best rates on data purchase for all available networks in Nigeria.</p>
                    </div>
                </div>
                <!-- Card 1 -->
                <div class="col-md-6 col-lg-4 col-12 mb-lg-0 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="public/assets/img/icons/bills.png" alt="icon" class="card-icon">
                        </div>
                        <h6 class="primary">Bill payments</h6>
                        <p>Convert your airtime to cash in seconds using the new and improved Airtime to cash service.
                        </p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-6 col-lg-4 col-12 mb-lg-0 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="public/assets/img/icons/nairapin.png" alt="icon" class="card-icon">
                        </div>
                        <h6 class="primary">Airtime payments</h6>
                        <p>Purchase airtime for MTN, Glo, 9mobile & Airtel at the best possible / discounted rates.</p>
                    </div>
                </div>
            </div>
        </section>
        <!--  -->
        <section id="sectionIII">
            <div>
                <h2 class="primary text-center">Transactions Made Easy!</h2>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <i class="icon">xxx</i>
                            <h6>Seamless Transcation</h6>
                            <p>Pay for your Tv/Cable (Dstv, Gotv, etc.), Internet, Electricity & other merchants using
                                the DataSpeed app.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <i class="icon">xxx</i>
                            <h6>Seamless Transcation</h6>
                            <p>Pay for your Tv/Cable (Dstv, Gotv, etc.), Internet, Electricity & other merchants using
                                the DataSpeed app.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <i class="icon">xxx</i>
                            <h6>Seamless Transcation</h6>
                            <p>Pay for your Tv/Cable (Dstv, Gotv, etc.), Internet, Electricity & other merchants using
                                the DataSpeed app.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <i class="icon">xxx</i>
                            <h6>Seamless Transcation</h6>
                            <p>Pay for your Tv/Cable (Dstv, Gotv, etc.), Internet, Electricity & other merchants using
                                the DataSpeed app.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--  -->
        <section id="sectionIV">
            <div class="text-center">
                <h4>Newsletter</h4>
                <p class="mb-4">Lorem ipsum dolor sit amet consectetur adipisicing elit. At aut neque consectetur nihil
                    sint excepturi omnis adipisci possimus!</p>
                <form action="" method="post" class="d-flex" style="width: 50%;margin: auto;">
                    <input type="email" name="email" class="input" placeholder="Your email address">
                    <button type="submit" name="submit" class="btn primary-btn ms-3">SUBSCRIBE</button>
                </form>
            </div>
        </section>
    </main>
    <footer>
        <div style="height: 400px;"> </div>
        <div class="dataspeed-footer">
            <div class="dataspeed-footer-top">

            </div>
            <div class="dataspeed-footer-content">
                <div class="dataspeed-footer-section">
                    <h1 class="dataspeed-footer-heading">Dataspeed</h1>
                </div>
                <div class="dataspeed-footer-section">
                    <ul class="d-footer-ul">
                        <li class="d-footer-li-h"><b>Links</b></li>
                        <li class="d-footer-li">Home</li>
                        <li class="d-footer-li">About</li>
                        <li class="d-footer-li">Blog</li>
                        <li class="d-footer-li">Design</li>
                        <li class="d-footer-li">Documentation</li>
                    </ul>
                </div>
                <div class="dataspeed-footer-section">
                    <ul class="d-footer-ul">
                        <li class="d-footer-li-h"><b>Blog</b></li>
                        <li class="d-footer-li">UI / UX</li>
                        <li class="d-footer-li">CodePens</li>
                        <li class="d-footer-li">Codedamn</li>
                        <li class="d-footer-li">Figma</li>
                        <li class="d-footer-li">Oracle EBS</li>
                    </ul>
                </div>
                <div class="dataspeed-footer-section">
                    <ul class="d-footer-ul">
                        <li class="d-footer-li-h"><b>Privacy policy</b></li>
                        <li class="d-footer-li-h"><b>Contact Us</b></li>

                    </ul>
                </div>
                <div class="dataspeed-footer-section">
                    <div class="logo-head">
                        <b>Follow me on</b>
                    </div>
                    <div class="logo">
                        <a href="https://www.instagram.com/dark.soul.io/" target="_blank"><img width="30" height="30"
                                src="https://img.icons8.com/ios-glyphs/100/instagram-new.png" alt="instagram-new" /></a>
                        <a href="https://www.youtube.com/channel/UCooCOD2j8Q4Y9ysYZIFzI_w" target="_blank"><img
                                width="30" height="30" src="https://img.icons8.com/ios-filled/100/youtube-squared.png"
                                alt="youtube-squared" /></a>
                        <a href="https://dribbble.com/dark-soul" target="_blank"><img width="25" height="25"
                                src="https://img.icons8.com/offices/100/dribbble.png" alt="dribbble" /></a>
                    </div>
                </div>
            </div>
            <div class="dataspeed-footer-bottom">
                <div class="dataspeed-footer-bottom-sec">
                    <p>Icons by <a href="https://icons8.com/" target="_blank">Icons8</a></p>
                </div>
                <div class="dataspeed-footer-bottom-sec">
                    <p>Designed & Created by dataspeed</p>
                </div>
                <div class="dataspeed-footer-bottom-sec">
                    <p>&copy; 2024 dataspeed</p>
                </div>

            </div>

        </div>
    </footer>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Popper.js (for tooltips and popovers) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="public/assets/js/navbar.js"></script>
</body>

</html>