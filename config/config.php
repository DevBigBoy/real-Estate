<?php

$dbhost = 'localhost';
$dbname = 'real_estate_project';
$dbuser = 'root';
$dbpass = '';

try {
    $pdo = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo "Connection error :" . $exception->getMessage();
}

# 
define("BASE_URL", "http://localhost/real-estate/");
define("ADMIN_URL", BASE_URL . "admin/");

#
define("SMTP_HOST", "sandbox.smtp.mailtrap.io");
define("SMTP_PORT", "587");
define("SMTP_USERNAME", "07fe0ea2121097");
define("SMTP_PASSWORD", "253ba1d6e8ef46");
define("SMTP_ENCRYPTION", "tls");
define("SMTP_FROM", "contact@yourwebsite.com");
