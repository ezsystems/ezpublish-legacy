<?php
/**
 * Crude bin test script
 * Simulates GET /api/content/node/2 and outputs the ezcMvcResult
 */
$request = new ezcMvcRequest();
$request->date = new DateTime;
$request->uri = '/api/content/object/1/field/name';
$request->protocol = 'http';
$request->host = 'api.example.no';

$request->variables['objectId'] = 1;
$request->variables['fieldIdentifier'] = 'name';

$controller = new ezpRestContentController( 'viewField', $request );
$result = $controller->doViewField();
print_r( $result );
?>