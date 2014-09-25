<?php
/**
 * File containing the ezpTestRunner class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */


class ezpTestRunner extends PHPUnit_TextUI_Command
{
    /**
     * @var ezpTestRunner
     */
    static private $instance = null;

    /**
     * Enables additional parameters for the test runner
     */
    public function __construct()
    {
        $this->longOptions['list-suites'] = 'handleListSuites';
        $this->longOptions['list-tests'] = 'handleListTests';
        $this->longOptions['dsn='] = 'handleDsn';
        $this->longOptions['db-per-test'] = 'handleDbPerTest';

        // Default values
        $this->arguments['list-suites'] = false;
        $this->arguments['list-tests'] = false;
        $this->arguments['dsn'] = '';
        $this->arguments['db-per-test'] = false;
    }

    /**
     * Returns the ezpTestRunner instance
     *
     * @return ezpTestRunner
     */
    static public function instance()
    {
        if( self::$instance === null )
        {
            self::$instance = new ezpTestRunner;
        }
        return self::$instance;
    }

    /**
     * Return the argument $argumentName given on the command line
     * $argumentName must be the long option name ( 'configuration' ), not the short option name ( 'c' )
     *
     * @param $argumentName
     *
     * @return mixed|null
     */
    public function getLongOption( $argumentName )
    {
        if ( array_key_exists( $argumentName, $this->longOptions ) || array_key_exists( "$argumentName=", $this->longOptions ) )
        {
            if ( array_key_exists( $argumentName, $this->arguments ) )
            {
                return $this->arguments[$argumentName];
            }
            else
            {
                return null;
            }
        }
        else
        {
            var_dump( "Error: Invalid argument name '{$argumentName}'" );
            var_dump( $this->longOptions );
            die;
        }
    }

    /**
     * If called, will output a list of all available tests
     *
     * @see handleCustomTestSuite()
     */
    public function handleListTests()
    {
        $this->arguments['list-tests'] = true;
    }

    /**
     * Stores the given DSN string into the runners arguments array
     *
     * @see dsn()
     * @param string $value
     */
    public function handleDsn( $value )
    {
        $this->arguments['dsn'] = $value;
    }

    /**
     * Sets the argument 'db-per-test' to true, which means that every test will be
     * performed on a clean database
     */
    public function handleDbPerTest()
    {
        $this->arguments['db-per-test'] = true;
    }

    /**
     * Extends PHPUnit's default help text with the additional options coming with
     * this runner
     */
    protected function showHelp()
    {
        parent::showHelp();
        print <<<EOT

  --db-per-test             Use a clean database per test
  --dsn <resource>          Use the database specified with a DSN: type://user:password@host/database.
                            An example to connect with the local MySQL database is:
                            mysqli://root:mypass@localhost/unittests
  --list-suites             Lists all suites
  --list-tests              Lists all tests

EOT;
    }

    /**
     * Returns the eZ Publish test suite
     *
     * If $directories are given, only the tests in these directories will be executed.
     * If omitted, the default eZ Publish test suite and all extension test suites will be executed.
     *
     * @param array $directories
     * @return ezpTestSuite $suite
     */
    protected function prepareTests( array $directories = array() )
    {
        // If no $directories are given, we return the standard eZ Publish test suite
        if ( count( $directories ) === 0 )
        {
            // The default eZ Publish test suite with all core tests
            $suite = new eZTestSuite();
            // Add the extension directories to search for test suites
            $directories = eZDir::findSubitems( eZExtension::baseDirectory(), 'dl', true );
        }
        else
        {
            $suite = new ezpTestSuite();
            $suite->setName( "eZ Publish Test Suite" );
        }

        foreach ( $directories as $dir )
        {
            $file = $dir;
            if ( strpos( $file, 'suite.php' ) === false )
            {
                $file = eZDir::path( array( $file, "suite.php" ) );
            }

            if ( !file_exists( $file ) )
            {
                $normalizedExtDir = $this->normalizeExtensionPath( $dir );
                $file = eZDir::path( array( $normalizedExtDir, "tests/suite.php") );
            }

            if ( file_exists( $file ) )
            {
                require_once( $file );
                $class = self::getClassName( $file );
                $suite->addTest( new $class );
            }
            else
            {
                // No suite.php found anywhere in given (extension) directory.
                print( "No tests found for $dir\n" );
            }
        }

        return $suite;
    }

