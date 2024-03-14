<?php
include 'db_connect.php';
$connection = OpenCon();
$showLogin = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
if (isset($_GET['ausloggen']) && isset($_SESSION['userId'])){
    session_abort();
    session_destroy();
    $showLogin = true;
    echo 'Erfolgreich ausgeloggt';
}


if (isset($_GET['login'])){
    $error = false;
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<script>alert("Bitte eine gültige E-Mail-Adresse eingeben")</script>';
        $error = true;
    }
    if(strlen($passwort) == 0) {
        echo 'Bitte ein Passwort angeben<br>';
        $error = true;
    }

    if(!$error){
        $statement = $connection->prepare('SELECT * FROM users WHERE email= :email');
        $result = $statement->execute(array('email' => $email));
        $user = $statement->fetch();

        if (!strlen($user['email'])==0 && password_verify($passwort, $user['passwort'])){
            $showLogin = false;
            $_SESSION['userId'] = $user['id'];
            echo 'Du wurdest erfolgreich eingeloggt!<br>';
            echo 'Willkommen ',$user['vorname'] ,'<br>';
            echo $user['email'], '<br>';
            ?>
                <form action="?ausloggen=1" method="post">
                    <input type="submit" value="Ausloggen">
                </form>
        <?php
        } else {
            $showLogin = true;
            echo 'Email oder Passwort ungültig';
        }

    }
}
if ($showLogin){
?>


<form action="?login=1" method="post">
    <h1>LOGIN</h1><br>
    <a>E-Mail:</a><br>
    <input type="email" maxlength="50" name="email"><br>
    <a>Passwort:</a><br>
    <input type="password" maxlength="250" name="passwort"><br>
<br>
    <input type="submit" value="Einloggen">
</form>
<br>
<a href="register.php">Zur Registrierung</a>
<br>
<a href="passwort_vergessen.php">Passwort vergessen?</a>

</body>
</html>
<?php
}
?>