<?php
//
// Created on: <07-Jul-2003 13:42:06 jhe>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

$steps = array( 'basic' => array( 'template' => 'datatype_basic.tpl',
                                  'function' => 'datatypeBasic' ),
                'describe' => array( 'pre_function' => 'datatypeBasicFetchData',
                                     'template' => 'datatype_describe.tpl',
                                     'function' => 'datatypeDescribe' ),
                'download' => array( 'pre_function' => 'datatypeDescribeFetchData',
                                     'function' => 'datatypeDownload' ) );

$template = 'datatype.tpl';

$http = eZHTTPTool::instance();

$persistentData = array();
if ( $http->hasPostVariable( 'PersistentData' ) )
    $persistentData = $http->postVariable( 'PersistentData' );

$currentStep = false;
if ( $http->hasPostVariable( 'OperatorStep' ) and
     $http->hasPostVariable( 'DatatypeStepButton' ) )
{
    $step = $http->postVariable( 'OperatorStep' );
    if ( isset( $steps[$step] ) )
    {
        $currentStep = $steps[$step];
        $currentStep['name'] = $step;
    }
}

if ( $http->hasPostVariable( 'DatatypeRestartButton' ) )
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
                                'text' => ezpI18n::translate( 'kernel/setup', 'Datatype wizard' ) ) );


function datatypeBasic( $tpl, &$persistentData, $stepData )
{
}

function datatypeBasicFetchData( $tpl, &$persistentData )
{
    $http = eZHTTPTool::instance();
    $datatypeName = false;
    if ( $http->hasPostVariable( 'Name' ) )
        $datatypeName = $http->postVariable( 'Name' );
    $parameterCheck = false;
    if ( $http->hasPostVariable( 'DescName' ) )
        $descName = $http->postVariable( 'DescName' );
    $classInput = false;
    if ( $http->hasPostVariable( 'ClassInput' ) )
        $classInput = true;

    $datatypeName = preg_replace( array( "#([a-z])([A-Z])#",
                                         "#__+#",
                                         "#(^_|_$)#" ),
                                  array( '$1_$2',
                                         '_',
                                         '' ),
                                  $datatypeName );
    $datatypeName = strtolower( $datatypeName );

    if ( substr( $datatypeName, 0, 2 ) != "ez" )
        $extensionName = "ez" . $datatypeName;

    $persistentData['extension-name'] = $extensionName;
    $persistentData['name'] = $datatypeName;
    $persistentData['class-input'] = $classInput;
    $persistentData['desc-name'] = $descName;
}

function datatypeDescribe( $tpl, &$persistentData, $stepData )
{
    $datatypeName = $persistentData['name'];
    $classInput = $persistentData['class-input'];
    $descName = $persistentData['desc-name'];

    if ( substr( $datatypeName, 0, 2 ) != "ez" )
        $fullClassName = "ez" . $datatypeName;

    $persistentData['datatype-name'] = $fullClassName;

    if ( substr( $datatypeName, -4 ) != "type" )
        $fullClassName .= "type";

    $constantName = "DATA_TYPE_STRING";

    $tpl->setVariable( 'class_name', $fullClassName );
    $tpl->setVariable( 'datatype_name', $datatypeName );
    $tpl->setVariable( 'constant_name', $constantName );
    $tpl->setVariable( 'class_input', $classInput );
    $tpl->setVariable( 'desc-name', $descName );
}

function datatypeDescribeFetchData( $tpl, &$persistentData )
{
    $http = eZHTTPTool::instance();
    $className = false;
    if ( $http->hasPostVariable( 'ClassName' ) )
        $className = $http->postVariable( 'ClassName' );
    $constantName= false;
    if ( $http->hasPostVariable( 'ConstantName' ) )
        $constantName = $http->postVariable( 'ConstantName' );
    $creatorName = false;
    if ( $http->hasPostVariable( 'CreatorName' ) )
        $creatorName = $http->postVariable( 'CreatorName' );
    $description = false;
    if ( $http->hasPostVariable( 'Description' ) )
        $description = $http->postVariable( 'Description' );

    $persistentData['class-name'] = $className;
    $persistentData['constant-name'] = $constantName;
    $persistentData['creator-name'] = $creatorName;
    $persistentData['description'] = $description;
}

function datatypeDownload( $tpl, &$persistentData, $stepData )
{
    $datatypeName = $persistentData['name'];
    $classInput = $persistentData['class-input'];
    $descName = $persistentData['desc-name'];
    $className = $persistentData['class-name'];
    $constantName = $persistentData['constant-name'];
    $creator = $persistentData['creator-name'];
    $description = $persistentData['description'];
    $datatypeName = $persistentData['datatype-name'];

    $filename = strtolower( $className ) . '.php';

    $brief = '';
    $full = '';
    $lines = explode( "\n", $description );
    if ( count( $lines ) > 0 )
    {
        $brief = $lines[0];
        $full = implode( "\n", array_slice( $lines, 1 ) );
    }

    $tpl->setVariable( 'full_class_name', $className );
    $tpl->setVariable( 'constant_name', $constantName );
    $tpl->setVariable( 'datatype_name', $datatypeName );
    $tpl->setVariable( 'desc_name', $descName );
    $tpl->setVariable( 'file_name', $filename );
    $tpl->setVariable( 'creator_name', $creator );
    $tpl->setVariable( 'description_brief', $brief );
    $tpl->setVariable( 'description_full', $full );
    $tpl->setVariable( 'class_input', $classInput );

    $content = $tpl->fetch( 'design:setup/datatype_code.tpl' );

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
