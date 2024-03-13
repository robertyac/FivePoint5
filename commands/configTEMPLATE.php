<!-- Rename this as config.php in your own environment this is JUST A TEMPLATE -->
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
