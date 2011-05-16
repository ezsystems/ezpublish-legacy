<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
