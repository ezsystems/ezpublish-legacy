<?php
/**
 * File containing the eZRSSExportTest class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
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

        // get server url
        $this->ezp_server = eZINI::instance()->variable( 'SiteSettings', 'SiteURL' );

        // login admin
        $this->currentUser = eZUser::currentUser();
        $admin = eZUser::fetchByName( 'admin' );
        eZUser::setCurrentlyLoggedInUser( $admin, $admin->attribute( 'contentobject_id' ) );
        $this->ezp_admin_id = $admin->attribute('contentobject_id');
        $this->ezp_admin_email = $admin->attribute('email');

        $this->test_data_folder = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'ezrss' . DIRECTORY_SEPARATOR;
        $this->remote_id_map = array( '894c0959925a6ac47c915b7c8fb6376c', '935f192b93cbadbbf93d7b031bdceb70' );
    }

    /**
     * Called by PHPUnit after each test.
     */
    public function tearDown()
    {
        // Log in as whoever was logged in
        eZUser::setCurrentlyLoggedInUser( $this->currentUser, $this->currentUser->attribute( 'id' ) );

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
    public function createEZPArticle( $folderId, $articleTitle, $articleIntro, $articleRemoteID )
    {
        $object = new ezpObject( 'article', $folderId );
        $object->title = $articleTitle;
        $object->intro = $articleIntro;
        $object->publish();

        // Update object RemoteID
        $object->setAttribute( 'remote_id', $articleRemoteID );
        $object->store();

        $objectId = (int)$object->attribute( 'id' );
        return $objectId;
    }

    /**
     * Hides an object specified by $objectId.
     *
     * @param int $objectId
     */
    public function hideObject( $objectId )
    {
        $node = eZContentObjectTreeNode::fetchByContentObjectID( $objectId );
        eZContentObjectTreeNode::hideSubTree( $node[0] );
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
        // Create default rssExport object to use
        $rssExport = eZRSSExport::create( $this->ezp_admin_id );
        $rssExport->setAttribute( 'node_id', $folderId );
        $rssExport->setAttribute( 'rss_version', $version );
        $rssExport->setAttribute( 'title', $title );
        $rssExport->setAttribute( 'description', $description );
        $rssExport->store();
        $rssExportID = $rssExport->attribute( 'id' );

        // Create one empty export item
        $rssExportItem = eZRSSExportItem::create( $this->ezp_admin_id );
        $rssExportItem->setAttribute( 'title', 'title' );
        $rssExportItem->setAttribute( 'class_id', 2 ); // 2 = article
        $rssExportItem->setAttribute( 'rssexport_id', $rssExportID );
        $rssExportItem->setAttribute( 'description', 'intro' );
        $rssExportItem->setAttribute( 'source_node_id', $folderId );
        $rssExportItem->store();

        return $rssExport;
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
        $text = str_replace( '@ezp_server@', $this->ezp_server, $text );
        $text = str_replace( '@ezp_admin_email@', $this->ezp_admin_email, $text );
        $text = preg_replace( '@<name>.*?</name>@', '<name>XXX</name>', $text );
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
                    "Summary for Test object #{$i} for " . __FUNCTION__,
                    $this->remote_id_map[$i] );
        }

        $rssExport = $this->createEZPRSSExport( '1.0', $folderId, 'RSS 1.0 feed', 'This feed is of <b>RSS 1.0</b> type.' );
        $rssContent = $this->cleanRSSFeed( $rssExport->attribute( 'rss-xml-content' ) );

        $expected = $this->cleanRSSFeed( file_get_contents( $this->test_data_folder . __FUNCTION__ . '.xml' ) );

        $this->assertEquals( $expected, $rssContent );
    }

    public function testCreateRSS2With1Items()
    {
        $folderId = $this->createEZPFolder( __FUNCTION__ );

        for ( $i = 0; $i < 1; $i++ )
        {
            $this->createEZPArticle( $folderId,
                    "Test object #{$i} for " . __FUNCTION__,
                    "Summary for Test object #{$i} for " . __FUNCTION__,
                    $this->remote_id_map[$i] );
        }

        $rssExport = $this->createEZPRSSExport( '2.0', $folderId, 'RSS 2.0 feed', 'This feed is of <b>RSS 2.0</b> type.' );
        $rssContent = $this->cleanRSSFeed( $rssExport->attribute( 'rss-xml-content' ) );

        $expected = $this->cleanRSSFeed( file_get_contents( $this->test_data_folder . __FUNCTION__ . '.xml' ) );

        $this->assertEquals( $expected, $rssContent );
    }

    public function testCreateATOMWith1Items()
    {
        $folderId = $this->createEZPFolder( __FUNCTION__ );

        for ( $i = 0; $i < 1; $i++ )
        {
            $this->createEZPArticle( $folderId,
                    "Test object #{$i} for " . __FUNCTION__,
                    "Summary for Test object #{$i} for " . __FUNCTION__,
                    $this->remote_id_map[$i] );
        }

        $rssExport = $this->createEZPRSSExport( 'ATOM', $folderId, 'ATOM feed', 'This feed is of <b>ATOM</b> type.' );
        $rssContent = $this->cleanRSSFeed( $rssExport->attribute( 'rss-xml-content' ) );

        $expected = $this->cleanRSSFeed( file_get_contents( $this->test_data_folder . __FUNCTION__ . '.xml' ) );

        $this->assertEquals( $expected, $rssContent );
    }

    /**
     * Test for issue #14480: 4.0.0 (and ? uppers) : Hidden nodes are displayed in RSS exports
     */
    public function testCreateRSS1With2Items1Hidden()
    {
        $folderId = $this->createEZPFolder( __FUNCTION__ );

        $ids = array();
        for ( $i = 0; $i < 2; $i++ )
        {
            $ids[] = $this->createEZPArticle( $folderId,
                    "Test object #{$i} for " . __FUNCTION__,
                    "Summary for Test object #{$i} for " . __FUNCTION__,
                    $this->remote_id_map[$i] );
        }

        $this->hideObject( $ids[1] );

        $rssExport = $this->createEZPRSSExport( '1.0', $folderId, 'RSS 1.0 feed', 'This feed is of <b>RSS 1.0</b> type.' );
        $rssContent = $this->cleanRSSFeed( $rssExport->attribute( 'rss-xml-content' ) );

        $expected = $this->cleanRSSFeed( file_get_contents( $this->test_data_folder . __FUNCTION__ . '.xml' ) );

        $this->assertEquals( $expected, $rssContent );
    }

    /**
     * Test for issue #14480: 4.0.0 (and ? uppers) : Hidden nodes are displayed in RSS exports
     */
    public function testCreateRSS2With2Items1Hidden()
    {
        $folderId = $this->createEZPFolder( __FUNCTION__ );

        $ids = array();
        for ( $i = 0; $i < 2; $i++ )
        {
            $ids[] = $this->createEZPArticle( $folderId,
                    "Test object #{$i} for " . __FUNCTION__,
                    "Summary for Test object #{$i} for " . __FUNCTION__,
                    $this->remote_id_map[$i] );
        }

        $this->hideObject( $ids[1] );

        $rssExport = $this->createEZPRSSExport( '2.0', $folderId, 'RSS 2.0 feed', 'This feed is of <b>RSS 2.0</b> type.' );
        $rssContent = $this->cleanRSSFeed( $rssExport->attribute( 'rss-xml-content' ) );

        $expected = $this->cleanRSSFeed( file_get_contents( $this->test_data_folder . __FUNCTION__ . '.xml' ) );

        $this->assertEquals( $expected, $rssContent );
    }

    /**
     * Test for issue #14480: 4.0.0 (and ? uppers) : Hidden nodes are displayed in RSS exports
     */
    public function testCreateATOMWith2Items1Hidden()
    {
        $folderId = $this->createEZPFolder( __FUNCTION__ );

        $ids = array();
        for ( $i = 0; $i < 2; $i++ )
        {
            $ids[] = $this->createEZPArticle( $folderId,
                    "Test object #{$i} for " . __FUNCTION__,
                    "Summary for Test object #{$i} for " . __FUNCTION__,
                    $this->remote_id_map[$i] );
        }

        $this->hideObject( $ids[1] );

        $rssExport = $this->createEZPRSSExport( 'ATOM', $folderId, 'ATOM feed', 'This feed is of <b>ATOM</b> type.' );
        $rssContent = $this->cleanRSSFeed( $rssExport->attribute( 'rss-xml-content' ) );

        $expected = $this->cleanRSSFeed( file_get_contents( $this->test_data_folder . __FUNCTION__ . '.xml' ) );

        $this->assertEquals( $expected, $rssContent );
    }

    /**
     * Test for issue #016953: &nbsp; breaks RSS/ATOM feeds
     *
     * @link http://issues.ez.no/016953
     */
    public function testCreateATOMWithnbsp()
    {
        $folderId = $this->createEZPFolder( __FUNCTION__ );

        $ids = array();
        for ( $i = 0; $i < 1; $i++ )
        {
            $ids[] = $this->createEZPArticle( $folderId,
                    "Test object #{$i} for " . __FUNCTION__,
                    "Summary for Test object #{$i} for " . __FUNCTION__ . " & with a nbsp char right here&nbsp;.",
                    $this->remote_id_map[$i] );
        }

        $rssExport = $this->createEZPRSSExport( 'ATOM', $folderId, 'ATOM feed', 'This feed is of <b>ATOM</b> type.' );
        $rssContent = $this->cleanRSSFeed( $rssExport->attribute( 'rss-xml-content' ) );

        $expected = $this->cleanRSSFeed( file_get_contents( $this->test_data_folder . __FUNCTION__ . '.xml' ) );

        $this->assertEquals( $expected, $rssContent );
    }
}
?>
