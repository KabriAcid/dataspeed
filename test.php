<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Financial Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a1a 0%, #0a0a0a 100%);
        }

        .card {
            border: none;
            border-radius: 1rem;
            background: rgba(33, 37, 41, 0.5) !important;
            backdrop-filter: blur(10px);
        }

        .profile-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.25rem;
        }

        .action-button {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .action-button:hover {
            transform: scale(1.05);
        }

        .transaction-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .btn-link:hover {
            color: white !important;
        }

        .transaction-list>div:not(:last-child) {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 1rem;
        }
    </style>
</head>

<body class="">
    

    <script>
        document.getElementById('toggleBalance').addEventListener('click', function() {
            const balanceAmount = document.getElementById('balanceAmount');
            const hiddenBalance = document.getElementById('hiddenBalance');
            const eyeIcon = this.querySelector('svg');

            if (balanceAmount.classList.contains('d-none')) {
                balanceAmount.classList.remove('d-none');
                hiddenBalance.classList.add('d-none');
                eyeIcon.innerHTML = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
            } else {
                balanceAmount.classList.add('d-none');
                hiddenBalance.classList.remove('d-none');
                eyeIcon.innerHTML = '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" y1="2" x2="22" y2="22"/>';
            }
        });
    </script>
</body>

</html>