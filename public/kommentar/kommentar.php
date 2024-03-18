<?php
include '../db_connect.php';

$connection = OpenCon();


if (isset($_GET['sentCom'])){
    $error = false;
    $bewertung = $_POST['bewertung'];
    $kommentar = $_POST['kommentartxt'];


    if(strlen($kommentar) == 0 || preg_match('/[^a-z0-9 ]+/i',$kommentar)) {
        echo 'Bitte ein Kommentar eingeben ohne spezial Zeichen<br>';
        $error = true;
    }

    if(!$error){
        $statement = $connection->prepare('SELECT id,rating,NumRatings FROM themen WHERE name= :name');
        $result = $statement->execute(array('name' => $_COOKIE["chosenComment"]));
        $ratings = $statement->fetch();
        //Get the ratings and how many ppl already rated to update the average
        $id_thema = $ratings[0];
        $rating = $ratings[1];
        $NumRating = $ratings[2];

        $statement2 = $connection->prepare("INSERT INTO kommentare (name_person, id_thema, text, rated) VALUES (:name_person, :id_thema, :text, :rated)");
        $result = $statement2->execute(array('name_person' => $_COOKIE['UserName'],'id_thema' =>$id_thema ,'text' => $kommentar, 'rated' => $bewertung));
        //--------------------------------------------------------------------------
        $newRating = (($rating * $NumRating) + $bewertung) / ($NumRating + 1);
        $newNumRatings = $NumRating + 1;

        $statement3 = $connection->prepare("UPDATE themen SET rating = :rating, NumRatings = :NumRatings WHERE id = :id_thema");
        $result = $statement3->execute(array('rating' => $newRating, 'NumRatings' => $newNumRatings, 'id_thema' => $id_thema));

        header('location: kommentar.php');
        exit;
    } else {
        echo "<script type='text/javascript'>alert('Ein Fehler ist aufetreten versuche es erneut!');</script>";
        }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
</head>
<body>
<script>
    function CloseCommentDisplay(){
        document.getElementById("openMakeComment").style.display = "flex";
        document.getElementById("main-chosen").style.display = "none";
        document.getElementById("input_comment").style.display = "none";
    }

    function OpenWriteComment(){
        document.getElementById("input_comment").style.display = "flex";
        document.getElementById("openMakeComment").style.display = "none";
    }
    //Mace logo animation
    window.onload = function() {
        var m = document.getElementById("m1");
        var a = document.getElementById("a1");
        var c = document.getElementById("c1");
        var e = document.getElementById("e1");
        setInterval(function () {
            m.style.animationIterationCount = "1";
            m.style.animation = "Mace 1s";
            var aTimeout = setTimeout(function () {
                a.style.animationIterationCount = "1";
                a.style.animation = "Mace 1s";
                clearTimeout(aTimeout)
                var cTimeout = setTimeout(function () {
                    c.style.animationIterationCount = "1";
                    c.style.animation = "Mace 1s";
                    clearTimeout(cTimeout)
                    var eTimeout = setTimeout(function () {
                        e.style.animationIterationCount = "1";
                        e.style.animation = "Mace 1s";
                        clearTimeout(eTimeout)
                        var resTimeout = setTimeout(function () {
                            m.style.animationIterationCount = "";
                            m.style.animation = "";
                            a.style.animationIterationCount = "";
                            a.style.animation = "";
                            c.style.animationIterationCount = "";
                            c.style.animation = "";
                            e.style.animationIterationCount = "";
                            e.style.animation = "";
                            clearTimeout(resTimeout)
                        }, 1200)
                    }, 1000)
                }, 1000)
            }, 1000)

        }, 6000)
    }
</script>
<?php



?>

<div class="nav">
    <div class="MACE-div"><a class="m1" id="m1">M</a><a class="a1" id="a1">A</a><a class="c1" id="c1">C</a><a class="e1" id="e1">E</a></div>
    <?php echo '<div class="nav-welcome">Willkommen, '. $_COOKIE['UserName'].'  </div>' ?>
</div>
<div class="main-con">
    <div class="theme-txt"><h1>Themen des Monats</h1></div>
    <?php

    $stmt = $connection->query("SELECT * FROM themen Where active > 0");
    $i = 1;
    while ($row = $stmt->fetch()) {
        if ($i<7) {
            echo '<div class="comment"  id="'.$i.'" onmousedown="CommentChosen('.$i.')" title="'.$row['name'].','.$row['rating'].','.$row['NumRatings'].'"> <p style="margin-top: 0.5vw">'. $row['name'] . "</p><br />\n" . '
                <div class="rating" style="margin-top: -3vw "><a>Durchschnitts Bewertung:</a> '. $row['rating'] . '<a> / 5</a></div>
                <div class="NumRated"><a>Anzahl Bewertungen:</a>' . $row['NumRatings'] . '</div>
                </div>';
            $i++;
        } else {
            break;
        }
    }



    ?>
</div>
<!-------------------------------------------------------------------------->
<div class="main-con-chosen" id="main-chosen">
    <a href="#" onmousedown="CloseCommentDisplay()" class="close"></a>
    <?php



        $statement = $connection->prepare('SELECT id,rating,NumRatings FROM themen WHERE name= :name');
        $result1 = $statement->execute(array('name' => $_COOKIE["chosenComment"]));

        $stmt = $connection->query("SELECT * FROM kommentare ORDER BY name_person ASC");
        $count = $connection->query("SELECT COUNT(id) FROM kommentare")->fetchColumn();
        $i = 0;
        $theme_comments1 = [];
        $theme_comments2 = [];
        $theme_comments3 = [];
        $theme_comments4 = [];
        $theme_comments5 = [];
        $theme_comments6 = [];

        $theme_usernames1 = [];
        $theme_usernames2 = [];
        $theme_usernames3 = [];
        $theme_usernames4 = [];
        $theme_usernames5 = [];
        $theme_usernames6 = [];

        $theme_rating1 = [];
        $theme_rating2 = [];
        $theme_rating3 = [];
        $theme_rating4 = [];
        $theme_rating5 = [];
        $theme_rating6 = [];

        while ($row = $stmt->fetch()) {
            if ($i < $count) {
                $stmt3 = $connection->prepare("SELECT vorname FROM users Where id = :name");
                $result3 = $stmt3->execute(array('name' => $row["name_person"]));

                if ($row["id_thema"]== 1){
                    $theme_comments1[] = $row["text"];
                    $theme_usernames1[] = $row["name_person"];
                    $theme_rating1[] = $row["rated"];
                } elseif ($row["id_thema"]== 2){
                    $theme_comments2[] = $row["text"];
                    $theme_usernames2[] = $row["name_person"];
                    $theme_rating2[] = $row["rated"];
                } elseif ($row["id_thema"]== 3){
                    $theme_comments3[] = $row["text"];
                    $theme_usernames3[] = $row["name_person"];
                    $theme_rating3[] = $row["rated"];
                } elseif ($row["id_thema"]== 4){
                    $theme_comments4[] = $row["text"];
                    $theme_usernames4[] = $row["name_person"];
                    $theme_rating4[] = $row["rated"];
                } elseif ($row["id_thema"]== 5){
                    $theme_comments5[] = $row["text"];
                    $theme_usernames5[] = $row["name_person"];
                    $theme_rating5[] = $row["rated"];
                } elseif ($row["id_thema"]== 6){
                    $theme_comments6[] = $row["text"];
                    $theme_usernames6[] = $row["name_person"];
                    $theme_rating6[] = $row["rated"];
                }
                $i++;
            } else {
                break;
            }

        }

    ?>
    <div class="chosen-name" id="chosen-name"></div>
<!--    <div class="chosen-rating" id="chosen-rating"></div>-->
<!--    <div class="chosen-NumRated" id="chosen-NumRated"></div>-->
    <script>


        function CommentChosen(i) {
            var oldComments = document.getElementsByClassName('foreign_whole');

            while(oldComments[0]) {
                oldComments[0].parentNode.removeChild(oldComments[0]);
            }

            var n = i-1
            console.log(n)
            var chosenComment = document.getElementById(i);
            var wholetitle = chosenComment.title
            console.log(wholetitle)
            var splitTitle = wholetitle.split(',');
            var name = splitTitle[0]

            var rating = splitTitle[1]
            var NumRating = splitTitle[2]

            document.getElementById("chosen-name").innerHTML = name;
            // document.getElementById("chosen-rating").innerHTML = rating;
            // document.getElementById("chosen-NumRated").innerHTML = NumRating;

            document.getElementById("main-chosen").style.display = "flex";


            var theme_comments1 = <?php echo '["' . implode('", "', $theme_comments1) . '"]' ?>;
            var theme_comments2 = <?php echo '["' . implode('", "', $theme_comments2) . '"]' ?>;
            var theme_comments3 = <?php echo '["' . implode('", "', $theme_comments3) . '"]' ?>;
            var theme_comments4 = <?php echo '["' . implode('", "', $theme_comments4) . '"]' ?>;
            var theme_comments5 = <?php echo '["' . implode('", "', $theme_comments5) . '"]' ?>;
            var theme_comments6 = <?php echo '["' . implode('", "', $theme_comments6) . '"]' ?>;

            var theme_usernames1 = <?php echo '["' . implode('", "', $theme_usernames1) . '"]' ?>;
            var theme_usernames2 = <?php echo '["' . implode('", "', $theme_usernames2) . '"]' ?>;
            var theme_usernames3 = <?php echo '["' . implode('", "', $theme_usernames3) . '"]' ?>;
            var theme_usernames4 = <?php echo '["' . implode('", "', $theme_usernames4) . '"]' ?>;
            var theme_usernames5 = <?php echo '["' . implode('", "', $theme_usernames5) . '"]' ?>;
            var theme_usernames6 = <?php echo '["' . implode('", "', $theme_usernames6) . '"]' ?>;

            var theme_rating1 = <?php echo '["' . implode('", "', $theme_rating1) . '"]' ?>;
            var theme_rating2 = <?php echo '["' . implode('", "', $theme_rating2) . '"]' ?>;
            var theme_rating3 = <?php echo '["' . implode('", "', $theme_rating3) . '"]' ?>;
            var theme_rating4 = <?php echo '["' . implode('", "', $theme_rating4) . '"]' ?>;
            var theme_rating5 = <?php echo '["' . implode('", "', $theme_rating5) . '"]' ?>;
            var theme_rating6 = <?php echo '["' . implode('", "', $theme_rating6) . '"]' ?>;

            var theme_colletion_comments = [theme_comments1,theme_comments2,theme_comments3,theme_comments4,theme_comments5,theme_comments6]
            var theme_colletion_usernames = [theme_usernames1,theme_usernames2,theme_usernames3,theme_usernames4,theme_usernames5,theme_usernames6]
            var theme_colletion_ratings = [theme_rating1,theme_rating2,theme_rating3,theme_rating4,theme_rating5,theme_rating6]

            var main_display_comments = document.getElementById("main_display_comments")
            // reminder: n is the id of the theme chosen by the user minus one
            for (var x = 0; x < theme_colletion_comments[n].length; x++){

                var newElement = document.createElement('div');
                newElement.id = theme_colletion_comments[n][x];
                newElement.className = "foreign_whole";
                main_display_comments.appendChild(newElement)

                var newUsername = document.createElement('div');

                newUsername.className = "foreign_username";
                newUsername.innerHTML = "Autor: " + theme_colletion_usernames[n][x];
                newElement.appendChild(newUsername);

                var newRating = document.createElement('div');

                newRating.className = "foreign_rating";
                newRating.innerHTML = "Bewertung: " + theme_colletion_ratings[n][x] + "/ 5";
                newElement.appendChild(newRating);

                var newComment = document.createElement('div');
                newComment.className = "foreign_comment";
                newComment.innerHTML = theme_colletion_comments[n][x];
                newElement.appendChild(newComment);


            }

        }
        function ConfirmComment(){
            document.cookie ="chosenComment =" + document.getElementById("chosen-name").innerHTML
        }

    </script>
    <div class="main-display-comments" id="main_display_comments">


    </div>
    <div class="openMakeComment" id="openMakeComment" onmousedown="OpenWriteComment()">Teile deine Meinung</div>
    <div class="input-comment" id="input_comment">

    <form action="?sentCom=1" method="post">
        <h1 style="color: white; margin-left: 7vw; font-size: 2vw">Kommentar</h1><br>
        <a style="color: white; margin-left: 8vw; font-size: 1vw">Bewertung von 0-5</a><br>
        <div style="margin-left: 1.5vw">
        <input type="range" min="0" max="5"  name="bewertung" class="range"><br>
        </div>
        <textarea  maxlength="750" name="kommentartxt" class="input-txtfield" placeholder="Teile uns deine Meinung mit..."></textarea><br>
        <a style="color: white; font-size: 0.7vw; margin-left: 1.25vw">Beachte das jeder deinen Kommentar sehen wird sowie deinen Namen!</a>
        <br>
        <br>
        <input type="submit" value="Absenden" class="submit-button" onmouseover="ConfirmComment()">
    </form>
    </div>
</div>


</body>
</html>


<!--

        $statement = $connection->prepare('SELECT id,rating,NumRatings FROM themen WHERE name= :name');
        $result1 = $statement->execute(array('name' => $_COOKIE["chosenComment"]));

        $stmt = $connection->query("SELECT * FROM kommentare Where id_thema = '$result1'");
        $i = 0;
        while ($row = $stmt->fetch()) {
            if ($i < 2) {
                $stmt3 = $connection->prepare("SELECT vorname FROM users Where id = :name");
                $result3 = $stmt3->execute(array('name' => $row["id_person"]));

                echo $result3;
                echo '<div class="comment" >' . $result3 . "<br />\n" . '
                <div class="rating">' . $row['text'] . '</div>
                <div class="NumRated">' . $row['rated'] . '</div>
                </div>';
                $i++;
            } else {
                break;
            }
        }


-->

<?php
//To do
    //Make the page not refresh on submit
        //Make a cookie with the id of the person that is logged in
            //Whenever a page loads check if the person is logged in
                //

?>