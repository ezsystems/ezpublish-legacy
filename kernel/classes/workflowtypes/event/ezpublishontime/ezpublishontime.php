<?php
//
// Definition of eZPublishOnTime class
//
// Created on: <09-Jan-2003 16:20:05 sp>
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

/*! \file ezpublishontime.php
*/

/*!
  \class eZPublishOnTime ezpublishontime.php
  \brief The class eZPublishOnTime does

*/
include_once( 'kernel/classes/workflowtypes/event/ezpublishontime/ezpublishontimevalue.php' );

class eZPublishOnTime
{
    function eZPublishOnTime( $eventID, $eventVersion )
    {
        $this->WorkflowEventID = $eventID;
        $this->WorkflowEventVersion = $eventVersion;
        $this->Entries =& eZPublishOnTimeValue::fetchAllElements( $eventID, $eventVersion );
    }

    function hasAttribute( $attr )
    {
        return $attr == 'workflow_event_id'
            or $attr == 'workflow_event_version'
            or $attr == 'entry_list'
            or $attr == 'classattribute_id_list';
    }

    function &attribute( $attr )
    {
        switch ( $attr )
        {
            case "workflow_event_id" :
            {
                return $this->WorkflowEventID;
            }break;
            case "workflow_event_version" :
            {
                return $this->WorkflowEventVersion;
            }break;
            case "entry_list" :
            {
                return $this->Entries;
            }break;
            case 'classattribute_id_list' :
            {
                return $this->classAttributeIDList();
            }
            default :
            {
                eZDebug::writeError( "Unknown attribute: " . $attr );
            }break;
        }
    }
    function removePublishOnTimeEntries( $workflowEventID, $workflowEventVersion )
    {
         eZPublishOnTimeValue::removeAllElements( $workflowEventID, $workflowEventVersion );
    }
    /*!
     Adds an enumeration
    */
    function addEntry( $contentClassAttributeID, $contentClassID = false )
    {
        if ( !$contentClassID )
        {
            $contentClassAttribute = eZContentClassAttribute::fetch( $contentClassAttributeID );
            $contentClassID = $contentClassAttribute->attribute( 'contentclass_id' );
        }
        $publishOnTimeValue =& eZPublishOnTimeValue::create( $this->WorkflowEventID, $this->WorkflowEventVersion, $contentClassAttributeID, $contentClassID );
        $publishOnTimeValue->store();
        $this->Entries =& eZPublishOnTimeValue::fetchAllElements( $this->WorkflowEventID, $this->WorkflowEventVersion );
    }

    function removeEntry( $workflowEventID, $id, $version )
    {
        eZDebug::writeDebug( "$workflowEventID - $id - $version ", 'remove params 2' );

       eZPublishOnTimeValue::remove( $id, $version );
       $this->Entries =& eZPublishOnTimeValue::fetchAllElements( $workflowEventID, $version );
    }

    function &classAttributeIDList()
    {
        $attributeIDList = array();
        foreach ( array_keys( $this->Entries ) as $key )
        {
            $entry =& $this->Entries[$key];
            $attributeIDList[] = $entry->attribute( 'contentclass_attribute_id' );
        }
        return $attributeIDList;
    }

    function setVersion( $version )
    {
        eZPublishOnTimeValue::removeAllElements( $this->WorkflowEventID, 0 );
        for ( $i = 0; $i < count( $this->Entries ); $i++ )
        {
            $entry =& $this->Entries[$i];
            $oldversion = $entry->attribute ( "workflow_event_version" );
            $id = $entry->attribute( "id" );
            $workflowEventID = $entry->attribute( "workflow_event_id" );
            $contentClassID = $entry->attribute( "contentclass_id" );
            $contentClassAttributeID = $entry->attribute( "contentclass_attribute_id" );
            $entryCopy =& eZPublishOnTimeValue::createCopy( $id,
                                                            $workflowEventID,
                                                            0,
                                                            $contentClassID,
                                                            $contentClassAttributeID );
            eZDebug::writeDebug( $entryCopy, "entryCopy" );
            $entryCopy->store();
            if ( $oldversion != $version )
            {
                $entry->setAttribute("contentclass_attribute_version", $version );
                $entry->store();
            }
        }
    }


    var $WorkflowEventID;
    var $WorkflowEventVersion;
    var $Entries;

}


?>
