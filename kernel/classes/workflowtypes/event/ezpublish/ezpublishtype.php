<?php
//
// Definition of eZPublishType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
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

/*!
  \class eZPublishType ezpublishtype.php
  \brief Event type which publishes and object

*/

include_once( "kernel/classes/ezworkflowtype.php" );

define( "EZ_WORKFLOW_TYPE_PUBLISH_ID", "ezpublish" );

class eZPublishType extends eZWorkflowEventType
{
    function eZPublishType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_PUBLISH_ID, ezi18n( 'kernel/workflow/event', "Publish" ) );
    }

    function &eventDescription()
    {
        return "Publish object";
    }

    function execute( &$process, &$event )
    {
        $contentObject =& $process->attribute( "content" );
        if ( $contentObject !== null )
        {
            $isPublished = $event->attribute( "data_int1" );
            $contentObject->setAttribute( "current_version", $process->attribute( "content_version" ) );
            $contentObject->setAttribute( "is_published", $isPublished );
            if ( $isPublished )
                $this->setInformation( "Object was published with version " . $process->attribute( "content_version" ) );
            else
                $this->setInformation( "Object was unpublished" );
            $contentObject->store();
            return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
        }
        return EZ_WORKFLOW_TYPE_STATUS_REJECTED;
    }

    function initializeEvent( &$event )
    {
        $event->setAttribute( "data_int1", 1 );
    }

    function fetchHTTPInput( &$http, $base, &$event )
    {
        $publish_var = $base . "_event_ezpublish_publish_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $publish_var ) )
        {
            $publish_action = $http->postVariable( $publish_var );
            $event->setAttribute( "data_int1", $publish_action );
        }
    }
}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_PUBLISH_ID, "ezpublishtype" );

?>
