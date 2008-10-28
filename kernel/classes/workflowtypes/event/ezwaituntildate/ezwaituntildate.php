<?php
//
// Definition of eZWaitUntilDate class
//
// Created on: <09-Jan-2003 16:20:05 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezwaituntildate.php
*/

/*!
  \class eZWaitUntilDate ezwaituntildate.php
  \brief The class eZWaitUntilDate does

*/
class eZWaitUntilDate
{
    function eZWaitUntilDate( $eventID, $eventVersion )
    {
        $this->WorkflowEventID = $eventID;
        $this->WorkflowEventVersion = $eventVersion;
        $this->Entries = eZWaitUntilDateValue::fetchAllElements( $eventID, $eventVersion );
    }

    function attributes()
    {
        return array( 'workflow_event_id',
                      'workflow_event_version',
                      'entry_list',
                      'classattribute_id_list' );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
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
                eZDebug::writeError( "Attribute '$attr' does not exist", 'eZWaitUntilDate::attribute' );
                return null;
            }break;
        }
    }
    static function removeWaitUntilDateEntries( $workflowEventID, $workflowEventVersion )
    {
         eZWaitUntilDateValue::removeAllElements( $workflowEventID, $workflowEventVersion );
    }
    /*!
     Adds an enumeration
    */
    function addEntry( $contentClassAttributeID, $contentClassID = false )
    {
        if ( !isset( $contentClassAttributeID ) )
        {
            return;
        }
        if ( !$contentClassID )
        {
            $contentClassAttribute = eZContentClassAttribute::fetch( $contentClassAttributeID );
            $contentClassID = $contentClassAttribute->attribute( 'contentclass_id' );
        }
        // Checking if $contentClassAttributeID and $contentClassID already exist (in Entries)
        foreach ( $this->Entries as $entrie )
        {
            if ( $entrie->attribute( 'contentclass_attribute_id' ) == $contentClassAttributeID and
                 $entrie->attribute( 'contentclass_id' ) == $contentClassID )
                return;
        }
        $waitUntilDateValue = eZWaitUntilDateValue::create( $this->WorkflowEventID, $this->WorkflowEventVersion, $contentClassAttributeID, $contentClassID );
        $waitUntilDateValue->store();
        $this->Entries = eZWaitUntilDateValue::fetchAllElements( $this->WorkflowEventID, $this->WorkflowEventVersion );
    }

    function removeEntry( $workflowEventID, $id, $version )
    {
       eZWaitUntilDateValue::removeByID( $id, $version );
       $this->Entries = eZWaitUntilDateValue::fetchAllElements( $workflowEventID, $version );
    }

    function classAttributeIDList()
    {
        $attributeIDList = array();
        foreach ( $this->Entries as $entry )
        {
            $attributeIDList[] = $entry->attribute( 'contentclass_attribute_id' );
        }
        return $attributeIDList;
    }

    function setVersion( $version )
    {
        if ( $version == 1 && count( $this->Entries ) == 0 )
        {
            $this->Entries = eZWaitUntilDateValue::fetchAllElements( $this->WorkflowEventID, 0 );
            foreach( $this->Entries as $entry )
            {
                $entry->setAttribute( "workflow_event_version", 1 );
                $entry->store();
            }
        }
        if ( $version == 0 )
        {
            eZWaitUntilDateValue::removeAllElements( $this->WorkflowEventID, 0 );
            foreach( $this->Entries as $entry )
            {
                $oldversion = $entry->attribute( "workflow_event_version" );
                $id = $entry->attribute( "id" );
                $workflowEventID = $entry->attribute( "workflow_event_id" );
                $contentClassID = $entry->attribute( "contentclass_id" );
                $contentClassAttributeID = $entry->attribute( "contentclass_attribute_id" );
                $entryCopy = eZWaitUntilDateValue::createCopy( $id,
                                                               $workflowEventID,
                                                               0,
                                                               $contentClassID,
                                                               $contentClassAttributeID );

                $entryCopy->store();
            }
        }
    }


    public $WorkflowEventID;
    public $WorkflowEventVersion;
    public $Entries;

}


?>
