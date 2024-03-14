<?php
include 'db_connect.php';
$connection = OpenCon();

function random_str()
{
    $bytes = random_bytes(16);
    $str = bin2hex($bytes);
    return $str;

}

echo random_str();

if(isset($_GET['send']) ) {
    if(!isset($_POST['email']) || empty($_POST['email'])) {
        $error = "<b>Eine E-Mail-Adresse eintragen</b>";
    } else {
        $statement = $connection->prepare("SELECT * FROM users WHERE email = :email");
        $result = $statement->execute(array('email' => $_POST['email']));
        $user = $statement->fetch();

        if($user === false) {
            $error = "<b>Diese E-Mail ist nicht registriert, <a href='register.php'>Zur Registrierung</a></b>";
        } else {

            $passwortcode = random_str();
            $statement = $connection->prepare("UPDATE users SET passwortcode = :passwortcode, passwortcode_time = NOW() WHERE id = :userid");
            $result = $statement->execute(array('passwortcode' => sha1($passwortcode), 'userid' => $user['id']));
            //sha1 is used to encrypt the string
            $empfaenger = $user['email'];
            $betreff = "Neues Passwort für deinen Account auf www.mace-ist-cool.ch";
            $from = 'From: Mace 26 <hallomaciek19@gmail.com>' . "\r\n";
            $url_passwortcode = 'http://localhost:5500/passwort_reset.php?userid='.$user['id'].'&code='.$passwortcode;
            $text = 'Hallo '.$user['vorname'].',
für deinen Account auf www.mace-ist-cool.ch wurde nach einem neuen Passwort gefragt. Um das Passwort zurückzusetzen drücke hier:
'.$url_passwortcode.'
 
Erwartest du diese E-Mail nicht? Dann haben sie eh schon dein Passwort.
 
Viele Grüße,
dein Mace <3';

            mail($empfaenger, $betreff, $text, $from);

            echo "Eine Email um dein Passwort zurückzusetzen wurde gesendet.";
            $showForm = false;
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<h1>Passwort zurücksetzen</h1>
<p>Geben sie ihre Email an um eine Email zum zurücksetzen des Passwords zu bekommen:</p><br>
<form action="?send=1" method="post">
    <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlentities($_POST['email']) : ''; ?>"><br>
    <input type="submit" name="submit"><br>
</form>


</body>
</html>

