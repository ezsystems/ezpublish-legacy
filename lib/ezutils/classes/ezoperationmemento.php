<?php
//
// Definition of eZOperationMemento class
//
// Created on: <06-Ноя-2002 16:19:18 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

    function &definition()
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


    function hasAttribute( $attr )
    {
        return ( $attr == 'main_memento' or
                 eZPersistentObject::hasAttribute( $attr ) );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'main_memento':
            {
                return $this->mainMemento();
            } break;
            default:
                return eZPersistentObject::attribute( $attr );
        }
    }

    function &mainMemento()
    {
        if ( !isset( $this->MainMemento ) )
        {
            $this->MainMemento =& eZOperationMemento::fetchMain( $this->attribute( 'main_key' ) );
        }
        return $this->MainMemento;
    }

    function &fetch( $mementoKey, $asObject = true )
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

    function &fetchChild( $mementoKey, $asObject = true )
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

    function &fetchMain( $mementoKey, $asObject = true )
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

    function &fetchList( $mementoKey, $asObject = true )
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

    function &create( $mementoKey, $data = array(), $isMainKey = false, $mainKey = null )
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

    function createKey( $parameters )
    {
        $string = '';
        foreach ( array_keys( $parameters ) as $key )
        {
            $value =& $parameters[$key];
            $string .= $key . $value;
        }
        return md5( $string );
    }

    /*!
     \static
     Removes all active operation mementos.
    */
    function cleanup()
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezoperation_memento" );
    }

}

?>
