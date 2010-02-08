<?php
/**
 * File containing the eZContentLanguageRegression class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
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

    public function setup()
    {
        parent::setup();
        eZContentLanguage::addLanguage( 'nno-NO', 'Nynorsk' );
    }

    public function teardown()
    {
        eZContentLanguage::removeLanguage( 'nno-NO' );
        eZContentLanguage::removeLanguage( 'dan-DK' );

        parent::teardown();
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