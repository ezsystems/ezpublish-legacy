<?php
/**
 * File containing the eZOperationMemento class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZOperationMemento ezoperationmemento.php
  \brief The class eZOperationMemento does

*/

class eZOperationMemento extends eZPersistentObject
{
    public $MainMemento;
    public $MementoData;
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
                                                              'required' => true,
                                                              'foreign_class' => 'eZOperationMemento',
                                                              'foreign_attribute' => 'memento_key',
                                                              'multiplicity' => '1..*' ),
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
            if ( is_array( $value ) )
                $string .= $key . serialize( $value );
            else
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
