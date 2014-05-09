<?php
/**
 * File containing the eZNamePatternResolverRegression class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZNamePatternResolverRegression extends ezpTestCase
{
    /**
     * @return array
     */
    public function providerForTestNamePatternResolver()
    {
        return array(
            array(// test ASCII string split, no trimming
                "This is a Very Long Name isn't it?",
                20,
                "..",
                "<name>",
                "name",
                "This is a Very Lon.."
            ),
            array(// test ASCII string split, whitespace trimming
                "This is a Very Long \n\t\r\0Name isn't it?",
                25,
                "..",
                "<name>",
                "name",
                "This is a Very Long.."
            ),
            array(// test ASCII string split, comma and dot trimming
                "This is a Very Long,.Name isn't it?",
                22,
                "..",
                "<name>",
                "name",
                "This is a Very Long,.."
            ),
            array(// test uft-8 string split, no trimming
                "私は簡単にパブリッシュの記事で使用することができるようなもの、何でも、記述する必要が、それはそう、私はただ何を書き留めて、何を参照してくださいますね、あなたが実際にTIに考えている心の中で何かが出てくるのが難しいのようなものだ登場。ポイントは、私が百五文字が必要だということです。これで十分です。くそー、もっと2",
                31,
                "..",
                "<name>",
                "name",
                "私は簡単にパブリッシュの記事で使用することができるようなも.."
            ),
            array(// test uft-8 string split, with ideographic comma trimming
                "私は簡単にパブリッシュの記事で使用することができるようなもの、何でも、記述する必要が、それはそう、私はただ何を書き留めて、何を参照してくださいますね、あなたが実際にTIに考えている心の中で何かが出てくるのが難しいのようなものだ登場。ポイントは、私が百五文字が必要だということです。これで十分です。くそー、もっと2",
                32,
                "..",
                "<name>",
                "name",
                "私は簡単にパブリッシュの記事で使用することができるようなもの.."
            ),
            array(// test a string that doesn't need to be modified
                "A string that doesn't need to be altered",
                0,
                "..",
                "<name>",
                "name",
                "A string that doesn't need to be altered"
            )
        );
    }

    /**
     * Test to check fix for "object name limit does not support multibyte charset"
     *
     * @link https://jira.ez.no/browse/EZP-21410
     * @dataProvider providerForTestNamePatternResolver
     */
    public function testNamePatternResolver( $name, $limit, $sequence, $namePattern, $identifier, $expects )
    {
        $contentObjectMock = $this->getMock( "eZContentObject", array(), array(), '', false, false );
        $contentObjectAttributeMock = $this->getMock( "eZContentObjectAttribute", array(), array(), '', false, false );


        $contentObjectMock
            ->expects( $this->once() )
            ->method( "fetchAttributesByIdentifier" )
            ->with( array( $identifier ), false, array( false ) )
            ->will( $this->returnValue( array( $contentObjectAttributeMock ) ) );

        $contentObjectAttributeMock
            ->expects( $this->once() )
            ->method( "contentClassAttributeIdentifier" )
            ->will( $this->returnValue( $identifier ) );

        $contentObjectAttributeMock
            ->expects( $this->once() )
            ->method( 'title' )
            ->will( $this->returnValue( $name ) );


        $resolver = new eZNamePatternResolver( $namePattern, $contentObjectMock );
        $result = $resolver->resolveNamePattern( $limit, $sequence );

        $this->assertEquals( $expects, $result );
    }
}
