<?php
/**
 * File containing the ezpRestClient class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Persistent object class representing a REST application.
 */
class ezpRestClient
{
    public $id = null;

    /**
     * Application name
     * @var string
     */
    public $name = null;

    /**
     * Application description
     * @var string
     */
    public $description = null;

    /**
     * Application client ID, as used over oAuth to authentify the application
     * @var string
     */
    public $client_id = null;

    /**
     * Application client secret, as used over oAuth to authentify the application
     * @var string
     */
    public $client_secret = null;

    /**
     * Application client endpoint URI. Used to validate the redirection URI requested by the authorize call.
     * @var string
     */
    public $endpoint_uri = null;

    /**
     * ID of the eZ Publish user who owns the application
     * @var int
     */
    public $owner_id = null;

    /**
     * Application creation date, as a unix timestamp
     * @var int
     */
    public $created = null;

    /**
     * Application update date, as a unix timestamp
     * @var int
     */
    public $updated = null;

    /**
     * Application version, used to pre-create a draft when first creation a new application.
     * @var int
     */
    public $version = null;

    public function getState()
    {
        $result = array();
        $result['id'] = $this->id;
        $result['name'] = $this->name;
        $result['description'] = $this->description;
        $result['client_id'] = $this->client_id;
        $result['client_secret'] = $this->client_secret;
        $result['endpoint_uri'] = $this->endpoint_uri;
        $result['owner_id'] = $this->owner_id;
        $result['created'] = $this->created;
        $result['updated'] = $this->updated;
        $result['version'] = $this->version;
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
     * eZPersistentObject wrapper method
     * @param string $attributeName
     * @return mixed
     */
    public function attribute( $attributeName )
    {
        if ( property_exists( $this, $attributeName ) )
            return $this->$attributeName;
        elseif ( $this->__isset( $attributeName ) )
            return $this->__get( $attributeName );
        else
            eZDebug::writeError( "Attribute '$attributeName' does not exist", __METHOD__ );
    }

    /**
     * eZPersistentObject wrapper method
     * @param string $attributeName
     * @return bool
     */
    public function hasAttribute( $attributeName )
    {
        return property_exists( $this, $attributeName ) or $this->__isset( $attributeName );
    }

    /**
     * eZPersistentObject wrapper method:
     * handles "function attributes"
     * @param string $propertyName
     * @return mixed
     */
    public function __get( $propertyName )
    {
        switch( $propertyName )
        {
            case 'owner':
            {
                return $this->_owner();
            } break;

            default:
                throw new ezcBasePropertyNotFoundException( $propertyName );
        }
    }

    /**
     * Returns the eZUser who owns the object
     * @return eZUser
     */
    protected function _owner()
    {
        static $owner = false;

        if ( $owner === false )
        {
            $owner = eZUser::fetch( $this->owner_id );
        }

        return $owner;
    }

    public function __isset( $propertyName )
    {
        return in_array( $propertyName, array( 'owner' ) );
    }

    /**
     * Validates an authorization request by an application using the ID, redirection URI and secret if provided.
     *
     * @var string $clientId
     * @var string $endPointUri
     * @var string $clientSecret
     *
     * @return bool True if the app is valid, false if it isn't
     * @todo Enhance the return variable, as several status would be required. Exceptions, or constants ?
     */
    public static function authorizeApplication( $clientId, $endPointUri, $clientSecret = null )
    {
        $client = self::fetchByClientId( $clientId );

        // no client found with this ID
        if ( $client === false )
            return false;

        if ( $clientSecret !== null && ( $clientSecret !== $client->client_secret ) )
            return false;

        if ( ( $client->endpoint_uri !== '' ) && ( $endPointUri !== $client->endpoint_uri ) )
            return false;

        return true;
    }

    /**
     * Fetches a rest application using a client Id
     * @param string $clientId
     * @return ezpRestClient
     */
    public static function fetchByClientId( $clientId )
    {
        $session = ezcPersistentSessionInstance::get();

        $q = $session->createFindQuery( __CLASS__ );
        $q->where( $q->expr->eq( 'client_id', $q->bindValue( $clientId ) ) );
        $results = $session->find( $q, __CLASS__ );
        if ( count( $results ) != 1 )
            return false;
        else
            return array_shift( $results );
    }

    /**
     * Convenience method to validate a client secret.
     *
     * @param  $secret
     * @return bool
     */
    public function validateSecret( $secret )
    {
        return $secret === $this->client_secret;
    }

    /**
     * Checks if this application has been authorized by the current user
     *
     * @param mixed $scope The requested security scope
     * @param eZUser $user The user to check authorization for. Will check for current user if not given.
     *
     * @return bool
     *
     * @todo Handle non-authorization using
     */
    public function isAuthorizedByUser( $scope, $user = null )
    {
        if ( $user === null )
            $user = eZUser::currentUser();

        if ( !$user->isLoggedIn() )
            throw new Exception( "Anonymous user can not authorize an application" );

        $authorized = ezpRestAuthorizedClient::fetchForClientUser( $this, $user );
        return ( $authorized instanceof ezpRestAuthorizedClient );
    }

    /**
     * Authorizes this application for a user
     * @param eZUser $user
     * @return void
     */
    public function authorizeFor( $user = null )
    {
        $authorization = new ezpRestAuthorizedClient();
        $authorization->rest_client_id = $this->id;
        $authorization->user_id = $user->attribute( 'contentobject_id' );

        $session = ezcPersistentSessionInstance::get();
        $session->save( $authorization );
    }

    /**
     * Validates an attempt (endpoint) redirect URI against the one configured for the client
     *
     * @param string $endPointUri
     *
     * @return bool true if the URI is valid, false otherwise
     */
    public function isEndPointValid( $endPointUri )
    {
        return ( $endPointUri === $this->endpoint_uri );
    }

    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 0;
}
?>
