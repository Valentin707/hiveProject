@include('errors.layout', [
    'code' => '404',
    'title' => 'Signal not found',
    'classification' => 'ROUTE UNAVAILABLE',
    'message' => 'The address you requested is not registered in this identity core.',
    'networkStatus' => 'ADDRESS NOT REGISTERED',
])
