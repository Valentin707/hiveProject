@include('errors.layout', [
    'code' => '403',
    'title' => 'Access denied',
    'classification' => 'UNAUTHORIZED NODE ACCESS',
    'message' => 'CLEARANCE DENIED // UNAUTHORIZED NODE ACCESS. Your account does not have clearance for this request.',
    'networkStatus' => 'REQUEST BLOCKED',
])
