<?php

return [
    'web' => App\Middlewares\Default\Web::class,
    'auth' => App\Middlewares\Default\Auth::class,
    'maintenance' => App\Middlewares\Default\Maintenance::class,
];

?>