<?php
/**
 * File containing function definition for LanguageSwitcher module
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$FunctionList = array();
$FunctionList['url_alias'] = array( 'name' => 'url_alias',
                                    'call_method' => array( 'class' => 'ezpLanguageSwitcherFunctionCollection',
                                                            'method' => 'fetchUrlAlias' ),
                                    'parameters' => array(
                                                           array( 'name' => 'node_id',
                                                                  'type' => 'integer',
                                                                  'default' => false,
                                                                  'required' => false ),

                                                           array( 'name' => 'path',
                                                                  'type' => 'string',
                                                                  'default' => false,
                                                                  'required' => false ),

                                                           array( 'name' => 'locale',
                                                                  'type' => 'string',
                                                                  'default' => false,
                                                                  'required' => true ), ) );

?>
