<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
