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
        $this->ini = eZINI::instance();
        $this->ini->setVariable( 'MailSettings', 'Transport', 'SMTP' );
        $this->ini->setVariable( 'MailSettings', 'TransportServer', 'smtp.ez.no' );
        $this->ini->setVariable( 'MailSettings', 'TransportPort', 25 );
        $this->ini->setVariable( 'MailSettings', 'TransportUser', 'ezcomponents@mail.ez.no' );
        $this->ini->setVariable( 'MailSettings', 'TransportPassword', 'ezcomponents' );
        $this->ini->setVariable( 'MailSettings', 'AdminEmail', $this->adminEmail );
        $this->ini->setVariable( 'MailSettings', 'EmailSender', 'ezp-unittests-01@ez.no' );
        $this->ini->setVariable( 'MailSettings', 'EmailReplyTo', 'ezp-unittests-01@ez.no' );
        $this->ini->setVariable( 'MailSettings', 'DebugSending', 'disabled' );
        $this->ini->setVariable( 'MailSettings', 'DebugReceiverEmail', 'ezp-unittests-01@ez.no' );
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
        eZMailTransport::send( $mail );
    }

    public function testRegressionToEmail()
    {
        $mail = new eZMail();
        $mail->setReceiver( $this->adminEmail, $this->adminName );

        $result = $mail->receiverEmailText();
        $expected = $mail->composeEmailItems( array( array( 'email' => $this->adminEmail, 'name' => $this->adminName ) ), true, 'email', true );
//        var_dump( __FUNCTION__ . ' ' . $expected . '/' . $result );
        $this->assertEquals( $expected, $result );
    }

    public function testRegressionToAll()
    {
        $mail = new eZMail();
        $mail->setReceiver( $this->adminEmail, $this->adminName );

        $result = $mail->receiverText();
        $expected = $mail->composeEmailItems( array( array( 'email' => $this->adminEmail, 'name' => $this->adminName ) ), true, false, true );
//        var_dump( __FUNCTION__ . ' ' . $expected . '/' . $result );
        $this->assertEquals( $expected, $result );
    }

    public function testRegressionCcEmail()
    {
        $mail = new eZMail();
        $mail->addCc( $this->adminEmail, $this->adminName );

        $result = $mail->ccReceiverTextList();
        $expected = array( 'ezp-unittests-01@ez.no' );
//        var_dump( __FUNCTION__ . ' ' . $expected . '/' . $result );
        $this->assertEquals( $expected, $result );
    }

    public function testRegressionCcAll()
    {
        $mail = new eZMail();
        $mail->addCc( $this->adminEmail, $this->adminName );

        $result = $mail->ccElements();
        $expected = 
        array( array( 'email' => $this->adminEmail, 'name' => $this->adminName ) );
//        var_dump( __FUNCTION__ . ' ' . $expected . '/' . $result );
        $this->assertEquals( $expected, $result );
    }

    public function testRegressionBccEmail()
    {
        $mail = new eZMail();
        $mail->addBcc( $this->adminEmail, $this->adminName );

        $result = $mail->bccReceiverTextList();
        $expected = array( 'ezp-unittests-01@ez.no' );
//        var_dump( __FUNCTION__ . ' ' . $expected . '/' . $result );
        $this->assertEquals( $expected, $result );
    }

    public function testRegressionBccAll()
    {
        $mail = new eZMail();
        $mail->addBcc( $this->adminEmail, $this->adminName );

        $result = $mail->bccElements();
        $expected = 
        array( array( 'email' => $this->adminEmail, 'name' => $this->adminName ) );
//        var_dump( __FUNCTION__ . ' ' . $expected . '/' . $result );
        $this->assertEquals( $expected, $result );
    }

    public function testRegressionSubject()
    {
        $mail = new eZMail();
        $mail->setSubject( __FUNCTION__ );

        $result = $mail->subject();
        $expected = __FUNCTION__;
//        var_dump( __FUNCTION__ . ' ' . $expected . '/' . $result );
        $this->assertEquals( $expected, $result );
    }

    public function testRegressionUserAgent()
    {
        $mail = new eZMail();
        $mail->setUserAgent( __FUNCTION__ );

        $result = $mail->userAgent();
        $expected = __FUNCTION__;
//        var_dump( __FUNCTION__ . ' ' . $expected . '/' . $result );
        $this->assertEquals( $expected, $result );
    }

    public function testRegressionBodyString()
    {
        $mail = new eZMail();
        $mail->setBody( __FUNCTION__ );

        $result = $mail->body();
        $expected = __FUNCTION__;
//        var_dump( __FUNCTION__ . ' ' . $expected . '/' . $result );
        $this->assertEquals( $expected, $result );
    }
}

?>
