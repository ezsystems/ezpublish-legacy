<?php
//
// Created on: <05-Oct-2002 20:15:32 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file eztemplateautoload.php
*/

// Operator autoloading

$eZTemplateOperatorArray = array();
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatearrayoperator.php',
                                    'class' => 'eZTemplateArrayOperator',
                                    'operator_names' => array( 'array',
                                                               'hash',
                                                               'array_prepend', // DEPRECATED/OBSOLETE
                                                               'prepend',       // New,replaces array_prepend.
                                                               'array_append',  // DEPRECATED/OBSOLETE
                                                               'append',        // New,replaces array_append.
                                                               'array_merge',   // DEPRECATED/OBSOLETE
                                                               'merge',         // New,replaces array_merge.
                                                               'contains',
                                                               'compare',
                                                               'extract',
                                                               'extract_left',
                                                               'extract_right',
                                                               'begins_with',
                                                               'ends_with',
                                                               'implode',
                                                               'explode',
                                                               'repeat',
                                                               'reverse',
                                                               'insert',
                                                               'remove',
                                                               'replace',
                                                               'unique',
                                                               'array_sum'
                                                               ) );



$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateexecuteoperator.php',
                                    'class' => 'eZTemplateExecuteOperator',
                                    'operator_names' => array( 'fetch', 'fetch_alias' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatelocaleoperator.php',
                                    'class' => 'eZTemplateLocaleOperator',
                                    'operator_names' => array( 'l10n', 'locale', 'datetime', 'currentdate', 'maketime', 'makedate', 'gettime' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateattributeoperator.php',
                                    'class' => 'eZTemplateAttributeOperator',
                                    'operator_names' => array( 'attribute' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatenl2broperator.php',
                                    'class' => 'eZTemplateNl2BrOperator',
                                    'operator_names' => array( 'nl2br' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatetextoperator.php',
                                    'class' => 'eZTemplateTextOperator',
                                    'operator_names' => array( 'concat', 'indent' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateunitoperator.php',
                                    'class' => 'eZTemplateUnitOperator',
                                    'operator_names' => array( 'si' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatelogicoperator.php',
                                    'class' => 'eZTemplateLogicOperator',
                                    'operator_names' => array( 'lt', 'gt', 'le',
                                                               'ge', 'eq', 'ne', 'null',
                                                               'not', 'true', 'false',
                                                               'or', 'and', 'choose' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatetypeoperator.php',
                                    'class' => 'eZTemplateTypeOperator',
                                    'operator_names' => array( 'is_array', 'is_boolean', 'is_integer',
                                                               'is_float', 'is_numeric', 'is_string',
                                                               'is_object', 'is_class', 'is_null',
                                                               'is_set', 'is_unset', 'get_type', 'get_class' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatecontroloperator.php',
                                    'class' => 'eZTemplateControlOperator',
                                    'operator_names' => array( 'cond', 'first_set' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatearithmeticoperator.php',
                                    'class' => 'eZTemplateArithmeticOperator',
                                    'operator_names' => array( 'sum', 'sub', 'inc', 'dec',
                                                               'div', 'mod', 'mul',
                                                               'max', 'min',
                                                               'abs', 'ceil', 'floor', 'round',
                                                               'int', 'float',
                                                               'count',
                                                               'roman' ) );

$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateimageoperator.php',
                                    'class' => 'eZTemplateImageOperator',
                                    'operator_names' => array( 'texttoimage',
                                                               'image',
                                                               'imagefile' ) );


$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatestringoperator.php',
                                    'class' => 'eZTemplateStringOperator',
                                    'operator_names' => array( 'upcase',
                                                               'downcase',
                                                               'count_words',
                                                               'count_chars',
                                                               'trim',
                                                               'break',
                                                               'wrap',
                                                               'upfirst',
                                                               'upword',
                                                               'simplify',
                                                               'trim',
                                                               'wash',
                                                               'chr',
                                                               'ord',
                                                               'shorten',
                                                               'pad') );

$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatedigestoperator.php',
                                    'class' => 'eZTemplateDigestOperator',
                                    'operator_names' => array( 'crc32',
                                                               'md5',
                                                               'rot13', ) );



// Function autoloading

$eZTemplateFunctionArray = array();
$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatesectionfunction.php',
                                    'class' => 'eZTemplateSectionFunction',
                                    'function_names' => array( 'section' ),
                                    'function_attributes' => array( 'delimiter',
                                                                    'section-exclude',
                                                                    'section-include',
                                                                    'section-else' ) );
$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatedelimitfunction.php',
                                    'class' => 'eZTemplateDelimitFunction',
                                    'function_names' => array( 'ldelim', 'rdelim' ) );
$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateincludefunction.php',
                                    'class' => 'eZTemplateIncludeFunction',
                                    'function_names' => array( 'include' ) );
$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateswitchfunction.php',
                                    'class' => 'eZTemplateSwitchFunction',
                                    'function_names' => array( 'switch' ),
                                    'function_attributes' => array( 'case' ) );
$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatesequencefunction.php',
                                    'class' => 'eZTemplateSequenceFunction',
                                    'function_names' => array( 'sequence' ) );
