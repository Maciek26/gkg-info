<?php
include 'db_connect.php';

$connection = OpenCon();

if(!isset($_GET['userid']) || !isset($_GET['code'])) {
    die("Leider ist ein Fehler aufgetreten, versuche es erneut später");
}

$userid = $_GET['userid'];
$code = $_GET['code'];

//Abfrage des Nutzers
$statement = $connection->prepare("SELECT * FROM users WHERE id = :userid");
$result = $statement->execute(array('userid' => $userid));
$user = $statement->fetch();

//Überprüfe dass dieser User auch ein Passwortcode hat
if($user === null || $user['passwortcode'] === null) {
    die("Es wurde kein passender Benutzer gefunden");
}

if($user['passwortcode_time'] === null || strtotime($user['passwortcode_time']) < (time()-24*3600) ) {
    die("Dein Code ist leider abgelaufen, frage einen neuen an");
}


//Überprüfe den Passwortcode
if(sha1($code) != $user['passwortcode']) {
    die("Der übergebene Code war ungültig. Stell sicher, dass du den genauen Link in der URL aufgerufen hast.");
}



if(isset($_GET['send'])) {
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];

    if($passwort != $passwort2) {
        echo "Die Passwörter stimmen nicht über ein";
    } else { //Speichere neues Passwort und lösche den Code
        $passworthash = password_hash($passwort, PASSWORD_DEFAULT);
        $statement = $connection->prepare("UPDATE users SET passwort = :passworthash, passwortcode = NULL, passwortcode_time = NULL WHERE id = :userid");
        $result = $statement->execute(array('passworthash' => $passworthash, 'userid'=> $userid ));

        if($result) {
            die("Dein Passwort wurde erfolgreich geändert, du wirst in 5 Sekunden weiter geleitet");
            sleep(5);
            header("http://localhost:5500/public/login.php");
            exit;
        }
    }
}
?>

<h1>Neues Passwort vergeben</h1>
<form action="?send=1&amp;userid=<?php echo htmlentities($userid); ?>&amp;code=<?php echo htmlentities($code); ?>" method="post">
    Bitte gib ein neues Passwort ein:<br>
    <input type="password" name="passwort"><br><br>

    Passwort erneut eingeben:<br>
    <input type="password" name="passwort2"><br><br>

    <input type="submit" value="Passwort speichern">
</form>
