<?php

use App\Http\Request;

$request = new Request;

return [
    '/errors' => [
        'url' => $request->getUrl(),
        'title' => 'Internal server errors!',
        'file' => 'errors/index.component.html',
        'code' => 500,
        'moment' => date("d-m-Y H:i:s")
    ],
    '/standard' => [
        'url' => $request->getUrl(),
        'title' => 'We will be back online shortly!',
        'file' => 'maintenance/index.component.html',
        'code' => 503,
        'moment' => date("d-m-Y H:i:s")
    ],
    '/not-found' => [
        'url' => $request->getUrl(),
        'title' => 'Page not found!',
        'file' => 'notfound/index.component.html',
        'code' => 404,
        'moment' => date("d-m-Y H:i:s")
    ],
];