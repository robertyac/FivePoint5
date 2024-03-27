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
    $query = "DELETE FROM userFavoriteTags WHERE userID = $userID;";
    makeQuery($query);
    foreach ($tags as $tag) {
        $query = "SELECT tagID FROM tag WHERE LOWER(name) = '$tag';";
        $result = makeQuery($query);
        if (count($result) == 0) {
            echo "Tag $tag does not exist.";
            echo "<br>";
            echo $query;
            echo "<br>";
            $query = "SELECT tagID FROM tag";
            $result = makeQuery($query);
            foreach ($result as $row) {
                echo $row;
                echo "<br>";
                echo $row['tagID'];
                echo "<br>";
                echo "<br>";
            }
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