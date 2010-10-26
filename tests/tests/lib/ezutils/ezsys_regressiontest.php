<?php
/**
 * File containing the eZWebDAVBackendContentRegressionTest class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

/**
 * Main test class for eZSys regression tests
 */
class eZSysRegressionTest extends ezpRegressionTest
{
    /**
     * Setup webdav test siteaccess & fills $this->files with all the
     * .request files found in the regression directory, recursively.
     */
    public function __construct()
    {
        // load tests
        $this->readDirRecursively( dirname( __FILE__ ) . '/server', $this->files, 'php' );

        // call parent (including sorting $this->files as set above)
        parent::__construct();
    }

    /**
     * Called by PHPUnit to create this test suite.
     */
    public static function suite()
    {
        return new ezpTestRegressionSuite( __CLASS__ );
    }

    /**
     * Called by PHPUnit before each test, bakup eZSys instance
     */
    public function setUp()
    {
        parent::setUp();
        $this->eZSysInstanceBackup = eZSys::instance();
    }

    /**
     * Called by PHPUnit after each test, correct eZSys instance
     */
    public function tearDown()
    {
        eZSys::setInstance( $this->eZSysInstanceBackup );
        parent::tearDown();
    }

    /**
     * Skips the test $file if the directory name is uncommented in $skipTests
     * inside the function.
     *
     * @param string $file
     */
    protected function skip( $file )
    {
        // Uncomment the tests that you want to skip,  name = > pattern
        $skipTests = array(
                // 'rootUri' => 'vh/root/',
                'utf8Uri' => 'vh/utf8/',
                // 'viewUri' => 'vh/view/',
                // 'Linux' => '/linux',
                // 'Windows' => '/win',
                'GG' => '/winvista', // breaks the tests in multiple ways atm, some could be data issue
                // 'Mac' => '/mac',
                // 'Freebsd' => '/freebsd',
                // 'Solaris' => '/solaris',
                // 'Virtualhost' => 'server/vh/',
                // 'NonVirtualhost' => 'server/nvh/',
                // 'Apache' => '_apache',
                // 'IIS' => '_iis',
                // 'Nginx' => '_nginx',
            );

        foreach ( $skipTests as $testName => $testPattern )
        {
            if ( strpos( $file, $testPattern ) !== false )
            {
                $this->markTestSkipped( "Test environment is configured to skip {$testName} tests." );
                return true;
            }
        }
        return false;
    }

    /**
     * Runs the $file (.request file from $this->files) as a PHPUnit test.
     *
     * Steps performed:
     *  - setUp() is called automatically before this function
     *  - skip the test $file if declared. See {@link skip()}
     *  - load data from file, create eZSys instance and run init
     *  - check that misc os / vh variables where as expected
     *  - tearDown() is called automatically after this function
     *
     * @param string $file
     */
    protected function testRunRegression( $file )
    {
        if ( $this->skip( $file ) )
            return;

        $testData = include $file;
        $instance = new eZSys( $testData );
        eZSys::setInstance( $instance );
        eZSys::init( 'index.php', strpos( $file, 'server/vh/' ) !== false );

        // OS tests
        if ( $testData['PHP_OS'] === 'WINNT' )
        {

        }
        elseif ( $testData['PHP_OS'] === 'MAC' )
        {

        }
        else // unix
        {

        }

        // Uri test
        // vh / nvh part
        if ( strpos( $file, 'server/nvh/' ) )
            $expected = '/index.php';
        else
            $expected = '';

        $this->assertEquals( $expected, $instance->indexFileName(), "The expected indexFileName response '" . $expected . "' is not the same as the response got from request: " . $instance->indexFileName()  );

        // uri part
        if ( strpos( $file, 'vh/utf8' ) )
            $expected = '/News/Blåbær-Øl-med-d\'or-新闻军事社会体育中超';
        elseif ( strpos( $file, 'vh/view' ) )
            $expected = '/content/view/full/44';
        else
            $expected = '/';

        $this->assertEquals( $expected, $instance->requestURI(), "The expected requestURI response '" . $expected . "' is not the same as the response got from request: " . $instance->requestURI()  );
    }
}
?>
