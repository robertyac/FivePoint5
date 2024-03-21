<?php
function getComments($pdo, $postID) {
    try {
        $sql = "SELECT * FROM Comment WHERE PostID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$postID]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("PDO error: " . $e->getMessage());
    }
}    
?>