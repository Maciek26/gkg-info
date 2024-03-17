<?php
include 'db_connect.php';
$connection = OpenCon();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
$showRegister = true;


if(isset($_GET['register'])) {
    $error = false;
    $vorname = $_POST['vorname'];
    $nachname = $_POST['nachname'];
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo '<script>alert("Bitte eine gültige E-Mail-Adresse eingeben")</script>';
        $error = true;
    }
    if(strlen($vorname)==0){
        echo 'Du hast doch nh Vornamen oder?<br>';
    }
    if(strlen($nachname)==0){
        echo 'Und ein Nachnamen hast du auch nicht?<br>';
    }
    if(strlen($passwort) == 0) {
        echo 'Bitte ein Passwort angeben<br>';
        $error = true;
    }
    if($passwort != $passwort2) {
        echo 'Die Passwörter müssen übereinstimmen<br>';
        $error = true;
    }

    //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
    if(!$error) {
        $statement = $connection->prepare("SELECT * FROM users WHERE email = :email");
        $result = $statement->execute(array('email' => $email));
        $user = $statement->fetch();

        if($user !== false) {
            echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
            $error = true;
        }
    }

    //Keine Fehler, wir können den Nutzer registrieren
    if(!$error) {
        $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

        $statement = $connection->prepare("INSERT INTO users (email, passwort, vorname, nachname) VALUES (:email, :passwort, :vorname, :nachname)");
        $result = $statement->execute(array('email' => $email, 'passwort' => $passwort_hash, 'vorname' => $vorname, 'nachname' => $nachname));

        if($result) {
            echo 'Du wurdest erfolgreich registriert. <a href="login.php">Zum Login</a>';
            $showRegister = false;
            CloseCon($connection);
        } else {
            echo 'Beim Abspeichern ist ein Fehler aufgetreten<br>';
            CloseCon($connection);
        }
    }
}


if($showRegister) {
    ?>
    <div style = "margin-left: 40vw; margin-top: 15vw" >
<form action = "?register=1" method = "post" >
    Vorname:<br >
    <input size = "40" maxlength = "25" name = "vorname" ><br ><br >

    Nachname:<br >
    <input size = "40" maxlength = "25" name = "nachname" ><br ><br >

        E - Mail:<br >
    <input type = "email" size = "40" maxlength = "50" name = "email" ><br ><br >

    Dein Passwort:<br >
    <input type = "password" size = "40"  maxlength = "250" name = "passwort" ><br >

    Passwort wiederholen:<br >
    <input type = "password" size = "40" maxlength = "250" name = "passwort2" ><br ><br >

    <input type = "submit" value = "Abschicken" >
</form >

<br>
<a href="login.php">Zum Login</a>
</div >
<?php
}
?>


</body>
</html>
