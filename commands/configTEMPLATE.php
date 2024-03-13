<!-- Rename this as config.php in your own environment this is JUST A TEMPLATE -->
<!-- THIS IS INTEGRATED WITH GITHUB ACTIONS TO AUTO FILL SECRETS -->
<?php
return [
    'database' => [
        'host' => 'localhost',
        'user' => getenv("USER"),
        'password' => getenv("PASSWORD"),
        'name' => getenv("DB"),
    ],
    'server' => [
        'host' => 'localhost',
        'port' => 8080,
    ],
];
?>
