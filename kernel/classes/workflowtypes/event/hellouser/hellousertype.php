<?php
//
// Definition of helloUserType class
//
// Created on: <02-äÅË-2002 13:48:06 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file hellousertype.php
*/

/*!
  \class helloUserType hellousertype.php
  \brief The class helloUserType does

*/
define( "EZ_WORKFLOW_TYPE_HELLO_USER_ID", "hellouser" );

class helloUserType extends eZWorkflowEventType
{
    /*!
     Constructor
    */
    function helloUserType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_HELLO_USER_ID, ezi18n( 'kernel/workflow/event', "Hello User" ) );
    }

    function execute( &$process, &$event )
    {
        $user =& eZUser::currentUser();
        $userName =& $user->attribute( 'login' );
        $localhostAddr = eZSys::hostname();
        $requestUri = eZSys::serverVariable( 'REQUEST_URI' );
        $process->Template = array( 'templateName' => 'design:workflow/eventtype/result/' . 'event_hellouser' . '.tpl',
                                     'templateVars' => array( 'return_uri' => $requestUri,
                                                              'user_name' => $userName ) );
        return EZ_WORKFLOW_TYPE_STATUS_FETCH_TEMPLATE;
    }
}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_HELLO_USER_ID, "hellousertype" );


?>
