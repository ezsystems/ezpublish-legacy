<?php
/**
 * File containing the eZUser class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZUser ezuser.php
  \brief eZUser handles eZ Publish user accounts
  \ingroup eZDatatype

*/

class eZUser extends eZPersistentObject
{
    /// MD5 of password
    const PASSWORD_HASH_MD5_PASSWORD = 1;
    /// MD5 of user and password
    const PASSWORD_HASH_MD5_USER = 2;
    /// MD5 of site, user and password
    const PASSWORD_HASH_MD5_SITE = 3;
    /// Legacy support for mysql hashed passwords
    const PASSWORD_HASH_MYSQL = 4;
    /// Passwords in plaintext, should not be used for real sites
    const PASSWORD_HASH_PLAINTEXT = 5;
    // Crypted passwords
    const PASSWORD_HASH_CRYPT = 6;

    /// Authenticate by matching the login field
    const AUTHENTICATE_LOGIN = 1;
    /// Authenticate by matching the email field
    const AUTHENTICATE_EMAIL = 2;

    const AUTHENTICATE_ALL = 3; //EZ_USER_AUTHENTICATE_LOGIN | EZ_USER_AUTHENTICATE_EMAIL;

    /**
     * Flag used to prevent session regeneration
     *
     * @var int
     * @see eZUser::setCurrentlyLoggedInUser()
     */
    const NO_SESSION_REGENERATE = 1;

    protected static $anonymousId = null;

    function eZUser( $row = array() )
    {
        $this->eZPersistentObject( $row );
        $this->OriginalPassword = false;
        $this->OriginalPasswordConfirm = false;
    }

    static function definition()
    {
        static $definition = array( 'fields' => array( 'contentobject_id' => array( 'name' => 'ContentObjectID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true,
                                                                      'foreign_class' => 'eZContentObject',
                                                                      'foreign_attribute' => 'id',
                                                                      'multiplicity' => '0..1' ),
                                         'login' => array( 'name' => 'Login',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => true ),
                                         'email' => array( 'name' => 'Email',
                                                           'datatype' => 'string',
                                                           'default' => '',
                                                           'required' => true ),
                                         'password_hash' => array( 'name' => 'PasswordHash',
                                                                   'datatype' => 'string',
                                                                   'default' => '',
                                                                   'required' => true ),
                                         'password_hash_type' => array( 'name' => 'PasswordHashType',
                                                                        'datatype' => 'integer',
                                                                        'default' => 1,
                                                                        'required' => true ) ),
                      'keys' => array( 'contentobject_id' ),
                      'sort' => array( 'contentobject_id' => 'asc' ),
                      'function_attributes' => array( 'contentobject' => 'contentObject',
                                                      'account_key' => 'accountKey',
                                                      'groups' => 'groups',
                                                      'has_stored_login' => 'hasStoredLogin',
                                                      'original_password' => 'originalPassword',
                                                      'original_password_confirm' => 'originalPasswordConfirm',
                                                      'roles' => 'roles',
                                                      'role_id_list' => 'roleIDList',
                                                      'limited_assignment_value_list' => 'limitValueList',
                                                      'is_logged_in' => 'isLoggedIn',
                                                      'is_enabled' => 'isEnabled',
                                                      'is_locked' => 'isLocked',
                                                      'last_visit' => 'lastVisit',
                                                      'login_count' => 'loginCount',
                                                      'has_manage_locations' => 'hasManageLocations' ),
                      'relations' => array( 'contentobject_id' => array( 'class' => 'ezcontentobject',
                                                                         'field' => 'id' ) ),
                      'class_name' => 'eZUser',
                      'name' => 'ezuser' );
        return $definition;
    }

    /*!
     \return a textual identifier for the hash type $id
    */
    static function passwordHashTypeName( $id )
    {
        switch ( $id )
        {
            case self::PASSWORD_HASH_MD5_PASSWORD:
            {
                return 'md5_password';
            } break;
            case self::PASSWORD_HASH_MD5_USER:
            {
                return 'md5_user';
            } break;
            case self::PASSWORD_HASH_MD5_SITE:
            {
                return 'md5_site';
            } break;
            case self::PASSWORD_HASH_MYSQL:
            {
                return 'mysql';
            } break;
            case self::PASSWORD_HASH_PLAINTEXT:
            {
                return 'plaintext';
            } break;
            case self::PASSWORD_HASH_CRYPT:
            {
                return 'crypt';
            } break;
        }
    }

    /*!
     \return the hash type for the textual identifier $identifier
    */
    static function passwordHashTypeID( $identifier )
    {
        switch ( $identifier )
        {
            case 'md5_password':
            {
                return self::PASSWORD_HASH_MD5_PASSWORD;
            } break;
            default:
            case 'md5_user':
            {
                return self::PASSWORD_HASH_MD5_USER;
            } break;
            case 'md5_site':
            {
                return self::PASSWORD_HASH_MD5_SITE;
            } break;
            case 'mysql':
            {
                return self::PASSWORD_HASH_MYSQL;
            } break;
            case 'plaintext':
            {
                return self::PASSWORD_HASH_PLAINTEXT;
            } break;
            case 'crypt':
            {
                return self::PASSWORD_HASH_CRYPT;
            } break;
        }
    }

    /*!
     Check if current user has "content/manage_locations" access
    */
    function hasManageLocations()
    {
        $accessResult = $this->hasAccessTo( 'content', 'manage_locations' );
        if ( $accessResult['accessWord'] != 'no' )
        {
            return true;
        }

        return false;
    }

    static function create( $contentObjectID )
    {
        $row = array(
            'contentobject_id' => $contentObjectID,
            'login' => null,
            'email' => null,
            'password_hash' => null,
            'password_hash_type' => null
            );
        return new eZUser( $row );
    }

    function store( $fieldFilters = null )
    {
        $this->Email = trim( $this->Email );
        $userID = $this->attribute( 'contentobject_id' );
        // Clear memory cache
        unset( $GLOBALS['eZUserObject_' . $userID] );
        $GLOBALS['eZUserObject_' . $userID] = $this;
        self::purgeUserCacheByUserId( $userID );
        eZPersistentObject::store( $fieldFilters );
    }

    function originalPassword()
    {
        return $this->OriginalPassword;
    }

    function setOriginalPassword( $password )
    {
        $this->OriginalPassword = $password;
    }

    function originalPasswordConfirm()
    {
        return $this->OriginalPasswordConfirm;
    }

    function setOriginalPasswordConfirm( $password )
    {
        $this->OriginalPasswordConfirm = $password;
    }

    function hasStoredLogin()
    {
        $db = eZDB::instance();
        $contentObjectID = $this->attribute( 'contentobject_id' );
        $sql = "SELECT * FROM ezuser WHERE contentobject_id='$contentObjectID' AND LENGTH( login ) > 0";
        $rows = $db->arrayQuery( $sql );
        return !empty( $rows );
    }

    /*!
     Fills in the \a $id, \a $login, \a $email and \a $password for the user
     and creates the proper password hash.
    */
    function setInformation( $id, $login, $email, $password, $passwordConfirm = false )
    {
        $this->setAttribute( "contentobject_id", $id );
        $this->setAttribute( "email", $email );
        $this->setAttribute( "login", $login );
        if ( eZUser::validatePassword( $password ) and
             $password == $passwordConfirm ) // Cannot change login or password_hash without login and password
        {
            $this->setAttribute( "password_hash", eZUser::createHash( $login, $password, eZUser::site(),
                                                                      eZUser::hashType() ) );
            $this->setAttribute( "password_hash_type", eZUser::hashType() );
        }
        else
        {
            $this->setOriginalPassword( $password );
            $this->setOriginalPasswordConfirm( $passwordConfirm );
        }
    }

    static function fetch( $id, $asObject = true )
    {
        if ( !$id )
            return null;
        return eZPersistentObject::fetchObject( eZUser::definition(),
                                                null,
                                                array( 'contentobject_id' => $id ),
                                                $asObject );
    }

