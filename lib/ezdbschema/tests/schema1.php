<?php
	$def = array();

	$table = array (
		'fields' => array (
			'id'      => array ('type' => 'auto_increment'),
			'name_id' => array ('type' => 'char', 'length' => '64', 'not_null' => 1),
			'weight'  => array ('type' => 'float', 'default' => '75')
		),
		'indexes' => array (
			'primary' => array ('type' => 'primary', 'fields' => array ('id')),
			'search'  => array ('type' => 'unique',  'fields' => array ('name', 'weight')),
			'name'    => array ('type' => 'foreign', 'fields' => array ('name_id'), 'link_table' => 'names', 'link_fields' => array ('id')),
		)
	);
	$def['weight'] = $table;

	$table = array (
		'fields' => array (
			'id'    => array ('type' => 'char', 'length' => 32, 'not_null' => 1),
			'first' => array ('type' => 'char', 'length' => 64, 'not_null' => 1),
			'last'  => array ('type' => 'char', 'length' => 64, 'not_null' => 1)
		),
		'indexes' => array (
			'primary' => array ('type' => 'primary', 'fields' => array ('id')),
			'full'    => array ('type' => 'unique',  'fields' => array ('first', 'last'))
		)
	);
	$def['name'] = $table;

	return $def;
?>
