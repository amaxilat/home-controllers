<?php
define("DEVICE", "device");
define("COMMAND", "command");

$devices = array("ytf1", "bn59");
$commands = array("KEY_POWER", "KEY_TV", "KEY_1", "KEY_2", "KEY_3", "KEY_4", "KEY_5", "KEY_6", "KEY_7", "KEY_8",
    "KEY_9", "KEY_0", "KEY_FAVORITES", "Pre-Ch", "KEY_VOLUMEUP", "KEY_VOLUMEDOWN", "KEY_MUTE", "KEY_CYCLEWINDOWS",
    "KEY_CHANNELUP", "KEY_CHANNELDOWN", "KEY_INFO", "KEY_MENU", "KEY_INFO", "Tools", "KEY_ENTER", "Ch.List",
    "KEY_EXIT", "KEY_UP", "KEY_LEFT", "KEY_RIGHT", "KEY_DOWN", "KEY_ENTER", "KEY_RED", "KEY_GREEN", "KEY_YELLOW",
    "KEY_BLUE", "TTX/Mix", "SRS", "P.Size", "Dual", "AD", "KEY_SUBTITLE", "KEY_REWIND", "KEY_PAUSE", "KEY_FORWARD",
    "KEY_RECORD", "KEY_PLAY", "KEY_STOP");

if (isset($_GET[DEVICE]) && isset($_GET[COMMAND])) {
    $device = $_GET[DEVICE];
    $command = $_GET[COMMAND];
    if (in_array($device, $devices) && in_array($command, $commands)) {
        exec("irsend SEND_ONCE " . $device . " " . $command);
    }
}
header('Location: ./');
