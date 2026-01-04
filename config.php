<?php
// Telegram
$BOT_TOKEN = getenv("BOT_TOKEN");

// Optional MySQL (set only if you add DB vars)
$DB_HOST = getenv("DB_HOST");
$DB_USER = getenv("DB_USER");
$DB_PASS = getenv("DB_PASS");
$DB_NAME = getenv("DB_NAME");

$conn = null;
if ($DB_HOST && $DB_USER && $DB_NAME) {
    $conn = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
}