<?php
	$def = array();

	$table = array (
		'fields' => array (
			'id'      => array ('type' => 'auto_increment'),
			'weight'  => array ('type' => 'float', 'default' => '72'),
			'length'  => array ('type' => 'int',   'default' => '180')
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
