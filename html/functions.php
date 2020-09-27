<?php


function _mail($to, $title, $mess, $from) {
  $headers = 'From: '.$from."\r\n";
  $headers .= "Content-type: text/html; charset=\"utf-8\"";
  return mail($to, $title, $mess, $headers);
};


function connectToDB() {
  global $link, $dbhost, $dbuser, $dbpass, $dbname;
  ($link = mysqli_connect("$dbhost", "$dbuser", "$dbpass", "$dbname")) || die("Couldn't connect to MySQL");

  mysqli_set_charset($link, 'utf8');
};



function newUser($login, $password, $email) {
  global $link;

  $pwd = md5($password);

  $date = new DateTime();
  $datereg = $date->format('Y-m-d H:i:s');

  $query="INSERT INTO users (name, pwd, datereg, email) VALUES('$login', '$pwd', '$datereg', '$email')";
  $result=mysqli_query($link, $query) or die("Died inserting login info into db.  Error returned if any: ".mysqli_error());

  return true;
}



function displayErrors($messages) {
  print("<b>Возникли следующие ошибки:</b>\n<ul>\n");

  foreach($messages as $msg){
    print("<li>$msg</li>\n");
  }
  print("</ul>\n");
}




function checkPass($login, $password) {
  global $link;

  $pwd = md5($password);

  $query="SELECT name, pwd FROM users WHERE name='$login' and pwd='$pwd'";
  $result=mysqli_query($link, $query)
    or die("checkPass fatal error: ".mysqli_error());

  if(mysqli_num_rows($result)==1) {
    $row=mysqli_fetch_array($result);
    return $row;
  }
  return false;
}



function flushMemberSession() {
  unset($_SESSION["login"]);
  unset($_SESSION["password"]);
  unset($_SESSION["loggedIn"]);
  unset($_SESSION["access"]);
  session_destroy();
  return true;
}



?>
