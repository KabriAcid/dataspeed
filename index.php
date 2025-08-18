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
    <link rel="shortcut icon" href="public/favicon.png" type="image/x-icon">

    <!-- SEO Meta Tags -->
    <meta name="description" content="Dataspeed VTU platform: Buy data, airtime, pay bills, and subscribe to TV instantly. Nigeria's trusted VTU site for secure wallet funding, transfers, and instant settlements. VTU for MTN, Airtel, Glo, 9mobile, DStv, GOtv, Startimes, and more.">
    <meta name="keywords" content="VTU, VTU Nigeria, VTU platform, Buy Data, Buy Airtime, VTU Bill Payment, VTU TV Subscription, VTU Wallet, VTU Transfer, VTU Secure Payments, VTU MTN, VTU Airtel, VTU Glo, VTU 9mobile, VTU DStv, VTU GOtv, VTU Startimes, VTU Electricity, VTU Betting, Dataspeed VTU, VTU API, VTU Reseller, VTU Business, VTU Portal, VTU Site, VTU Online, VTU Fast, VTU Reliable, VTU Secure">
    <meta name="author" content="Dataspeed VTU Team">
    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="7 days">
    <meta name="language" content="en">
    <meta name="theme-color" content="#94241e">

    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="Dataspeed VTU - Buy Data, Airtime, Pay Bills & TV Subscription">
    <meta property="og:description" content="VTU platform for instant data, airtime, bill payments, and TV subscriptions. Secure wallet funding and transfers. Nigeria's trusted VTU site.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://dataspeed.com.ng/">
    <meta property="og:image" content="https://dataspeed.com.ng/public/favicon.png">
    <meta property="og:site_name" content="Dataspeed VTU">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Dataspeed VTU - Buy Data, Airtime, Pay Bills & TV Subscription">
    <meta name="twitter:description" content="VTU platform for instant data, airtime, bill payments, and TV subscriptions. Secure wallet funding and transfers. Nigeria's trusted VTU site.">
    <meta name="twitter:image" content="https://dataspeed.com.ng/public/favicon.png">

    <!-- Canonical URL -->
    <link rel="canonical" href="https://dataspeed.com.ng/">

    <!-- Bootstrap & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet">

    <!-- Lottie Animations -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <!-- <script src="public/assets/js/lottie-player.js"></script> -->

    <!-- Toasted JS for notifications -->
    <link rel="stylesheet" href="public/assets/css/toasted.css" />
    <script src="public/assets/js/toasted.js"></script>

    <link rel="stylesheet" href="public/assets/css/soft-design-system-pro.min3f71.css">
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>

