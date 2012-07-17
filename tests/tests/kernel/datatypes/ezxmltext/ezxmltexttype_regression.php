<?php
/**
 * File containing the eZXMLTextTypeRegression class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZXMLTextTypeRegression extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZMultiPriceType Regression Tests" );
    }

    /**
     * Regression test for issue {@see #17632 http://issues.ez.no/17632}
     *
     * In a multi language environment, a node fetched with a language other than the prioritized one(s) will return the
     * URL alias in the prioritized language
     */
    public function testIssue19174()
    {
        $bkpLanguages = eZContentLanguage::prioritizedLanguageCodes();

        // add a secondary language
        $locale = eZLocale::instance( 'fre-FR' );
        $translation = eZContentLanguage::addLanguage( $locale->localeCode(), $locale->internationalLanguageName() );

        // create related objects
        $relatedObjectOne = new ezpObject( 'article', 2, 14, 1, 'eng-GB' );
        $relatedObjectOne->title = __METHOD__ . ' related object one';
        $relatedObjectOneId = $relatedObjectOne->publish();

        $relatedObjectTwo = new ezpObject( 'article', 2, 14, 1, 'eng-GB' );
        $relatedObjectTwo->title = __METHOD__ . ' related object two';
        $relatedObjectTwoId = $relatedObjectTwo->publish();

        $xmlTextEn = "<embed href=\"ezobject://$relatedObjectOneId\" />";
        $xmlTextFr = "<embed href=\"ezobject://$relatedObjectTwoId\" />";

        // Create an article in eng-GB, and embed related object one in the intro
        $article = new ezpObject( 'article', 2, 14, 1, 'eng-GB' );
        $article->title = __METHOD__ . ' eng-GB';
        $article->intro = $xmlTextEn;
        $articleId = $article->publish();

        // Workaround as setting folder->name directly doesn't produce the expected result
        $article->addTranslation( 'fre-FR', array(
            'title' => __METHOD__ . ' fre-FR',
            'intro' => $xmlTextFr
        ) );

        $relatedObjects = eZContentObject::fetch( $articleId )->relatedObjects( false, false, 0, false, array( 'AllRelations' => eZContentObject::RELATION_EMBED ) );
        self::assertEquals( 2, count( $relatedObjects ) );
        $expectedRelations = array( $relatedObjectOneId => true, $relatedObjectTwoId => true );
        foreach( $relatedObjects as $relatedObject )
        {
            if ( isset( $expectedRelations[$relatedObject->ID] ) )
                unset( $expectedRelations[$relatedObject->ID] );
        }
        self::assertEquals( 0, count( $expectedRelations ) );

        eZContentLanguage::setPrioritizedLanguages( $bkpLanguages );
    }
}

?>
