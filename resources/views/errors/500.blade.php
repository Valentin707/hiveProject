@include('errors.layout', [
    'code' => '500',
    'title' => 'Core interruption',
    'classification' => 'INTERNAL FAULT',
    'message' => 'A system fault interrupted this request. Try again in a moment.',
    'networkStatus' => 'REQUEST INTERRUPTED',
])
