<?php
/**
 * File containing the ezpRegressionTest class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

/**
 * Regression test case which inherits from ezpDatabaseTestCase, needed to
 * write regression tests like WebDAV regression tests.
 *
 * Same code as ezcTestRegressionTest which is in UnitTest from eZ
 * Components trunk.
 */
abstract class ezpRegressionTest extends ezpTestCase
{
    /**
     * How to sort the test files: 'mtime' sorts by modification time, any other
     * value sorts by name.
     */
    const SORT_MODE = 'name';

    protected $files = array();
    protected $currentFile;

    public function __construct()
    {
        parent::__construct();
        if ( self::SORT_MODE === 'mtime' )
        {
            // Sort by modification time to get updated tests first
            usort( $this->files,
                   array( $this, 'sortTestsByMtime' ) );
        }
        else
        {
            // Sort it, then the file a.in will be processed first. Handy for development.
            usort( $this->files,
                   array( $this, 'sortTestsByName' ) );
        }
    }

    /**
     * Get name of current test (uses file name)
     *
     * @param bool $withDataSet
     * @return string
     */
    public function getName( $withDataSet = TRUE )
    {
        return $this->currentFile;
    }

    /**
     * Get list of files for current set of tests
     *
     * @return array
     */
    final public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set current file name
     *
     * @param string $file
     */
    final public function setCurrentFile( $file )
    {
        $this->currentFile = $file;
    }

    /**
     * Recursivly read files in sub folders of $dir, optionally
     * filtered by $onlyWithExtension and apply all to $total.
     *
     * @param string $dir
     * @param array $total By ref list of files (the result of this function)
     * @param false|string $onlyWithExtension
     */
    final protected function readDirRecursively( $dir, &$total, $onlyWithExtension = false )
    {
        $extensionLength = strlen( $onlyWithExtension );
        $path = opendir( $dir );

        if ( $path === false )
        {
            return;
        }

        while ( false !== ( $file = readdir( $path ) ) )
        {
            if ( $file !== '.' && $file !== '..' )
            {
                $new = $dir . DIRECTORY_SEPARATOR . $file;

                if ( is_file( $new ) )
                {
                    if ( !$onlyWithExtension ||
                         substr( $file,  -$extensionLength - 1 ) === ".{$onlyWithExtension}" )
                    {
                        $total[] = array( 'file' => $new,
                                          'mtime' => filemtime( $new ) );
                    }
                }
                elseif ( is_dir( $new ) )
                {
                    $this->readDirRecursively( $new, $total, $onlyWithExtension );
                }
            }
        }
    }

    final protected function sortTestsByMtime( $a, $b )
    {
        if ( $a['mtime'] != $b['mtime'] )
        {
            return $a['mtime'] < $b['mtime'] ? 1 : -1;
        }
        return strnatcmp( $a['file'], $b['file'] );
    }

    final protected function sortTestsByName( $a, $b )
    {
        return strnatcmp( $a['file'], $b['file'] );
    }

    final protected function outFileName( $file, $inExtension, $outExtension = '.out' )
    {
        $baseFile = substr( $file, 0, strlen( $file ) - strlen( $inExtension ) );
        return $baseFile . $outExtension;
    }

    final public function runTest()
    {
        if ( $this->currentFile === false )
        {
            throw new PHPUnit_Framework_ExpectationFailedException( "No currentFile set for test " . __CLASS__ );
        }

        $exception = null;
        $this->retryTest = true;
        while ( $this->retryTest )
        {
            try
            {
                $this->retryTest = false;
                $this->testRunRegression( $this->currentFile );
            }
            catch ( Exception $e )
            {
                $exception = $e;
            }
        }

        if ( $exception !== null )
        {
            throw $exception;
        }
    }

    /**
     * The actually test function, is executed pr unit test file.
     *
     * @param string $file
     */
    abstract protected function testRunRegression( $file );

    /**
     * Needs to be re implemented by child
     *
     * @return ezpTestRegressionSuite
     */
    abstract public static function suite();
    /*{
        return new ezpTestRegressionSuite( __CLASS__ );
    }*/
}
?>
