<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$FunctionList = array();
$FunctionList['role'] = array( 'name' => 'role',
                               'operation_types' => array( 'read' ),
                               'call_method' => array( 'class' => 'eZRoleFunctionCollection',
                                                       'method' => 'fetchRole' ),
                               'parameter_type' => 'standard',
                               'parameters' => array( array( 'name' => 'role_id',
                                                             'type' => 'integer',
                                                             'required' => true ) ) );

?>