<body>
    <nav class="navbar fixed-top">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div>
                <a href="index.php" class="d-block fw-bold"><img src="public/favicon.png" alt="" class="favicon"></a>
            </div>
            <div class="d-flex align-items-center">
                <a href="public/pages/login.php" class="secondary-btn m-0 me-2">LOGIN</a>
                <a href="public/pages/register.php" class="btn primary-btn m-0">CREATE ACCOUNT</a>
            </div>
        </div>
    </nav>

    <main class="pt-5" style="position: relative; overflow: hidden;">
        <!--  -->
        <section class="hero-wrapper">
            <div class="container-fluid">
                <h1 class="fw-bolder mb-3" style="font-size:2.2rem;">
                    Seamless <span class="primary">Payments</span>,<br>
                    <span class="primary">Affordable Data</span> & Secure Solutions.
                </h1>
                <p class="lead mb-4" style="max-width: 500px;">
                    Fast, reliable, and secure payments. Instant settlements, wallet funding, and bill payments in real time.
                </p>
                <div class="">
                    <a href="public/pages/register.php" class="btn primary-btn m-0">Get Started</a>
                </div>
            </div>
        </section>
        <!--  -->
        <section id="sectionII">
            <div id="join-people">
                <h3>Join over <span class="primary">500</span> <br> people who use <span
                        class="primary">DataSpeed.</span></h3>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4 col-12 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="public/assets/img/icons/data.png" alt="icon" class="card-icon">
                        </div>
                        <h6 class="primary text-center text-capitalize">Data purchase</h6>
                        <p> Empower your connectivity with the best data deals across all Nigerian networks.
                            Choose plans that suit your needs and stay online effortlessly!</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 col-12 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="public/assets/img/icons/airtime.png" alt="icon" class="card-icon">
                        </div>
                        <h6 class="primary text-center text-capitalize">Airtime top up</h6>
                        <p>Stay connected with affordable top-ups for MTN, Glo, 9mobile,
                            and Airtel. Enjoy competitive rates and exclusive savings on every recharge!</p>
                    </div>
                </div>

                <!-- Card 1 -->
                <div class="col-md-6 col-lg-4 col-12 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="https://img.icons8.com/ios-filled/100/000000/retro-tv.png" alt="TV Logo" class="card-icon">

                        </div>
                        <h6 class="primary text-center text-capitalize">Instant TV subscriptions </h6>
                        <p> Stay entertained with affordable subscriptions for DStv, GOtv, and StarTimes.
                            Enjoy competitive rates and exclusive savings on every subscription renewal!</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-6 col-lg-4 col-12 mb-lg-0 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="public/assets/img/icons/nairapin.png" alt="icon" class="card-icon">
                        </div>
                        <h6 class="primary text-center text-capitalize">Transfer Money</h6>
                        <p>Send or receive money instantly from your account balance to any friend on Dataspeed.
                            Enjoy unbeatable rates and seamless, real-time transfers!</p>
                    </div>
                </div>

                <!-- Card 1 -->
                <div class="col-md-6 col-lg-4 col-12 mb-lg-0 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="public/assets/img/icons/bills.png" alt="icon" class="card-icon">
                        </div>
                        <h6 class="primary text-center text-capitalize">Bill payments</h6>
                        <p> Simplify your finances with our enhanced airtime-to-cash solution.
                            Pay bills quickly and reliably with just a few taps!
                        </p>
                    </div>
                </div>

                <!-- Card 2 -->
            </div>
        </section>
        <!--  -->
        <section id="sectionIII">
            <div class="my-3">
                <h2 class="primary text-center">Transactions Made Easy!</h2>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-6 mb-lg-0 mb-3">
                    <div class="card transaction-card p-0">
                        <div class="card-body">
                            <i class="icon"></i>
                            <h6 class="text-center primary">Stay connected with MTN & Airtel</h6>
                            <p>Recharge airtime and data for MTN and Airtel anytime,
                                ensuring seamless communication with the DataSpeed app.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 mb-lg-0 mb-3">
                    <div class="card transaction-card p-0">
                        <div class="card-body">
                            <i class="icon"></i>
                            <h6 class="text-center primary">Enjoy TV with DStv, GOtv & Startimes</h6>
                            <p>Pay for DStv, GOtv, and Startimes subscriptions easily and access your favorite
                                channels via the DataSpeed app.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 mb-lg-0 mb-3">
                    <div class="card transaction-card p-0">
                        <div class="card-body">
                            <i class="icon"></i>
                            <h6 class="text-center primary">Power Your Home</h6>
                            <p>Settle Internet and Electricity bills quickly and
                                conveniently using the DataSpeed app.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 mb-lg-0 mb-3">
                    <div class="card transaction-card p-0">
                        <div class="card-body">
                            <i class="icon"></i>
                            <h6 class="text-center primary"> Support Local Merchants</h6>
                            <p>Make hassle-free payments to local merchants
                                and vendors with the DataSpeed app.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--  -->
        <section id="sectionIV">
            <div class="text-center">
                <h4>Newsletter</h4>
                <p class="mb-4">
                    Stay ahead with DataSpeed!<br>
                    Get exclusive updates, tips, and offers delivered straight to your inbox.
                </p>
                <form action="" method="post" class="">
                    <input type="email" name="email" class="input" placeholder="Your email address">
                    <button type="submit" name="submit" class="btn primary-btn mt-3" id="news">SUBSCRIBE</button>
                </form>
            </div>
        </section>
    </main>

    <footer style="background-color: #111; color: #fff; font-family: Arial, sans-serif; padding: 60px 20px;">
        <div style="max-width: 1200px; margin: auto;">
            <!-- Top Area -->
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #333; padding-bottom: 40px;">

                <!-- Logo & Info -->
                <div style="flex: 1 1 180px; margin: 10px 0;">
                    <h2 style="font-size: 25px; font-weight: bold; margin-bottom: 10px;">Dataspeed</h2>
                    <p style="font-size: 14px; color: #ccc;">Your trusted tech insights and creative code solutions.</p>
                </div>
                <!-- Links -->
                <div style="flex: 1 1 180px; margin: 10px 0;">
                    <h3 style="font-size: 20px; margin-bottom: 10px;">Links</h3>
                    <p><a href="index.php" style="color: #ccc; text-decoration: none;">Home</a></p>
                    <p><a href="about.php" style="color: #ccc; text-decoration: none;">About</a></p>
                </div>

                <!-- Contact / Policy -->
                <div style="flex: 1 1 180px; margin: 10px 0;">
                    <h3 style="font-size: 20px; margin-bottom: 10px;">Contact</h3>
                    <p><a href="privacy-policy.php" style="color: #ccc; text-decoration: none;">Privacy Policy</a></p>
                    <p><a href="contact.php" style="color: #ccc; text-decoration: none;">Contact Us</a></p>
                </div>

                <!-- Social Links -->
                <div style="flex: 1 1 200px; margin: 10px 0;">
                    <h3 style="font-size: 20px; margin-bottom: 10px;">Follow Me</h3>
                    <div style="display: flex; gap: 15px;">
                        <!-- YouTube  -->
                        <a href="https://www.youtube.com/" target="_blank">
                            <img width="30" src="https://img.icons8.com/color/48/youtube-play.png" alt="youtube-alt" /></a>

                        <!-- Instagram -->
                        <a href="https://www.instagram.com//" target="_blank">
                            <img width="30" src="https://img.icons8.com/fluency/48/instagram-new.png" alt="Instagram Alt" />
                        </a>
                        <!-- Twitter  -->
                        <a href="https://x.com/" target="_blank">
                            <img width="24" src="https://img.icons8.com/ios-filled/50/ffffff/x.png" alt="X Logo White" />
                        </a>

                        <!-- Facebook -->
                        <a href="https://facebook.com/" target="_blank">
                            <img width="30" src="https://img.icons8.com/color/48/facebook--v1.png" alt="Facebook" />
                        </a>

                        <!-- Telegram -->
                        <a href="https://t.me/" target="_blank">
                            <img width="30" src="https://img.icons8.com/color/48/telegram-app--v1.png" alt="Telegram Alt" />
                        </a>

                        <!-- Email -->
                        <a href="mailto:dataspeedcontact@gmail.com">
                            <img width="30" src="https://img.icons8.com/color/48/filled-message--v1.png" alt="Email" />
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Info -->
        <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-top: 40px; font-size: 14px; color: #666;">
            <div>
                Designed & Created by Dreamerscodes
            </div>
            <div>
                &copy; 2025 dataspeed
            </div>
        </div>
        </div>
    </footer>
</body>

</html>