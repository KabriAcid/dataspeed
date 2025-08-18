<?php
function set_title($title = null)
{
    $default = "404 | DataSpeed";
    return htmlspecialchars($title ?: $default);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= set_title("404 | DataSpeed") ?></title>
    <link rel="shortcut icon" href="public/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">
    <link rel="stylesheet" href="public/assets/css/soft-design-system-pro.min3f71.css">
    <link rel="stylesheet" href="public/assets/css/style.css">
    <style>
        body {
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .error-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 16px 0 16px;
            text-align: center;
        }

        .error-title {
            font-size: clamp(2.2rem, 6vw, 3.5rem);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .error-desc {
            font-size: clamp(1.1rem, 2vw, 1.3rem);
            color: #333;
            margin-bottom: 2rem;
        }

        .error-actions .btn {
            min-width: 140px;
            margin: 0 8px 8px 0;
        }

        @media (max-width: 576px) {
            .error-title {
                font-size: 2.1rem;
            }

            .error-desc {
                font-size: 1rem;
            }
        }
    </style>
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
    <main class="error-container">
        <div class="error-title primary">404 - Page Not Found</div>
        <div class="error-desc">
            Sorry, the page you’re looking for doesn’t exist.<br>
            It may have been moved, deleted, or you may have mistyped the address.
        </div>
        <div class="error-actions">
            <a href="index.php" class="btn primary-btn">Go Home</a>
            <a href="pages/contact.php" class="btn secondary-btn">Contact Support</a>
        </div>
    </main>
    <footer style="background-color: #111; color: #fff; font-family: Arial, sans-serif; padding: 40px 20px 20px 20px;">
        <div style="max-width: 1200px; margin: auto;">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #333; padding-bottom: 30px;">
                <div style="flex: 1 1 180px; margin: 10px 0;">
                    <h2 style="font-size: 22px; font-weight: bold; margin-bottom: 10px;">Dataspeed</h2>
                    <p style="font-size: 13px; color: #ccc;">Your trusted tech insights and creative code solutions.</p>
                </div>
                <div style="flex: 1 1 180px; margin: 10px 0;">
                    <h3 style="font-size: 17px; margin-bottom: 10px;">Links</h3>
                    <p><a href="index.php" style="color: #ccc; text-decoration: none;">Home</a></p>
                    <p><a href="pages/about.php" style="color: #ccc; text-decoration: none;">About</a></p>
                </div>
                <div style="flex: 1 1 180px; margin: 10px 0;">
                    <h3 style="font-size: 17px; margin-bottom: 10px;">Contact</h3>
                    <p><a href="pages/privacy-policy.php" style="color: #ccc; text-decoration: none;">Privacy Policy</a></p>
                    <p><a href="pages/contact.php" style="color: #ccc; text-decoration: none;">Contact Us</a></p>
                </div>
                <div style="flex: 1 1 200px; margin: 10px 0;">
                    <h3 style="font-size: 17px; margin-bottom: 10px;">Follow Me</h3>
                    <div style="display: flex; gap: 15px;">
                        <a href="https://www.youtube.com/" target="_blank"><img width="30" src="https://img.icons8.com/color/48/youtube-play.png" alt="youtube-alt" /></a>
                        <a href="https://www.instagram.com/" target="_blank"><img width="30" src="https://img.icons8.com/fluency/48/instagram-new.png" alt="Instagram Alt" /></a>
                        <a href="https://x.com/" target="_blank"><img width="24" src="https://img.icons8.com/ios-filled/50/ffffff/x.png" alt="X Logo White" /></a>
                        <a href="https://facebook.com/" target="_blank"><img width="30" src="https://img.icons8.com/color/48/facebook--v1.png" alt="Facebook" /></a>
                        <a href="https://t.me/" target="_blank"><img width="30" src="https://img.icons8.com/color/48/telegram-app--v1.png" alt="Telegram Alt" /></a>
                        <a href="mailto:dataspeedcontact@gmail.com"><img width="30" src="https://img.icons8.com/color/48/filled-message--v1.png" alt="Email" /></a>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; flex-wrap: wrap; margin-top: 30px; font-size: 13px; color: #666;">
            <div>Designed & Created by Dreamerscodes</div>
            <div>&copy; 2025 dataspeed</div>
        </div>
    </footer>
</body>

</html>