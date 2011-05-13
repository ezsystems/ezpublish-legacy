<?php
/**
 * Definition of eZTemplate benchmark
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$MarkDefinition = array( 'name' => 'eZTemplate',
                         'marks' => array() );

$MarkDefinition['marks'][] = array( 'name' => 'Template Compiler',
                                    'file' => 'ezmarktemplatecompiler.php',
                                    'class' => 'eZMarkTemplateCompiler' );

?>
