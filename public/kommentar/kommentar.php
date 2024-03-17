<?php
include '../db_connect.php';

$connection = OpenCon();


if (isset($_GET['sentCom'])){
    $error = false;
    $bewertung = $_POST['bewertung'];
    $kommentar = $_POST['kommentartxt'];


    if(strlen($kommentar) == 0) {
        echo 'Bitte ein Kommentar eingeben<br>';
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

        $statement2 = $connection->prepare("INSERT INTO kommentare (id_person, id_thema, text, rated) VALUES (:id_person, :id_thema, :text, :rated)");
        $result = $statement2->execute(array('id_person' => "0",'id_thema' =>$id_thema ,'text' => $kommentar, 'rated' => $bewertung));
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





</script>
<div class="main-con">
    <?php

    $stmt = $connection->query("SELECT * FROM themen Where active > 0");
    $i = 1;
    while ($row = $stmt->fetch()) {
        if ($i<7) {
            echo '<div class="comment" style="order=' . $i . '" id="'.$i.'" onmousedown="CommentChosen('.$i.')" title="'.$row['name'].','.$row['rating'].','.$row['NumRatings'].'">' . $row['name'] . "<br />\n" . '
                <div class="rating">' . $row['rating'] . '</div>
                <div class="NumRated">' . $row['NumRatings'] . '</div>
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
    <?php



        $statement = $connection->prepare('SELECT id,rating,NumRatings FROM themen WHERE name= :name');
        $result1 = $statement->execute(array('name' => $_COOKIE["chosenComment"]));

        $stmt = $connection->query("SELECT * FROM kommentare ORDER BY id_person ASC");
        $count = $connection->query("SELECT COUNT(id) FROM kommentare")->fetchColumn();
        $i = 0;
        $theme_comments1 = [];
        $theme_comments2 = [];
        $theme_comments3 = [];
        $theme_comments4 = [];
        $theme_comments5 = [];
        $theme_comments6 = [];

        while ($row = $stmt->fetch()) {
            if ($i < $count) {
                $stmt3 = $connection->prepare("SELECT vorname FROM users Where id = :name");
                $result3 = $stmt3->execute(array('name' => $row["id_person"]));
                echo $row["text"]."  + ". $row['id_thema']. "  ";
                if ($row["id_thema"]== 1){
                    $theme_comments1[] = $row["text"];
                } elseif ($row["id_thema"]== 2){
                    $theme_comments2[] = $row["text"];
                } elseif ($row["id_thema"]== 3){
                    $theme_comments3[] = $row["text"];
                } elseif ($row["id_thema"]== 4){
                    $theme_comments4[] = $row["text"];
                } elseif ($row["id_thema"]== 5){
                    $theme_comments5[] = $row["text"];
                } elseif ($row["id_thema"]== 6){
                    $theme_comments6[] = $row["text"];
                }

                $i++;
            } else {
                break;
            }

        }

    ?>
    <div class="chosen-name" id="chosen-name"></div>
    <div class="chosen-rating" id="chosen-rating"></div>
    <div class="chosen-NumRated" id="chosen-NumRated"></div>
    <script>
        function CommentChosen(i) {
            var oldComments = document.getElementsByClassName('foreign_comment');

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
            document.getElementById("chosen-rating").innerHTML = rating;
            document.getElementById("chosen-NumRated").innerHTML = NumRating;

            document.getElementById("main-chosen").style.display = "flex";


            var theme_comments1 = <?php echo '["' . implode('", "', $theme_comments1) . '"]' ?>;
            var theme_comments2 = <?php echo '["' . implode('", "', $theme_comments2) . '"]' ?>;
            var theme_comments3 = <?php echo '["' . implode('", "', $theme_comments3) . '"]' ?>;
            var theme_comments4 = <?php echo '["' . implode('", "', $theme_comments4) . '"]' ?>;
            var theme_comments5 = <?php echo '["' . implode('", "', $theme_comments5) . '"]' ?>;
            var theme_comments6 = <?php echo '["' . implode('", "', $theme_comments6) . '"]' ?>;

            var theme_colletion = [theme_comments1,theme_comments2,theme_comments3,theme_comments4,theme_comments5,theme_comments6]
            var main_display_comments = document.getElementById("main_display_comments")
            // reminder: n is the id of the theme chosen by the user minus one
            for (var x = 0; x < theme_colletion[n].length; x++){
                console.log(theme_colletion)
                console.log(n)
                console.log(x)
                console.log(theme_colletion[n][x])
                var newElement = document.createElement('div');
                newElement.id = theme_colletion[n][x];
                newElement.className = "foreign_comment";
                newElement.innerHTML = theme_colletion[n][x];
                main_display_comments.appendChild(newElement);
            }









        }
        function ConfirmComment(){
            document.cookie ="chosenComment =" + document.getElementById("chosen-name").innerHTML
        }

    </script>
    <div class="main-display-comments" id="main_display_comments">


    </div>
    <div class="input-comment">
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