    static function fetchByName( $login, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZUser::definition(),
                                                null,
                                                array( 'LOWER( login )' => strtolower( $login ) ),
                                                $asObject );
    }

    static function fetchByEmail( $email, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZUser::definition(),
                                                  null,
                                                  array( 'LOWER( email )' => strtolower( $email ) ),
                                                  $asObject );
    }

    /**
     * Return an array of unactivated eZUser object
     *
     * @param array|false|null An associative array of sorting conditions,
     *        if set to false ignores settings in $def, if set to null uses
     *        settingss in $def.
     * @param int $limit
     * @param int $offset
     * @return array( eZUser )
     */
    static public function fetchUnactivated( $sort = false, $limit = 10, $offset = 0 )
    {
        $accountDef = eZUserAccountKey::definition();

        return eZPersistentObject::fetchObjectList(
            eZUser::definition(), null, null, $sort,
            array(
                'limit' => $limit,
                'offset' => $offset
            ),
            true, false, null, array( $accountDef['name'] ),
            " WHERE contentobject_id = user_id"
        );
    }

    /*!
     \static
     \return a list of the logged in users.
     \param $asObject If false it will return a list with only the names of the users as elements and user ID as key,
                      otherwise each entry is a eZUser object.
     \sa fetchLoggedInCount
    */
    static function fetchLoggedInList( $asObject = false, $offset = false, $limit = false, $sortBy = false )
    {
        $db = eZDB::instance();
        $time = time() - eZINI::instance()->variable( 'Session', 'ActivityTimeout' );

        $parameters = array();
        if ( $offset )
            $parameters['offset'] =(int) $offset;
        if ( $limit )
            $parameters['limit'] =(int) $limit;
        $sortText = '';
        if ( $asObject )
        {
            $selectArray = array( "distinct ezuser.*" );
        }
        else
        {
            $selectArray = array( "ezuser.contentobject_id as user_id", "ezcontentobject.name" );
        }
        if ( $sortBy !== false )
        {
            $sortElements = array();
            if ( !is_array( $sortBy ) )
            {
                $sortBy = array( array( $sortBy, true ) );
            }
            else if ( !is_array( $sortBy[0] ) )
                $sortBy = array( $sortBy );
            $sortColumns = array();
            foreach ( $sortBy as $sortElements )
            {
                $sortColumn = $sortElements[0];
                $sortOrder = $sortElements[1];
                $orderText = $sortOrder ? 'asc' : 'desc';
                switch ( $sortColumn )
                {
                    case 'user_id':
                    {
                        $sortColumn = "ezuser.contentobject_id $orderText";
                    } break;

                    case 'login':
                    {
                        $sortColumn = "ezuser.login $orderText";
                    } break;

                    case 'activity':
                    {
                        $selectArray[] = "ezuservisit.current_visit_timestamp AS activity";
                        $sortColumn = "activity $orderText";
                    } break;

                    case 'email':
                    {
                        $sortColumn = "ezuser.email $orderText";
                    } break;

                    default:
                    {
                        eZDebug::writeError( "Unkown sort column '$sortColumn'", __METHOD__ );
                        $sortColumn = false;
                    } break;
                }
                if ( $sortColumn )
                    $sortColumns[] = $sortColumn;
            }
            if ( !empty( $sortColumns ) )
                $sortText = "ORDER BY " . implode( ', ', $sortColumns );
        }
        if ( $asObject )
        {
            $selectText = implode( ', ',  $selectArray );
            $sql = "SELECT $selectText
FROM ezuservisit, ezuser
WHERE ezuservisit.user_id != '" . self::anonymousId() . "' AND
      ezuservisit.current_visit_timestamp > '$time' AND
      ezuser.contentobject_id = ezuservisit.user_id
$sortText";
            $rows = $db->arrayQuery( $sql, $parameters );
            $list = array();
            foreach ( $rows as $row )
            {
                $list[] = new eZUser( $row );
            }
        }
        else
        {
            $selectText = implode( ', ',  $selectArray );
            $sql = "SELECT $selectText
FROM ezuservisit, ezuser, ezcontentobject
WHERE ezuservisit.user_id != '" . self::anonymousId() . "' AND
      ezuservisit.current_visit_timestamp > '$time' AND
      ezuser.contentobject_id = ezuservisit.user_id AND
      ezcontentobject.id = ezuser.contentobject_id
$sortText";
            $rows = $db->arrayQuery( $sql, $parameters );
            $list = array();
            foreach ( $rows as $row )
            {
                $list[$row['user_id']] = $row['name'];
            }
        }
        return $list;
    }

    /*!
     \return the number of logged in users in the system.
     \note The count will be cached for the current page if caching is allowed.
     \sa fetchAnonymousCount
    */
    static function fetchLoggedInCount()
    {
        if ( isset( $GLOBALS['eZSiteBasics']['no-cache-adviced'] ) and
             !$GLOBALS['eZSiteBasics']['no-cache-adviced'] and
             isset( $GLOBALS['eZUserLoggedInCount'] ) )
            return $GLOBALS['eZUserLoggedInCount'];
        $db = eZDB::instance();
        $time = time() - eZINI::instance()->variable( 'Session', 'ActivityTimeout' );

        $sql = "SELECT count( DISTINCT user_id ) as count
FROM ezuservisit
WHERE user_id != '" . self::anonymousId() . "' AND
      user_id > 0 AND
      current_visit_timestamp > '$time'";
        $rows = $db->arrayQuery( $sql );
        $count = isset( $rows[0] ) ? $rows[0]['count'] : 0;
        $GLOBALS['eZUserLoggedInCount'] = $count;
        return $count;
    }

    /**
     * Return the number of anonymous users in the system.
     *
     * @deprecated As of 4.4 since default session handler does not support this.
     * @return int
     */
    static function fetchAnonymousCount()
    {
        if ( isset( $GLOBALS['eZSiteBasics']['no-cache-adviced'] ) and
             !$GLOBALS['eZSiteBasics']['no-cache-adviced'] and
             isset( $GLOBALS['eZUserAnonymousCount'] ) )
            return $GLOBALS['eZUserAnonymousCount'];
        $db = eZDB::instance();
        $time = time();
        $ini = eZINI::instance();
        $activityTimeout = $ini->variable( 'Session', 'ActivityTimeout' );
        $sessionTimeout = $ini->variable( 'Session', 'SessionTimeout' );
        $time = $time + $sessionTimeout - $activityTimeout;

        $sql = "SELECT count( session_key ) as count
FROM ezsession
WHERE user_id = '" . self::anonymousId() . "' AND
      expiration_time > '$time'";
        $rows = $db->arrayQuery( $sql );
        $count = isset( $rows[0] ) ? $rows[0]['count'] : 0;
        $GLOBALS['eZUserAnonymousCount'] = $count;
        return $count;
    }

    /*!
     \static
     \return true if the user with ID $userID is currently logged into the system.
     \note The information will be cached for the current page if caching is allowed.
     \sa fetchLoggedInList
    */
    static function isUserLoggedIn( $userID )
    {
        $userID = (int)$userID;
        if ( isset( $GLOBALS['eZSiteBasics']['no-cache-adviced'] ) and
             !$GLOBALS['eZSiteBasics']['no-cache-adviced'] and
             isset( $GLOBALS['eZUserLoggedInMap'][$userID] ) )
            return $GLOBALS['eZUserLoggedInMap'][$userID];
        $db = eZDB::instance();
        $time = time() - eZINI::instance()->variable( 'Session', 'ActivityTimeout' );

        $sql = "SELECT DISTINCT user_id
FROM ezuservisit
WHERE user_id = '" . $userID . "' AND
      current_visit_timestamp > '$time'";
        $rows = $db->arrayQuery( $sql, array( 'limit' => 2 ) );
        $isLoggedIn = isset( $rows[0] );
        $GLOBALS['eZUserLoggedInMap'][$userID] = $isLoggedIn;
        return $isLoggedIn;
    }

    /*!
     \static
     Removes any cached session information, this is:
     - logged in user count
     - anonymous user count
     - logged in user map
    */
    static function clearSessionCache()
    {
        unset( $GLOBALS['eZUserLoggedInCount'] );
        unset( $GLOBALS['eZUserAnonymousCount'] );
        unset( $GLOBALS['eZUserLoggedInMap'] );
    }

    /**
     * Remove session data for user \a $userID.
     * @todo should use eZSession api (needs to be created) so
     *       callbacks (like preference / basket..) is cleared as well.
     *
     * @params int $userID
     */
    static function removeSessionData( $userID )
    {
        eZUser::clearSessionCache();
        eZSession::getHandlerInstance()->deleteByUserIDs( array( $userID ) );
    }

    /*!
     Removes the user from the ezuser table.
     \note Will also remove any notifications and session related to the user.
    */
    static function removeUser( $userID )
    {
        $user = eZUser::fetch( $userID );
        if ( !$user )
        {
            eZDebug::writeError( "unable to find user with ID $userID", __METHOD__ );
            return false;
        }

        eZUser::removeSessionData( $userID );

        eZSubtreeNotificationRule::removeByUserID( $userID );
        eZCollaborationNotificationRule::removeByUserID( $userID );
        eZUserSetting::removeByUserID( $userID );
        eZUserAccountKey::removeByUserID( $userID );
        eZForgotPassword::removeByUserID( $userID );
        eZWishList::removeByUserID( $userID );

        // only remove general digest setting if there are no other users with the same e-mail
        $email = $user->attribute( 'email' );
        $usersWithEmailCount = eZPersistentObject::count( eZUser::definition(), array( 'email' => $email ) );
        if ( $usersWithEmailCount == 1 )
        {
            eZGeneralDigestUserSettings::removeByAddress( $email );
        }

        eZPersistentObject::removeObject( eZUser::definition(),
                                          array( 'contentobject_id' => $userID ) );
        return true;
    }

    /*!
     \return a list of valid and enabled users, the data returned is an array
             with ezcontentobject database data.
    */
    static function fetchContentList()
    {
        $contentObjectStatus = eZContentObject::STATUS_PUBLISHED;
        $query = "SELECT ezcontentobject.*
                  FROM ezuser, ezcontentobject, ezuser_setting
                  WHERE ezcontentobject.status = '$contentObjectStatus' AND
                        ezuser_setting.is_enabled = 1 AND
                        ezcontentobject.id = ezuser.contentobject_id AND
                        ezuser_setting.user_id = ezuser.contentobject_id";
        $db = eZDB::instance();
        $rows = $db->arrayQuery( $query );
        return $rows;
    }

    /*!
     \static
     \return the default hash type which is specified in UserSettings/HashType in site.ini
    */
    static function hashType()
    {
        $ini = eZINI::instance();
        $type = strtolower( $ini->variable( 'UserSettings', 'HashType' ) );
        if ( $type == 'md5_site' )
            return self::PASSWORD_HASH_MD5_SITE;
        else if ( $type == 'md5_user' )
            return self::PASSWORD_HASH_MD5_USER;
        else if ( $type == 'plaintext' )
            return self::PASSWORD_HASH_PLAINTEXT;
        else if ( $type == 'crypt' )
            return self::PASSWORD_HASH_CRYPT;
        else
            return self::PASSWORD_HASH_MD5_PASSWORD;
    }

    /*!
     \static
     \return the site name used in password hashing.
    */
    static function site()
    {
        $ini = eZINI::instance();
        return $ini->variable( 'UserSettings', 'SiteName' );
    }

    /*!
     Fetches a builtin user and returns it, this helps avoid special cases where
     user is not logged in.
    */
    static function fetchBuiltin( $id )
    {
        if ( !in_array( $id, $GLOBALS['eZUserBuiltins'] ) )
            $id = self::anonymousId();
        if ( empty( $GLOBALS["eZUserBuilitinInstance-$id"] ) )
        {
            $GLOBALS["eZUserBuilitinInstance-$id"] = eZUser::fetch( self::anonymousId() );
        }
        return $GLOBALS["eZUserBuilitinInstance-$id"];
    }

    /*!
     \return the user id.
    */
    function id()
    {
        return $this->ContentObjectID;
    }

    /*!
     \return a bitfield which decides the authenticate methods.
    */
    static function authenticationMatch()
    {
        $ini = eZINI::instance();
        $matchArray = $ini->variableArray( 'UserSettings', 'AuthenticateMatch' );
        $match = 0;
        foreach ( $matchArray as $matchItem )
        {
            switch ( $matchItem )
            {
                case "login":
                {
                    $match = ( $match | self::AUTHENTICATE_LOGIN );
                } break;
                case "email":
                {
                    $match = ( $match | self::AUTHENTICATE_EMAIL );
                } break;
            }
        }
        return $match;
    }

    /*!
     \return \c true if there can only be one instance of an email address on the site.
    */
    static function requireUniqueEmail()
    {
        $ini = eZINI::instance();
        return $ini->variable( 'UserSettings', 'RequireUniqueEmail' ) == 'true';
    }

    /**
     * Logs in the user if applied username and password is valid.
     *
     * @param string $login
     * @param string $password
     * @param bool $authenticationMatch
     * @return mixed eZUser on success, bool false on failure
     */
    public static function loginUser( $login, $password, $authenticationMatch = false )
    {
        $user = self::_loginUser( $login, $password, $authenticationMatch );

        if ( is_object( $user ) )
        {
            self::loginSucceeded( $user );
            return $user;
        }
        else
        {
            self::loginFailed( $user, $login );
            return false;
        }
    }

    /**
     * Does some house keeping work once a log in has succeeded.
     *
     * @param eZUser $user
     */
    protected static function loginSucceeded( $user )
    {
        $userID = $user->attribute( 'contentobject_id' );

        // if audit is enabled logins should be logged
        eZAudit::writeAudit( 'user-login', array( 'User id' => $userID, 'User login' => $user->attribute( 'login' ) ) );

        eZUser::updateLastVisit( $userID, true );
        eZUser::setCurrentlyLoggedInUser( $user, $userID );

        // Reset number of failed login attempts
        eZUser::setFailedLoginAttempts( $userID, 0 );
    }

    /**
     * Does some house keeping work when a log in has failed.
     *
     * @param mixed $userID
     * @param string $login
     */
     protected static function loginFailed( $userID = false, $login )
    {
        $loginEscaped = eZDB::instance()->escapeString( $login );

        // Failed login attempts should be logged
        eZAudit::writeAudit( 'user-failed-login', array( 'User login' => $loginEscaped,
                                                         'Comment' => 'Failed login attempt: eZUser::loginUser()' ) );

        // Increase number of failed login attempts.
        if ( $userID )
            eZUser::setFailedLoginAttempts( $userID );
    }

    /**
     * Logs in an user if applied login and password is valid.
     *
     * This method does not do any house keeping work anymore (writing audits, etc).
     * When you call this method make sure to call loginSucceeded() or loginFailed()
     * depending on the success of the login.
     *
     * @param string $login
     * @param string $password
     * @param bool $authenticationMatch
     * @return mixed eZUser object on log in success, int userID if the username
     *         exists but log in failed, or false if the username doesn't exists.
     */
    protected static function _loginUser( $login, $password, $authenticationMatch = false )
    {
        $http = eZHTTPTool::instance();
        $db = eZDB::instance();

        if ( $authenticationMatch === false )
            $authenticationMatch = eZUser::authenticationMatch();

        $loginEscaped = $db->escapeString( $login );
        $passwordEscaped = $db->escapeString( $password );

        $loginArray = array();
        if ( $authenticationMatch & self::AUTHENTICATE_LOGIN )
            $loginArray[] = "login='$loginEscaped'";
        if ( $authenticationMatch & self::AUTHENTICATE_EMAIL )
        {
            if ( eZMail::validate( $login ) )
            {
                $loginArray[] = "email='$loginEscaped'";
            }
        }
        if ( empty( $loginArray ) )
            $loginArray[] = "login='$loginEscaped'";
        $loginText = implode( ' OR ', $loginArray );

        $contentObjectStatus = eZContentObject::STATUS_PUBLISHED;

        $ini = eZINI::instance();
        $databaseName = $db->databaseName();
        // if mysql
        if ( $databaseName === 'mysql' )
        {
            $query = "SELECT contentobject_id, password_hash, password_hash_type, email, login
                      FROM ezuser, ezcontentobject
                      WHERE ( $loginText ) AND
                        ezcontentobject.status='$contentObjectStatus' AND
                        ezcontentobject.id=contentobject_id AND
                        ( ( password_hash_type!=4 ) OR
                          ( password_hash_type=4 AND
                              ( $loginText ) AND
                          password_hash=PASSWORD('$passwordEscaped') ) )";
        }
        else
        {
            $query = "SELECT contentobject_id, password_hash,
                             password_hash_type, email, login
                      FROM   ezuser, ezcontentobject
                      WHERE  ( $loginText )
                      AND    ezcontentobject.status='$contentObjectStatus'
                      AND    ezcontentobject.id=contentobject_id";
        }

        $users = $db->arrayQuery( $query );
        $exists = false;
        if ( $users !== false && isset( $users[0] ) )
        {
            $ini = eZINI::instance();
            foreach ( $users as $userRow )
            {
                $userID = $userRow['contentobject_id'];
                $hashType = $userRow['password_hash_type'];
                $hash = $userRow['password_hash'];
                $exists = eZUser::authenticateHash( $userRow['login'], $password, eZUser::site(),
                                                    $hashType,
                                                    $hash );

                // If hash type is MySql
                if ( $hashType == self::PASSWORD_HASH_MYSQL and $databaseName === 'mysql' )
                {
                    $queryMysqlUser = "SELECT contentobject_id, password_hash, password_hash_type, email, login
                              FROM ezuser, ezcontentobject
                              WHERE ezcontentobject.status='$contentObjectStatus' AND
                                    password_hash_type=4 AND ( $loginText ) AND password_hash=PASSWORD('$passwordEscaped') ";
                    $mysqlUsers = $db->arrayQuery( $queryMysqlUser );
                    if ( isset( $mysqlUsers[0] ) )
                        $exists = true;

                }

                eZDebugSetting::writeDebug( 'kernel-user', eZUser::createHash( $userRow['login'], $password, eZUser::site(),
                                                                               $hashType, $hash ), "check hash" );
                eZDebugSetting::writeDebug( 'kernel-user', $hash, "stored hash" );
                 // If current user has been disabled after a few failed login attempts.
                $canLogin = eZUser::isEnabledAfterFailedLogin( $userID );

                if ( $exists )
                {
                    // We should store userID for warning message.
                    $GLOBALS['eZFailedLoginAttemptUserID'] = $userID;

                    $userSetting = eZUserSetting::fetch( $userID );
                    $isEnabled = $userSetting->attribute( "is_enabled" );
                    if ( $hashType != eZUser::hashType() and
                         strtolower( $ini->variable( 'UserSettings', 'UpdateHash' ) ) == 'true' )
                    {
                        $hashType = eZUser::hashType();
                        $hash = eZUser::createHash( $userRow['login'], $password, eZUser::site(),
                                                    $hashType );
                        $db->query( "UPDATE ezuser SET password_hash='$hash', password_hash_type='$hashType' WHERE contentobject_id='$userID'" );
                    }
                    break;
                }
            }
        }

        if ( $exists and $isEnabled and $canLogin )
        {
            return new eZUser( $userRow );
        }
        else
        {
            return isset( $userID ) ? $userID : false;
        }
    }

    /*!
     \static
     Checks if IP address of current user is in \a $ipList.
    */
    static function isUserIPInList( $ipList )
    {
        $ipAddress = eZSys::clientIP();
        if ( $ipAddress )
        {
            $result = false;
            foreach( $ipList as $itemToMatch )
            {
                if ( preg_match("/^(([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+))(\/([0-9]+)$|$)/", $itemToMatch, $matches ) )
                {
                    if ( $matches[6] )
                    {
                        if ( eZDebug::isIPInNet( $ipAddress, $matches[1], $matches[7] ) )
                        {
                            $result = true;
                            break;
                        }
                    }
                    else
                    {
                        if ( $matches[1] == $ipAddress )
                        {
                            $result = true;
                            break;
                        }
                    }
                }
            }
        }
        else
        {
            $result = (
                in_array( 'commandline', $ipList ) &&
                ( php_sapi_name() == 'cli' )
            );
        }
        return $result;
    }

    /*!
     \static
      Returns true if current user is trusted user.
    */
    static function isTrusted()
    {
        $ini = eZINI::instance();

        // Check if current user is trusted user.
        $trustedIPs = $ini->hasVariable( 'UserSettings', 'TrustedIPList' ) ? $ini->variable( 'UserSettings', 'TrustedIPList' ) : array();

        // Check if IP address of current user is in $trustedIPs array.
        $trustedUser = eZUser::isUserIPInList( $trustedIPs );
        if ( $trustedUser )
            return true;

        return false;
    }

    /*!
     \static
     Returns max number of failed login attempts.
    */
    static function maxNumberOfFailedLogin()
    {
        $ini = eZINI::instance();

        $maxNumberOfFailedLogin = $ini->hasVariable( 'UserSettings', 'MaxNumberOfFailedLogin' ) ? $ini->variable( 'UserSettings', 'MaxNumberOfFailedLogin' ) : '0';
        return $maxNumberOfFailedLogin;
    }

    /*
     \static
     Returns true if the user can login
     If user has number of failed login attempts more than eZUser::maxNumberOfFailedLogin()
     and user is not trusted
     the user will not be allowed to login.
    */
    static function isEnabledAfterFailedLogin( $userID, $ignoreTrusted = false )
    {
        if ( !is_numeric( $userID ) )
            return true;

        $userObject = eZUser::fetch( $userID );
        if ( !$userObject )
            return true;

        $trustedUser = eZUser::isTrusted();
        // If user is trusted we should stop processing
        if ( $trustedUser and !$ignoreTrusted )
            return true;

        $maxNumberOfFailedLogin = eZUser::maxNumberOfFailedLogin();

        if ( $maxNumberOfFailedLogin == '0' )
            return true;

        $failedLoginAttempts = $userObject->failedLoginAttempts();
        if ( $failedLoginAttempts > $maxNumberOfFailedLogin )
            return false;

        return true;
    }

    /**
     * Makes sure the $user is set as the currently logged in user by
     * updating the session and setting the necessary global variables.
     *
     * All login handlers should use this function to ensure that the process
     * is executed properly.
     *
     * @access private
     *
     * @param eZUser $user User
     * @param int $userID User ID
     * @param int $flags Optional flag that can be set to:
     *        eZUser::NO_SESSION_REGENERATE to avoid session to be regenerated
     */
    static function setCurrentlyLoggedInUser( $user, $userID, $flags = 0 )
    {
        $GLOBALS["eZUserGlobalInstance_$userID"] = $user;
        // Set/overwrite the global user, this will be accessed from
        // instance() when there is no ID passed to the function.
        $GLOBALS["eZUserGlobalInstance_"] = $user;
        eZSession::setUserID( $userID );

        if ( !( $flags & self::NO_SESSION_REGENERATE) )
            eZSession::regenerate();

        eZSession::set( 'eZUserLoggedInID', $userID );
        self::cleanup();
    }

    /*!
     \virtual
     Used by login handler to clean up session variables
    */
    function sessionCleanup()
    {
    }

    /**
     * Cleanup user related session values, for use by login / logout code
     *
     * @internal
     */
    static function cleanup()
    {
        $http = eZHTTPTool::instance();

        $http->removeSessionVariable( 'CanInstantiateClassList' );
        $http->removeSessionVariable( 'ClassesCachedForUser' );

        // Note: This must be done more generic with an internal
        //       callback system.
        eZPreferences::sessionCleanup();
    }

    /*!
     \return logs in the current user object
    */
    function loginCurrent()
    {
        self::setCurrentlyLoggedInUser( $this, $this->ContentObjectID );
    }

    /*!
     \static
     Logs out the current user
    */
    static function logoutCurrent()
    {
        $http = eZHTTPTool::instance();
        $id = false;
        $GLOBALS["eZUserGlobalInstance_$id"] = false;
        $contentObjectID = $http->sessionVariable( 'eZUserLoggedInID' );

        // reset session data
        $newUserID = self::anonymousId();
        eZSession::setUserID( $newUserID );
        $http->setSessionVariable( 'eZUserLoggedInID', $newUserID );

        // Clear current basket if necessary
        $db = eZDB::instance();
        $db->begin();
        eZBasket::cleanupCurrentBasket();
        $db->commit();

        if ( $contentObjectID )
            self::cleanup();

        // give user new session id
        eZSession::regenerate();

        // set the property used to prevent SSO from running again
        self::$userHasLoggedOut = true;
    }

    /**
     * Returns a shared instance of the eZUser class pr $id value.
     * If user can not be fetched, then anonymous user is returned and
     * a warning trown, if anonymous user can not be fetched, then NoUser
     * is returned and another warning is thrown.
     *
     * @param int|false $id On false: Gets current user id from session
     *        or from {@link eZUser::anonymousId()} if not set.
     * @return eZUser
     */
    static function instance( $id = false )
    {
        if ( !empty( $GLOBALS["eZUserGlobalInstance_$id"] ) )
        {
            return $GLOBALS["eZUserGlobalInstance_$id"];
        }

        $userId = $id;
        $currentUser = null;
        $http = eZHTTPTool::instance();
        $anonymousUserID = self::anonymousId();
        $sessionHasStarted = eZSession::hasStarted();
        // If not specified get the current user
        if ( $userId === false )
        {
            if ( $sessionHasStarted )
            {
                $userId = $http->sessionVariable( 'eZUserLoggedInID' );
                if ( !is_numeric( $userId ) )
                {
                    $userId = $anonymousUserID;
                    eZSession::setUserID( $userId );
                    $http->setSessionVariable( 'eZUserLoggedInID', $userId );
                }
            }
            else
            {
                $userId = $anonymousUserID;
                eZSession::setUserID( $userId );
            }
        }

        // Check user cache (this effectivly fetches user from cache)
        // user not found if !isset( isset( $userCache['info'][$userId] ) )
        $userCache = self::getUserCacheByUserId( $userId );
        if ( isset( $userCache['info'][$userId] ) )
        {
            $userArray = $userCache['info'][$userId];
            if ( is_numeric( $userArray['contentobject_id'] ) )
            {
                $currentUser = new eZUser( $userArray );
                $currentUser->setUserCache( $userCache );
            }
        }

        $ini = eZINI::instance();
        // Check if:
        // - the user has not logged out,
        // - the user is not logged in,
        // - and if a automatic single sign on plugin is enabled.
        if ( !self::$userHasLoggedOut and is_object( $currentUser ) and !$currentUser->isLoggedIn() )
        {
            $ssoHandlerArray = $ini->variable( 'UserSettings', 'SingleSignOnHandlerArray' );
            if ( !empty( $ssoHandlerArray ) )
            {
                $ssoUser = false;
                foreach ( $ssoHandlerArray as $ssoHandler )
                {
                    // Load handler
                    $handlerFile = 'kernel/classes/ssohandlers/ez' . strtolower( $ssoHandler ) . 'ssohandler.php';
                    if ( file_exists( $handlerFile ) )
                    {
                        include_once( $handlerFile );
                        $className = 'eZ' . $ssoHandler . 'SSOHandler';
                        $impl = new $className();
                        $ssoUser = $impl->handleSSOLogin();
                    }
                    else // check in extensions
                    {
                        $extensionDirectories = $ini->variable( 'UserSettings', 'ExtensionDirectory' );
                        $directoryList = eZExtension::expandedPathList( $extensionDirectories, 'sso_handler' );
                        foreach( $directoryList as $directory )
                        {
                            $handlerFile = $directory . '/ez' . strtolower( $ssoHandler ) . 'ssohandler.php';
                            if ( file_exists( $handlerFile ) )
                            {
                                include_once( $handlerFile );
                                $className = 'eZ' . $ssoHandler . 'SSOHandler';
                                $impl = new $className();
                                $ssoUser = $impl->handleSSOLogin();
                            }
                        }
                    }
                }
                // If a user was found via SSO, then use it
                if ( $ssoUser !== false )
                {
                    $currentUser = $ssoUser;
                    $userId = $currentUser->attribute( 'contentobject_id' );

                    $userInfo = array();
                    $userInfo[$userId] = array( 'contentobject_id' => $userId,
                                            'login' => $currentUser->attribute( 'login' ),
                                            'email' => $currentUser->attribute( 'email' ),
                                            'password_hash' => $currentUser->attribute( 'password_hash' ),
                                            'password_hash_type' => $currentUser->attribute( 'password_hash_type' )
                                            );
                    eZSession::setUserID( $userId );
                    $http->setSessionVariable( 'eZUserLoggedInID', $userId );

                    eZUser::updateLastVisit( $userId );
                    eZUser::setCurrentlyLoggedInUser( $currentUser, $userId );
                    eZHTTPTool::redirect( eZSys::wwwDir() . eZSys::indexFile( false ) . eZSys::requestURI(), array(), 302 );
                    eZExecution::cleanExit();
                }
            }
        }

        if ( $userId <> $anonymousUserID )
        {
            $sessionInactivityTimeout = $ini->variable( 'Session', 'ActivityTimeout' );
            if ( !isset( $GLOBALS['eZSessionIdleTime'] ) )
            {
                eZUser::updateLastVisit( $userId );
            }
            else
            {
                $sessionIdle = $GLOBALS['eZSessionIdleTime'];
                if ( $sessionIdle > $sessionInactivityTimeout )
                {
                    eZUser::updateLastVisit( $userId );
                }
            }
        }

        if ( !$currentUser )
        {
            $currentUser = eZUser::fetch( self::anonymousId() );
            eZDebug::writeWarning( 'User not found, returning anonymous' );
        }

        if ( !$currentUser )
        {
            $currentUser = new eZUser( array( 'id' => -1, 'login' => 'NoUser' ) );
            eZDebug::writeWarning( 'Anonymous user not found, returning NoUser' );
        }

        $GLOBALS["eZUserGlobalInstance_$id"] = $currentUser;
        return $currentUser;
    }

    /**
     * Get User cache from cache file
     *
     * @since 4.4
     * @return array( 'info' => array, 'groups' => array, 'roles' => array, 'role_limitations' => array, 'access_array' => array)
     */
    public function getUserCache()
    {
        if ( $this->UserCache === null )
        {
            $this->setUserCache( self::getUserCacheByUserId( $this->ContentObjectID ) );
        }
        return $this->UserCache;
    }

    /**
     * Delete User cache from locale var and cache file for current user.
     *
     * @since 4.4
     */
    public function purgeUserCache()
    {
        $this->UserCache = null;
        self::purgeUserCacheByUserId( $this->ContentObjectID );
    }

    /**
     * Set User cache from cache file
     * Needs to be in excact same format as {@link eZUser::getUserCache()}!
     *
     * @since 4.4
     * @param array $userCache
     */
    public function setUserCache( array $userCache )
    {
        $this->UserCache = $userCache;
    }

    /**
     * Delete User cache from cache file for Anonymous user(usefull for sessionless users)
     *
     * @since 4.4
     * @see eZUser::purgeUserCacheByUserId()
     */
    static public function purgeUserCacheByAnonymousId()
    {
        self::purgeUserCacheByUserId( self::anonymousId() );
    }

    /**
     * Delete User cache pr user
     *
     * @since 4.4
     * @param int $userId
     */
    static public function purgeUserCacheByUserId( $userId )
    {
        $cacheFilePath = eZUser::getCacheDir( $userId ). "/user-data-{$userId}.cache.php" ;
        eZClusterFileHandler::instance()->fileDelete( $cacheFilePath );
    }

    /**
     * Get User cache from cache file for Anonymous user(usefull for sessionless users)
     *
     * @since 4.4
     * @see eZUser::getUserCacheByUserId()
     * @return array
     */
    static public function getUserCacheByAnonymousId()
    {
        return self::getUserCacheByUserId( self::anonymousId() );
    }

    /**
     * Get User cache from cache file (usefull for sessionless users)
     *
     * @since 4.4
     * @see eZUser::getUserCache()
     * @param int $userId
     * @return array
     */
    static protected function getUserCacheByUserId( $userId )
    {
        $cacheFilePath = eZUser::getCacheDir( $userId ). "/user-data-{$userId}.cache.php" ;
        $cacheFile = eZClusterFileHandler::instance( $cacheFilePath );
        return $cacheFile->processCache( array( 'eZUser', 'retrieveUserCacheFromFile' ),
                                         array( 'eZUser', 'generateUserCacheForFile' ),
                                         null,
                                         self::userInfoExpiry(),
                                         $userId );
    }

    /**
     * Callback which fetches user cache from local file.
     *
     * @internal
     * @since 4.4
     * @see eZUser::getUserCacheByUserId()
     */
    static function retrieveUserCacheFromFile( $filePath, $mtime, $userId )
    {
        return include( $filePath );
    }

    /**
     * Callback which generates user cache for user
     *
     * @internal
     * @since 4.4
     * @see eZUser::getUserCacheByUserId()
     */
    static function generateUserCacheForFile( $filePath, $userId )
    {
        $data = array( 'info' => array(),
                       'groups' => array(),
                       'roles' => array(),
                       'role_limitations' => array(),
                       'access_array' => array(),
                       'discount_rules' => array() );
        $user = eZUser::fetch( $userId );
        if ( $user instanceOf eZUser )
        {
            // user info (session: eZUserInfoCache)
            $data['info'][$userId] = array( 'contentobject_id'   => $user->attribute( 'contentobject_id' ),
                                            'login'              => $user->attribute( 'login' ),
                                            'email'              => $user->attribute( 'email' ),
                                            'password_hash'      => $user->attribute( 'password_hash' ),
                                            'password_hash_type' => $user->attribute( 'password_hash_type' ) );

            // user groups list (session: eZUserGroupsCache)
            $groups = $user->generateGroupIdList();
            $data['groups'] = $groups;

            // role list (session: eZRoleIDList)
            $groups[] = $userId;
            $data['roles'] = eZRole::fetchIDListByUser( $groups );

            // role limitation list (session: eZRoleLimitationValueList)
            $limitList = $user->limitList( false );
            foreach ( $limitList as $limit )
            {
                $data['role_limitations'][] = $limit['limit_value'];
            }

            // access array (session: AccessArray)
            $data['access_array'] = $user->generateAccessArray();

            // discount rules (session: eZUserDiscountRules<userId>)
            $data['discount_rules'] = eZUserDiscountRule::generateIDListByUserID( $userId );
        }
        return array( 'content'  => $data,
                      'scope'    => 'user-info-cache',
                      'datatype' => 'php',
                      'store'    => true );
    }

    /*!
       Updates the user's last visit timestamp
       Optionally updates user login count by setting $updateLoginCount to true
    */
    static function updateLastVisit( $userID, $updateLoginCount = false )
    {
        if ( isset( $GLOBALS['eZUserUpdatedLastVisit'] ) )
            return;
        $db = eZDB::instance();
        $userID = (int) $userID;
        $userVisitArray = $db->arrayQuery( "SELECT 1 FROM ezuservisit WHERE user_id=$userID" );
        $time = time();

        if ( isset( $userVisitArray[0] ) )
        {
            $loginCountSQL = $updateLoginCount ? ', login_count=login_count+1' : '';
            $db->query( "UPDATE ezuservisit SET last_visit_timestamp=current_visit_timestamp, current_visit_timestamp=$time$loginCountSQL WHERE user_id=$userID" );
        }
        else
        {
            $intialLoginCount = $updateLoginCount ? 1 : 0;
            $db->query( "INSERT INTO ezuservisit ( current_visit_timestamp, last_visit_timestamp, user_id, login_count ) VALUES ( $time, $time, $userID, $intialLoginCount )" );
        }
        $GLOBALS['eZUserUpdatedLastVisit'] = true;
    }

    /*!
      Returns the last visit timestamp to the current user.
    */
    function lastVisit()
    {
        $db = eZDB::instance();

        $userVisitArray = $db->arrayQuery( "SELECT last_visit_timestamp FROM ezuservisit WHERE user_id=$this->ContentObjectID" );
        if ( isset( $userVisitArray[0] ) )
        {
            return $userVisitArray[0]['last_visit_timestamp'];
        }
        else
        {
            return time();
        }
    }

    /**
     * Returns the login count for the current user.
     *
     * @since Version 4.1
     * @return int Login count for current user.
     */
    function loginCount()
    {
        $db = eZDB::instance();
        $userVisitArray = $db->arrayQuery( "SELECT login_count FROM ezuservisit WHERE user_id=$this->ContentObjectID" );
        if ( isset( $userVisitArray[0] ) )
        {
            return $userVisitArray[0]['login_count'];
        }
        else
        {
            return 0;
        }
    }

    /*!
       If \a $value is false will increase the user's number of failed login attempts
       otherwise failed_login_attempts will be updated by $value.
       \a $setByForce if true checking for trusting or max number of failed login attempts will be ignored.
    */
    static function setFailedLoginAttempts( $userID, $value = false, $setByForce = false )
    {
        $trustedUser = eZUser::isTrusted();
        // If user is trusted we should stop processing
        if ( $trustedUser and !$setByForce )
            return true;

        $maxNumberOfFailedLogin = eZUser::maxNumberOfFailedLogin();

        if ( $maxNumberOfFailedLogin == '0' and !$setByForce )
            return true;

        $userID = (int) $userID;
        $userObject = eZUser::fetch( $userID );
        if ( !$userObject )
            return true;

        $isEnabled = $userObject->isEnabled();
        // If current user is disabled we should not continue
        if ( !$isEnabled and !$setByForce )
            return true;

        $db = eZDB::instance();
        $db->begin();

        $userVisitArray = $db->arrayQuery( "SELECT 1 FROM ezuservisit WHERE user_id=$userID" );

        if ( isset( $userVisitArray[0] ) )
        {
            if ( $value === false )
            {
                $failedLoginAttempts = $userObject->failedLoginAttempts();
                $failedLoginAttempts += 1;
            }
            else
                $failedLoginAttempts = (int) $value;

            $db->query( "UPDATE ezuservisit SET failed_login_attempts=$failedLoginAttempts WHERE user_id=$userID" );
        }
        else
        {
            if ( $value === false )
            {
                $failedLoginAttempts = 1;
            }
            else
                $failedLoginAttempts = (int) $value;

            $db->query( "INSERT INTO ezuservisit ( failed_login_attempts, user_id ) VALUES ( $failedLoginAttempts, $userID )" );
        }
        $db->commit();

        eZContentCacheManager::clearContentCacheIfNeeded( $userID );
        eZContentCacheManager::generateObjectViewCache( $userID );
    }

    /*!
      Returns the current user's number of failed login attempts.
    */
    function failedLoginAttempts()
    {
        return eZUser::failedLoginAttemptsByUserID( $this->attribute( 'contentobject_id' ) );
    }

    /*!
      Returns the current user's number of failed login attempts.
    */
    static function failedLoginAttemptsByUserID( $userID )
    {
        $db = eZDB::instance();
        $contentObjectID = (int) $userID;

        $userVisitArray = $db->arrayQuery( "SELECT failed_login_attempts FROM ezuservisit WHERE user_id=$contentObjectID" );

        $failedLoginAttempts = isset( $userVisitArray[0] ) ? $userVisitArray[0]['failed_login_attempts'] : 0;
        return $failedLoginAttempts;
    }

    /*!
     \return \c true if the user is locked (is enabled after failed login) and can be logged on the site.
    */
    function isLocked()
    {
        $userID = $this->attribute( 'contentobject_id' );
        $isNotLocked = eZUser::isEnabledAfterFailedLogin( $userID, true );
        return !$isNotLocked;
    }

    /*!
     \return \c true if the user is enabled and can be used on the site.
    */
    function isEnabled()
    {
        if ( $this == eZUser::currentUser() )
        {
            return true;
        }

        $setting = eZUserSetting::fetch( $this->attribute( 'contentobject_id' ) );
        if ( $setting and !$setting->attribute( 'is_enabled' ) )
        {
            return false;
        }
        return true;
    }

    /*!
     \return \c true if the user is the anonymous user.
    */
    function isAnonymous()
    {
        if ( $this->attribute( 'contentobject_id' ) != self::anonymousId() )
        {
            return false;
        }
        return true;
    }

    /*!
     \static
     Returns the currently logged in user.
    */
    static function currentUser()
    {
        $user = eZUser::instance();
        return $user;
    }

    /*!
     \static
     Returns the ID of the currently logged in user.
    */
    static function currentUserID()
    {
        $user = eZUser::instance();
        if ( !$user )
            return 0;
        return $user->attribute( 'contentobject_id' );
    }

    /*!
     \static
     Creates a hash out of \a $user, \a $password and \a $site according to the type \a $type.
     \return true if the generated hash is equal to the supplied hash \a $hash.
    */
    static function authenticateHash( $user, $password, $site, $type, $hash )
    {
        return eZUser::createHash( $user, $password, $site, $type, $hash ) === (string) $hash;
    }

    /*!
     \static
     \return an array with characters which are allowed in password.
    */
    static function passwordCharacterTable()
    {
        if ( !empty( $GLOBALS['eZUserPasswordCharacterTable'] ) )
        {
            return $GLOBALS['eZUserPasswordCharacterTable'];
        }
        $table = array_merge( range( 'a', 'z' ), range( 'A', 'Z' ), range( 0, 9 ) );

        $ini = eZINI::instance();
        if ( $ini->variable( 'UserSettings', 'UseSpecialCharacters' ) == 'true' )
        {
            $specialCharacters = '!#%&{[]}+?;:*';
            $table = array_merge( $table, preg_split( '//', $specialCharacters, -1, PREG_SPLIT_NO_EMPTY ) );
        }
        // Remove some characters that are too similar visually
        $table = array_diff( $table, array( 'I', 'l', 'o', 'O', '0' ) );
        $tableTmp = $table;
        $table = array();
        foreach ( $tableTmp as $item )
        {
            $table[] = $item;
        }

        return $GLOBALS['eZUserPasswordCharacterTable'] = $table;
    }

    /*!
     Checks if the supplied content object is a user object ( contains ezuser datatype )

     \param ContentObject

     \return true or false
    */
    static function isUserObject( $contentObject )
    {
        if ( !$contentObject )
        {
            return false;
        }

        eZDataType::loadAndRegisterType( 'ezuser' );

        $contentClass = $contentObject->attribute( 'content_class' );
        $classAttributeList = $contentClass->fetchAttributes();
        foreach( $classAttributeList as $classAttribute )
        {
            if ( $classAttribute->attribute( 'data_type_string' ) == eZUserType::DATA_TYPE_STRING )
                return true;
        }

        return false;
    }

    /*!
     \static
     Creates a password with number of characters equal to \a $passwordLength and returns it.
     If you want pass a value in \a $seed it will be used as basis for the password, if not
     it will use the current time value as seed.
     \note If \a $passwordLength exceeds 16 it will need to generate new seed for the remaining
           characters.
    */
    static function createPassword( $passwordLength, $seed = false )
    {
        $chars = 0;
        $password = '';
        if ( $passwordLength < 1 )
            $passwordLength = 1;
        $decimal = 0;
        while ( $chars < $passwordLength )
        {
            if ( $seed == false )
                $seed = time() . ":" . mt_rand();
            $text = md5( $seed );
            $characterTable = eZUser::passwordCharacterTable();
            $tableCount = count( $characterTable );
            for ( $i = 0; ( $chars < $passwordLength ) and $i < 32; ++$chars, $i += 2 )
            {
                $decimal += hexdec( substr( $text, $i, 2 ) );
                $index = ( $decimal % $tableCount );
                $character = $characterTable[$index];
                $password .= $character;
            }
            $seed = false;
        }
        return $password;
    }

    /*!
     \static
     Will create a hash of the given string. This is used to store the passwords in the database.
    */
    static function createHash( $user, $password, $site, $type, $hash = false )
    {
        $str = '';
        if( $type == self::PASSWORD_HASH_MD5_USER )
        {
            $str = md5( "$user\n$password" );
        }
        else if ( $type == self::PASSWORD_HASH_MD5_SITE )
        {
            $str = md5( "$user\n$password\n$site" );
        }
        else if ( $type == self::PASSWORD_HASH_MYSQL )
        {
            $db = eZDB::instance();
            $hash = $db->escapeString( $password );

            $str = $db->arrayQuery( "SELECT PASSWORD( '$hash' )" );
            $hashes = array_values( $str[0] );
            $str = $hashes[0];
        }
        else if ( $type == self::PASSWORD_HASH_PLAINTEXT )
        {
            $str = $password;
        }
        else if ( $type == self::PASSWORD_HASH_CRYPT )
        {
            if ( $hash )
            {
                $str = crypt( $password, $hash );
            }
            else
            {
                $str = crypt( $password );
            }
        }
        else // self::PASSWORD_HASH_MD5_PASSWORD
        {
            $str = md5( $password );
        }
        eZDebugSetting::writeDebug( 'kernel-user', $str, "ezuser($type)" );
        return $str;
    }

    /*!
     Check if user has got access to the specified module and function

     \param module name
     \param funtion name

     \return Array containg result.
             Array elements : 'accessWord', yes - access allowed
                                            no - access denied
                                            limited - access array describing access included
                              'policies', array containing the policy limitations
                              'accessList', array describing missing access rights
    */
    function hasAccessTo( $module, $function = false )
    {
        $accessArray = $this->accessArray();

        $functionArray = array();
        if ( isset( $accessArray['*']['*'] ) )
        {
            $functionArray = $accessArray['*']['*'];
        }
        if ( isset( $accessArray[$module] ) )
        {
            if ( isset( $accessArray[$module]['*'] ) )
            {
                $functionArray = array_merge_recursive( $functionArray, $accessArray[$module]['*'] );
            }
            if ( $function and isset( $accessArray[$module][$function] ) and $function != '*' )
            {
                $functionArray = array_merge_recursive( $functionArray, $accessArray[$module][$function] );
            }
        }

        if ( !$functionArray )
        {
            return array( 'accessWord' => 'no',
                          'accessList' => array( 'FunctionRequired' => array ( 'Module' => $module,
                                                                               'Function' => $function,
                                                                               'ClassID' => '',
                                                                               'MainNodeID' => '' ),
                                                 'PolicyList' => array() )
                          );
        }

        if ( isset( $functionArray['*'] ) &&
                  ( $functionArray['*'] == '*' || in_array( '*',  $functionArray['*'] ) ) )
        {
            return array( 'accessWord' => 'yes' );
        }

        return array( 'accessWord' => 'limited', 'policies' => $functionArray );
    }

    /**
     * Returns either cached or newly generated accessArray for the user depending on
     * site.ini\[RoleSettings]\EnableCaching setting
     *
     * @access private
     * @return array
     */
    function accessArray()
    {
        if ( !isset( $this->AccessArray ) )
        {
            if ( eZINI::instance()->variable( 'RoleSettings', 'EnableCaching' ) === 'true' )
            {
                $userCache = $this->getUserCache();
                $this->AccessArray = $userCache['access_array'];
            }
            else
            {
                // if role caching is disabled then generate access array on-the-fly.
                $this->AccessArray = $this->generateAccessArray();
            }
        }
        return $this->AccessArray;
    }

    /**
     * Generates the accessArray for the user (for $this).
     * This function is uncached, and used as basis for user cache callback.
     *
     * @internal
     * @return array
     */
    function generateAccessArray()
    {
        $idList = $this->generateGroupIdList();
        $idList[] = $this->attribute( 'contentobject_id' );

        return eZRole::accessArrayByUserID( $idList );
    }

    /*!
     \private
     \static
     Callback which figures out global expiry and returns it.
     */
    static protected function userInfoExpiry()
    {
        /* Figure out when the last update was done */
        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        if ( $handler->hasTimestamp( 'user-info-cache' ) )
        {
            $expiredTimestamp = $handler->timestamp( 'user-info-cache' );
        }
        else
        {
            $expiredTimestamp = time();
            $handler->setTimestamp( 'user-info-cache', $expiredTimestamp );
        }

        return $expiredTimestamp;
    }

    /*
     Returns list of sections which are allowed to assign to the given content object by the user.
    */
    function canAssignToObjectSectionList( $contentObject )
    {
        $access = $this->hasAccessTo( 'section', 'assign' );

        if ( $access['accessWord'] == 'yes' )
        {
            return array( '*' );
        }
        else if ( $access['accessWord'] == 'limited' )
        {
            $userID = $this->attribute( 'contentobject_id' );
            $classID = $contentObject->attribute( 'contentclass_id' );
            $ownerID = $contentObject->attribute( 'owner_id' );
            $sectionID = $contentObject->attribute( 'section_id' );

            $allowedSectionIDList = array();
            foreach ( $access['policies'] as $policy )
            {
                if ( ( isset( $policy['Class'] ) and !in_array( $classID, $policy['Class'] ) ) or
                     ( isset( $policy['Owner']  ) and in_array( 1, $policy['Owner'] ) and $userID != $ownerID ) or
                     ( isset( $policy['Section'] ) and !in_array( $sectionID, $policy['Section'] ) ) )
                {
                    continue;
                }
                if ( isset( $policy['NewSection'] ) && !empty( $policy['NewSection'] ) )
                {
                    $allowedSectionIDList = array_merge( $allowedSectionIDList, $policy['NewSection'] );
                }
                else
                {
                    return array( '*' );
                }
            }
            $allowedSectionIDList = array_unique( $allowedSectionIDList );
            return $allowedSectionIDList;
        }
        return array();
    }

    /*
     Checks whether user can assign the section to the given content object or not.
    */
    function canAssignSectionToObject( $checkSectionID, $contentObject )
    {
        $access = $this->hasAccessTo( 'section', 'assign' );

        if ( $access['accessWord'] == 'yes' )
        {
            return true;
        }
        else if ( $access['accessWord'] == 'limited' )
        {
            $hasSubtreeLimitation = false;
            $subtreeLimitationResult = false;

            // Trying first to discover if a subtree limitation exists.
            // Only the main node of the object is considered!
            foreach ( $access['policies'] as $policy )
            {
                if ( isset( $policy['User_Subtree'] ) )
                {
                    $hasSubtreeLimitation = true;
                    $nodePath = $contentObject->mainNode()->PathString;

                    foreach ( $policy['User_Subtree'] as $path )
                    {
                        if ( strpos( $nodePath, $path ) !== false )
                        {
                            $subtreeLimitationResult = true;
                        }
                    }
                    continue;
                }
            }

            // If a subtree limitation exists and none of the path corresponds then the user have not enough rights.
            if ( $hasSubtreeLimitation && !$subtreeLimitationResult )
            {
                return false;
            }

            unset( $hasSubtreeLimitation, $subtreeLimitationResult, $nodePath );

            $userID = $this->attribute( 'contentobject_id' );
            $classID = $contentObject->attribute( 'contentclass_id' );
            $ownerID = $contentObject->attribute( 'owner_id' );
            $sectionID = $contentObject->attribute( 'section_id' );

            foreach ( $access['policies'] as $policy )
            {
                if ( ( isset( $policy['Class'] ) and !in_array( $classID, $policy['Class'] ) ) or
                     ( isset( $policy['Owner']  ) and in_array( 1, $policy['Owner'] ) and $userID != $ownerID ) or
                     ( isset( $policy['Section'] ) and !in_array( $sectionID, $policy['Section'] ) ) )
                {
                    continue;
                }
                if ( isset( $policy['NewSection'] ) )
                {
                    if ( is_array( $policy['NewSection'] ) and in_array( $checkSectionID, $policy['NewSection'] ) )
                    {
                        return true;
                    }
                }
                else
                {
                    return true;
                }
            }
        }
        return false;
    }

    /*
     Checks whether user has privileges to assign the section or not at all.
    */
    function canAssignSection( $checkSectionID )
    {
        $access = $this->hasAccessTo( 'section', 'assign' );

        if ( $access['accessWord'] == 'yes' )
        {
            return true;
        }
        else if ( $access['accessWord'] == 'limited' )
        {
            foreach ( $access['policies'] as $policy )
            {
                if ( isset( $policy['NewSection'] ) )
                {
                    if ( in_array( $checkSectionID, $policy['NewSection'] ) )
                    {
                        return true;
                    }
                }
                else
                {
                    return true;
                }
            }
        }
        return false;
    }

    /*
     Returns list of sections allowed to assign for the user.
    */
    function canAssignSectionList()
    {
        $access = $this->hasAccessTo( 'section', 'assign' );

        if ( $access['accessWord'] == 'yes' )
        {
            return array( '*' );
        }
        else if ( $access['accessWord'] == 'limited' )
        {
            $allowedSectionIDList = array();
            foreach ( $access['policies'] as $policy )
            {
                if ( isset( $policy['NewSection'] ) )
                {
                    if ( is_array( $policy['NewSection'] ) && !empty( $policy['NewSection'] ) )
                    {
                        $allowedSectionIDList = array_merge( $allowedSectionIDList, $policy['NewSection'] );
                    }
                }
                else
                {
                    return array( '*' );
                }
            }
            $allowedSectionIDList = array_unique( $allowedSectionIDList );
            return $allowedSectionIDList;
        }
        return array();
    }

    /*
     Returns list of classes allowed to assign to the given section for the user.
    */
    function canAssignSectionToClassList( $checkSectionID )
    {
        $access = $this->hasAccessTo( 'section', 'assign' );

        if ( $access['accessWord'] == 'yes' )
        {
            return array( '*' );
        }
        else if ( $access['accessWord'] == 'limited' )
        {
            $allowedClassList = array();
            foreach ( $access['policies'] as $policy )
            {
                if ( !isset( $policy['NewSection'] ) or in_array( $checkSectionID, $policy['NewSection'] ) )
                {
                    if ( isset( $policy['Class'] ) )
                    {
                        $allowedClassList = array_merge( $allowedClassList, $policy['Class'] );
                    }
                    else
                    {
                        return array( '*' );
                    }
                }
            }

            if ( !empty( $allowedClassList ) )
            {
                // Now we are trying to fetch classes by collected ids list to return
                // class list consisting of existing classes's identifiers only.
                $allowedClassList = array_unique( $allowedClassList );
                $classList = eZContentClass::fetchList( eZContentClass::VERSION_STATUS_DEFINED, false, false, null, null, $allowedClassList );
                if ( is_array( $classList ) && !empty( $classList ) )
                {
                    $classIdentifierList = array();
                    foreach( $classList as $class )
                    {
                        $classIdentifierList[] = $class['identifier'];
                    }
                    return $classIdentifierList;
                }
            }
        }
        return array();
    }

    /*
     Evaluates if $this user has access to the view $viewName based on its policy functions and
     checks if the assigned to the view functions expression is valid and handles errors if it is not.
     Returns true if access is allowed, false if access is denied.
    */
    function hasAccessToView( $module, $viewName, &$params )
    {
        $validView = false;
        $accessAllowed = false;
        if ( $module->singleFunction() )
        {
            $info = $module->attribute( 'info' );
            if ( isset( $info['function'] ) )
            {
                $validView = true;
                if ( isset( $info['function']['functions'] ) && !empty( $info['function']['functions'] ) )
                {
                    $functions = $info['function']['functions'];
                }
            }
        }
        else
        {
            $views = $module->attribute( 'views' );
            if ( isset( $views[$viewName] ) )
            {
                $view = $views[$viewName];
                $validView = true;
                if ( isset( $view['functions'] ) && !empty( $view['functions'] ) )
                {
                    $functions = $view['functions'];
                }
            }
        }

        if ( $validView )
        {
            if ( isset( $functions ) )
            {
                if ( is_array( $functions ) )
                {
                    $funcExpression = false;
                    $accessAllowed = true;
                    foreach ( $functions as $function )
                    {
                        if ( empty( $function ) )
                        {
                            $funcExpression = false;
                            $accessAllowed = false;
                            break;
                        }
                        else if ( is_string( $function ) )
                        {
                            if ( $funcExpression )
                            {
                                $funcExpression .= ' and ';
                            }
                            $funcExpression .= '( ' . $function . ' )';
                        }
                    }
                }
                else if ( is_string( $functions ) )
                {
                    $funcExpression = $functions;
                }
                else
                {
                    $funcExpression = false;
                    $accessAllowed = true;
                }

                if ( $funcExpression )
                {
                    // Validate and evaluate functions expression.
                    // Lets construct functions's expression ready for evaluating first.
                    $pS = '/(?<=\b)';
                    $pE = '(?=\b)/';

                    $moduleName = $module->attribute( 'name' );
                    $availableFunctions = $module->attribute( 'available_functions' );
                    if ( is_array( $availableFunctions ) and
                         !empty( $availableFunctions ) )
                    {
                        $pattern = $pS . '(' . implode( '|', array_keys( $availableFunctions ) ) . ')' . $pE;
                        $matches = array();
                        if ( preg_match_all( $pattern, ' ' . $funcExpression . ' ', $matches ) > 0 )
                        {
                            $patterns = array();
                            $replacements = array();
                            $matches = array_unique( $matches[1] );
                            foreach ( $matches as $match )
                            {
                                if ( !isset( $replacements[$match] ) )
                                {
                                    $accessResult = $this->hasAccessTo( $moduleName, $match );
                                    if ( $accessResult['accessWord'] == 'no' )
                                    {
                                        $replacements[$match] = 'false';
                                        $params['accessList'] = $accessResult['accessList'];
                                    }
                                    else
                                    {
                                        $replacements[$match] = 'true';
                                        if ( $accessResult['accessWord'] == 'limited' )
                                        {
                                            $params['Limitation'] = $accessResult['policies'];
                                            $GLOBALS['ezpolicylimitation_list'][$this->ContentObjectID][$moduleName][$match] = $params['Limitation'];
                                        }
                                    }
                                    $patterns[$match] = $pS . $match . $pE;
                                }
                            }
                            $funcExpression = preg_replace( $patterns, $replacements, ' ' . $funcExpression . ' ' );
                        }
                    }
                    $funcExpressionForEval = $funcExpression;

                    // continue to validate expression
                    $words = array();
                    $words[] = $pS . 'and' . $pE;
                    $words[] = $pS . 'or' . $pE;
                    $words[] = $pS . 'true' . $pE;
                    $words[] = $pS . 'false' . $pE;
                    $pS = '/(?<=[^&|])';
                    $pE = '(?=[^&|])/';
                    $words[] = $pS . '\|\|' . $pE;
                    $words[] = $pS . '&&' . $pE;
                    $words[] = '/[\(\)]/';

                    $replacement = '';
                    $funcExpression = preg_replace( $words, $replacement, ' ' . $funcExpression . ' ' );

                    $funcExpression = trim( $funcExpression );

                    if ( empty( $funcExpression ) )
                    {
                        // if expression is valid then evaluate value of the $functionsToEvaluate string
                        ob_start();
                        $ret = eval( "\$accessAllowed = ( bool ) ( $funcExpressionForEval );" );
                        $buffer = ob_get_contents();
                        ob_end_clean();

                        // if we get any error while evaluating then set result to false
                        if ( !empty( $buffer ) or $ret === false )
                        {
                            eZDebug::writeError( "There was an error while evaluating the policy functions value of the '$moduleName/$viewName' view. " .
                                                 "Please check the '$moduleName/module.php' file." );
                            $accessAllowed = false;
                        }
                    }
                    else
                    {
                        eZDebug::writeError( "There is a mistake in the functions array data of the '$moduleName/$viewName' view. " .
                                             "Please check the '$moduleName/module.php' file." );
                        $accessAllowed = false;
                    }
                }
            }
            else
            {
                $moduleName = $module->attribute( 'name' );
                $accessResult = $this->hasAccessTo( $moduleName );
                if ( $accessResult['accessWord'] == 'no' )
                {
                    $params['accessList'] = $accessResult['accessList'];
                    $accessAllowed = false;
                }
                else
                {
                    $accessAllowed = true;
                    if ( $accessResult['accessWord'] == 'limited' )
                    {
                        $params['Limitation'] = $accessResult['policies'];
                        $GLOBALS['ezpolicylimitation_list'][$this->ContentObjectID][$moduleName]['*'] = $params['Limitation'];
                    }
                }
            }
        }

        return $accessAllowed;
    }

    /*!
     \return an array of roles which the user is assigned to
    */
    function roles()
    {
        $groups = $this->attribute( 'groups' );
        $groups[] = $this->attribute( 'contentobject_id' );
        return eZRole::fetchByUser( $groups );
    }

    /*!
     \return an array of role ids which the user is assigned to
    */
    function roleIDList()
    {
        if ( eZINI::instance()->variable( 'RoleSettings', 'EnableCaching' ) === 'true' )
        {
            $userCache = $this->getUserCache();
            return $userCache['roles'];
        }

        $groups = $this->attribute( 'groups' );
        $groups[] = $this->attribute( 'contentobject_id' );
        return eZRole::fetchIDListByUser( $groups );
    }

    /*!
     \return an array of limited assignments
    */
    function limitList( $useGroupsCache = true )
    {
        if ( $useGroupsCache )
            $groups = $this->groups();
        else
            $groups = $this->generateGroupIdList();
        $groups[] = $this->attribute( 'contentobject_id' );
        $groups = implode( ', ', $groups );

        $db = eZDB::instance();

        $limitationsArray = $db->arrayQuery( "SELECT DISTINCT limit_identifier, limit_value
                                              FROM ezuser_role
                                              WHERE contentobject_id IN ( $groups )" );

        return $limitationsArray;
    }

    /*!
     \return an array of values of limited assignments
    */
    function limitValueList()
    {
        if ( eZINI::instance()->variable( 'RoleSettings', 'EnableCaching' ) === 'true' )
        {
            $userCache = $this->getUserCache();
            return $userCache['role_limitations'];
        }

        $limitValueList = array();
        $limitList      = $this->limitList();
        foreach ( $limitList as $limit )
        {
            $limitValueList[] = $limit['limit_value'];
        }

        return $limitValueList;
    }

    function contentObject()
    {
        if ( isset( $this->ContentObjectID ) and $this->ContentObjectID )
        {
            return eZContentObject::fetch( $this->ContentObjectID );
        }
        return null;
    }

    /**
     * Returns the eZUserAccountKey associated with this user
     *
     * @return eZUserAccountKey
     */
    public function accountKey()
    {
        return eZUserAccountKey::fetchByUserID( $this->ContentObjectID );
    }

    /*!
     Returns true if it's a real user which is logged in. False if the user
     is the default user or the fallback buildtin user.
    */
    function isLoggedIn()
    {
        if ( $this->ContentObjectID == self::anonymousId() or
             $this->ContentObjectID == -1 )
        {
            return false;
        }
        return true;
    }

    /*!
     \return an array of id's with all the groups the user belongs to.
    */
    function groups( $asObject = false )
    {
        if ( $asObject == true )
        {
            if ( !isset( $this->GroupsAsObjects ) )
            {
                $db = eZDB::instance();
                $contentobjectID = $this->attribute( 'contentobject_id' );
                $userGroups = $db->arrayQuery( "SELECT d.*, c.path_string
                                                FROM ezcontentobject_tree  b,
                                                     ezcontentobject_tree  c,
                                                     ezcontentobject d
                                                WHERE b.contentobject_id='$contentobjectID' AND
                                                      b.parent_node_id = c.node_id AND
                                                      d.id = c.contentobject_id
                                                ORDER BY c.contentobject_id  ");
                $userGroupArray = array();
                $pathArray = array();
                foreach ( $userGroups as $group )
                {
                    $pathItems = explode( '/', $group["path_string"] );
                    array_pop( $pathItems );
                    array_pop( $pathItems );
                    foreach ( $pathItems as $pathItem )
                    {
                        if ( $pathItem != '' && $pathItem > 1 )
                            $pathArray[] = $pathItem;
                    }
                    $userGroupArray[] = new eZContentObject( $group );
                }
                $pathArray = array_unique( $pathArray );

                if ( !empty( $pathArray ) )
                {
                    $extraGroups = $db->arrayQuery( "SELECT d.*
                                                FROM ezcontentobject_tree  c,
                                                     ezcontentobject d
                                                WHERE c.node_id in ( " . implode( ', ', $pathArray ) . " ) AND
                                                      d.id = c.contentobject_id
                                                ORDER BY c.contentobject_id  ");
                    foreach ( $extraGroups as $group )
                    {
                        $userGroupArray[] = new eZContentObject( $group );
                    }
                }

                $this->GroupsAsObjects = $userGroupArray;
            }
            return $this->GroupsAsObjects;
        }
        else
        {
            if ( !isset( $this->Groups ) )
            {
                if ( eZINI::instance()->variable( 'RoleSettings', 'EnableCaching' ) === 'true' )
                {
                    $userCache = $this->getUserCache();
                    $this->Groups = $userCache['groups'];
                }
                else
                {
                    $this->Groups = $this->generateGroupIdList();
                }
            }
            return $this->Groups;
        }
    }

    /**
     * Generate list of group id's
     *
     * @since 4.4
     * @return array
     */
    protected function generateGroupIdList()
    {
        $db         = eZDB::instance();
        $userID     = $this->ContentObjectID;
        $userGroups = $db->arrayQuery( "SELECT  c.contentobject_id as id,c.path_string
                                        FROM ezcontentobject_tree  b,
                                             ezcontentobject_tree  c
                                        WHERE b.contentobject_id='$userID' AND
                                              b.parent_node_id = c.node_id
                                        ORDER BY c.contentobject_id  ");
        $pathArray      = array();
        $userGroupArray = array();
        foreach ( $userGroups as $group )
        {
            $pathItems = explode( '/', $group["path_string"] );
            array_pop( $pathItems );
            array_pop( $pathItems );
            foreach ( $pathItems as $pathItem )
            {
                if ( $pathItem != '' && $pathItem > 1 )
                    $pathArray[] = $pathItem;
            }
            $userGroupArray[] = $group['id'];
        }

        if ( !empty( $pathArray ) )
        {
            $pathArray = array_unique ($pathArray);
            $extraGroups = $db->arrayQuery( "SELECT c.contentobject_id as id
                                            FROM ezcontentobject_tree  c,
                                                 ezcontentobject d
                                            WHERE c.node_id in ( " . implode( ', ', $pathArray ) . " ) AND
                                                  d.id = c.contentobject_id
                                            ORDER BY c.contentobject_id  ");
            foreach ( $extraGroups as $group )
            {
                $userGroupArray[] = $group['id'];
            }
        }
        return $userGroupArray;
    }

    /*!
     Checks if user is logged in, if not and the site requires user login for access
     a module redirect is returned.

     \return null, user login not required.
    */
    function checkUser( &$siteBasics, $uri )
    {
        $http = eZHTTPTool::instance();
        $check = array( "module" => "user",
                        "function" => "login" );
        if ( eZSession::issetkey( 'eZUserLoggedInID', false ) &&
             $http->sessionVariable( "eZUserLoggedInID" ) != '' &&
             $http->sessionVariable( "eZUserLoggedInID" ) != self::anonymousId() )
        {
            $currentUser = eZUser::currentUser();
            if ( !$currentUser->isEnabled() )
            {
                eZUser::logoutCurrent();
                $currentUser = eZUser::currentUser();
            }
            else
            {
                return null;
            }
        }

        $ini        = eZINI::instance();
        $moduleName = $uri->element();
        $viewName   = $uri->element( 1 );
        $anonymousAccessList = $ini->variable( "SiteAccessSettings", "AnonymousAccessList" );
        foreach ( $anonymousAccessList as $anonymousAccess )
        {
            $elements = explode( '/', $anonymousAccess );
            if ( !isset( $elements[1] ) )
            {
                if ( $moduleName == $elements[0] )
                {
                    return null;
                }
            }
            else
            {
                if ( $moduleName == $elements[0] and
                     $viewName == $elements[1] )
                {
                    return null;
                }
            }
        }

        return $check;
    }

    /*!
     Funtion performed before user login info is collected.
     It's optional to implement this function in new login handler.

     \return @see eZUserLoginHandler::checkUser()
    */
    function preCollectUserInfo()
    {
        return array( 'module' => 'user', 'function' => 'login' );
    }

    /*!
     Function performed after user login info has been collected.
     Store login data as array:
     array( 'login' => <username>,
            'password' = <password> )
     to session variable EZ_LOGIN_HANDLER_USER_INFO for automatic processing of login data.

     \return @see eZUserLoginHandler::checkUser()
     */
    function postCollectUserInfo()
    {
        return true;
    }

    /*!
     Check if login handler require special login URI

     \return Special login uri. If false, use system standard login uri.
    */
    function loginURI()
    {
        return false;
    }

    /*!
     Check if login handler require forced login at user check.

     \return true if force login on user check, false if not.
    */
    function forceLogin()
    {
        return false;
    }

    /**
     * Creates the cache path if it doesn't exist, and returns the cache
     * directory. The $userId parameter is used to create multi-level directory names
     *
     * @params int $userId
     * @return string Cache directory for the user
     */
    static function getCacheDir( $userId = 0 )
    {
        $dir = eZSys::cacheDirectory() . '/user-info' . eZDir::createMultilevelPath( $userId, 5 );

        if ( !is_dir( $dir ) )
        {
            eZDir::mkdir( $dir, false, true );
        }
        return $dir;
    }

    /**
     * Expire user access / info cache globally
     */
    static function cleanupCache()
    {
        eZExpiryHandler::registerShutdownFunction();
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( 'user-info-cache', time() );
        $handler->store();
    }

    /**
     * Returns the filename for a cache file with user information
     *
     * @deprecated In 4.4.0
     * @params int $userId
     * @return string|false Filename of the cachefile, or false when the user should not be cached
     */
    static function getCacheFilename( $userId )
    {
        $ini = eZINI::instance();
        $cacheUserPolicies = $ini->variable( 'RoleSettings', 'UserPolicyCache' );
        if ( $cacheUserPolicies === 'enabled' )
        {
            return eZUser::getCacheDir( $userId ). '/user-'. $userId . '.cache.php';
        }
        else if ( $cacheUserPolicies !== 'disabled' )
        {
            $cachableIDs = explode( ',', $cacheUserPolicies );
            if ( in_array( $userId, $cachableIDs ) )
            {
                return eZUser::getCacheDir( $userId ). '/user-'. $userId . '.cache.php';
            }
        }
        return false;
    }

    static function fetchUserClassList( $asObject = false, $fields = false )
    {
        // Get names of user classes
        if ( !$asObject and
             is_array( $fields ) and
             !empty( $fields ) )
        {
            $fieldsFilter = '';
            $i = 0;
            foreach ( $fields as $fieldName )
            {
                if ( $i > 0 )
                    $fieldsFilter .= ', ';
                $fieldsFilter .= 'ezcontentclass.' . $fieldName;
                $i++;
            }
        }
        else
        {
            $fieldsFilter = 'ezcontentclass.*';
        }
        $db = eZDB::instance();
        $userClasses = $db->arrayQuery( "SELECT $fieldsFilter
                                         FROM ezcontentclass, ezcontentclass_attribute
                                         WHERE ezcontentclass.id = ezcontentclass_attribute.contentclass_id AND
                                               ezcontentclass.version = " . eZContentClass::VERSION_STATUS_DEFINED ." AND
                                               ezcontentclass_attribute.version = 0 AND
                                               ezcontentclass_attribute.data_type_string = 'ezuser'" );

        return eZPersistentObject::handleRows( $userClasses, "eZContentClass", $asObject );
    }

    static function fetchUserClassNames()
    {
        $userClassNames = array();
        $userClasses = eZUser::fetchUserClassList( false, array( 'identifier' ) );
        foreach ( $userClasses as $class )
        {
            $userClassNames[] = $class[ 'identifier' ];
        }
        return $userClassNames;
    }

    static function fetchUserGroupClassNames()
    {
        // Get names of user classes
        $userClassNames = array();
        $userClasses = eZUser::fetchUserClassList( false, array( 'identifier' ) );
        foreach ( $userClasses as $class )
        {
            $userClassNames[] = $class[ 'identifier' ];
        }

        // Get names of all allowed content-classes for the Users subtree
        $contentIni = eZINI::instance( "content.ini" );
        $userGroupClassNames = array();
        if ( $contentIni->hasVariable( 'ClassGroupIDs', 'Users' ) and
             is_numeric( $usersClassGroupID = $contentIni->variable( 'ClassGroupIDs', 'Users' ) ) and
             count( $usersClassList = eZContentClassClassGroup::fetchClassList( eZContentClass::VERSION_STATUS_DEFINED, $usersClassGroupID ) ) > 0 )
        {
            foreach ( $usersClassList as $userClass )
            {
                $userGroupClassNames[] = $userClass->attribute( 'identifier' );
            }
        }

        // Get names of user-group classes
        $groupClassNames = array_diff( $userGroupClassNames, $userClassNames );
        return $groupClassNames;
    }

    /*!
     Checks the password for validity
     \static
     \return true when password is valid by length and not empty, false if not
    */
    static function validatePassword( $password )
    {
        $ini = eZINI::instance();
        $minPasswordLength = $ini->variable( 'UserSettings', 'MinPasswordLength' );
        if ( $password !== false and
             $password !== null and
             strlen( $password ) >= (int) $minPasswordLength )
        {
            return true;
        }

        return false;
    }

    /**
     * Validates user login name using site.ini[UserSettings]UserNameValidationRegex[]
     *
     * @static
     * @since Version 4.1
     * @param string $loginName that we want to validate.
     * @param string $errorText by reference for details if validation fails.
     * @return bool Indicates if validation failed (false) or not (true).
     */
    static function validateLoginName( $loginName, &$errorText )
    {
        $ini = eZINI::instance();
        $regexList = $ini->variable( 'UserSettings', 'UserNameValidationRegex' );
        $errorTextList = $ini->variable( 'UserSettings', 'UserNameValidationErrorText' );
        foreach ( $regexList as $key => $regex )
        {
            if( preg_match( $regex, $loginName) )
            {
                if ( isset( $errorTextList[$key] ) )
                    $errorText = $errorTextList[$key];
                else
                    $errorText = $ini->variable( 'UserSettings', 'DefaultUserNameValidationErrorText' );
                return false;
            }
        }
        return true;
    }

    /**
     * Gets the id of the anonymous user.
     *
     * @static
     * @return int User id of anonymous user.
     */
    public static function anonymousId()
    {
        if ( self::$anonymousId === null )
        {
            $ini = eZINI::instance();
            self::$anonymousId = (int)$ini->variable( 'UserSettings', 'AnonymousUserID' );
            $GLOBALS['eZUserBuiltins'] = array( self::$anonymousId );
        }
        return self::$anonymousId;
    }

    /*!
      Returns the IDs of content classes that contain user accounts
    */
    public static function contentClassIDs()
    {
        $userContentClassIDs = array();

        $ini = eZINI::instance( 'content.ini' );
        $userDatatypes = $ini->variable( "DataTypeSettings", "UserDataTypes" );

        $userContentClassIDs = array();
        foreach ( $userDatatypes as $datatypeIdentifier )
        {
            $userContentClassIDs = array_merge( $userContentClassIDs, eZContentClass::fetchIDListContainingDatatype( $datatypeIdentifier ) );
        }

        return $userContentClassIDs;
    }

    public function canLoginToSiteAccess( $access )
    {
        $siteAccessResult = $this->hasAccessTo( 'user', 'login' );
        $hasAccessToSite = false;

        if ( $siteAccessResult[ 'accessWord' ] == 'limited' )
        {
            $siteNameCRC = eZSys::ezcrc32( $access[ 'name' ] );
            $policyChecked = false;
            foreach ( $siteAccessResult['policies'] as $policy )
            {
                if ( isset( $policy['SiteAccess'] ) )
                {
                    $policyChecked = true;
                    if ( in_array( $siteNameCRC, $policy['SiteAccess'] ) )
                    {
                        $hasAccessToSite = true;
                        break;
                    }
                }
            }

            if ( !$policyChecked )
            {
                $hasAccessToSite = true;
            }
        }
        else if ( $siteAccessResult[ 'accessWord' ] == 'yes' )
        {
            $hasAccessToSite = true;
        }

        return $hasAccessToSite;
    }

    /// \privatesection
    public $Login;
    public $Email;
    public $PasswordHash;
    public $PasswordHashType;
    public $Groups;
    public $OriginalPassword;
    public $OriginalPasswordConfirm;

    /**
     * Holds user cache like user info and access array
     *
     * @since 4.4
     * @see eZUser::getUserCache()
     * @var array|null
     */
    protected $UserCache = null;

    /**
     * Used to keep track that a logout was performed, and therefore prevent
     * auto-login from happening if an SSO is used
     * @var bool
     * @since 4.3
     */
    protected static $userHasLoggedOut = false;
}

?>
