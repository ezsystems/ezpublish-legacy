<?php
/**
 * File containing the eZContentLanguageRegression class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZContentLanguageRegression extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZContentLanguage Regression Tests" );
    }

    public function setUp()
    {
        parent::setUp();
        eZContentLanguage::addLanguage( 'nno-NO', 'Nynorsk' );
    }

    public function tearDown()
    {
        eZContentLanguage::removeLanguage( 'nno-NO' );
        eZContentLanguage::removeLanguage( 'dan-DK' );

        parent::tearDown();
    }

    /**
     * Test that fetching the language listing, works after languages
     * have been altered in database, and then later refetched.
     *
     * @link http://issues.ez.no/15484
     */
    public function testMapLanguage()
    {
        $db = eZDB::instance();

        eZContentLanguage::addLanguage( 'nno-NO', 'Nynorsk' );
        $localeToChangeInto = 'dan-DK';
        $languageNameToChangeInto = 'Danish';

        $langObject = eZContentLanguage::fetchByLocale( 'nno-NO' );
        $langId = (int)$langObject->attribute( 'id' );
        $updateSql = <<<END
UPDATE ezcontent_language
SET
locale='$localeToChangeInto',
name='$languageNameToChangeInto'
WHERE
id=$langId
END;

        $db->query( $updateSql );

        eZContentLanguage::expireCache();
        $newLangObject = eZContentLanguage::fetchByLocale( $localeToChangeInto );

        if ( !( $newLangObject instanceof eZContentLanguage ) )
        {
            self::fail( "Language object not returned. Old version provided by cache?" );
        }

        $newLangId = (int)$newLangObject->attribute( 'id' );

        self::assertEquals( $langId, $newLangId, "New language not mapped to existing language" );
    }

}
?>
