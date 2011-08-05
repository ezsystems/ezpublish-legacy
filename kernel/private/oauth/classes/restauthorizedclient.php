<?php
/**
 * File containing the ezpRestAuthorizedClient class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Persistent object class representing a REST application authorization by a user.
 * Each entry matches one client/application allowed for oAuth by one user
 */
class ezpRestAuthorizedClient
{
    public $id = null;

    /**
     * Application creation date, as a unix timestamp
     * @var int
     */
    public $rest_client_id = null;

    /**
     * ID of the user who authorized this client/application
     * @var int
     */
    public $user_id = null;

    /**
     * Application creation date, as a unix timestamp
     * @var int
     */
    public $created = null;

    public function getState()
    {
        $result = array();
        $result['id'] = $this->id;
        $result['rest_client_id'] = $this->rest_client_id;
        $result['user_id'] = $this->user_id;
        $result['created'] = $this->created;
        return $result;
    }

    public function setState( array $properties )
    {
        foreach( $properties as $key => $value )
        {
            $this->$key = $value;
        }
    }

    /**
     * Returns the authorization object for a user & application
     * @param ezpRestClient $client
     * @param eZUser $user
     */
    public static function fetchForClientUser( ezpRestClient $client, eZUser $user )
    {
        $session = ezcPersistentSessionInstance::get();

        $q = $session->createFindQuery( __CLASS__ );

        $q->where( $q->expr->eq( 'rest_client_id', $q->bindValue( $client->id ) ) )
          ->where( $q->expr->eq( 'user_id', $q->bindValue( $user->attribute( 'contentobject_id' ) ) ) );

        $results = $session->find( $q, __CLASS__ );

        if ( count( $results ) != 1 )
            return false;
        else
            return array_shift( $results );
    }
}
?>