<?php
/**
 * File containing the eZURLTypeRegression class
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZURLTypeRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = FALSE;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZURLType Regression Tests" );
    }

    /**
     * Test scenario for issue #13604: url's are not encoded properly while serializing object of eZURL datatype.
     *
     * Test Outline
     * ------------
     * 1. Create a new object of class Link with a URL that needs escaping.
     * 2. Serialize the attribute of type 'ezurltype'
     *
     * @result: http://example.com/?param1=lorem>
     * @expected: http%3A%2F%2Fexample.com%2F%3Fparam1%3Dlorem%26param2%3Dispum
     *
     * @link http://issues.ez.no/13604
     * @see testUnserializeLinkEncoding
     * @group issue_13604
     */
    public function testSerializeLinkEncoding()
    {
        $url = "http://example.com/?param1=lorem&param2=ispum";

        // Step 1: Create link object
        $link = $this->createNewLinkObject( $url );

        // Step 2: Serialize data
        $ezurl = $link->dataMap['location'];
        $domNode = $ezurl->serialize( null );

        $this->assertEquals( urlencode( $url ), $domNode->textContent );
    }

    /**
     * Test scenario for issue #13604: url's are not encoded properly while serializing object of eZURL datatype.
     *
     * Test Outline
     * ------------
     * 1. Create a new object of class Link with a URL that needs escaping.
     * 2. Serialize the 'location' attribute (datatype 'ezurltype').
     * 3. Create another Link object with a different URL than the object in step 1.
     * 4. Use the serialized data from step 2 to unserialize the 'location' attribute
     *    created in step 3.
     *
     * @result: http://example.com/?param1=lorem
     * @expected: http://example.com/?param1=lorem&param2=ispum
     *
     * @link http://issues.ez.no/13604
     * @see testSerializeLinkEncoding
     * @group issue_13604
     */
    public function testUnserializeLinkEncoding()
    {
        $url = "http://example.com/?param1=lorem&param2=ispum";

        // Step 1: Create link object
        $link = $this->createNewLinkObject( $url );
        $ezurl = $link->dataMap['location'];

        // Step 2: Serialize
        $domNode = $ezurl->dataType()->serializeContentObjectAttribute( null, $ezurl );

        // Step 3: Create another link object
        $link2 = $this->createNewLinkObject( "http://example.com/no/params" );
        $ezurl2 = $link2->dataMap['location'];

        // Step 4: Unserialize data
        $ezurl2->unserialize( null, $domNode );

        // ezcontentobjectattribute caches the content, so we ask for the
        // content directly from the datatype.
        $unserializedUrl = $ezurl2->dataType()->objectAttributeContent( $ezurl2 );

        $this->assertEquals( $url, $unserializedUrl );
    }

    /**
     * Helper method that creates and returns a ezcontentobject of class 'link'
     *
     * @param string $url
     * @return ezcontentobject $link
     */
    private function createNewLinkObject( $url )
    {
        $link = new ezpObject( "link", 2 );
        $link->name = __FUNCTION__ . mt_rand();
        $link->location = array( "", $url );
        $link->publish();

        return $link;
    }

}

?>