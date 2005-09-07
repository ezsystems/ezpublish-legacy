<?php
//
// $Id$
//
// Definition of eZNullDB class
//
// Created on: <12-Feb-2002 15:54:17 bf>
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

/*!
  \class eZNullDB eznulldb.php
  \ingroup eZDB
  \brief The eZNullDB class provides a interface which does nothing

  This class is returned when a proper implementation could not be found.
*/

include_once( 'lib/ezdb/classes/ezdbinterface.php' );

class eZNullDB extends eZDBInterface
{
    /*!
      Does nothing.
    */
    function eZNullDB( $parameters )
    {
        $this->eZDBInterface( $parameters );
    }

    /*!
      Does nothing.
    */
    function databaseName()
    {
        return 'null';
    }

    /*!
      Returns false.
    */
    function query( $sql )
    {
        return false;
    }

    /*!
      Returns false.
    */
    function arrayQuery( $sql, $params = array() )
    {
        return false;
    }

    /*!
      Does nothing.
    */
    function lock( $table )
    {
    }

    /*!
      Does nothing.
    */
    function unlock()
    {
    }

    /*!
      Does nothing.
    */
    function begin()
    {
    }

    /*!
      Does nothing.
    */
    function commit()
    {
    }

    /*!
      Does nothing.
    */
    function rollback()
    {
    }

    /*!
      Returns false.
    */
    function lastSerialID( $table, $column )
    {
        return false;
    }

    /*!
      Returns $str.
    */
    function escapeString( $str )
    {
        return $str;
    }

    /*!
      Does nothing.
    */
    function close()
    {
    }
}

?>
