<?php

class eZMailEzcTest extends ezpTestCase
{
    public $adminEmail = 'ezp-unittests-01@ez.no';
    public $adminName = 'Admin';
    public static function imapIsEnabled()
    {
        return function_exists( 'imap_open' );
    }

    public function setUp()
    {
        parent::setUp();

        // Setup default settings, change these in each test when needed
        ezpINIHelper::setINISetting( 'site.ini', 'MailSettings', 'Transport', 'SMTP' );
        ezpINIHelper::setINISetting( 'site.ini', 'MailSettings', 'TransportServer', 'smtp.ez.no' );
        ezpINIHelper::setINISetting( 'site.ini', 'MailSettings', 'TransportPort', 25 );
        ezpINIHelper::setINISetting( 'site.ini', 'MailSettings', 'TransportUser', 'ezcomponents@mail.ez.no' );
        ezpINIHelper::setINISetting( 'site.ini', 'MailSettings', 'TransportPassword', 'ezcomponents' );
        ezpINIHelper::setINISetting( 'site.ini', 'MailSettings', 'AdminEmail', $this->adminEmail );
        ezpINIHelper::setINISetting( 'site.ini', 'MailSettings', 'EmailSender', 'ezp-unittests-01@ez.no' );
        ezpINIHelper::setINISetting( 'site.ini', 'MailSettings', 'EmailReplyTo', 'ezp-unittests-01@ez.no' );
        ezpINIHelper::setINISetting( 'site.ini', 'MailSettings', 'DebugSending', 'disabled' );
        ezpINIHelper::setINISetting( 'site.ini', 'MailSettings', 'DebugReceiverEmail', 'ezp-unittests-01@ez.no' );
    }

    public function tearDown()
    {
        ezpINIHelper::restoreINISettings();

        parent::tearDown();
    }

    /**
     * kernel/content/tipafriend.php
     */
    public function testTipAFriend()
    {
        $mail = new eZMail();
        $mail->setSender( $this->adminEmail, $this->adminName );
        $mail->setReceiver( $this->adminEmail, $this->adminName );
        $mail->setSubject( __FUNCTION__ );
        $mail->setBody( __FUNCTION__ );
        $this->assertEquals( true, eZMailTransport::send( $mail ) );
    }

    public function testRegressionToEmail()
    {
        $mail = new eZMail();
        $mail->setReceiver( $this->adminEmail, $this->adminName );

        $result = $mail->receiverEmailText();
        $expected = $mail->composeEmailItems( array( array( 'email' => $this->adminEmail, 'name' => $this->adminName ) ), true, 'email', true );

        $this->assertEquals( $expected, $result );
    }

    public function testRegressionToAll()
    {
        $mail = new eZMail();
        $mail->setReceiver( $this->adminEmail, $this->adminName );

        $ezpResult = $mail->receiverText();
        $ezcResult = $mail->Mail->to;
        $ezpExpected = $mail->composeEmailItems( array( array( 'email' => $this->adminEmail, 'name' => $this->adminName ) ), true, false, true );
        $ezcExpected = array( new ezcMailAddress( $this->adminEmail, $this->adminName ) );

        $this->assertEquals( $ezpExpected, $ezpResult );
        $this->assertEquals( $ezcExpected, $ezcResult );
    }

    public function testRegressionCcEmail()
    {
        $mail = new eZMail();
        $mail->addCc( $this->adminEmail, $this->adminName );

        $result = $mail->ccReceiverTextList();
        $expected = array( 'ezp-unittests-01@ez.no' );

        $this->assertEquals( $expected, $result );
    }

    public function testRegressionCcAll()
    {
        $mail = new eZMail();
        $mail->addCc( $this->adminEmail, $this->adminName );

        $ezpResult = $mail->ccElements();
        $ezcResult = $mail->Mail->cc;
        $ezpExpected =
        array( array( 'email' => $this->adminEmail, 'name' => $this->adminName ) );
        $ezcExpected = array( new ezcMailAddress( $this->adminEmail, $this->adminName ) );

        $this->assertEquals( $ezpExpected, $ezpResult );
        $this->assertEquals( $ezcExpected, $ezcResult );
    }

