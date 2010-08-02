<?php
/**
 * File containing the definition of the RestClient persistent object
 *
 * @version $Id$
 * @copyright 2010
 */

$def = new ezcPersistentObjectDefinition();
$def->table = "ezprest_clients";
$def->class = "ezpRestClients";

$def->idProperty = new ezcPersistentObjectIdProperty;
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition( 'ezcPersistentNativeGenerator' );

$def->properties['client_id'] = new ezcPersistentObjectProperty;
$def->properties['client_id']->columnName = 'client_id';
$def->properties['client_id']->propertyName = 'clientId';
$def->properties['client_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['client_secret'] = new ezcPersistentObjectProperty;
$def->properties['client_secret']->columnName = 'client_secret';
$def->properties['client_secret']->propertyName = 'clientSecret';
$def->properties['client_secret']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['endpoint_uri'] = new ezcPersistentObjectProperty;
$def->properties['endpoint_uri']->columnName = 'endpoint_uri';
$def->properties['endpoint_uri']->propertyName = 'endPointUri';
$def->properties['endpoint_uri']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def;
?>