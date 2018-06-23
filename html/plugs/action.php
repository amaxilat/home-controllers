<?php
  if(isset($_GET['plug']) && isset($_GET['status'])){
	  exec("sudo plugs ".$_GET['plug']." ".$_GET['status']);
	}
	if(isset($_GET['yee']) && isset($_GET['status'])){
      $ch = curl_init();
      if ($_GET['status']==1){
        curl_setopt($ch, CURLOPT_URL,"http://192.168.1.10:8082/v1/device/on/".$_GET['yee']);
      } else if ($_GET['status']==0){
        curl_setopt($ch, CURLOPT_URL,"http://192.168.1.10:8082/v1/device/off/".$_GET['yee']);
      }
      curl_setopt($ch, CURLOPT_POST, 1);
      // receive server response ...
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $server_output = curl_exec ($ch);
      curl_close ($ch);
  }
  header('Location: ./');
?>