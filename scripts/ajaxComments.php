<script>
    $(document).ready(function() {
        function loadComments() {
            <?php if (isset($_SESSION['user_id'])) : ?>
            <?php endif; ?>
            <?php
            $current_page = substr($_SERVER['PHP_SELF'], -12, 12); ?>
            let data = {
                userID: undefined,
                postID: undefined
            };
            data.by = 'post';
            <?php if ($current_page == '/profile.php') { ?>
                data.by = 'user'
                data.userID = <?php echo json_encode($_SESSION['user_id']) ?>
            <?php } else { ?>
                data.by = 'post'
                data.postID = <?php echo json_encode( $_GET['PostID']) ?>
            <?php } ?>

            $.ajax({
                url: 'commands/getComments.php',
                type: 'GET',
                data: data,
                success: function(data) {
                    $('#comments').html(data);
                }
            });
        }

        loadComments(); // Load comments on page load

        // This will reload comments every 5 seconds. User will not have to refresh the page to see new comments
        setInterval(loadComments, 5000);

        $('#commentForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: 'commands/submitComment.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function() {
                    loadComments(); // Reload comments after submitting a new one
                }
            });
        });
    });
</script>