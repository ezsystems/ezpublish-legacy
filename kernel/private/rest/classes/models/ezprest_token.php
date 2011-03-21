<?php
/**
 * File containing the ezpRestToken class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 *
 */

/**
 * Data class ezprest_tokens.
 * Class to be used with eZ Components PersistentObject.
 */
class ezpRestToken implements ezcPersistentObject
{
    /**
     * id
     *
     * @var string
     */
    public $id;
    /**
     * refresh_token
     *
     * @var string
     */
    public $refresh_token;
    /**
     * expirytime
     *
     * @var int
     */
    public $expirytime;
    /**
     * client_id
     *
     * @var string
     */
    public $client_id;
    /**
     * user_id
     *
     * @var int
     */
    public $user_id;
    /**
     * scope
     *
     * @var string
     */
    public $scope;

    /**
     * Set the PersistentObject state.
     *
     * @param array(string=>mixed) $state The state to set.
     * @return void
     */
     public function setState( array $state )
     {
         foreach ( $state as $attribute => $value )
         {
             $this->$attribute = $value;
         }
     }

    /**
     * Get the PersistentObject state.
     *
     * @return array(string=>mixed) The state of the object.
     */
     public function getState()
     {
         return array(
             'id' => $this->id,
             'refresh_token' => $this->refresh_token,
             'expirytime' => $this->expirytime,
             'client_id' => $this->client_id,
             'user_id' => $this->user_id,
             'scope' => $this->scope,
         );
     }

    /**
     * Generates a random token.
     *
     * Code is adopted from MvcAuthenticationTiein
     *
     * @return string The token.
     */
     public static function generateToken( $vary )
     {
         mt_srand( base_convert( substr( md5( $vary ), 0, 6 ), 36, 10 ) * microtime( true ) );
         $a = base_convert( mt_rand(), 10, 36 );
         $b = base_convert( mt_rand(), 10, 36 );
         $token = substr( $b . $a, 1, 8 );
         $tokenHash = sha1( $token );

         return $tokenHash;
     }

    /**
     * Fetches an ezpRestToken persistent object from an access token
     * @param string $accessToken Access token hash string
     * @param bool $authCheck If true, will also check if token corresponds to a client app authorized by its user
     * @return ezpRestToken
     */
    public static function fetch( $accessToken, $authCheck = true )
    {
        $tokenInfo = null;
        $session = ezcPersistentSessionInstance::get();
        $q = $session->createFindQuery( __CLASS__ );
        $e = $q->expr;

        $q->innerJoin( 'ezprest_clients', 'ezprest_token.client_id', 'ezprest_clients.client_id' );
        if ( $authCheck )
        {
            $q->innerJoin( 'ezprest_authorized_clients', $e->lAnd( $e->eq( 'ezprest_authorized_clients.rest_client_id', 'ezprest_clients.id' ),
                                                                   $e->eq( 'ezprest_authorized_clients.user_id', 'ezprest_token.user_id' ) ) );
        }

        $q->where( $q->expr->eq( 'ezprest_token.id', $q->bindValue( $accessToken ) ) );
        $tokenInfo = $session->find( $q, 'ezpRestToken' );
        if ( !empty( $tokenInfo ) )
        {
            $tokenInfo = array_shift( $tokenInfo );
        }
        else // Empty array. For consistency in result, cast it to null
        {
            $tokenInfo = null;
        }

        return $tokenInfo;
    }
}
?>
