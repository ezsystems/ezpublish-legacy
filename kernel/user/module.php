<?php
//
// Created on: <30-Apr-2002 12:36:36 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

$Module = array( 'name' => 'User management',
                 'variable_params' => true );

$ViewList = array();
$ViewList['logout'] = array(
    'script' => 'logout.php',
    'params' => array( ) );
$ViewList['login'] = array(
    'script' => 'login.php',
    'default_action' => array( array( 'name' => 'Login',
                                      'type' => 'post',
                                      'parameters' => array( 'Login',
                                                             'Password' ) ) ),
    'single_post_actions' => array( 'LoginButton' => 'Login' ),
    'post_action_parameters' => array( 'Login' => array( 'UserLogin' => 'Login',
                                                         'UserPassword' => 'Password',
                                                         'UserRedirectURI' => 'RedirectURI' ) ),
    'params' => array( ) );
$ViewList['setting'] = array(
    'functions' => array( 'preferences' ),
    'script' => 'setting.php',
    'params' => array( 'UserID' ) );

$ViewList['preferences'] = array(
    'functions' => array( 'login' ),
    'script' => 'preferences.php',
    'params' => array( 'Function', 'Key', 'Value' ) );

$ViewList['password'] = array(
    'functions' => array( 'password' ),
    'script' => 'password.php',
    'default_navigation_part' => 'ezmynavigationpart',
    'params' => array( 'UserID' ) );

/// \deprecated This view is kept for compatability
$ViewList['forgetpassword'] = array(
    'functions' => array( 'password' ),
    'script' => 'forgotpassword.php',
    'deprecated' => true,
    'params' => array( ),
    'single_post_actions' => array( 'GenerateButton' => 'Generate' ),
    'post_action_parameters' => array( 'Generate' => array( 'Login' => 'UserLogin',
                                                            'Email' => 'UserEmail' ) ),
    'params' => array( 'HashKey' ) );

/// Note the function above is misspelled and should be removed
$ViewList['forgotpassword'] = array(
    'functions' => array( 'password' ),
    'script' => 'forgotpassword.php',
    'params' => array( ),
    'single_post_actions' => array( 'GenerateButton' => 'Generate' ),
    'post_action_parameters' => array( 'Generate' => array( 'Login' => 'UserLogin',
                                                            'Email' => 'UserEmail' ) ),
    'params' => array( 'HashKey' ) );

$ViewList['edit'] = array(
    'function' => array( 'login' ),
    'script' => 'edit.php',
    'single_post_actions' => array( 'ChangePasswordButton' => 'ChangePassword',
                                    'ChangeSettingButton' => 'ChangeSetting',
                                    'CancelButton' => 'Cancel',
                                    'EditButton' => 'Edit' ),
    'params' => array( 'UserID' ) );

$ViewList['register'] = array(
    'script' => 'register.php',
    'params' => array( ),
    'default_navigation_part' => 'ezmynavigationpart',
    'single_post_actions' => array( 'PublishButton' => 'Publish',
                                    'CancelButton' => 'Cancel',
                                    'CustomActionButton' => 'CustomAction' ) );

$ViewList['activate'] = array(
    'script' => 'activate.php',
    'params' => array( 'Hash' ) );

$ViewList['success'] = array(
    'script' => 'success.php',
    'default_navigation_part' => 'ezmynavigationpart',
    'params' => array( ) );


$SiteAccess = array(
    'name'=> 'SiteAccess',
    'values'=> array(),
    'path' => 'classes/',
    'file' => 'ezsiteaccess.php',
    'class' => 'eZSiteAccess',
    'function' => 'siteAccessList',
    'parameter' => array()
    );

$FunctionList['login'] = array( 'SiteAccess' => $SiteAccess );
$FunctionList['selfedit'] = array();
$FunctionList['password'] = array();
$FunctionList['preferences'] = array();

?>
