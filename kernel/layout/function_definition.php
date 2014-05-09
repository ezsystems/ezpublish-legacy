<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$FunctionList = array();
$FunctionList['sitedesign_list'] = array( 'name' => 'sitedesign_list',
                                          'operation_types' => array( 'read' ),
                                          'call_method' => array( 'include_file' => 'kernel/layout/ezlayoutfunctioncollection.php',
                                                                  'class' => 'eZLayoutFunctionCollection',
                                                                  'method' => 'fetchSitedesignList' ),
                                          'parameter_type' => 'standard',
                                          'parameters' => array( ) );

?>
