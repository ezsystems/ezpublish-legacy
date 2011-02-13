<?php

class eZTextFileUserTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

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

        ezpINIHelper::setINISetting( 'site.ini', 'UserSettings', 'LoginHandler[]', 'textfile' );

        ezpINIHelper::setINISetting( 'textfile.ini', 'TextFileSettings', 'TextFileEnabled', 'true' );

        // the textfile.csv file contains a new line at the end, which causes issue #16322
        ezpINIHelper::setINISetting( 'textfile.ini', 'TextFileSettings', 'FileName', 'textfile.csv' );
        ezpINIHelper::setINISetting( 'textfile.ini', 'TextFileSettings', 'FilePath', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'data' );
        ezpINIHelper::setINISetting( 'textfile.ini', 'TextFileSettings', 'FileFieldSeparator', ',' );
        ezpINIHelper::setINISetting( 'textfile.ini', 'TextFileSettings', 'DefaultUserGroupType', 'id' );
        ezpINIHelper::setINISetting( 'textfile.ini', 'TextFileSettings', 'DefaultUserGroup', '13' );
        ezpINIHelper::setINISetting( 'textfile.ini', 'TextFileSettings', 'LoginAttribute', '1' );
        ezpINIHelper::setINISetting( 'textfile.ini', 'TextFileSettings', 'PasswordAttribute', '3' );
        ezpINIHelper::setINISetting( 'textfile.ini', 'TextFileSettings', 'FirstNameAttribute', '4' );
        ezpINIHelper::setINISetting( 'textfile.ini', 'TextFileSettings', 'LastNameAttribute', '5' );
        ezpINIHelper::setINISetting( 'textfile.ini', 'TextFileSettings', 'EmailAttribute', '2' );
    }

    public function tearDown()
    {
        ezpINIHelper::restoreINISettings();
        parent::tearDown();
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
