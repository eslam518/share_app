<?php
  ob_start();
    include 'control.php';
    include 'ip.php';
    include 'device.php';
  ob_end_clean();

  $stmt = $con->prepare("SELECT * FROM users");
  $stmt->execute();
  $user_row = $stmt->fetch();

  if ($user_row['UserAgent'] == '___') {
    $stmt = $con->prepare("TRUNCATE `share`.`users`");
    $stmt->execute();
  }

  if ($url_count == 0) {
    echo 'There is no URL';
  } else {
    $stmt = $con->prepare("UPDATE url SET UsersCount = UsersCount + 1");
    $stmt->execute();

    $stmt = $con->prepare("INSERT INTO users(UserAgent,MAC,Device,Date) VALUES (?,?,?,NOW())");
    $stmt->execute(array($browser,$MAC,$result));

    $redirect_url = $url_row['url'];
    header('Location:' . $redirect_url);
  }

  $stmt = $con->prepare("SELECT Date from users ORDER BY Date DESC LIMIT 1");
  $stmt->execute();
  $date_row = $stmt->fetch();

  $date = $date_row['Date'];

  fwrite($fp, 'Date: ' . $date);
  fwrite($fp, $useragent);
  fwrite($fp, $browser);
  
  fclose($fp);
