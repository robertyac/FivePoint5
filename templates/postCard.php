<!-- Styling for the slider -->
<?php
$rating = $post['Rating'];
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
<a href="/post/viewPost.html" class="text-decoration-none">
    <div style="max-width:95%;" class="mx-auto card bg-secondary text-light mb-3 p-2 zoom">
        <div class="card-body text-center">
            <div class="d-flex justify-content-between">
                <!-- Tags -->
                <span class="text-start h6 text-body-emphasis opacity-75 rounded">
                    <?php foreach (explode(',', $post['Tags']) as $tag) : ?>
                        <span class="badge bg-light text-dark m-1 rounded-pill"><?php echo $tag; ?></span>
                    <?php endforeach; ?>
                </span>
                <!-- Time -->
                <span class="badge"><?php echo $post['PostDateTime']; ?> mins ago</span>
            </div>
            <!-- post title  -->

            <h3 class="card-title mt-lg-4 mb-4 text-light"><?php echo $post['PostTitle']; ?></h3>
            <!-- post image -->
            <?php if (!empty($post['PostImage'])) : ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($post['PostImage']); ?>" alt="Post Image" class="zoom rounded card-image img-fluid mb-3">
            <?php else : ?>
                <p class="text-light">
                    <?php
                    if (isset($post['Description'])) {
                        $words = explode(' ', $post['Description'] . '  ...read more');
                        echo implode(' ', array_slice($words, 0, 20)); // show first 20 words of the description if no image and description exists
                    }
                    ?>
                </p>
            <?php endif; ?>
        </div>
        <!-- slider rating -->
        <div class="text-start mt-3">
            <!-- Read only slider, disabled just for aggregate rating, actually rate on view page -->
            <div class="d-flex justify-content-end align-items-center">
                <input type="range" class="form-range rating-range" id="rating-<?php echo $postId; ?>" value="<?php echo $post['Rating']; ?>" min="0" max="5.5" step="0.01" disabled style="--rating: <?php echo $post['Rating']; ?>;">
                <p class="zoom badge bg-light text-dark m-1" for="rating"><span id="aggregate-rate"><?php echo $post['Rating']; ?></span>/5.5</p>
            </div>
        </div>
        <!-- End slider rating -->
    </div>
</a>