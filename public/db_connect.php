<?php
function OpenConMysqli()
{
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "gkg-info";
    $conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);
    return $conn;
}

function OpenCon()
{
    session_start();
    $pdo = new PDO('mysql:host=localhost;dbname=gkg_info', 'root', '');
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;            //change to localhost if doesnt work, up there
}

function CloseConMysqli($conn)
{
    $conn -> close();
}

function CloseCon($pdo)
{
    session_abort();
}

?>