<?php
session_start();

// Check if the user is an admin
if (!isset($_SESSION['IsAdmin']) || $_SESSION['IsAdmin'] != 1) {
    die("Unauthorized access");
}
$users = include 'commands/getAllUsers.php';

if (!isset($users) || empty($users)) {
    die('Error: $users is not set or empty');
}

//Getting all tags
include 'commands/tagsUsage.php';
$tags = getAllTags();

if (!isset($tags) || empty($tags)) {
    die('Error: $tags is not set or empty');
}

$tags_utf8 = utf8ize($tags);
$tags_json = json_encode($tags_utf8);

if (json_last_error() != JSON_ERROR_NONE) {
    die('json_encode error: ' . json_last_error_msg());
}

// force convert the data to UTF-8
function utf8ize($mixed)
{
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    } else if (is_string($mixed)) {
        return mb_convert_encoding($mixed, "UTF-8", "auto");
    }
    return $mixed;
}

$users_utf8 = utf8ize($users);
$users_json = json_encode($users_utf8);

if (json_last_error() != JSON_ERROR_NONE) {
    die('json_encode error: ' . json_last_error_msg());
}
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
    <!-- Style the chart with CSS -->
    <style>
        .bar {
            height: 20px;
            background: #3498db;
            border: 1px solid #2980b9;
            margin-bottom: 10px;
            color: white;
            font-size: smaller;
            line-height: 20px;
            transition: background 0.3s ease;
            max-width: 100%;
        }

        .bar:hover {
            background: #2980b9;
        }
    </style>
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
                                    <?php foreach ($users as $user) : ?>
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

    <!-- Create a div to hold the chart -->
    <section id="registrationsChart" class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="text-center mb-0">User Registrations</h2>
                    </div>
                    <div class="card-body">
                        <div id="chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Create a div to hold the tags chart -->
    <section id="tagsChart" class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="text-center mb-0">Tag Usage</h2>
                    </div>
                    <div class="card-body">
                        <div id="tags"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Users per day chart -->
    <script>
        var users = JSON.parse('<?php echo $users_json; ?>');

        var usersPerDay = {};

        // Grouping users by the day they were created
        for (var i = 0; i < users.length; i++) {
            var user = users[i];
            var timeCreated = new Date(user.TimeCreated.replace(' ', 'T') + '.000Z');
            var dateCreated = timeCreated.toISOString().split('T')[0];
            if (usersPerDay[dateCreated] === undefined) {
                usersPerDay[dateCreated] = 1;
            } else {
                usersPerDay[dateCreated]++;
            }
        }
        var maxCount = Math.max(...Object.values(usersPerDay));

        var chart = document.getElementById('chart');
        for (var date in usersPerDay) {
            var count = usersPerDay[date];

            var bar = document.createElement('div');
            bar.className = 'bar';
            bar.style.width = (count / maxCount * 100) + '%';
            bar.textContent = date + ': ' + count;
            chart.appendChild(bar);
        }
    </script>
    <!-- Tags chart -->
    <script>
        var tags = JSON.parse('<?php echo $tags_json; ?>');

        var maxCount = Math.max(...Object.values(tags));

        var chart = document.getElementById('tags');
        for (var tag in tags) {
            var count = tags[tag];

            var bar = document.createElement('div');
            bar.className = 'bar';
            bar.style.width = (count / maxCount * 100) + '%';
            bar.textContent = tag + ': ' + count;
            chart.appendChild(bar);
        }
    </script>

    <!-- Bootstrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.2/js/bootstrap.min.js"></script>
</body>

</html>