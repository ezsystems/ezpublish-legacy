<?php
/**
 * File containing the eZNullDB class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZNullDB eznulldb.php
  \ingroup eZDB
  \brief The eZNullDB class provides a interface which does nothing

  This class is returned when a proper implementation could not be found.
*/

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

    /**
     * Returns false
     *
     * @param string|bool $table
     * @param string|bool $column
     * @return bool
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
