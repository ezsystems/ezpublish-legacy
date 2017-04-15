<?php

class eZPaymentLoggerTest extends ezpTestCase
{
    /**
     * eZLog always writes into 'var/log' per default and doesn't respect eZSys::varDirectory()
     *
     * @var string
     */
    protected $baseDir = 'var/log';

    /**
     * @dataProvider validFileNamesProvider
     */
    public function testWriteWithValidFileNames( $fileName, $expectedFileName )
    {
        $logger = new eZPaymentLogger( $fileName );
        $this->assertInstanceOf( 'eZPaymentLogger', $logger );
        $this->assertEquals( $expectedFileName, $logger->fileName );
        $this->assertTrue( $logger->writeString( "Test" ) );

        $fh = new eZFSFileHandler( $this->baseDir . '/' . $expectedFileName );
        $this->assertTrue( $fh->exists() );
        $contents = $fh->fetchContents();

        $this->assertInternalType( 'string', $contents );
        $this->assertRegExp( '/Test/', $contents );

        $fh->delete();
    }

    /**
     * @dataProvider invalidFileNamesProvider
     */
    public function testWriteWithInvalidFileNames( $fileName )
    {
        $logger = new eZPaymentLogger( $fileName );
        $this->assertInstanceOf( 'eZPaymentLogger', $logger );

        $this->assertFalse( $logger->writeString( "Test" ) );
    }

    public function validFileNamesProvider()
    {
        return array(
            array( 'var/log/eZPaymentLoggerTest.log', 'eZPaymentLoggerTest.log' ),
            array( 'test/eZPaymentLoggerTest.log', 'eZPaymentLoggerTest.log' ),
            array( 'eZPaymentLoggerTest.log', 'eZPaymentLoggerTest.log' ),
        );
    }

    public function invalidFileNamesProvider()
    {
        return array(
            array( null ),
        );
    }
}