$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatesetfunction.php',
                                    'class' => 'eZTemplateSetFunction',
                                    'function_names' => array( 'set', 'let', 'default' ) );
$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateblockfunction.php',
                                    'class' => 'eZTemplateBlockFunction',
                                    'function_names' => array( 'set-block', 'append-block', 'run-once' ) );

$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatedebugfunction.php',
                                    'class' => 'eZTemplateDebugFunction',
                                    'function_names' => array( 'debug-timing-point', 'debug-accumulator',
                                                               'debug-trace' ) );

$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatecachefunction.php',
                                    'class' => 'eZTemplateCacheFunction',
                                    'function_names' => array( 'cache-block' ) );

$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatetoolbarfunction.php',
                                    'class' => 'eZTemplateToolbarFunction',
                                    'function_names' => array( 'tool_bar' ) );

$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatemenufunction.php',
                                    'class' => 'eZTemplateMenuFunction',
                                    'function_names' => array( 'menu' ) );

// should we add 'break', 'continue' and 'skip' to the {if} attribute list?
$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateiffunction.php',
                                    'class' => 'eZTemplateIfFunction',
                                    'function_names' => array( 'if' ),
                                    'function_attributes' => array( 'elseif',
                                                                    'else' ) );
$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatewhilefunction.php',
                                    'class' => 'eZTemplateWhileFunction',
                                    'function_names' => array( 'while' ),
                                    'function_attributes' => array( 'delimiter',
                                                                    'break',
                                                                    'continue',
                                                                    'skip' ) );
$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateforfunction.php',
                                    'class' => 'eZTemplateForFunction',
                                    'function_names' => array( 'for' ),
                                    'function_attributes' => array( 'delimiter',
                                                                    'break',
                                                                    'continue',
                                                                    'skip' ) );

$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateforeachfunction.php',
                                    'class' => 'eZTemplateForeachFunction',
                                    'function_names' => array( 'foreach' ),
                                    'function_attributes' => array( 'delimiter',
                                                                    'break',
                                                                    'continue',
                                                                    'skip' ) );
$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatedofunction.php',
                                    'class' => 'eZTemplateDoFunction',
                                    'function_names' => array( 'do' ),
                                    'function_attributes' => array( 'delimiter',
                                                                    'break',
                                                                    'continue',
                                                                    'skip' ) );

$eZTemplateFunctionArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatedeffunction.php',
                                    'class' => 'eZTemplateDefFunction',
                                    'function_names' => array( 'def', 'undef' ) );


// eZTemplatePHPOperator is not autoload due to it's generic use
// it's up to the users of eZTemplate to initiate a proper usage
// for this operator class.

?>
