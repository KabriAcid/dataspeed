<? phpsession_start();
session_destroy();

header("Location: login.php");
