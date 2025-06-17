<?php
// Redirect functions
function redirect($url)
{
    header("Location: $url");
    exit();
}

function redirectToReferrer()
{
    $referrer = $_SERVER['HTTP_REFERER'] ?? '/';
    header("Location: $referrer");
    exit();
}

function checkEnvVars($required_vars = null)
{
    if ($required_vars === null) {
        $required_vars = [
            'MAIL_HOST',
            'MAIL_USERNAME',
            'MAIL_PASSWORD',
            'MAIL_PORT',
            'MAIL_FROM_ADDRESS',
            'MAIL_FROM_NAME',
            'VTPASS_API_KEY',
            'BILLSTACK_SECRET_KEY'
        ];
    }
    $missing = [];
    foreach ($required_vars as $var) {
        // Try getenv, then $_ENV fallback
        $value = getenv($var) ?: ($_ENV[$var] ?? null);
        if (empty($value)) {
            $missing[] = $var;
        }
    }
    return $missing;
}

function sanitizeInput($input, $type = 'default')
{
    $input = trim($input); // Trim spaces

    switch ($type) {
        case 'string':  // General text (e.g., names)
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            break;

        case 'capitalize':  // Capitalize first letter of each word (e.g., Full Name, City)
            $input = ucwords(strtolower($input));
            break;

        case 'email':  // Lowercase and validate email
            $input = filter_var(strtolower($input), FILTER_SANITIZE_EMAIL);
            break;

        case 'url':  // Sanitize URL
            $input = filter_var($input, FILTER_SANITIZE_URL);
            break;

        case 'number':  // Remove non-numeric characters
            $input = preg_replace('/\D/', '', $input);
            break;

        case 'textarea':  // Preserve line breaks but escape HTML
            $input = nl2br(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
            break;

        default: // Default sanitization (htmlspecialchars)
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            break;
    }

    return $input;
}

function validateInput($input, $type, $required = true, $min = null, $max = null) {
    $input = trim($input);

    if ($required && empty($input)) {
        return "This field is required.";
    }

    switch ($type) {
        case 'string':  // General text validation
            if (!preg_match("/^[a-zA-Z\s]+$/", $input)) {
                return "Only letters and spaces allowed.";
            }
            break;

        case 'capitalize':  // Capitalized text
            $input = ucwords(strtolower($input));
            break;

        case 'email':  // Email validation
            if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
                return "Invalid email format.";
            }
            break;

        case 'url':  // URL validation
            if (!filter_var($input, FILTER_VALIDATE_URL)) {
                return "Invalid URL.";
            }
            break;

        case 'number':  // Number validation
            if (!is_numeric($input)) {
                return "Only numbers are allowed.";
            }
            break;

        case 'password':  // Strong password validation
            if (strlen($input) < 8 || !preg_match("/[A-Z]/", $input) || !preg_match("/[0-9]/", $input)) {
                return "Password must be at least 8 characters long, include a number and an uppercase letter.";
            }
            break;

        case 'textarea':  // Textarea input validation (sanitized but allows new lines)
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            break;
    }

    // Length Validation
    if ($min !== null && strlen($input) < $min) {
        return "Minimum length is $min characters.";
    }

    if ($max !== null && strlen($input) > $max) {
        return "Maximum length is $max characters.";
    }

    return $input;
}
?>
