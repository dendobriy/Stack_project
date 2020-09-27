<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>Поиск книги</title>
  <style>
   select {
    width: 100px; /* Ширина списка в пикселах */
   }
  </style>
 </head>
 <body>

<?php

include_once("config.php");

global $link;
global $messages;


/*
print "<pre>";
print "_POST = ";
var_dump($_POST);
print "</pre>";

print "<pre>";
print "_POST[WorkId] = ";
var_dump($_POST["WorkId"]);
print "</pre>";
/**/

if (count($_POST) == 0){
  goto find;
};

if ($_POST["WorkId"] <> NULL){
  goto Route;
};

if (($_POST["Name"] == "") && ($_POST["Style"] == "") && ($_POST["Autor"] == "")){
  goto find;
};



$query = "SELECT `W`.`Id`, `W`.`Name`, `Styles`.`Name` AS Style, `Autors`.`Name` AS Autor FROM(SELECT * FROM `Works` WHERE (1=1)";
if ($_POST["Name"] <> ""){
  $query = $query."\n AND (`Name` LIKE '%$_POST[Name]%')";
};
if ($_POST["Style"] <> ""){
  $query = $query."\n AND (`Style` in (SELECT `Id` FROM `Styles` WHERE `Name` LIKE '%$_POST[Style]%'))";
};

if ($_POST["Autor"] <> ""){
  $query = $query."\n AND (`Autor` in (SELECT `Id` FROM `Autors` WHERE `Name` LIKE '%$_POST[Autor]%'))";
};

  $query = $query."\n ) AS W
    LEFT JOIN Styles
      ON W.Style = `Styles`.`Id`
    LEFT JOIN Autors
      ON W.Autor = `Autors`.`Id`";



$result = mysqli_query($link, $query);// or die('Запрос не удался: ' . mysqli_error().' <BR>');
if (!$result){
  print ('Запрос не удался: ' . mysqli_error().' <BR>');
  goto find;
}else{
/*
  while ($line = mysqli_fetch_array($result)) {
    print "<pre>";
    print "line = ";
    var_dump($line);
    print "</pre>";
  };
*/
};




if (mysqli_num_rows($result ) == 0){
  print "Мы не смогли найти книгу по заданным параметрам, проверьте данные и попробуйте еще раз.<br>";
  goto find;
}elseif(mysqli_num_rows($result ) > 1){
  print "<form action=\"index.php\" method=\"post\">";
  print "Пожалуйста, выберите интересующую книгу из списка или измените параметры запроса.<br>";
  print "    <select  name = \"WorkId\" style=\"width: 700px;\">";
  while ($line = mysqli_fetch_array($result)) {
    print "    <option value=\"$line[Id]\">$line[Autor]: $line[Name] ($line[Style])</option>\n";
  };
  print "<INPUT TYPE=\"submit\" VALUE=\"Выбрать!\"></form><br>\n";
  goto find;
}else{
  $line = mysqli_fetch_array($result);
  $_POST["WorkId"]= $line["Id"];
};


Route:

$query = "SELECT B.`Balance`, `Works`.`Name` AS Work, `Works`.`Autor`, `Works`.`Style`, `Locations`.`Name` AS Location, `Locations`.`cx`, `Locations`.`cy`
FROM(SELECT * FROM `Balances` WHERE `Work`= '$_POST[WorkId]') AS B
LEFT JOIN (SELECT `Works`.Id, `Works`.`Name`, `Autors`.`Name` AS Autor, `Styles`.`Name` AS Style
FROM `Works`
LEFT JOIN `Autors` ON `Autors`.`Id` = `Works`.`Autor`
LEFT JOIN `Styles` ON `Styles`.`Id` = `Works`.`Style` WHERE `Works`.`Id` = '$_POST[WorkId]' ) AS Works
ON `Works`.`Id` = B.Work
LEFT JOIN `Locations`
ON `Locations`.`Id` = B.`Location`";

$result = mysqli_query($link, $query);// or die('Запрос не удался: ' . mysqli_error().' <BR>');
if (!$result){
  print ('Запрос не удался: ' . mysqli_error().' <BR>');
}else{
  $line = mysqli_fetch_array($result);
    print "Расположение нужного Вам ресурса<br> ($line[Autor]: $line[Work], $line[Style]):<br>";
    print "$line[Location]<br>\n";
    print "<a href = \"i.php?cx=$line[cx]&cy=$line[cy]\" target = \"_blank\">Посмотреть конечную точку</a><br>\n<br><br>";
/*
    print "<pre>";
    print "line[cx] = ";
    var_dump($line[cx]);
    print "</pre>";

    print "<pre>";
    print "line[cy] = ";
    var_dump($line[cy]);
    print "</pre>";


    print "<pre>";
    print "line = ";
    var_dump($line);
    print "</pre>";
*/
    goto find;
};






?>


<?php
  find:
?>

Введите данные для поиска произведения (или события):<br>
  <form action="index.php" method="post">
   <table>
    <tr><td><strong>Название</strong></td><td><input length="250" size="40" value="" name = 	"Name" style="width: 300px;"></td></tr>



<?php

$query = "SELECT * FROM `Styles`";
$result = mysqli_query($link, $query) or die('Запрос не удался: ' . mysqli_error().' <BR>');
if (!$result){
  print "    <tr><td><strong>Жанр</strong></td><td><input maxlength=\"25\" size=\"40\" value=\"---\" name = \"Style\"></td></tr>";
}else{
  print "    <tr><td><strong>Жанр</strong></td><td><select  name = \"Style\" style=\"width: 300px;\">";
  print "    <option selected value=\"\">Выберите</option>\n";
  while ($line = mysqli_fetch_array($result)) {
    print "    <option value=\"$line[Name]\">$line[Name]</option>\n";
  };
  print "    </td></tr>";
};

?>

    <tr><td><strong>Автор</strong></td><td><input maxlength="250" size="40" value="" name = "Autor" style="width: 300px;"></td></tr>
</table>
<br>
<INPUT TYPE="submit" VALUE="Найти произведение (событие)!">
</form>



</body>
</html>
