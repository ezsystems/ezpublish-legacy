<?php
//
// Created on: <21-May-2003 14:49:27 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

$module = $Params['Module'];

require_once( "kernel/common/template.php" );
$ini = eZINI::instance();
$tpl = templateInit();

/*
- Name
- Single operator or multiple (single default)
- Parameter type (no, named, sequential, custom - no default)
- Input (yes or not, yes default)
- Output (yes or no, yes default)

- Class name (optional, auto created from name)
- Description (optional)
- Creator (optional)
- Example code(optional)

*/

$steps = array( 'basic' => array( 'template' => 'templateoperator_basic.tpl',
                                  'function' => 'templateOperatorBasic' ),
                'describe' => array( 'pre_function' => 'templateOperatorBasicFetchData',
                                     'template' => 'templateoperator_describe.tpl',
                                     'function' => 'templateOperatorDescribe' ),
                'download' => array( 'pre_function' => 'templateOperatorDescribeFetchData',
                                     'function' => 'templateOperatorDownload' ) );

$template = 'templateoperator.tpl';

$http = eZHTTPTool::instance();

$persistentData = array();
if ( $http->hasPostVariable( 'PersistentData' ) )
    $persistentData = $http->postVariable( 'PersistentData' );

$currentStep = false;
if ( $http->hasPostVariable( 'OperatorStep' ) and
     $http->hasPostVariable( 'TemplateOperatorStepButton' ) )
{
    $step = $http->postVariable( 'OperatorStep' );
    if ( isset( $steps[$step] ) )
    {
        $currentStep = $steps[$step];
        $currentStep['name'] = $step;
    }
}

if ( $http->hasPostVariable( 'TemplateOperatorRestartButton' ) )
{
    $currentStep = false;
    $persistentData = array();
}

if ( $currentStep )
{
    if ( isset( $currentStep['pre_function'] ) )
    {
        $preFunctionName = $currentStep['pre_function'];
        if ( function_exists( $preFunctionName ) )
        {
            $preFunctionName( $tpl, $persistentData );
        }
        else
        {
            eZDebug::writeWarning( 'Unknown pre step function ' . $preFunctionName );
        }
    }
    if ( isset( $currentStep['function'] ) )
    {
        $functionName = $currentStep['function'];
        if ( function_exists( $functionName ) )
        {
            $functionName( $tpl, $persistentData, $currentStep );
        }
        else
        {
            eZDebug::writeWarning( 'Unknown step function ' . $functionName );
        }
    }
    if ( isset( $currentStep['template'] ) )
    {
        $template = $currentStep['template'];
    }
}

$tpl->setVariable( 'persistent_data', $persistentData );

