<?php
/**
 * Crude bin test script
 * Simulates GET /api/content/node/2 and outputs the ezcMvcResult
 */
$request = new ezcMvcRequest();
$request->date = new DateTime;
$request->uri = '/api/content/object/1/fields';
$request->protocol = 'http';
$request->host = 'api.example.no';

$request->variables['objectId'] = 1;

$controller = new ezpRestContentController( 'viewFields', $request );
$result = $controller->doViewFields();
print_r( $result );
?>