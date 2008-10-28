<?php
//
// Definition of eZOperationMemento class
//
// Created on: <06-Ноя-2002 16:19:18 sp>
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

/*! \file ezoperationmemento.php
*/

/*!
  \class eZOperationMemento ezoperationmemento.php
  \brief The class eZOperationMemento does

*/

class eZOperationMemento extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZOperationMemento( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'main' => array( 'name' => 'Main',
                                                          'datatype' => 'integer',
                                                          'default' => 0,
                                                          'required' => true ),
                                         'memento_key' => array( 'name' => 'MementoKey',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         'main_key' => array( 'name' => 'MainKey',
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ),
                                         'memento_data' => array( 'name' => 'MementoData',
                                                                  'datatype' => 'text',
                                                                  'default' => '',
                                                                  'required' => true ) ),
                      'function_attributes' => array( 'main_memento' => 'mainMemento' ),
                      'keys' => array( 'id' ),
                      "increment_key" => "id",
                      'class_name' => 'eZOperationMemento',
                      'name' => 'ezoperation_memento' );
    }

    function &mainMemento()
    {
        if ( !isset( $this->MainMemento ) )
        {
            $this->MainMemento = eZOperationMemento::fetchMain( $this->attribute( 'main_key' ) );
        }
        return $this->MainMemento;
    }

    static function fetch( $mementoKey, $asObject = true )
    {
        if ( is_array( $mementoKey ) )
        {
            $mementoKey = eZOperationMemento::createKey( $mementoKey );
        }

        return eZPersistentObject::fetchObject( eZOperationMemento::definition(),
                                                null,
                                                array( 'memento_key' => $mementoKey ),
                                                $asObject );
    }

    static function fetchChild( $mementoKey, $asObject = true )
    {
        if ( is_array( $mementoKey ) )
        {
            $mementoKey = eZOperationMemento::createKey( $mementoKey );
        }

        return eZPersistentObject::fetchObject( eZOperationMemento::definition(),
                                                null,
                                                array( 'memento_key' => $mementoKey,
                                                       'main' => 0 ),
                                                $asObject );
    }

    static function fetchMain( $mementoKey, $asObject = true )
    {
        if ( is_array( $mementoKey ) )
        {
            $mementoKey = eZOperationMemento::createKey( $mementoKey );
        }

        return eZPersistentObject::fetchObject( eZOperationMemento::definition(),
                                                null,
                                                array( 'memento_key' => $mementoKey,
                                                       'main' => 1 ),
                                                $asObject );
    }

    static function fetchList( $mementoKey, $asObject = true )
    {
        if ( is_array( $mementoKey ) )
        {
            $mementoKey = eZOperationMemento::createKey( $mementoKey );
        }

        return eZPersistentObject::fetchObjectList( eZOperationMemento::definition(),
                                                    null,
                                                    array( 'memento_key' => $mementoKey,
                                                           'main' => 0 ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    function setData( $data = array() )
    {
        $this->MementoData = serialize( $data );
    }

    function data()
    {
        return unserialize( $this->MementoData );
    }

    static function create( $mementoKey, $data = array(), $isMainKey = false, $mainKey = null )
    {
        if( is_array( $mementoKey ) )
        {
            $mementoKey = eZOperationMemento::createKey( $mementoKey );
        }

        $serializedData = serialize( $data );
        return new eZOperationMemento( array( 'id' => null,
                                              'main' => ( $isMainKey ? 1 : 0 ),
                                              'memento_key' => $mementoKey,
                                              'main_key' => ( $isMainKey ? $mementoKey : $mainKey ),
                                              'memento_data' => $serializedData ) );
    }

    static function createKey( $parameters )
    {
        $string = '';
        foreach ( $parameters as $key => $value )
        {
            $string .= $key . $value;
        }
        return md5( $string );
    }

    /*!
     \static
     Removes all active operation mementos.
    */
    static function cleanup()
    {
        $db = eZDB::instance();
        $db->query( "DELETE FROM ezoperation_memento" );
    }

}

?>
