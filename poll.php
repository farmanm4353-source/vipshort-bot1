<?php
set_time_limit(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . "/config.php";

$offset = 0;

while (true) {
    $url = "https://api.telegram.org/bot{$BOT_TOKEN}/getUpdates?" . http_build_query([
        "timeout" => 30,
        "offset"  => $offset + 1
    ]);

    $resp = @file_get_contents($url);
    if ($resp === false) {
        sleep(3);
        continue;
    }

    $data = json_decode($resp, true);
    if (!empty($data["result"])) {
        foreach ($data["result"] as $u) {
            $offset = $u["update_id"];

            if (!isset($u["message"])) continue;

            $chat_id = $u["message"]["chat"]["id"];
            $text    = trim($u["message"]["text"] ?? "");

            // Save user (optional DB)
            if ($conn) {
                @$conn->query("INSERT IGNORE INTO users (telegram_id) VALUES ($chat_id)");
            }

            if ($text === "/start") {
                send($chat_id,
"👋 Welcome to VIPShort Bot

/unlock – Get access
/vip – Buy VIP
/referral – Referral link"
                );
            } elseif ($text === "/unlock") {
                send($chat_id, "🔓 Complete verification to unlock (demo).");
            } elseif ($text === "/vip") {
                send($chat_id, "💎 VIP info (demo).");
            } elseif ($text === "/referral") {
                send($chat_id, "👥 Referral system (demo).");
            }
        }
    }
    sleep(1);
}

function send($chat_id, $msg) {
    global $BOT_TOKEN;
    @file_get_contents(
        "https://api.telegram.org/bot{$BOT_TOKEN}/sendMessage?" .
        http_build_query([
            "chat_id" => $chat_id,
            "text"    => $msg
        ])
    );
}
