<?php
/**
 * File containing the eZUserAuthenticationTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZUserAuthenticationTest extends ezpTestCase
{
    protected $backupGlobals = false;

    /**
     * Unit test for eZUser::createHash()
     * @dataProvider hashDataProvider
     */
    public function testCreateHash( $user, $password, $site, $type, $hash, $expected )
    {
        $this->assertSame( $expected, eZUser::createHash( $user, $password, $site, $type, $hash ) );
    }

    /**
     * Unit test for eZUser::authenticateHash()
     * @dataProvider hashDataProvider
     */
    public function testAuthenticateHashSuccess( $user, $password, $site, $type, $hash, $expected )
    {
        $this->assertTrue( eZUser::authenticateHash( $user, $password, $site, $type, $expected ) );
    }

    /**
     * Unit test for eZUser::authenticateHash()
     * @dataProvider hashDataProviderFail
     */
    public function testAuthenticateHashFail( $user, $password, $site, $type, $hash, $expected )
    {
        $this->assertFalse( eZUser::authenticateHash( $user, $password, $site, $type, $expected ) );
    }

    /**
     * Data provider for successful hash creation/authentication
     * @see testCreateHash()
     * @see testAuthenticateHashSuccess()
     */
    public static function hashDataProvider()
    {
        return array(
            array( 'admin', 'password', 'ez.no', eZUser::PASSWORD_HASH_MD5_USER, false, '51f9ee797053cbfa8c77e8fa273f5518' ),
            array( 'AVeryLongUsername', 'wîŧħAQuiteßécurePasswörð', 'ez.no', eZUser::PASSWORD_HASH_MD5_USER, false, '06fe380a1bce9e9ffc9858413f199f38' ),
            array( 'admin', 'password', 'ez.no', eZUser::PASSWORD_HASH_MD5_SITE, false, '532f517deae96c64770987c656dd507e' ),
            array( 'AVeryLongUsername', 'wîŧħAQuiteßécurePasswörð', 'ez.no', eZUser::PASSWORD_HASH_MD5_SITE, false, 'a76f77e805be362fc90765451d630d77' ),
            array( 'admin', 'password', 'ez.no', eZUser::PASSWORD_HASH_PLAINTEXT, false, 'password' ),
            array( 'AVeryLongUsername', 'wîŧħAQuiteßécurePasswörð', 'ez.no', eZUser::PASSWORD_HASH_PLAINTEXT, false, 'wîŧħAQuiteßécurePasswörð' ),
        );
    }

   /**
     * Data provider for self::testAuthenticateHashFail()
     * @see testAuthenticateHashFail()
     */
    public static function hashDataProviderFail()
    {
        return array(
            // Data changed slightly
            array( 'admin', 'password', 'ez.no', eZUser::PASSWORD_HASH_MD5_USER, false, '51f9ee797053cbfa8c77e8fa273f5518a' ),
            array( 'AVeryLongUsername', 'wîŧħAQuiteßécurePasswörð', 'ez.no', eZUser::PASSWORD_HASH_MD5_USER, false, '06fe380a1bce9e9ffc9858413f199f38a' ),
            array( 'admin', 'passwordx', 'ez.no', eZUser::PASSWORD_HASH_MD5_SITE, false, '532f517deae96c64770987c656dd507e' ),
            array( 'AVeryLongUsername', 'wîŧħAQuiteßécurePasswörðx', 'ez.no', eZUser::PASSWORD_HASH_MD5_SITE, false, 'a76f77e805be362fc90765451d630d77' ),
            array( 'admin', 'password', 'ezpublish.no', eZUser::PASSWORD_HASH_MD5_SITE, false, '532f517deae96c64770987c656dd507e' ),
            array( 'AVeryLongUsername', 'wîŧħAQuiteßécurePasswörð', 'ezpublish.no', eZUser::PASSWORD_HASH_MD5_SITE, false, 'a76f77e805be362fc90765451d630d77' ),
            array( 'admin', 'password', 'ez.no', eZUser::PASSWORD_HASH_PLAINTEXT, false, 'passwordx' ),
            array( 'AVeryLongUsername', 'wîŧħAQuiteßécurePasswörð', 'ez.no', eZUser::PASSWORD_HASH_PLAINTEXT, false, 'wîŧħAQuiteßécurePasswörðx' ),

            // Wrong type used
            array( true, 'password', 'ez.no', eZUser::PASSWORD_HASH_MD5_USER, false, '51f9ee797053cbfa8c77e8fa273f5518' ),
            array( 'admin', true, 'ez.no', eZUser::PASSWORD_HASH_MD5_USER, false, '51f9ee797053cbfa8c77e8fa273f5518' ),
            array( 'admin', 'password', 'ez.no', eZUser::PASSWORD_HASH_MD5_USER, false, true ),
            array( 'admin', true, 'ez.no', eZUser::PASSWORD_HASH_PLAINTEXT, false, 'password' ),
            array( 'admin', 'password', 'ez.no', eZUser::PASSWORD_HASH_PLAINTEXT, false, true ),
            array( 'admin', true, 'ez.no', eZUser::PASSWORD_HASH_PLAINTEXT, false, true ),
        );
    }

}

?>
