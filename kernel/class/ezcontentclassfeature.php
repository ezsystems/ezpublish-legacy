<?php
//
// Definition of eZContentClassFeature class
//
// Created on: <17-Jun-2002 13:58:12 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

/*! \file ezcontentclassfeature.php
*/

/*!
  \class eZContentClassFeature ezcontentclassfeature.php
  \brief The class eZContentClassFeature does

*/

include_once( "kernel/classes/ezcontentclass.php" );

class eZContentClassFeature
{
    /*!
     Constructor
    */
    function eZContentClassFeature()
    {
        $this->SortField = "id";
        $this->SortAscending = true;
        $this->SortFields = array( "sort_by_id" => "id",
                                   "sort_by_name" => "name",
                                   "sort_by_identifier" => "identifier",
                                   "sort_by_status" => "status",
                                   "sort_by_creator" => "creator",
                                   "sort_by_modifier" => "modifier",
                                   "sort_by_created" => "created",
                                   "sort_by_modified" => "modified" );

    }

    function attributes()
    {
        return array_merge( array( "mixed_list", "temporary_list", "defined_list", "asc", "desc" ),
                            array_keys( $this->SortFields ) );
    }

    function hasAttribute( $attr )
    {
        return ( is_numeric( $attr ) or
                 $attr == "mixed_list" or $attr == "temporary_list" or $attr == "defined_list" or
                 $attr == "asc" or $attr == "desc" or
                 isset( $this->SortFields[$attr] ) );
    }

    function &attribute( $attr )
    {
        if ( is_numeric( $attr ) )
        {
            $id = $attr;
            $obj =& eZContentClass::fetch( $id );
            return $obj;
        }
        else
        {
            $obj = null;
            switch( $attr )
            {
                case "mixed_list":
                {
                    $obj =& eZContentClass::fetchList( null, true, false,
                                                       array( $this->SortField => ( $this->SortAscending ? "asc" : "desc" ) ) );
                } break;
                case "list":
                {
                    $obj =& eZContentClass::fetchList( null, true, false,
                                                       array( $this->SortField => ( $this->SortAscending ? "asc" : "desc" ) ) );
                } break;
                case "defined_list":
                {
                    $obj =& eZContentClass::fetchList( 0, true, false,
                                                       array( $this->SortField => ( $this->SortAscending ? "asc" : "desc" ) ) );
                } break;
                case "temporary_list":
                {
                    $obj =& eZContentClass::fetchList( 1, true, false,
                                                       array( $this->SortField => ( $this->SortAscending ? "asc" : "desc" ) ) );
                } break;
                case "sort_by_id":
                case "sort_by_name":
                case "sort_by_identifier":
                case "sort_by_status":
                case "sort_by_creator":
                case "sort_by_modifier":
                case "sort_by_created":
                case "sort_by_modified":
                {
                    $this->SortField = $this->SortFields[$attr];
                    $obj =& $this;
                } break;
                case "asc":
                case "desc":
                {
                    $this->SortAscending = $attr == "asc";
                    $obj =& $this;
                } break;
            }
            return $obj;
        }
    }

    /// \privatesection
    var $SortField;
    var $SortFields;
    var $SortAscending;
}

?>
