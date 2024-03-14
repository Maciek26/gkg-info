<?php
include 'db_connect.php';

$connection = OpenCon();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
</head>
<body>

<div class="main-con">
    <div class="enter-comment">
        <div class="input-txt">
            <a>Schreibe etwas, jeder wird das sehen können:</a>
        </div>

        <form onkeydown="return event.key != 'Enter';">
            <input type="text" class="input-comment">
            <input type="submit" class="submit">
        </form>
    </div>
    <div class="show-comments">

    </div>
</div>

</body>
</html>
