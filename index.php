<?php
    require __DIR__ . '/vendor/autoload.php';

    use App\Bootstrap\Bootstrap;

    $handle = new Bootstrap();

    $handle->env(__DIR__ . '/.env');

    $handle->handle();
?>