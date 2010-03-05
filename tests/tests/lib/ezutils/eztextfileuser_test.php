<?php

class eZTextFileUserTest extends ezpTestCase
{
    public $username = 'foobar';
    public $password = 'foobar';
    public $firstname = 'Foo';
    public $lastname = 'Bar';

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZTextFileUser Unit Tests" );
    }

    public function setUp()
    {
        parent::setUp();

        $this->ini = eZINI::instance();
        $this->ini->setVariable( 'UserSettings', 'LoginHandler[]', 'textfile' );
        $this->ini->setVariable( 'TextFileSettings', 'TextFileEnabled', 'true' );

        // this text file contains a new line at the end, which causes issue #16322
        $this->ini->setVariable( 'TextFileSettings', 'FileName', 'textfile.csv' );
        $this->ini->setVariable( 'TextFileSettings', 'FilePath', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'data' );
        $this->ini->setVariable( 'TextFileSettings', 'FileFieldSeparator', ',' );
        $this->ini->setVariable( 'TextFileSettings', 'DefaultUserGroupType', 'id' );
        $this->ini->setVariable( 'TextFileSettings', 'DefaultUserGroup', '13' );
        $this->ini->setVariable( 'TextFileSettings', 'LoginAttribute', '1' );
        $this->ini->setVariable( 'TextFileSettings', 'PasswordAttribute', '3' );
        $this->ini->setVariable( 'TextFileSettings', 'FirstNameAttribute', '4' );
        $this->ini->setVariable( 'TextFileSettings', 'LastNameAttribute', '5' );
        $this->ini->setVariable( 'TextFileSettings', 'EmailAttribute', '2' );
    }

    /**
     * Test for issue #16322: eZTextFileUser makes user names with newline (with patch)
     */
    public function testLoginCorrect()
    {
        $userClass = eZUserLoginHandler::instance( 'textfile' );
        $user = $userClass->loginUser( $this->username, $this->password );

        // the username and password were accepted
        $this->assertEquals( true, $user instanceof eZUser );

        // check that the name doesn't contain new line at the end
        $userObject = eZContentObject::fetch( $user->ContentObjectID );
        $this->assertEquals( $this->firstname . ' ' . $this->lastname, $userObject->Name );
    }

    /**
     * Test for issue #16322: eZTextFileUser makes user names with newline (with patch)
     */
    public function testLoginWrongPassword()
    {
        $userClass = eZUserLoginHandler::instance( 'textfile' );
        $user = $userClass->loginUser( $this->username, 'wrong password' );

        // the username and password were not accepted
        $this->assertEquals( false, $user instanceof eZUser );
    }

    /**
     * Test for issue #16322: eZTextFileUser makes user names with newline (with patch)
     */
    public function testLoginWrongUsername()
    {
        $userClass = eZUserLoginHandler::instance( 'textfile' );
        $user = $userClass->loginUser( 'wrong username', 'wrong password' );

        // the username and password were not accepted
        $this->assertEquals( false, $user instanceof eZUser );
    }
}

?>