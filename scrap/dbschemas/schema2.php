<?php
	$def = array();

	$table = array (
		'fields' => array (
			'id'      => array ('type' => 'auto_increment'),
			'name_id' => array ('type' => 'char', 'length' => '255', 'not_null' => 1),
			'weight'  => array ('type' => 'float', 'default' => '72')
		),
		'indexes' => array (
			'primary' => array ('type' => 'primary', 'fields' => array ('id')),
			'search'  => array ('type' => 'unique',  'fields' => array ('name', 'weight')),
			'name'    => array ('type' => 'foreign', 'fields' => array ('name_id'), 'link_table' => 'names', 'link_fields' => array ('id')),
		)
	);
	$def['weight'] = $table;

	return $def;
?>
