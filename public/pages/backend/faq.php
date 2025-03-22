<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - ScoreSync</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #F2F2F7;
            color: #1C1C1E;
            line-height: 1.5;
            padding-bottom: env(safe-area-inset-bottom);
        }

        /* Status bar */
        .status-bar {
            background-color: #F2F2F7;
            padding: 14px 24px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .time {
            font-weight: 600;
            font-size: 15px;
        }

        .icons {
            display: flex;
            gap: 5px;
        }

        .signal-icon,
        .wifi-icon,
        .battery-icon {
            width: 18px;
            height: 12px;
            background-color: #000;
            border-radius: 2px;
        }

        /* Container */
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #F2F2F7;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            background-color: #F2F2F7;
            position: sticky;
            top: 44px;
            z-index: 99;
        }

        .back-button {
            background: none;
            border: none;
            font-size: 24px;
            color: #007AFF;
            padding: 8px 12px;
            cursor: pointer;
            margin-right: 8px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 700;
        }

        /* Description */
        .description {
            padding: 16px;
            color: #6E6E73;
            font-size: 15px;
            line-height: 1.4;
        }

        /* FAQ List */
        .faq-list {
            background-color: #FFFFFF;
            border-radius: 10px;
            margin: 16px;
            overflow: hidden;
        }

        .faq-item {
            border-bottom: 1px solid #E5E5EA;
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .faq-question {
            width: 100%;
            padding: 16px;
            background: none;
            border: none;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-size: 16px;
            font-weight: 400;
            color: #1C1C1E;
        }

        .arrow {
            color: #C7C7CC;
            font-size: 24px;
            transition: transform 0.3s ease;
        }

        .faq-item.active .arrow {
            transform: rotate(90deg);
        }

        .faq-answer {
            display: none;
            padding: 0 16px 16px;
            color: #6E6E73;
            font-size: 15px;
            line-height: 1.4;
        }

        .faq-item.active .faq-answer {
            display: block;
        }
    </style>
</head>

<body>

    <div class="container">
        <header class="header">
            <button class="back-button">←</button>
            <h1>FAQ</h1>
        </header>

        <div class="description">
            <p>An FAQ or Frequently Asked Questions is a section for helps users find information quickly without needing to contact customer support!</p>
        </div>

        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-question">
                    <span>What is the ScoreSync ?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>The ScoreSync provides real-time updates on scores, statistics, and news for your favorite sports and teams. Stay informed with instant notifications and in-depth analysis.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>How do I download the ScoreSync ?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>You can download ScoreSync from the App Store or Google Play Store. Simply search for "ScoreSync" and tap the install button.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>Is the Live Score App free to use?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>Yes, ScoreSync is free to download and use. Some premium features may require a subscription.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>How do I set up notifications for my favorite teams?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>Go to your team's page, tap the bell icon, and choose which types of notifications you'd like to receive.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>Can I customize the sports and teams I follow?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>Yes, you can customize your feed by selecting your favorite sports and teams in the app settings.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>How often are the scores updated?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>Scores are updated in real-time as events occur during live games.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>What sports are covered in the Live Score App?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>We cover major sports including football, basketball, baseball, hockey, soccer, tennis, and more.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question">
                    <span>Can I watch live games on the Live Score App?</span>
                    <span class="arrow">›</span>
                </button>
                <div class="faq-answer">
                    <p>While we don't stream live games, we provide detailed live statistics and play-by-play updates.</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');

                question.addEventListener('click', () => {
                    // Close other open items
                    faqItems.forEach(otherItem => {
                        if (otherItem !== item && otherItem.classList.contains('active')) {
                            otherItem.classList.remove('active');
                        }
                    });

                    // Toggle current item
                    item.classList.toggle('active');
                });
            });
        });
    </script>
    <script src="script.js"></script>
</body>

</html>