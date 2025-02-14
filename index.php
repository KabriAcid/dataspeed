<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dataspeed - Home</title>
    <link rel="shortcut icon" href="public/logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">
    <link rel="stylesheet" href="public/assets/css/style.css">
</head>

<body>
    <?php require __DIR__ . "/public/partials/navbar.php"; ?>
    <main>
        <section class="mt-5"></section>
        <section class="jumbotoron" id="sectionI">
            <h1 id="main-header">The cheapest and most <br>affordable <span class="primary">airtime</span> data <br>plug.</h1>
            <p id="header-para">Convert your airtime to cash, make & receive payments,<br> instant airtime and data
                delivery, make bill payments <br> with DataSpeed. The all-in-one payments app.</p>
            <a href="" class="btn btn-primary">Get Started</a>
        </section>
        <!--  -->
        <section id="sectionII">
            <div id="join-people">
                <h3>Join over <span class="strong-color">500</span><br>people who use <span class="strong-color">DataSpeed.</span></h3>
            </div>
            <div class="row">
                <!-- Card 1 -->
                <div class="col-md-6 col-lg-4 col-12 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <img src="public/assets/img/icons/airtime-to-cash.png" alt="icon" class="card-icon">
                        <h6 class="strong-color">Instant airtime to cash</h6>
                        <p>Convert your airtime to cash in seconds using the new and improved Airtime to cash service.</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-6 col-lg-4 col-12 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <img src="public/assets/img/icons/airtime.png" alt="icon" class="card-icon">
                        <h6 class="strong-color">Airtime top up</h6>
                        <p>Purchase airtime for MTN, Glo, 9mobile & Airtel at the best possible / discounted rates.</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-6 col-lg-4 col-12 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <img src="public/assets/img/icons/data.png" alt="icon" class="card-icon">
                        <h6 class="strong-color">Data purchase</h6>
                        <p>We offer the best rates on data purchase for all available networks in Nigeria.</p>
                    </div>
                </div>
                <!-- Card 1 -->
                <div class="col-md-6 col-lg-4 col-12 mb-lg-0 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <img src="public/assets/img/icons/bills.png" alt="icon" class="card-icon">
                        <h6 class="strong-color">Bill payments</h6>
                        <p>Convert your airtime to cash in seconds using the new and improved Airtime to cash service.</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-6 col-lg-4 col-12 mb-lg-0 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <img src="public/assets/img/icons/nairapin.png" alt="icon" class="card-icon">
                        <h6 class="strong-color">Airtime payments</h6>
                        <p>Purchase airtime for MTN, Glo, 9mobile & Airtel at the best possible / discounted rates.</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-6 col-lg-4 col-12 mb-lg-0 mb-3">
                    <div class="card card-container text-md-start text-center">
                        <img src="public/assets/img/icons/send-receive.png" alt="icon" class="card-icon">
                        <h6 class="strong-color">Send & Receive money</h6>
                        <p>We offer the best rates on data purchase for all available networks in Nigeria.</p>
                    </div>
                </div>
            </div>
        </section>
        <!--  -->
        <section id="sectionIII">
            <div>
                <h2 class="strong-color text-center">Transactions Made Easy!</h2>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <i class="icon">xxx</i>
                            <h6>Seamless Transcation</h6>
                            <p>Pay for your Tv/Cable (Dstv, Gotv, etc.), Internet, Electricity & other merchants using the DataSpeed app.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <i class="icon">xxx</i>
                            <h6>Seamless Transcation</h6>
                            <p>Pay for your Tv/Cable (Dstv, Gotv, etc.), Internet, Electricity & other merchants using the DataSpeed app.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <i class="icon">xxx</i>
                            <h6>Seamless Transcation</h6>
                            <p>Pay for your Tv/Cable (Dstv, Gotv, etc.), Internet, Electricity & other merchants using the DataSpeed app.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <i class="icon">xxx</i>
                            <h6>Seamless Transcation</h6>
                            <p>Pay for your Tv/Cable (Dstv, Gotv, etc.), Internet, Electricity & other merchants using the DataSpeed app.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>

</html>