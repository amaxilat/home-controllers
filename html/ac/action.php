<?php
$devices = array("ytf1", "bn59");
$commands = array("on", "off");

if (isset($_GET['device']) && isset($_GET['command'])) {
    $device = $_GET['device'];
    $command = $_GET['command'];
    if (in_array($device, $devices) && in_array($command, $commands)) {
        exec("irsend SEND_ONCE " . $_GET['device'] . " " . $_GET['command']);
    }
}
header('Location: ./');
