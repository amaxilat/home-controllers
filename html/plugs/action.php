<?php
define("PLUG", "plug");
define("STATUS", "status");
define("YEE", "yee");
define("HUE", "hue");
define("YEE_SERVER", "http://192.168.1.10:8082/v1");
define("HUE_SERVER", "http://192.168.1.30/api/token/groups/3/action");

$plugs = array("1", "2", "3", "4");
$statuses = array(0, 1, "energize", "concentrate", "relax", "read", "dimmed");
$yees = array("0x0000000000c936d0");
$hues = array("3");

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
if (isset($_GET[HUE]) && isset($_GET[STATUS])) {
    $hue = $_GET[HUE];
    $status = $_GET[STATUS];
    if (in_array($hue, $hues) && in_array($status, $statuses)) {
        $ch = curl_init();
        $data = array();
        if ($status == 1) {
            $data = array("on" => true, "bri" => 254, "hue" => 34069, "sat" => 251);
        } else if ($status == "energize") {
            $data = array("on" => true, "bri" => 254, "hue" => 34069, "sat" => 251);
        } else if ($status == "concentrate") {
            $data = array("on" => true, "bri" => 254, "hue" => 32938, "sat" => 50);
        } else if ($status == "relax") {
            $data = array("on" => true, "bri" => 144, "hue" => 13401, "sat" => 204);
        } else if ($status == "read") {
            $data = array("on" => true, "bri" => 254, "hue" => 15338, "sat" => 121);
        } else if ($status == "dimmed") {
            $data = array("on" => true, "bri" => 77, "hue" => 14988, "sat" => 141);
        } else if ($status == 0) {
            $data = array("on" => false);
        }

        $data_json = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_URL, HUE_SERVER);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_json)));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
    }
}

header('Location: ./');
