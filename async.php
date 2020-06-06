<?php
require __DIR__ . '/vendor/autoload.php';
use Boting\Boting;

$Bot = new Boting("yourtoken");
echo "Bot is working";
while (True) {
    $Updates = $Bot->getUpdates();
    if (!is_array($Updates)) continue;
    $Sent = [];
    for ($i = 0; $i < count($Updates); $i++) {
        $Bot->sendMessage(["chat_id" => $Updates[$i]["message"]["chat"]["id"], "text" => microtime(true)]);
    }
    $Bot->asyncMethod->run();
}