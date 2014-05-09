<?php
/**
 * File containing the eZXMLTextTypeRegression class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
     * Regression test for issue {@see #19174 http://issues.ez.no/19174}
     */
    public function testIssue19174()
    {
        $bkpLanguages = eZContentLanguage::prioritizedLanguageCodes();

        // add a secondary language
        $locale = eZLocale::instance( 'fre-FR' );
        $translation = eZContentLanguage::addLanguage( $locale->localeCode(), $locale->internationalLanguageName() );

        // create related objects
        $relatedObjectsIds = array(
            $this->createObjectForRelation(),
            $this->createObjectForRelation(),
            $this->createObjectForRelation(),
            $this->createObjectForRelation()
        );

        $xmlTextEn = "<embed href=\"ezobject://{$relatedObjectsIds[0]}\" /><link href=\"ezobject://{$relatedObjectsIds[1]}\">link</link>";
        $xmlTextFr = "<embed href=\"ezobject://{$relatedObjectsIds[2]}\" /><link href=\"ezobject://{$relatedObjectsIds[3]}\">link</link>";

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

        $relatedObjects = eZContentObject::fetch( $articleId )->relatedObjects( false, false, 0, false, array( 'AllRelations' => eZContentObject::RELATION_LINK | eZContentObject::RELATION_EMBED ) );
        self::assertEquals( 4, count( $relatedObjects ) );
        $expectedRelations = array_flip( $relatedObjectsIds );
        foreach( $relatedObjects as $relatedObject )
        {
            if ( isset( $expectedRelations[$relatedObject->ID] ) )
                unset( $expectedRelations[$relatedObject->ID] );
        }
        self::assertEquals( 0, count( $expectedRelations ) );

        $article->remove();
        $translation->removeThis();
        eZContentLanguage::setPrioritizedLanguages( $bkpLanguages );
    }

    private function createObjectForRelation( $title = false )
    {
        if ( !$title )
            $title = uniqid( __METHOD__ );
        $relatedObject = new ezpObject( 'article', 2, 14, 1, 'eng-GB' );
        $relatedObject->title = $title;
        return $relatedObject->publish();
    }
}

?>
