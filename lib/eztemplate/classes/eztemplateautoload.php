<?php
//
// Created on: <05-Oct-2002 20:15:32 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
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
                                    'operator_names' => array( 'array', 'hash' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateexecuteoperator.php',
                                    'class' => 'eZTemplateExecuteOperator',
                                    'operator_names' => array( 'fetch' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatelocaleoperator.php',
                                    'class' => 'eZTemplateLocaleOperator',
                                    'operator_names' => array( 'l10n', 'currentdate' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateattributeoperator.php',
                                    'class' => 'eZTemplateAttributeOperator',
                                    'operator_names' => array( 'attribute' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatetextoperator.php',
                                    'class' => 'eZTemplateTextOperator',
                                    'operator_names' => array( 'concat' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateunitoperator.php',
                                    'class' => 'eZTemplateUnitOperator',
                                    'operator_names' => array( 'si' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatelogicoperator.php',
                                    'class' => 'eZTemplateLogicOperator',
                                    'operator_names' => array( 'lt', 'gt', 'le',
                                                               'ge', 'eq', 'null',
                                                               'not', 'true', 'false',
                                                               'or', 'and', 'choose' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatetypeoperator.php',
                                    'class' => 'eZTemplateTypeOperator',
                                    'operator_names' => array( 'is_array' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplatearithmeticoperator.php',
                                    'class' => 'eZTemplateArithmeticOperator',
                                    'operator_names' => array( 'sum', 'sub', 'inc', 'dec',
                                                               'div', 'mod', 'mul',
                                                               'max', 'min',
                                                               'abs', 'ceil', 'floor', 'round' ) );
$eZTemplateOperatorArray[] = array( 'script' => 'lib/eztemplate/classes/eztemplateimageoperator.php',
                                    'class' => 'eZTemplateImageOperator',
                                    'operator_names' => array( 'texttoimage', 'image', 'imagefile' ) );

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

// eZTemplatePHPOperator is not autoload due to it's generic use
// it's up to the users of eZTemplate to initiate a proper usage
// for this operator class.

?>
