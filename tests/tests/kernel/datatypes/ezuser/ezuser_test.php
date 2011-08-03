<?php
/**
 * File containing the eZUserTest class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZUserTest extends ezpDatabaseTestCase
{
    public $username = 'admin';
    public $password = 'publish';
    public $email = 'nospam@ez.no';

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZUser Unit Tests" );
    }

    public function setUp()
    {
        parent::setUp();

        // Set HashType to md5_password (to update the password_hash in the ezuser table)
        ezpINIHelper::setINISetting( 'site.ini', 'UserSettings', 'HashType', 'md5_password' );
        ezpINIHelper::setINISetting( 'site.ini', 'UserSettings', 'UpdateHash', 'true' );
        ezpINIHelper::setINISetting( 'site.ini', 'UserSettings', 'AuthenticateMatch', 'login;email' );

        // Login the user
        $userClass = eZUserLoginHandler::instance( 'standard' );
        $user = $userClass->loginUser( $this->username, $this->password );

        // Verify that the username and password were accepted
        if ( !( $user instanceof eZUser ) )
        {
            self::markTestSkipped( "User {$this->username} is not in database.");
        }
    }

    public function tearDown()
    {
        ezpINIHelper::restoreINISettings();

        parent::tearDown();
    }

    /**
     * Test for issue #16328: Wrong hash stored in database on hash update in ezUser.php
     */
    public function testPasswordHashSamePasswordToUser()
    {
        // Get the password_hash
        $db = eZDB::instance();
        $rows = $db->arrayQuery( "SELECT * FROM ezuser where login = '{$this->username}'" );
        if ( count( $rows ) !== 1 )
        {
            $this->fail( "User {$this->username} is not in database.");
        }
        // Not used in this test
        $passwordHashMD5Password = $rows[0]['password_hash'];

        // Above it was only the setup for the test, the real test begins now
        // Set HashType to md5_user (password_hash in the ezuser table is updated again)
        ezpINIHelper::setINISetting( 'site.ini', 'UserSettings', 'HashType', 'md5_user' );

        // Login the user with email instead of username
        $userClass = eZUserLoginHandler::instance( 'standard' );
        $user = $userClass->loginUser( $this->email, $this->password );

        // Verify that the email and password were accepted
        if ( !( $user instanceof eZUser ) )
        {
            $this->fail( "User {$this->email} is not in database.");
        }

        // Get the password_hash
        $db = eZDB::instance();
        $rows = $db->arrayQuery( "SELECT * FROM ezuser where login = '{$this->username}'" );
        $passwordHashMD5User = $rows[0]['password_hash'];

        // The value that is expected to be saved in the ezuser table after updating the HashType to md5_user
        // (using the username and not the email address, which caused issue #16328)
        $hashMD5Expected = md5( "{$this->username}\n{$this->password}" );

        // Verify that the 2 password hashes saved above are the same
        $this->assertEquals( $hashMD5Expected, $passwordHashMD5User );

        // Verify that the user can still login with username
        $userClass = eZUserLoginHandler::instance( 'standard' );
        $user = $userClass->loginUser( $this->username, $this->password );

        // Verify that the username and password were accepted
        if ( !( $user instanceof eZUser ) )
        {
            $this->fail( "User {$this->username} is not in database.");
        }
    }
}
?>