    public function testRegressionBccEmail()
    {
        $mail = new eZMail();
        $mail->addBcc( $this->adminEmail, $this->adminName );

        $result = $mail->bccReceiverTextList();
        $expected = array( 'ezp-unittests-01@ez.no' );

        $this->assertEquals( $expected, $result );
    }

    public function testRegressionBccAll()
    {
        $mail = new eZMail();
        $mail->addBcc( $this->adminEmail, $this->adminName );

        $ezpResult = $mail->bccElements();
        $ezcResult = $mail->Mail->bcc;
        $ezpExpected =
        array( array( 'email' => $this->adminEmail, 'name' => $this->adminName ) );
        $ezcExpected = array( new ezcMailAddress( $this->adminEmail, $this->adminName ) );

        $this->assertEquals( $ezpExpected, $ezpResult );
        $this->assertEquals( $ezcExpected, $ezcResult );
    }

    public function testRegressionSubject()
    {
        $mail = new eZMail();
        $mail->setSubject( __FUNCTION__ );

        $ezpResult = $mail->subject();
        $ezcResult = $mail->Mail->subject;
        $expected = __FUNCTION__;

        $this->assertEquals( $expected, $ezpResult );
        $this->assertEquals( $expected, $ezcResult );
    }

    public function testRegressionUserAgent()
    {
        $mail = new eZMail();
        $mail->setUserAgent( __FUNCTION__ );

        $ezpResult = $mail->userAgent();
        $ezcResult = $mail->Mail->getHeader( 'User-Agent' );
        $expected = __FUNCTION__;

        $this->assertEquals( $expected, $ezpResult );
        $this->assertEquals( $expected, $ezcResult );
    }

    public function testRegressionBodyString()
    {
        $mail = new eZMail();
        $mail->setBody( __FUNCTION__ );

        $ezpResult = $mail->body();
        $ezcResult = $mail->Mail->body;
        $ezpExpected = __FUNCTION__;
        $ezcExpected = new ezcMailText( __FUNCTION__, 'utf-8' );

        $this->assertEquals( $ezpExpected, $ezpResult );
        $this->assertEquals( $ezcExpected, $ezcResult );
    }

    public function testRegressionWrongPasswordCatchException()
    {
        ezpINIHelper::setINISetting( 'site.ini', 'MailSettings', 'TransportPassword', 'wrong password' );
        $mail = new eZMail();
        $mail->setSender( $this->adminEmail, $this->adminName );
        $mail->setReceiver( $this->adminEmail, $this->adminName );
        $mail->setSubject( __FUNCTION__ );
        $mail->setBody( __FUNCTION__ );

        // catching the exception of wrong password and turning it into return false
        $this->assertEquals( false, eZMailTransport::send( $mail ) );
    }

    /**
     * Test for issue #16401: email for confirming when anonymous is subscribing to
     * comments is in plain text, but with html tags
     */
    public function testRegressionSetContentType()
    {
        $mail = new eZMail();
        $mail->setBody( __FUNCTION__ );
        $mail->setContentType( "text/html" );

        $ezcResult = $mail->Mail->generate();

        preg_match( "/Content-Type: text\/html/", $ezcResult, $matches );
        $this->assertEquals( 1, count( $matches ) );
    }

    /**
     * Test for issue #16893: Wrong charset encoding in notification email
     */
    public function testRegressionSetContentTypeCharset()
    {
        // Set a custom charset in site.ini which will be tested
        // if it's set properly in the sent mail
        ezpINIHelper::setINISetting( 'site.ini', 'MailSettings', 'OutputCharset', 'custom-charset' );

        $mail = new eZMail();
        $mail->setBody( __FUNCTION__ );

        $ezcResult = $mail->Mail->generate();

        preg_match( "/Content-Type: text\/plain; charset=custom-charset/", $ezcResult, $matches );
        $this->assertEquals( 1, count( $matches ) );
    }
}

?>
