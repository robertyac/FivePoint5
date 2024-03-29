<!-- Styling for the slider -->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$rating = $post['AverageRating'];
$postId = $post['PostID'];
if ($rating > 2.75) {
?>
    <style>
        .rating-range#rating-<?php echo $postId; ?>::-webkit-slider-runnable-track {
            background: linear-gradient(to right, black 0%, blue calc((var(--rating) / 5.5) * 50%), red calc((var(--rating) / 5.5) * 100%), #ddd calc((var(--rating) / 5.5) * 100%), #ddd 100%);
        }

        .rating-range#rating-<?php echo $postId; ?>::-moz-range-track {
            background: linear-gradient(to right, black 0%, blue calc((var(--rating) / 5.5) * 50%), red calc((var(--rating) / 5.5) * 100%), #ddd calc((var(--rating) / 5.5) * 100%), #ddd 100%);
        }
    </style>
<?php
} else {
?>
    <style>
        .rating-range#rating-<?php echo $postId; ?>::-webkit-slider-runnable-track {
            background: linear-gradient(to right, black 0%, blue calc((var(--rating) / 5.5) * 100%), #ddd calc((var(--rating) / 5.5) * 100%), #ddd 100%);
        }

        .rating-range#rating-<?php echo $postId; ?>::-moz-range-track {
            background: linear-gradient(to right, black 0%, blue calc((var(--rating) / 5.5) * 100%), #ddd calc((var(--rating) / 5.5) * 100%), #ddd 100%);
        }
    </style>
<?php
}
?>

<!-- Actual card -->
<a href="viewPost.php?PostID=<?php echo $post['PostID']; ?>" class="text-decoration-none">
    <div style="max-width:95%;" class="mx-auto card bg-secondary text-light mb-3 p-2 zoom">
        <div class="card-body text-center">
            <div class="d-flex justify-content-between">
                <!-- Tags -->
                <span class="text-start h6 text-body-emphasis opacity-75 rounded">
                    <?php foreach (explode(',', $post['Tags']) as $tag) : ?>
                        <span class="badge bg-light text-dark m-1 rounded-pill"><?php echo $tag; ?></span>
                    <?php endforeach; ?>
                </span>
                <!-- Delete button for admins -->
                <?php if (isset($_SESSION['IsAdmin']) && $_SESSION['IsAdmin']) : ?>
                    <div class="d-flex justify-content-center">
                        <form action="commands/deletePost.php" method="post">
                            <input type="hidden" name="PostID" value="<?php echo $post['PostID']; ?>">
                            <button type="submit" class="btn btn-danger">Delete Post</button>
                        </form>
                    </div>
                <?php endif; ?>
                <!-- Time -->
                <span class="badge">
                    <?php
                    $currentDateTime = new DateTime('', new DateTimeZone('America/Vancouver'));
                    $postDateTime = new DateTime($post['PostDateTime'], new DateTimeZone('America/Vancouver'));
                    // Calculate time difference
                    $interval = $currentDateTime->diff($postDateTime);
                    if ($interval->y > 0) {
                        echo $interval->y . ($interval->y > 1 ? ' years' : ' year') . ' ago';
                    } elseif ($interval->m > 0) {
                        echo $interval->m . ($interval->m > 1 ? ' months' : ' month') . ' ago';
                    } elseif ($interval->d > 0) {
                        echo $interval->d . ($interval->d > 1 ? ' days' : ' day') . ' ago';
                    } elseif ($interval->h > 0) {
                        echo $interval->h . ($interval->h > 1 ? ' hours' : ' hour') . ' ago';
                    } elseif ($interval->i > 0) {
                        echo $interval->i . ($interval->i > 1 ? ' mins' : ' min') . ' ago';
                    } else {
                        echo 'Just now';
                    }
                    ?>
                </span>
            </div>
            <!-- post title  -->

            <h3 class="card-title mt-lg-4 mb-4 text-light"><?php echo $post['PostTitle']; ?></h3>
            <!-- post image -->
            <?php if (!empty($post['PostImage'])) : ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($post['PostImage']); ?>" alt="Post Image" class="zoom rounded card-image img-fluid" style="max-height: 600px;">
            <?php else : ?>
                <p class="text-light">
                    <?php
                    if (isset($post['Description'])) {
                        echo substr($post['Description'], 0, 50); // first 50 characters of description
                        if (strlen($post['Description']) > 50) {
                            echo '...'; 
                        }
                    }
                    ?>
                </p>
            <?php endif; ?>
        </div>
        <!-- slider rating -->
        <div class="text-start mt-3 mb-3">
            <!-- Read only slider, disabled just for aggregate rating, actually rate on view page -->
            <div class="d-flex justify-content-end align-items-center">
                <input type="range" class="form-range rating-range" id="rating-<?php echo $postId; ?>" value="<?php echo $post['AverageRating']; ?>" min="0" max="5.5" step="0.01" disabled style="--rating: <?php echo $post['AverageRating']; ?>;">
                <p class="zoom badge bg-light text-dark m-1" for="rating"><span id="aggregate-rate"><?php echo $post['AverageRating'] == 0 ? 'No Rating' : $post['AverageRating']; ?></span>/5.5</p>
            </div>
        </div>
        <!-- End slider rating -->
    </div>
</a>