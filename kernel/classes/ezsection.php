<?php
//
// Definition of eZSection class
//
// Created on: <27-Aug-2002 15:55:18 bf>
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

/*!
  \class eZSection ezsection.php
  \brief eZSection handles grouping of content in eZ publish

*/

include_once( "kernel/classes/ezpersistentobject.php" );

class eZSection extends eZPersistentObject
{
    /*!
    */
    function eZSection( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZCard class.
    */
    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "name" => "Name",
                                         "locale" => "Locale"
                                         ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZSection",
                      "name" => "ezsection" );
    }

    /*!
     \return the section object with the given id.
    */
    function &fetch( $sectionID, $as_object = true )
    {
        return eZPersistentObject::fetchObject( eZSection::definition(),
                                                null,
                                                array( "id" => $sectionID
                                                      ),
                                                $as_object );
    }

    function &fetchList( $as_object = true )
    {
        return eZPersistentObject::fetchObjectList( eZSection::definition(),
                                                    null, null, null, null,
                                                    $as_object );
    }

    function attribute( $attr )
    {
        return eZPersistentObject::attribute( $attr );
    }

    function hasAttribute( $attr )
    {
        return eZPersistentObject::hasAttribute( $attr );
    }

    /*!
     Will remove the current section from the database.
    */
    function remove( )
    {
        $def =& $this->definition();

        eZPersistentObject::removeObject( $def, array( "id" => $this->ID ) );
    }

}

?>
