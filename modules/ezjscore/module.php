<?php
//
// Created on: <16-Jun-2008 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ JSCore extension for eZ Publish
// SOFTWARE RELEASE: 1.x
// COPYRIGHT NOTICE: Copyright (C) 2009 eZ Systems AS
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

$Module = array( 'name' => 'ezjsc Module and Views' );


$ViewList = array();

$ViewList['hello'] = array(
    'script' => 'hello.php',
    'params' => array( 'with_pagelayout' )
    );
    
$ViewList['call'] = array(
    'functions' => array( 'call' ),
    'script' => 'call.php',
    'params' => array( 'function_arguments', 'type', 'interval', 'debug' )
    );

$ViewList['run'] = array(
    'functions' => array( 'run' ),
    'script' => 'run.php',
    'params' => array( )
    );



$ezjscServerFunctionList = array(
    'name'=> 'FunctionList',
    'values'=> array()
    );

$iniFunctionList = eZINI::instance('ezjscore.ini')->variable( 'ezjscServer', 'FunctionList' );
foreach ( $iniFunctionList as $iniFunction )
{
    $ezjscServerFunctionList['values'][] = array(
               'Name' => $iniFunction,
               'value' => $iniFunction
    );
} 

$FunctionList = array();
$FunctionList['run'] = array();
$FunctionList['call'] = array( 'FunctionList' => $ezjscServerFunctionList );


?>