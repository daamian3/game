<?php

session_start();

$now = time();
$regenerateSec = 1800;
$regenerateReq = 10;

if (isset($_SESSION['HTTP_USER_AGENT'])){
  if ($_SESSION['HTTP_USER_AGENT'] != crypt($_SERVER['HTTP_USER_AGENT'], uniqid())){
    session_destroy();
    exit();
  }
}

else if (!isset($_SESSION['started'])){
  session_regenerate_id();
  $_SESSION['started'] = true;
  $_SESSION['time'] = $now;
  $_SESSION['req'] = 1;
  $_SESSION['HTTP_USER_AGENT'] = crypt($_SERVER['HTTP_USER_AGENT'], uniqid());
}

else{
  $_SESSION['req']++;

  if (isset($_SESSION['time']) && ((int)$_SESSION['time'] + $regenerateSec < $now)){
    session_regenerate_id(true);
    $_SESSION['time'] = $now;
  }

  if (isset($_SESSION['req']) && ((int)$_SESSION['req'] > $regenerateReq)){
    session_regenerate_id(true);
    $_SESSION['req'] = 1;
  }
  $_SESSION['HTTP_USER_AGENT'] = crypt($_SERVER['HTTP_USER_AGENT'], uniqid());
}
