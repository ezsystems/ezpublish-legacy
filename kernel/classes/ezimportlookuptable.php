<?php
//
// Definition of eZImportLookupTable class
//
// Created on: <08-Mar-2004 16:09:21 kk>
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

/*! \file ezimportlookuptable.php

  This class is for storing node and object id transformations which occur during import of objects.
*/

class eZImportLookupTable
{
    /*!
     Constructor
    */
    function eZImportLookupTable()
    {
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
    function &instance( $forceNewInstance = false )
    {
        if ( $forceNewInstance === true )
        {
            $GLOBALS['eZImportLookupTable'] =& new eZImportLookupTable();
        }

        $object =& $GLOBALS['eZImportLookupTable'];
        if ( !$object )
        {
            $GLOBALS['eZImportLookupTable'] =& new eZImportLookupTable();
            $object =& $GLOBALS['eZImportLookupTable'];
        }

        return $object;
    }

    var $NodeIDTable = array();
    var $NodePathTable = array();
    var $ObjectIDTable = array();
    var $ObjectPathTable = array();
}
?>
