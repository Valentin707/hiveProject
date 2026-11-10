<div>
    <!-- The whole future lies in uncertainty: live immediately. - Seneca -->
</div>
@include('errors.layout', [
    'code' => '403',
    'title' => 'Access denied',
    'classification' => 'CLEARANCE REQUIRED',
    'message' => 'This request is outside the clearance assigned to your operator account.',
    'networkStatus' => 'REQUEST BLOCKED',
])
