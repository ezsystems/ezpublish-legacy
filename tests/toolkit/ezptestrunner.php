<?php
/**
 * File containing the ezpTestRunner class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

require_once 'PHPUnit/Util/Filter.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

PHPUnit_Util_Filter::addFileToFilter( __FILE__ );

class ezpTestRunner extends PHPUnit_TextUI_TestRunner
{
    static $consoleInput;

    public static function main()
    {
        $testRunner = new ezpTestRunner();
        $testRunner->runFromArguments();
    }

    public function runFromArguments()
    {
        self::$consoleInput = new ezcConsoleInput();

        self::registerConsoleArguments( self::$consoleInput );
        self::processConsoleArguments( self::$consoleInput );

        $options = self::getSpecifiedConsoleOptions( self::$consoleInput );
        $suite = $this->prepareTests( self::$consoleInput->getArguments(), $options );

        if ( self::$consoleInput->getOption( 'list-tests' )->value )
        {
            $this->listTests( $suite );
            exit( PHPUnit_TextUI_TestRunner::SUCCESS_EXIT );
        }

        if ( self::$consoleInput->getOption( 'list-groups' )->value )
        {
            $this->listGroups( $suite );
            exit( PHPUnit_TextUI_TestRunner::SUCCESS_EXIT );
        }

        try
        {
            $result = $this->doRun( $suite, $options );
        }
        catch ( ezcConsoleOptionException $e )
        {
            die ( $e->getMessage() . "\n" );
        }
    }

    /**
     * Registers the consoleInput options and arguments.
     *
     * The options and arguments are registered in the given $consoleInput object.
     *
     * @param ezcConsoleInput $consoleInput
     */
    protected static function registerConsoleArguments( $consoleInput )
    {
        // Ansi option
        $ansi = new ezcConsoleOption( 'a', 'ansi', ezcConsoleInput::TYPE_NONE );
        $ansi->shorthelp = "Use ANSI colors in output. Needs PHPUnit 3.3 or newer.";
        $consoleInput->registerOption( $ansi );

        // Color option, 'ansi' renamed. Will keep ansi for PHPUnit 3.3.0 for now.
        $colors = new ezcConsoleOption( '', 'colors', ezcConsoleInput::TYPE_NONE );
        $colors->shorthelp = "Use ANSI colors in output. Needs PHPUnit 3.3.2 or newer.";
        $consoleInput->registerOption( $colors );

        // Configuration XML File option
        $configuration = new ezcConsoleOption( '', 'configuration', ezcConsoleInput::TYPE_STRING );
        $configuration->shorthelp = "Read configuration from XML file.";
        $consoleInput->registerOption( $configuration );

        // Database-per-test option
        $dbPerTest = new ezcConsoleOption( '', 'db-per-test', ezcConsoleInput::TYPE_NONE );
        $dbPerTest->shorthelp = "Use a clean database per test";
        $consoleInput->registerOption( $dbPerTest );

        // DSN option
        $dsn = new ezcConsoleOption( 'D', 'dsn', ezcConsoleInput::TYPE_STRING );
        $dsn->shorthelp = "Use the database specified with a DSN: type://user:password@host/database.";
        $dsn->longhelp = "An example to connect with the local MySQL database is:\n";
        $dsn->longhelp .= "mysql://root@mypass@localhost/unittests";
        $consoleInput->registerOption( $dsn );

        // Coverage Clover XML option
        $coverageXml = new ezcConsoleOption( '', 'coverage-xml', ezcConsoleInput::TYPE_STRING );
        $coverageXml->shorthelp = "Write code coverage information in Clover XML format.";
        $consoleInput->registerOption( $coverageXml );

        // Code Coverage generation in html format
        $coverageHtml = new ezcConsoleOption( '', 'coverage-html', ezcConsoleInput::TYPE_STRING );
        $coverageHtml->shorthelp = "Generate code coverage report in HTML format [dir].";
        $consoleInput->registerOption( $coverageHtml );

        // Filter option
        $filter = new ezcConsoleOption( 'f', 'filter', ezcConsoleInput::TYPE_STRING );
        $filter->shorthelp = "Filter which tests to run.";
        $consoleInput->registerOption( $filter );

        // Groups option
        $groups = new ezcConsoleOption( 'g', 'group', ezcConsoleInput::TYPE_STRING );
        $groups->shorthelp = "Only runs tests from the specified group(s).";
        $consoleInput->registerOption( $groups );

        // Help option
        $help = new ezcConsoleOption( 'h', 'help', ezcConsoleInput::TYPE_NONE );
        $help->shorthelp = "Show this help";
        $help->isHelpOption = true;
        $consoleInput->registerOption( $help );

        // List groups option
        $listGroups = new ezcConsoleOption( '', 'list-groups', ezcConsoleInput::TYPE_NONE );
        $listGroups->shorthelp = "List available test groups.";
        $consoleInput->registerOption( $listGroups );

        // List tests option
        $listTests = new ezcConsoleOption( '', 'list-tests', ezcConsoleInput::TYPE_NONE );
        $listTests->shorthelp = "Lists all tests";
        $consoleInput->registerOption( $listTests );

        // Metrics XML option
        $metrics = new ezcConsoleOption( '', 'log-metrics', ezcConsoleInput::TYPE_STRING );
        $metrics->shorthelp = "Write metrics report in XML format.";
        $consoleInput->registerOption( $metrics );

        // Project Mess Detector (PMD) XML option
        $pmd = new ezcConsoleOption( '', 'log-pmd', ezcConsoleInput::TYPE_STRING );
        $pmd->shorthelp = "Write violations report in PMD XML format.";
        $consoleInput->registerOption( $pmd );

        // Verbose option
        $verbose = new ezcConsoleOption( 'v', 'verbose', ezcConsoleInput::TYPE_NONE );
        $verbose->shorthelp = "Output more verbose information.";
        $consoleInput->registerOption( $verbose );

        // XML logfile option
        $xml = new ezcConsoleOption( 'x', 'log-xml', ezcConsoleInput::TYPE_STRING );
        $xml->shorthelp = "Log test execution in XML format to file.";
        $consoleInput->registerOption( $xml );

        // Stop on failure option
        $stopOnFailure = new ezcConsoleOption( '', 'stop-on-failure', ezcConsoleInput::TYPE_NONE );
        $stopOnFailure->shorthelp = "Stop execution upon first error or failure.";
        $consoleInput->registerOption( $stopOnFailure );

        // PHPUnit debug output option
        $debug = new ezcConsoleOption( '', 'debug', ezcConsoleInput::TYPE_NONE );
        $debug->shorthelp = "Turns on debugout output from PHPUnit.";
        $consoleInput->registerOption( $debug );

        // Set up dependencies
        $dbPerTest->addDependency( new ezcConsoleOptionRule( $dsn ) );
    }

    /**
     * Returns an array of all the specified console options
     *
     * @param ezcConsoleInput $consoleInput
     */
    protected static function getSpecifiedConsoleOptions( $consoleInput )
    {
        $ansi = $consoleInput->getOption( 'ansi' )->value;
        $colors = $consoleInput->getOption( 'colors' )->value;
        $config = $consoleInput->getOption( 'configuration' )->value;
        $coverageXml = $consoleInput->getOption( 'coverage-xml' )->value;
        $coverageHtml = $consoleInput->getOption( 'coverage-html' )->value;
        $dsn = $consoleInput->getOption( 'dsn' )->value;
        $filter = $consoleInput->getOption( 'filter' )->value;
        $groups = $consoleInput->getOption( 'group' )->value;
        $logfile = $consoleInput->getOption( 'log-xml' )->value;
        $metrics = $consoleInput->getOption( 'log-metrics' )->value;
        $pmd = $consoleInput->getOption( 'log-pmd' )->value;
        $verbose = $consoleInput->getOption( "verbose" )->value;
        $stopOnFailure = $consoleInput->getOption( 'stop-on-failure' )->value;
        $debug = $consoleInput->getOption( 'debug' )->value;

        $options = array();
        $options['ansi'] = $ansi ? True : null;
        $options['colors'] = $colors ? True : null;
        $options['configuration'] = $config ? $config : null;
        $options['dsn'] = $dsn ? $dsn : null;
        $options['groups'] = $groups ? explode(',', $groups ): null;
        $options['filter'] = $filter ? $filter : null;
        $options['metricsXML'] = $metrics ? $metrics : null;
        $options['pmdXML'] = $pmd ? $pmd : null;
        $options['coverageClover'] = $coverageXml ? $coverageXml : null;
        $options['reportDirectory'] = $coverageHtml ? $coverageHtml : null;
        $options['verbose'] = $verbose ? true : false;
        $options['xmlLogfile'] = $logfile ? $logfile : null;
        $options['stopOnFailure'] = $stopOnFailure ? $stopOnFailure : null;
        $options['debug'] = $debug ? true : null;

        return $options;
    }

    /**
     * Processes all console options
     *
     * If the help option is specified by the user the help text will be
     * displayed and the program will exit.
     *
     * @param ezcConsoleInput $consoleInput
     */
    protected static function processConsoleArguments( $consoleInput )
    {
        try
        {
             $consoleInput->process();
        }
        catch ( ezcConsoleOptionException $e )
        {
            die ( $e->getMessage() . "\n" );
        }

        if ( $consoleInput->getOption( "help" )->value )
        {
            self::displayHelp( self::$consoleInput );
            exit();
        }
    }

    /**
     * Displays the help text
     *
     * @param ezcConsoleInput $consoleInput
     */
    protected static function displayHelp( $consoleInput )
    {
        echo $consoleInput->getHelpText( 'eZ Publish Test Runner' );
    }

    /**
     * Scans a set of directories looking for suite.php to add to include.
     *
     * @param string $directories
     * @param string $params
     * @return ezpTestSuite $suite
     */
    protected function prepareTests( $directories, $params )
    {
        if ( count( $directories ) <= 0 )
            return self::suite();

        $suite = new ezpTestSuite;
        $suite->setName( "eZ Publish Test Suite" );

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
                print( "Unable to load $file\n" );
            }
        }

        return $suite;
    }

    /**
     * Returns the default test suite including all tests in all extensions.
     *
     * @return ezpTestSuite $suite
     */
    public static function suite()
    {
        $suite = new eZTestSuite;

        // Add suites from extensions.
        $extensions = eZDir::findSubitems( eZExtension::baseDirectory(), 'd', true );

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
     * Prints all groups (@group annotation) found in $suite
     *
     * @param ezpTestCase $suite
     */
    protected function listGroups( $suite )
    {
        print "Available test group(s):\n";

        $groups = $suite->getGroups();
        sort( $groups );

        foreach ( $groups as $group )
        {
            print " - $group\n";
        }
    }

    /**
     * Prints all tests found in $suite
     *
     * @param ezpTestCase $suite
     */
    protected function listTests( $suite )
    {
        $iterator = $suite->getIterator();
        print "Available test(s):\n";

        foreach ( $iterator as $test )
        {
            $reflectionClass = new ReflectionClass( $test );
            $currentTest = $reflectionClass->getName() . "::" . $test->getName();
            print( "- $currentTest\n" );
        }
    }

    /**
     * Prepends the extension path to $path if not already in $path
     *
     * @param string $path
     */
    protected function normalizeExtensionPath( $path )
    {
        if ( strpos( $path, eZExtension::baseDirectory() ) === false )
            $path = eZDir::path( array( eZExtension::baseDirectory(), $path ) );

        return $path;
    }

    /**
     * Returns the first class name found inside of $file
     *
     * @param string $file
     */
    static public function getClassName( $file )
    {
        // $file argument has / directory seperator, but the getFileName() method of ReflectionClass
        // returns a path with platform specific style of directory seperator
        if ( DIRECTORY_SEPARATOR != '/' )
        {
           $file = strtr( $file, '/', DIRECTORY_SEPARATOR );
        }

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
    }

    /**
     * Returns a ezpDsn object created from the dsn console input
     *
     * @throws ezcConsoleOptionMandatoryViolationException if no dsn input option is found
     * @return ezpDsn dsn
     */
    static public function dsn()
    {
        $dsnOption = self::$consoleInput->getOption( 'dsn' );
        if ( $dsnOption->value )
        {
            $dsn = new ezpDsn( $dsnOption->value );
        }
        else
        {
            throw new ezcConsoleOptionMandatoryViolationException( $dsnOption );
        }

        return $dsn;
    }

    /**
     * Returns true/false if the database should be created per database
     *
     * @return bool
     */
    static public function dbPerTest()
    {
        if ( ezpTestRunner::$consoleInput->getOption( 'db-per-test' )->value )
        {
            return true;
        }

        return false;
    }
}

?>
