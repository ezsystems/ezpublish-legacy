<?php
/**
 * Crude bin test script
 * Simulates GET /api/content/node/2 and outputs the ezcMvcResult
 */
$request = new ezcMvcRequest();
$request->date = new DateTime;
$request->uri = '/api/content/node/2/field/name';
$request->protocol = 'http';
$request->host = 'api.example.no';

$request->variables['nodeId'] = 2;
$request->variables['fieldIdentifier'] = 'name';

$controller = new ezpRestContentController( 'viewField', $request );
$result = $controller->doViewField();
print_r( $result );
?>