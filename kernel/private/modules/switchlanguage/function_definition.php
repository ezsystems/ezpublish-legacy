<?php
/**
 * File containing function definition for LanguageSwitcher module
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
