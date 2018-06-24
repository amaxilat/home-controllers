<?php
define("DEVICE", "device");
define("COMMAND", "command");

$devices = array("ytf1", "bn59");
$commands = array("cool-26-low", "cool-28-low", "off");

if (isset($_GET[DEVICE]) && isset($_GET[COMMAND])) {
    $device = $_GET[DEVICE];
    $command = $_GET[COMMAND];
    if (in_array($device, $devices) && in_array($command, $commands)) {
        exec("irsend SEND_ONCE " . $device . " " . $command);
    }
}
header('Location: ./');
