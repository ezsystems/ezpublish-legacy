<?php
/**
 * File containing the eZUserAccountKey class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZUserAccountKey ezuseraccountkey.php
  \ingroup eZDatatype
  \brief The class eZUserAccountKey does

*/

class eZUserAccountKey extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZUserAccountKey( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'user_id' => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '0..1' ),
                                         'hash_key' => array( 'name' => 'HashKey',
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ),
                                         'time' => array( 'name' => 'Time',
                                                          'datatype' => 'integer',
                                                          'default' => 0,
                                                          'required' => true )
                                         ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'sort' => array( 'id' => 'asc' ),
                      'class_name' => 'eZUserAccountKey',
                      'name' => 'ezuser_accountkey' );
    }

    static function createNew( $userID, $hashKey, $time)
    {
        return new eZUserAccountKey( array( 'user_id' => $userID,
                                            'hash_key' => $hashKey,
                                            'time' => $time ) );
    }

    static function fetchByKey( $hashKey )
    {
        return eZPersistentObject::fetchObject( eZUserAccountKey::definition(),
                                                null,
                                                array( 'hash_key' => $hashKey ),
                                                true );
    }

    /**
     * Return the eZUserAccountKey object associated to a user id
     *
     * @param int $userID
     * @return eZUserAccountKey
     */
    static public function fetchByUserID( $userID )
    {
        return eZPersistentObject::fetchObject(
            eZUserAccountKey::definition(),
            null,
            array( 'user_id' => $userID ),
            true
        );
    }

    /*!
     Remove account keys belonging to user \a $userID
    */
    static function removeByUserID( $userID )
    {
        eZPersistentObject::removeObject( eZUserAccountKey::definition(),
                                          array( 'user_id' => $userID ) );
    }

}

?>
