<?php
//
// Created on: <30-Apr-2002 12:36:36 bf>
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

$Module = array( "name" => "User management",
                 "variable_params" => true );

$ViewList = array();
$ViewList["logout"] = array(
    "script" => "logout.php",
    "params" => array( ) );
$ViewList["login"] = array(
    "script" => "login.php",
    'default_action' => array( array( 'name' => 'Login',
                                      'type' => 'post',
                                      'parameters' => array( 'Login',
                                                             'Password' ) ) ),
    'single_post_actions' => array( 'LoginButton' => 'Login' ),
    'post_action_parameters' => array( 'Login' => array( 'UserLogin' => 'Login',
                                                         'UserPassword' => 'Password',
                                                         'UserRedirectURI' => 'RedirectURI' ) ),
    "params" => array( ) );
$ViewList["setting"] = array(
    "script" => "setting.php",
    "params" => array( "UserID" ) );
$ViewList["password"] = array(
    "script" => "password.php",
    "params" => array( "UserID" ) );
$ViewList["edit"] = array(
    "script" => "edit.php",
    "params" => array( "UserID" ) );
$ViewList["register"] = array(
    "script" => "register.php",
    "params" => array( ) );
$ViewList["activate"] = array(
    "script" => "activate.php",
    "params" => array( "LoginID","UserIDHash" ) );
$ViewList["success"] = array(
    "script" => "success.php",
    "params" => array( ) );

?>
