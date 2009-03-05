<?php
//
// Definition of eZImportLookupTable class
//
// Created on: <08-Mar-2004 16:09:21 kk>
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

/*! \file

  This class is for storing node and object id transformations which occur during import of objects.
*/

class eZImportLookupTable
{
    /*!
     Constructor
    */
    function eZImportLookupTable()
    {
        eZDebug::writeWarning( "The class " . __CLASS__ . " is deprecated and will be removed from future versions of eZ Publish." );
    }

    /*!
     Add node transformation lookup

     \param old node id
     \param old node path
     \param new node id
     \param new node path
    */
    function addNodeLookup( $oldNodeID, $oldNodePath, $newNodeID, $newNodePath )
    {
        $this->NodeIDTable[(string)$oldNodeID] = $newNodeID;
        $this->NodePathTable[(string)$oldNodePath] = $newNodePath;
    }

    /*!
     Add object transformation lookup

     \param old object id
     \param new object id
    */
    function addObjectLookup( $oldObjectID, $newObjectID )
    {
        $this->ObjectIDTable[(string)$oldObjectID] = $newObjectID;
    }

    /*!
     Get new Node id from old node id

     \param old node id

     \return new node id
    */
    function newNodeID( $oldNodeID )
    {
        return $this->NodeIDTable[(string)$oldNodeID];
    }

    /*!
     Get new Node path from old node path

     \param old node path

     \return new node path
    */
    function newNodePath( $oldNodePath )
    {
        return $this->NodePathTable[(string)$oldNodePath];
    }

    /*!
     Get new Object id from old object id

     \param old object id

     \return new object id
    */
    function newObjectID( $oldObjectID )
    {
        return $this->ObjectIDTable[(string)$oldObjectID];
    }

    /*!
     \static

     Fetch instance of eZImportLookupTable

     \param force new instance (optional), default false
    */
    static function instance( $forceNewInstance = false )
    {
        if ( $forceNewInstance === true )
        {
            $GLOBALS['eZImportLookupTable'] = new eZImportLookupTable();
        }

        $object =& $GLOBALS['eZImportLookupTable'];
        if ( !$object )
        {
            $GLOBALS['eZImportLookupTable'] = new eZImportLookupTable();
            $object =& $GLOBALS['eZImportLookupTable'];
        }

        return $object;
    }

    public $NodeIDTable = array();
    public $NodePathTable = array();
    public $ObjectIDTable = array();
    public $ObjectPathTable = array();
}
?>
