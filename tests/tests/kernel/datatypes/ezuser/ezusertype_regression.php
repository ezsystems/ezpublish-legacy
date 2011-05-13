<?php
/**
 * File containing the eZUserTypeRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZUserTypeRegression extends ezpDatabaseTestCase
{
    const USER_PASSWORD_HASH = 1234;
    const USER_PASSWORD_HASH_ID = 'md5_password';

    protected $userObject;
    protected $userEmail;
    protected $userLogin;
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( __CLASS__ . " tests" );
    }

    public function setUp()
    {
        parent::setUp();

        $this->userLogin = uniqid( '', true );
        $this->userEmail = "{$this->userLogin}@ez.no";

        $ini = eZINI::instance();
        $params = array(
            'creator_id' => 14,
            'class_identifier' => 'user',
            'parent_node_id' => $ini->variable( 'UserSettings', 'DefaultUserPlacement' ),
            'attributes' => array(
                'first_name' => 'foo',
                'last_name' => 'bar' ),
        );

        $contentObject = eZContentFunctions::createAndPublishObject( $params );

        if( !$contentObject instanceof eZContentObject )
        {
            die( 'Impossible to create user object' );
        }

        $this->userObject = $contentObject;
    }

    public function tearDown()
    {
        $this->userObject = null;
        $this->userEmail = null;
        $this->userLogin = null;
        parent::tearDown();
    }

    /**
     * Checks that eZUserType::toString and eZUserType::fromString
     * handles disabled users.
     *
     * So if you export users from one site to another you will lose this "data".
     * This has the potential for disabled users to become enabled.
     *
     * @link http://issues.ez.no/16111
     */
    public function testToStringHandlesDisabledAccount()
    {
        $this->createNeweZUser();
        $this->disableCurrentUser();

        $dataMap = $this->userObject->dataMap();
        $userAccount = $dataMap['user_account']->toString();

        $this->assertEquals(
            join( '|', array( $this->userLogin, $this->userEmail, self::USER_PASSWORD_HASH, self::USER_PASSWORD_HASH_ID, 0 ) ),
            $userAccount
        );
    }

    /**
     * @link http://issues.ez.no/16111
     */
    public function testToStringHandlesEnabledAccount()
    {
        $this->createNeweZUser();
        $this->enableCurrentUser();

        $dataMap = $this->userObject->dataMap();
        $userAccount = $dataMap['user_account']->toString();

        $this->assertEquals(
            join( '|', array( $this->userLogin, $this->userEmail, self::USER_PASSWORD_HASH, self::USER_PASSWORD_HASH_ID, 1 ) ),
            $userAccount
        );
    }

    /**
     * @link http://issues.ez.no/16111
     */
    public function testFromStringHandlesDisabledAccount()
    {
        $dataMap = $this->userObject->dataMap();
        $tmpLogin = __METHOD__;
        $tmpEmail = "{$tmpLogin}@ez.no";
        $userAccount = $dataMap['user_account']->fromString(
            join( '|', array( $tmpLogin, $tmpEmail, self::USER_PASSWORD_HASH, self::USER_PASSWORD_HASH_ID, 0 ) )
        );
        $userAccount->store();


        $tempObject = eZContentObject::fetch( $this->userObject->attribute( 'id' ) );
        $dataMap = $tempObject->dataMap();

        $eZUser = $dataMap['user_account']->attribute( 'content' );

        $this->assertEquals(
            $eZUser->attribute( 'login' ), $tmpLogin,
            "Login should be {$tmpLogin}"
        );
        $this->assertEquals(
            $eZUser->attribute('email'), $tmpEmail,
            "Email should be {$tmpEmail}"
        );
        $this->assertEquals(
            $eZUser->attribute('password_hash'), self::USER_PASSWORD_HASH,
            "Password hash should be '" . self::USER_PASSWORD_HASH . "'"
        );
        $this->assertEquals(
            $eZUser->attribute('password_hash_type'), eZUser::passwordHashTypeID( self::USER_PASSWORD_HASH_ID ),
            "Password hash type should be " . eZUser::passwordHashTypeID( self::USER_PASSWORD_HASH_ID ) . " found " . $eZUser->attribute( 'password_hash_type' )
        );
        $this->assertFalse(
            $eZUser->attribute( 'is_enabled' ),
            'User should be disabled'
        );
    }

    /**
     * @link http://issues.ez.no/16111
     */
    public function testFromStringHandlesEnabledAccount()
    {
        $dataMap = $this->userObject->dataMap();
        $tmpLogin = __METHOD__;
        $tmpEmail = "{$tmpLogin}@ez.no";
        $userAccount = $dataMap['user_account']->fromString(
            join( '|', array( $tmpLogin, $tmpEmail, self::USER_PASSWORD_HASH, self::USER_PASSWORD_HASH_ID, 1 ) )
        );
        $userAccount->store();


        $tempObject = eZContentObject::fetch( $this->userObject->attribute( 'id' ) );
        $dataMap = $tempObject->dataMap();

        $eZUser = $dataMap['user_account']->attribute( 'content' );

        $this->assertEquals(
            $eZUser->attribute( 'login' ), $tmpLogin,
            "Login should be {$tmpLogin}"
        );
        $this->assertEquals(
            $eZUser->attribute('email'), $tmpEmail,
            "Email should be {$tmpEmail}"
        );
        $this->assertEquals(
            $eZUser->attribute('password_hash'), self::USER_PASSWORD_HASH,
            "Password hash should be '" . self::USER_PASSWORD_HASH . "'"
        );
        $this->assertEquals(
            $eZUser->attribute('password_hash_type'), eZUser::passwordHashTypeID( self::USER_PASSWORD_HASH_ID ),
            "Password hash type should be " . eZUser::passwordHashTypeID( self::USER_PASSWORD_HASH_ID ) . " found " . $eZUser->attribute( 'password_hash_type' )
        );
        $this->assertTrue(
            $eZUser->attribute( 'is_enabled' ),
            'User should be enabled'
        );
    }

    /**
     * Enables the current user
     */
    private function enableCurrentUser()
    {
        $this->updateUserStatus( $enabled = true );
    }

    /**
     * Disables the current user
     */
    private function disableCurrentUser()
    {
        $this->updateUserStatus( $enabled = false );
    }

    private function updateUserStatus( $enabled = true )
    {
        $userSetting = eZUserSetting::fetch(
            $this->userObject->attribute( 'id' )
        );
        $userSetting->setAttribute( 'is_enabled', (int)$enabled );
        $userSetting->store();

        $eZUser = eZUser::fetch( $this->userObject->attribute( 'id' ) );

        if( $enabled )
            $this->assertTrue( $eZUser->isEnabled() );
        else
            $this->assertFalse( $eZUser->isEnabled() );
    }

    private function createNeweZUser()
    {
        $user = eZUser::create( $this->userObject->attribute( 'id' ) );

        $user->setAttribute( 'login', $this->userLogin );
        $user->setAttribute( 'email', $this->userEmail );
        $user->setAttribute( 'password_hash', self::USER_PASSWORD_HASH );
        $user->setAttribute( 'password_hash_type', eZUser::passwordHashTypeID( self::USER_PASSWORD_HASH_ID ) );
        $user->store();

        if( !$user instanceof eZUser )
        {
            die( 'FAIL : not an eZUser' );
        }
    }
}
