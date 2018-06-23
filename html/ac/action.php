<?php
        exec("irsend SEND_ONCE ".$_GET['device']." ".$_GET['command']);
        header('Location: ./');
?>