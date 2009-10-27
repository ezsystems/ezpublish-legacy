<?php
/**
 * File containing the eZRSSExportTest class.
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

/**
 * Tests for exporting of RSS feeds.
 */
class eZRSSExportTest extends ezpDatabaseTestCase
{
    /**
     * Setting needed to keep the global variables working between the tests.
     *
     * @var bool
     */
    protected $backupGlobals = FALSE;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZRSSExport Unit Tests" );
    }

    /**
     * Called by PHPUnit before each test.
     */
    public function setUp()
    {
        // Call the setUp() in ezpDatabaseTestCase
        parent::setUp();

        // Modify these values for your own
        $GLOBALS['ezp_username'] = 'admin';
        $GLOBALS['ezp_password'] = 'publish';

        $GLOBALS['ezp_server'] = 'ezp';

        $GLOBALS['test_data_folder'] = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'ezrss' . DIRECTORY_SEPARATOR;
    }

    /**
     * Called by PHPUnit after each test.
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Creates a folder in eZ Publish with name $folderName and returns its ID.
     *
     * @param string $folderName
     * @return int The ID of the folder created
     */
    public function createEZPFolder( $folderName )
    {
        $folder = new ezpObject( 'folder', 2 );
        $folder->name = $folderName;
        $folder->publish();
 
        $folderId = (int)$folder->mainNode->node_id;
        return $folderId;
    }

    /**
     * Creates an article in eZ Publish in the folder $folderId,
     * with title $articleTitle and summary $articleIntro and returns its ID.
     *
     * @param int $folderId
     * @param string $articleName
     * @param string $articleIntro
     * @return int The ID of the article created
     */
    public function createEZPArticle( $folderId, $articleTitle, $articleIntro )
    {
        $object = new ezpObject( 'article', $folderId );
        $object->title = $articleTitle;
        $object->intro = $articleIntro;
        $object->publish();
 
        $objectId = (int)$object->attribute( 'id' );
        return $objectId;
    }

    /**
     * Creates an RSS export object and returns it.
     *
     * @param string $version One of '1.0', '2.0' or 'ATOM'
     * @param int $folderId
     * @param string $title
     * @param string $description
     * @return eZRSSExport
     */
    public function createEZPRSSExport( $version, $folderId, $title, $description )
    {
        $userId = $this->loginEZPUser( $GLOBALS['ezp_username'], $GLOBALS['ezp_password'] );

        // Create default rssExport object to use
        $rssExport = eZRSSExport::create( $userId );
        $rssExport->setAttribute( 'node_id', $folderId );
        $rssExport->setAttribute( 'rss_version', $version );
        $rssExport->setAttribute( 'title', $title );
        $rssExport->setAttribute( 'description', $description );
        $rssExport->store();
        $rssExportID = $rssExport->attribute( 'id' );

        // Create one empty export item
        $rssExportItem = eZRSSExportItem::create( $userId );
        $rssExportItem->setAttribute( 'title', 'title' );
        $rssExportItem->setAttribute( 'class_id', 2 ); // 2 = article
        $rssExportItem->setAttribute( 'rssexport_id', $rssExportID );
        $rssExportItem->setAttribute( 'description', 'intro' );
        $rssExportItem->setAttribute( 'source_node_id', $folderId );
        $rssExportItem->store();

        return $rssExport;
    }

    /**
     * Logins with $username and $password and returns the userID.
     *
     * @param string $username
     * @param string $password
     * @return int
     */
    public function loginEZPUser( $username, $password )
    {
        $userClass = eZUserLoginHandler::instance( 'standard' );
        $user = $userClass->loginUser( 'admin', 'publish' );

        if ( !( $user instanceof eZUser ) )
        {
            return false;
        }

        $user = eZUser::currentUser();
        $userId = $user->attribute( "contentobject_id" );

        return $userId;
    }

    /**
     * Cleans an RSS feed to be ready for string comparison.
     *
     * Example: make date to 'XXX' to ensure the test files are compared without
     * variable values.
     *
     * @param string $text
     * @return string
     */
    protected function cleanRSSFeed( $text )
    {
        // ATOM cleaning
        $text = preg_replace( '@<updated>.*?</updated>@', '<updated>XXX</updated>', $text );
        $text = preg_replace( '@<published>.*?</published>@', '<published>XXX</published>', $text );
        $text = preg_replace( '@<generator.*?>.*?</generator>@', '<generator>XXX</generator>', $text );

        // RSS 2.0 cleaning
        $text = preg_replace( '@<pubDate>.*?</pubDate>@', '<pubDate>XXX</pubDate>', $text );
        $text = preg_replace( '@<lastBuildDate>.*?</lastBuildDate>@', '<lastBuildDate>XXX</lastBuildDate>', $text );

        // Machine values cleaning
        $text = str_replace( '@ezp_server@', $GLOBALS['ezp_server'], $text );
        return $text;
    }

    /**
     *
     */
    public function testCreateRSS1With1Items()
    {
        $folderId = $this->createEZPFolder( __FUNCTION__ );

        for ( $i = 0; $i < 1; $i++ )
        {
            $this->createEZPArticle( $folderId,
                    "Test object #{$i} for " . __FUNCTION__,
                    "Summary for Test object #{$i} for " . __FUNCTION__ );
        }

        $rssExport = $this->createEZPRSSExport( '1.0', $folderId, 'RSS 1.0 feed', 'This feed is of <b>RSS 1.0</b> type.' );
        $rssContent = $this->cleanRSSFeed( $rssExport->attribute( 'rss-xml-content' ) );

        $expected = $this->cleanRSSFeed( file_get_contents( $GLOBALS['test_data_folder'] . __FUNCTION__ . '.xml' ) );

        $this->assertEquals( $expected, $rssContent );
    }

    public function testCreateRSS2With1Items()
    {
        $folderId = $this->createEZPFolder( __FUNCTION__ );

        for ( $i = 0; $i < 1; $i++ )
        {
            $this->createEZPArticle( $folderId,
                    "Test object #{$i} for " . __FUNCTION__,
                    "Summary for Test object #{$i} for " . __FUNCTION__ );
        }

        $rssExport = $this->createEZPRSSExport( '2.0', $folderId, 'RSS 2.0 feed', 'This feed is of <b>RSS 2.0</b> type.' );
        $rssContent = $this->cleanRSSFeed( $rssExport->attribute( 'rss-xml-content' ) );

        $expected = $this->cleanRSSFeed( file_get_contents( $GLOBALS['test_data_folder'] . __FUNCTION__ . '.xml' ) );

        $this->assertEquals( $expected, $rssContent );
    }

    public function testCreateATOMWith1Items()
    {
        $folderId = $this->createEZPFolder( __FUNCTION__ );

        for ( $i = 0; $i < 1; $i++ )
        {
            $this->createEZPArticle( $folderId,
                    "Test object #{$i} for " . __FUNCTION__,
                    "Summary for Test object #{$i} for " . __FUNCTION__ );
        }

        $rssExport = $this->createEZPRSSExport( 'ATOM', $folderId, 'ATOM feed', 'This feed is of <b>ATOM</b> type.' );
        $rssContent = $this->cleanRSSFeed( $rssExport->attribute( 'rss-xml-content' ) );

        $expected = $this->cleanRSSFeed( file_get_contents( $GLOBALS['test_data_folder'] . __FUNCTION__ . '.xml' ) );

        $this->assertEquals( $expected, $rssContent );
    }
}
?>