    /**
     * Returns the default test suite including all tests in all extensions.
     *
     * @return ezpTestSuite $suite
     */
    static public function suite()
    {
        if ( !class_exists( 'eZTestSuite', true ) )
        {
            echo "\nThe eZTestSuite class isn't defined. Are the tests autoloads generated ?\n";
            echo "You can generate them using php bin/php/ezpgenerateautoloads.php -s\n\n";
            exit( PHPUnit_TextUI_TestRunner::FAILURE_EXIT );
        }

        $suite = new eZTestSuite;

        // Add suites from extensions.
        $extensions = eZDir::findSubitems( eZExtension::baseDirectory(), 'dl', true );

        foreach( $extensions as $extension )
        {
            $suiteFile = eZDir::path( array( $extension, "tests", "suite.php" ) );

            if ( file_exists( $suiteFile ) )
            {
                $suite->addTestFile( $suiteFile );
            }
        }

        return $suite;
    }

    /**
     * Checks if an argument has been given to the testrunner and uses it to prepare the tests
     * If set, the test runner will ONLY execute the given tests and omit the kernel
     * @see prepareTests()
     */
    protected function handleCustomTestSuite()
    {
        $this->arguments['test'] = $this->prepareTests( $this->options[1] );

        if( $this->arguments['list-suites'] )
        {
            $this->listSuites( $this->arguments['test'] );
        }

        if( $this->arguments['list-tests'] )
        {
            $this->listTests( $this->arguments['test'] );
        }
    }

    /**
     * Displays a list of available tests
     *
     * @param ezpTestSuite $suite
     */
    protected function listTests( ezpTestSuite $suite )
    {
        $tests = $this->getTests( $suite );

        print "Available test(s):\n";
        foreach( $tests as $test )
        {
            print "- {$test}\n";
        }
        exit( PHPUnit_TextUI_TestRunner::SUCCESS_EXIT);
    }

    /**
     * Prepends the extension path to $path if not already in $path
     *
     * @param string $path
     * @return string
     */
    protected function normalizeExtensionPath( $path )
    {
        if ( strpos( $path, eZExtension::baseDirectory() ) === false )
            $path = eZDir::path( array( eZExtension::baseDirectory(), $path ) );

        return $path;
    }

    /**
     * Returns the first class name found inside of $file or false if no class is in the file
     *
     * @param string $file
     * @return string|bool
     */
    static public function getClassName( $file )
    {
        // $file argument has / directory separator, but the getFileName() method of ReflectionClass
        // returns a path with platform specific style of directory separator
        if ( DIRECTORY_SEPARATOR != '/' )
        {
            $file = strtr( $file, '/', DIRECTORY_SEPARATOR );
        }

        // Resolve symlinks and expand path to file
        $file = realpath( $file );

        $classes = get_declared_classes();

        $size = count( $classes );
        $total = $size > 30 ? 30 : $size;

        // check only the last 30 classes.
        for ( $i = $size - 1; $i > $size - $total - 1; $i-- )
        {
            $rf = new ReflectionClass( $classes[$i] );

            $len = strlen( $file );
            if ( strcmp( $file, substr( $rf->getFileName(), -$len ) ) == 0 )
            {
                return $classes[$i];
            }
        }

        return false;
    }

    /**
     * Returns an ezpDsn object created from the dsn console input
     *
     * @return ezpDsn dsn
     */
    static public function dsn()
    {
        $testRunner = ezpTestRunner::instance();

        return new ezpDsn( $testRunner->arguments['dsn'] );
    }

