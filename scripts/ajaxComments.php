<script>
    $(document).ready(function() {
        const collapsedComments = {};
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
                    const collapseButtons = document.querySelectorAll('.collapse-button');

                    collapseButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const parentDiv = this.closest('.card-body');
                            const commentId = parentDiv.getAttribute('data-comment-id');
                            console.log(commentId);

                            // Toggle collapsed state in the collapsedComments object
                            collapsedComments[commentId] = !collapsedComments[commentId];

                            // Toggle display based on collapsed state
                            const commentContent = parentDiv.querySelector('.comment-content');
                            commentContent.style.display = collapsedComments[commentId] ? 'none' : 'block';
                        });
                    });

                    // Set initial collapsed state based on collapsedComments object
                    const commentContents = document.querySelectorAll('.comment-content');
                    commentContents.forEach(commentContent => {
                        const commentId = commentContent.closest('.card-body').getAttribute('data-comment-id');
                        commentContent.style.display = collapsedComments[commentId] ? 'none' : 'block';
                    });
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