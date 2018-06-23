<?php
define("PLUG", "plug");
define("STATUS", "status");
define("YEE", "yee");
define("YEE_SERVER", "http://192.168.1.10:8082/v1");

$plugs = array("1", "2", "3", "4");
$statuses = array(0, 1);
$yees = array("0x0000000000c936d0");

if (isset($_GET[PLUG]) && isset($_GET[STATUS])) {
    $plug = $_GET[PLUG];
    $status = $_GET[STATUS];
    if (in_array($plug, $plugs) && in_array($status, $statuses)) {
        exec("sudo plugs " . $plug . " " . $status);
    }
}
if (isset($_GET[YEE]) && isset($_GET[STATUS])) {
    $yee = $_GET[YEE];
    $status = $_GET[STATUS];
    if (in_array($yee, $yees) && in_array($status, $statuses)) {
        $ch = curl_init();
        if ($status == 1) {
            curl_setopt($ch, CURLOPT_URL, YEE_SERVER . "/device/on/" . $yee);
        } else if ($status == 0) {
            curl_setopt($ch, CURLOPT_URL, YEE_SERVER . "/device/off/" . $yee);
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
    }
}
header('Location: ./');
