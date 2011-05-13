<?php
/**
 * File containing the definition of the RestClient persistent object.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$def = new ezcPersistentObjectDefinition();
$def->table = "ezprest_clients";
$def->class = "ezpRestClient";

$def->idProperty = new ezcPersistentObjectIdProperty;
$def->idProperty->columnName = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator = new ezcPersistentGeneratorDefinition( 'ezcPersistentSequenceGenerator', array( "sequence" => "ezprest_clients_s" ) );

$def->properties['name'] = new ezcPersistentObjectProperty;
$def->properties['name']->columnName = 'name';
$def->properties['name']->propertyName = 'name';
$def->properties['name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['description'] = new ezcPersistentObjectProperty;
$def->properties['description']->columnName = 'description';
$def->properties['description']->propertyName = 'description';
$def->properties['description']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['client_id'] = new ezcPersistentObjectProperty;
$def->properties['client_id']->columnName = 'client_id';
$def->properties['client_id']->propertyName = 'client_id';
$def->properties['client_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['client_secret'] = new ezcPersistentObjectProperty;
$def->properties['client_secret']->columnName = 'client_secret';
$def->properties['client_secret']->propertyName = 'client_secret';
$def->properties['client_secret']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['endpoint_uri'] = new ezcPersistentObjectProperty;
$def->properties['endpoint_uri']->columnName = 'endpoint_uri';
$def->properties['endpoint_uri']->propertyName = 'endpoint_uri';
$def->properties['endpoint_uri']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['owner_id'] = new ezcPersistentObjectProperty;
$def->properties['owner_id']->columnName = 'owner_id';
$def->properties['owner_id']->propertyName = 'owner_id';
$def->properties['owner_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['created'] = new ezcPersistentObjectProperty;
$def->properties['created']->columnName = 'created';
$def->properties['created']->propertyName = 'created';
$def->properties['created']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['updated'] = new ezcPersistentObjectProperty;
$def->properties['updated']->columnName = 'updated';
$def->properties['updated']->propertyName = 'updated';
$def->properties['updated']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['version'] = new ezcPersistentObjectProperty;
$def->properties['version']->columnName = 'version';
$def->properties['version']->propertyName = 'version';
$def->properties['version']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

return $def;
?>
