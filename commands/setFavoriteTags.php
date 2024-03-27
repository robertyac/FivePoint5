<?php
include 'makeQuery.php';
include 'prepUserId.php';
function updateTags($tags) {
    if (!isset($_SESSION)) {
        session_start();
    }
    prepUserId($_SESSION['user']);
    var_dump($tags);
    echo "<br>";
    echo $_SESSION['user_id'];
    echo "<br>";

    $userID = $_SESSION['user_id'];
    $query = "DELETE FROM UserFavoriteTags WHERE UserID = $userID;";
    makeQuery($query);
    foreach ($tags as $tag) {
        $query = "SELECT tagID FROM Tag WHERE LOWER(name) = '$tag';";
        $result = makeQuery($query);
        if (count($result) == 0) {
            echo "Tag $tag does not exist.";
            echo "<br>";
            echo $query;
            echo "<br>";
            $query = "SELECT TagID FROM Tag";
            $result = makeQuery($query);
            foreach ($result as $row) {
                echo $row;
                echo "<br>";
                echo $row['tagID'];
                echo "<br>";
                echo "<br>";
            }
            echo "end of results";
            exit();
        }
        $tagID = $result[0]['tagID'];
        $query = "INSERT INTO userFavoriteTags (userID, tagID) VALUES ($userID, $tagID);";
        makeQuery($query);
    }
}

if (isset($_POST['tags'])) {
    $submittedTags = explode(',', $_POST['tags']);
    updateTags($submittedTags);
    header('Location: ../profile.php');
}