    /**
     * Returns true/false if the database should be created per test
     *
     * @return bool
     */
    static public function dbPerTest()
    {
        $testRunner = ezpTestRunner::instance();

        return $testRunner->arguments['db-per-test'];
    }

    /**
     * Additionally ensures that the required argument "--dsn" is set
     *
     * @inheritdoc
     * @param array $argv
     */
    protected function handleArguments(array $argv)
    {
        parent::handleArguments($argv);

        if ( empty( $this->arguments['dsn'] ) )
        {
            PHPUnit_TextUI_TestRunner::showError( 'The parameter --dsn is required' );
        }
    }

    /**
     * If called, will output a list of all available suites
     *
     * @see handleCustomTestSuite()
     */
    public function handleListSuites()
    {
        $this->arguments['list-suites'] = true;
    }

    /**
     * Displays a list of available test suites
     *
     * @param ezpTestSuite $suite
     */
    public function listSuites( ezpTestSuite $suite )
    {
        $suites = $this->getSuites( $suite );

        print "Available suite(s):\n";
        foreach( $suites as $s )
        {
            print "- {$s}\n";
        }
        exit( PHPUnit_TextUI_TestRunner::SUCCESS_EXIT);
    }

    /**
     * Returns an array with the names of all available test suites which are children of $suite
     *
     * @param ezpTestSuite $suite
     * @return array
     */
    protected function getSuites( ezpTestSuite $suite )
    {
        $suites = array();

        /** @var PHPUnit_Framework_Test[]|ezpTestSuite[] $tests */
        $tests = $suite->tests();

        foreach( $tests as $test )
        {
            $reflectionClass = new ReflectionClass( $test );

            if ( $reflectionClass->isSubclassOf( 'PHPUnit_Framework_TestSuite' ) )
            {
                $suites[] = $reflectionClass->getName();
            }

            if ( $reflectionClass->isSubclassOf( 'ezpTestSuite' ) )
            {
                $suites = array_merge( $suites, $this->getSuites( $test ) );
            }
        }

        sort( $suites );

        return $suites;
    }

    /**
     * Returns an array with the names of all available tests which are children of $suite
     *
     * @param ezpTestSuite $suite
     * @return string[]
     */
    protected function getTests( ezpTestSuite $suite )
    {
        $tests = array();

        $iterator = $suite->getIterator();

        /** @var PHPUnit_Framework_TestCase $test */
        foreach ( $iterator as $test )
        {
            $reflectionClass = new ReflectionClass( $test );
            $tests[] = $reflectionClass->getName() . "::" . $test->getName();
        }

        sort( $tests );

        return $tests;
    }

    /**
     * Creates a test runner with eZ Publish specific code coverage configuration
     *
     * @inheritdoc
     * @return PHPUnit_TextUI_TestRunner
     */
    protected function createRunner()
    {
        $filter = $this->createCodeCoverageFilter();
        return new PHPUnit_TextUI_TestRunner($this->arguments['loader'], $filter );
    }

    /**
     * Whitelists all kernel, lib and extension classes for code coverage
     *
     * @return PHP_CodeCoverage_Filter
     */
    protected function createCodeCoverageFilter()
    {
        $filter = new PHP_CodeCoverage_Filter();

        // Add kernel classes to whitelist
        $kernelClassFiles = require 'autoload/ezp_kernel.php';
        $filter->addFilesToWhitelist( $kernelClassFiles );

        // Add extension classes to whitelist
        if ( !is_file( 'var/autoload/ezp_extension.php' ) )
        {
            PHPUnit_TextUI_TestRunner::showError(
                "Please generate the extension autoloads first.\n"
                ."You can generate them using php bin/php/ezpgenerateautoloads.php -e"
            );
        }

        $extensionClassFiles = require 'var/autoload/ezp_extension.php';
        $filter->addFilesToWhitelist( $extensionClassFiles );

        return $filter;
    }
}

?>
