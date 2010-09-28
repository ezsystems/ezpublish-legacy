<?php
/**
 * Crude bin test script
 * Simulates GET /api/content/node/2 and outputs the ezcMvcResult
 */
$request = new ezcMvcRequest();
$request->date = new DateTime;
$request->uri = '/api/content/node/2/fields';
$request->protocol = 'http';
$request->host = 'api.example.no';

$request->variables['nodeId'] = 2;

$controller = new ezpRestContentController( 'viewFields', $request );
$result = $controller->doViewFields();
print_r( $result );
?>