$Result = array();
$Result['content'] = $tpl->fetch( "design:setup/$template" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/setup', 'Template operator wizard' ) ) );


function templateOperatorBasic( $tpl, &$persistentData, $stepData )
{
}

function templateOperatorBasicFetchData( $tpl, &$persistentData )
{
    $http = eZHTTPTool::instance();
    $operatorName = false;
    if ( $http->hasPostVariable( 'Name' ) )
        $operatorName = $http->postVariable( 'Name' );
    $singleOperator = false;
    if ( $http->hasPostVariable( 'SingleOperatorCheck' ) )
        $singleOperator = true;
    $useInput = false;
    if ( $http->hasPostVariable( 'InputCheck' ) )
        $useInput = true;
    $useOutput = false;
    if ( $http->hasPostVariable( 'OutputCheck' ) )
        $useOutput = true;
    $parameterCheck = 1;
    if ( $http->hasPostVariable( 'Parameter' ) )
        $parameterCheck = $http->postVariable( 'Parameter' );

    $operatorName = preg_replace( array( "#([a-z])([A-Z])#",
                                         "#__+#",
                                         "#(^_|_$)#" ),
                                  array( '$1_$2',
                                         '_',
                                         '' ),
                                  $operatorName );
    $operatorName = strtolower( $operatorName );

    $persistentData['name'] = $operatorName;
    $persistentData['single-operator'] = $singleOperator;
    $persistentData['use-input'] = $useInput;
    $persistentData['use-output'] = $useOutput;
    $persistentData['parameter-check'] = $parameterCheck;
}

function templateOperatorDescribe( $tpl, &$persistentData, $stepData )
{
    $operatorName = $persistentData['name'];
    $fullClassName = 'Template' . strtoupper( $operatorName[0] ) . substr( $operatorName, 1 ) . 'Operator';

    $singleOperator = $persistentData['single-operator'];
    $useInput = $persistentData['use-input'];
    $useOutput = $persistentData['use-output'];
    $parameterCheck = $persistentData['parameter-check'];
    $exampleCode = '{';
    if ( $useInput )
        $exampleCode .= "\$value|";
    $exampleCode .= $operatorName;
    if ( $parameterCheck != 1 )
        $exampleCode .= '(\'first\',$input2)';
    else if ( !$useInput and !$useOutput )
        $exampleCode .= '()';
    if ( $useOutput )
        $exampleCode .= "|wash";
    $exampleCode .= '}';

    $tpl->setVariable( 'class_name', $fullClassName );
    $tpl->setVariable( 'example_code', $exampleCode );
    $tpl->setVariable( 'operator_name', $operatorName );
    $tpl->setVariable( 'single_operator', $singleOperator );
    $tpl->setVariable( 'use_input', $useInput );
    $tpl->setVariable( 'use_output', $useOutput );
    $tpl->setVariable( 'parameter_check', $parameterCheck );
}

function templateOperatorDescribeFetchData( $tpl, &$persistentData )
{
    $http = eZHTTPTool::instance();
    $className = false;
    if ( $http->hasPostVariable( 'ClassName' ) )
        $className = $http->postVariable( 'ClassName' );
    $description = false;
    if ( $http->hasPostVariable( 'Description' ) )
        $description = $http->postVariable( 'Description' );
    $creatorName = false;
    if ( $http->hasPostVariable( 'CreatorName' ) )
        $creatorName = $http->postVariable( 'CreatorName' );
    $exampleCode = false;
    if ( $http->hasPostVariable( 'ExampleCode' ) )
        $exampleCode = $http->postVariable( 'ExampleCode' );

    $persistentData['class-name'] = $className;
    $persistentData['description'] = $description;
    $persistentData['creator-name'] = $creatorName;
    $persistentData['example-code'] = $exampleCode;
}

function templateOperatorDownload( $tpl, &$persistentData, $stepData )
{
    $singleOperator = $persistentData['single-operator'];
    $useInput = $persistentData['use-input'];
    $useOutput = $persistentData['use-output'];
    $parameterCheck = $persistentData['parameter-check'];
    $useInput = true;
    $useOutput = false;
    $parameterCheck = 2;

    $operatorName = $persistentData['name'];
    $className = $persistentData['class-name'];
    if ( !$className )
        $fullClassName = 'Template' . strtoupper( $operatorName[0] ) . substr( $operatorName, 1 ) . 'Operator';
    else
        $fullClassName = $className;
    $filename = strtolower( $fullClassName ) . '.php';

    $description = $persistentData['description'];
    $creator = $persistentData['creator-name'];
    $example = $persistentData['example-code'];

    $brief = '';
    $full = '';
    $lines = explode( "\n", $description );
    if ( count( $lines ) > 0 )
    {
        $brief = $lines[0];
        $full = implode( "\n", array_slice( $lines, 1 ) );
    }

    $tpl->setVariable( 'full_class_name', $fullClassName );
    $tpl->setVariable( 'class_name', $className );
    $tpl->setVariable( 'file_name', $filename );
    $tpl->setVariable( 'operator_name', $operatorName );
    $tpl->setVariable( 'example_code', $example );
    $tpl->setVariable( 'creator_name', $creator );
    $tpl->setVariable( 'description_brief', $brief );
    $tpl->setVariable( 'description_full', $full );

    $tpl->setVariable( 'single_operator', $singleOperator );
    $tpl->setVariable( 'use_input', $useInput );
    $tpl->setVariable( 'use_output', $useOutput );
    $tpl->setVariable( 'parameter_check', $parameterCheck );

    $content = $tpl->fetch( 'design:setup/templateoperator_code.tpl' );

    $contentLength = strlen( $content );
    $mimeType = 'application/octet-stream';

    $version = eZPublishSDK::version();

    header( "Pragma: " );
    header( "Cache-Control: " );
    header( "Content-Length: $contentLength" );
    header( "Content-Type: $mimeType" );
    header( "X-Powered-By: eZ Publish $version" );
    header( "Content-Disposition: attachment; filename=$filename" );
    header( "Content-Transfer-Encoding: binary" );
    ob_end_clean();
    print( $content );
    fflush();
    eZExecution::cleanExit();
}

?>
