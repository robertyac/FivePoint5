<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php 
session_start();

// Check if the user is an admin
if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    die("Unauthorized access");
}

$users = include 'commands/getAllUsers.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    <link rel="icon" type="image/x-icon" href="img/5.5.ico">
    <link rel="apple-touch-icon" href="img/5.5-white.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="bg-secondary">
    <!--Navigation bar-->
    <div id="nav" style="height: 100px;"><?php include 'display_elements/nav.php'; ?></div>
    <!-- Login modal -->
    <?php include 'display_elements/login_modal.php'; ?>

    <section id="usersTable" class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="text-center mb-0">Admin Page</h1>
                </div>
                <div class="card-body">
                    <h2 class="text-center">All Users</h2>
        <!-- Search form -->
        <form class="mb-4" action="admin.php" method="get">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search for users by username, email or post ID" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>

        <!-- Users table -->
        <div style="height: 400px; overflow-y: auto;">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Enable/Disable</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['UserID'] ?></td>
                            <td><?= $user['Username'] ?></td>
                            <td><?= $user['Email'] ?></td>
                            <td>
                                <form action="commands/toggleUser.php" method="post">
                                    <input type="hidden" name="userID" value="<?= $user['UserID'] ?>">
                                    <button type="submit" class="btn btn-primary">
                                        <?= $user['IsEnabled'] ? 'Disable' : 'Enable' ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
    <!-- Bootstrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>
</html>