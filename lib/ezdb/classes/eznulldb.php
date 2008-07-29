<?php
//
// $Id$
//
// Definition of eZNullDB class
//
// Created on: <12-Feb-2002 15:54:17 bf>
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

/*!
  \class eZNullDB eznulldb.php
  \ingroup eZDB
  \brief The eZNullDB class provides a interface which does nothing

  This class is returned when a proper implementation could not be found.
*/

//include_once( 'lib/ezdb/classes/ezdbinterface.php' );

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
    function query( $sql, $server = false )
    {
        return false;
    }

    /*!
      Returns false.
    */
    function arrayQuery( $sql, $params = array(), $server = false )
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
    function lastSerialID( $table = false, $column = false )
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
