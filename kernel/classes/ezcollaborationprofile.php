<?php
/**
 * File containing the eZCollaborationProfile class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZCollaborationProfile ezcollaborationprofile.php
  \brief The class eZCollaborationProfile does

*/

class eZCollaborationProfile extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationProfile( $row )
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
                                                             'multiplicity' => '1..*' ),
                                         'main_group' => array( 'name' => 'MainGroup',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => true,
                                                                'foreign_class' => 'eZCollaborationGroup',
                                                                'foreign_attribute' => 'id',
                                                                'multiplicity' => '1..*' ),
                                         'data_text1' => array( 'name' => 'DataText1',
                                                                'datatype' => 'text',
                                                                'default' => '',
                                                                'required' => true ),
                                         'created' => array( 'name' => 'Created',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'modified' => array( 'name' => 'Modified',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ) ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZCollaborationProfile',
                      'name' => 'ezcollab_profile' );
    }

    static function create( $userID, $mainGroup = 0 )
    {
        $date_time = time();
        $row = array( 'id' => null,
                      'user_id' => $userID,
                      'main_group' => $mainGroup,
                      'created' => $date_time,
                      'modified' => $date_time );
        $newCollaborationProfile = new eZCollaborationProfile( $row );
        return $newCollaborationProfile;
    }

    static function fetch( $id, $asObject = true )
    {
        $conditions = array( "id" => $id );
        return eZPersistentObject::fetchObject( eZCollaborationProfile::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    static function fetchByUser( $userID, $asObject = true )
    {
        $conditions = array( "user_id" => $userID );
        return eZPersistentObject::fetchObject( eZCollaborationProfile::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    /**
     * Returns a shared instance of the eZCollaborationProfile class
     * pr user id.
     * note: Transaction unsafe. If you call several transaction unsafe methods you must enclose
     * the calls within a db transaction; thus within db->begin and db->commit.
     *
     * @param int|false $userID Uses current user id if false.
     * @return eZCollaborationProfile
     */
    static function instance( $userID = false )
    {
        if ( $userID === false )
        {
            $user = eZUser::currentUser();
            $userID = $user->attribute( 'contentobject_id' );
        }
        $instance =& $GLOBALS["eZCollaborationProfile-$userID"];
        if ( !isset( $instance ) )
        {
            $instance = eZCollaborationProfile::fetchByUser( $userID );
            if ( $instance === null )
            {
                $group = eZCollaborationGroup::instantiate( $userID, ezpI18n::tr( 'kernel/classes', 'Inbox' ) );
                $instance = eZCollaborationProfile::create( $userID, $group->attribute( 'id' ) );
                $instance->store();
            }
        }
        return $instance;
    }

}

?>
