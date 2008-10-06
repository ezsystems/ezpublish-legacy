<?php
/**
 * File containing the ezpTestRunner class
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
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

    public function runFromArguments()
    {
        self::$consoleInput = new ezcConsoleInput();

        self::registerConsoleArguments( self::$consoleInput );
        self::processConsoleArguments( self::$consoleInput );

        if ( self::$consoleInput->getOption( "help" )->value )
        {
            self::displayHelp( self::$consoleInput );
            return;
        }

        $params = array();

        $config    = self::$consoleInput->getOption( 'configuration' )->value;
        $logfile   = self::$consoleInput->getOption( 'log-xml' )->value;
        $coverage  = self::$consoleInput->getOption( 'coverage-xml' )->value;
        $metrics   = self::$consoleInput->getOption( 'log-metrics' )->value;
        $pmd       = self::$consoleInput->getOption( 'log-pmd' )->value;
        $reportDir = self::$consoleInput->getOption( 'report-dir' )->value;
        $ansi = self::$consoleInput->getOption( 'ansi' )->value;
        $dsn = self::$consoleInput->getOption( 'dsn' )->value;
        $groups = self::$consoleInput->getOption( 'group' )->value;
        $listGroups = self::$consoleInput->getOption( 'list-groups' )->value;
        $listTests = self::$consoleInput->getOption( 'list-tests' )->value;
        $filter = self::$consoleInput->getOption( 'filter' )->value;

        if ( $config )
        {
            $params['configuration'] = $config;
        }

        if ( $logfile )
        {
            $params['xmlLogfile'] = $logfile;
        }

        if ( $coverage )
        {
            $params['coverageXML'] = $coverage;
        }

        if ( $metrics )
        {
            $params['metricsXML'] = $metrics;
        }

        if ( $pmd )
        {
            $params['pmdXML'] = $pmd;
        }

        if ( $reportDir )
        {
            $params['reportDirectory'] = $reportDir;
        }

        if ( $dsn )
        {
            $params['dsn'] = $dsn;
        }

        if ( $ansi )
        {
            $params['ansi'] = True;
        }

        if ( $groups )
        {
            $params['groups'] = explode(',', $groups );
        }

        if ( $filter )
        {
            $params['filter'] = $filter;
        }

        if ( self::$consoleInput->getOption( "verbose" )->value )
        {
            $params['verbose'] = true;
        }
        else
        {
            $params['verbose'] = false;
        }

        $allSuites = $this->prepareTests( self::$consoleInput->getArguments(), $params );

        if ( $listTests )
        {
            $this->listTests( $allSuites );
            exit( PHPUnit_TextUI_TestRunner::SUCCESS_EXIT );
        }

        if ( $listGroups )
        {
            $this->listGroups( $allSuites );
            exit( PHPUnit_TextUI_TestRunner::SUCCESS_EXIT );
        }

        try
        {
            $result = $this->doRun( $allSuites, $params );
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
     * @return void
     */
    protected static function registerConsoleArguments( $consoleInput )
    {
        // Help option
        $help = new ezcConsoleOption( 'h', 'help', ezcConsoleInput::TYPE_NONE );
        $help->shorthelp = "Show this help";
        $help->isHelpOption = true;
        $consoleInput->registerOption( $help );

        // DSN option
        $dsn = new ezcConsoleOption( 'D', 'dsn', ezcConsoleInput::TYPE_STRING );
        $dsn->shorthelp = "Use the database specified with a DSN: type://user:password@host/database.";
        $dsn->longhelp = "An example to connect with the local MySQL database is:\n";
        $dsn->longhelp .= "mysql://root@mypass@localhost/unittests";
        $consoleInput->registerOption( $dsn );

        // Database-per-test option
        $dbPerTest = new ezcConsoleOption( '', 'db-per-test', ezcConsoleInput::TYPE_NONE );
        $dbPerTest->shorthelp = "Use a clean database per test";
        $dbPerTest->addDependency( new ezcConsoleOptionRule( $dsn ) );
        $consoleInput->registerOption( $dbPerTest );

        // Code Coverage Report directory option
        $report = new ezcConsoleOption( 'c', 'report-dir', ezcConsoleInput::TYPE_STRING );
        $report->shorthelp = "Directory to store test reports and code coverage reports in.";
        $consoleInput->registerOption( $report );

        // Logfile XML option
        $xml = new ezcConsoleOption( 'x', 'log-xml', ezcConsoleInput::TYPE_STRING );
        $xml->shorthelp = "Log test execution in XML format to file.";
        $consoleInput->registerOption( $xml );

        // Coverage XML option
        $coverage = new ezcConsoleOption( '', 'coverage-xml', ezcConsoleInput::TYPE_STRING );
        $coverage->shorthelp = "Write code coverage information in XML format.";
        $consoleInput->registerOption( $coverage );

        // Metrics XML option
        $metrics = new ezcConsoleOption( '', 'log-metrics', ezcConsoleInput::TYPE_STRING );
        $metrics->shorthelp = "Write metrics report in XML format.";
        $consoleInput->registerOption( $metrics );

        // Project Mess Detector (PMD) XML option
        $pmd = new ezcConsoleOption( '', 'log-pmd', ezcConsoleInput::TYPE_STRING );
        $pmd->shorthelp = "Write violations report in PMD XML format.";
        $consoleInput->registerOption( $pmd );

        // Groups option
        $groups = new ezcConsoleOption( 'g', 'group', ezcConsoleInput::TYPE_STRING );
        $groups->shorthelp = "Only runs tests from the specified group(s).";
        $consoleInput->registerOption( $groups );

        // List groups option
        $listGroups = new ezcConsoleOption( '', 'list-groups', ezcConsoleInput::TYPE_NONE );
        $listGroups->shorthelp = "List available test groups.";
        $consoleInput->registerOption( $listGroups );

        // List tests option
        $listTests = new ezcConsoleOption( '', 'list-tests', ezcConsoleInput::TYPE_NONE );
        $listTests->shorthelp = "Lists all tests";
        $consoleInput->registerOption( $listTests );

        // Filter option
        $filter = new ezcConsoleOption( 'f', 'filter', ezcConsoleInput::TYPE_STRING );
        $filter->shorthelp = "Filter which tests to run.";
        $consoleInput->registerOption( $filter );

        // XML Configuration File option
        $configuration = new ezcConsoleOption( '', 'configuration', ezcConsoleInput::TYPE_STRING );
        $configuration->shorthelp = "Read configuration from XML file.";
        $consoleInput->registerOption( $configuration );

        // Verbose option
        $verbose = new ezcConsoleOption( 'v', 'verbose', ezcConsoleInput::TYPE_NONE );
        $verbose->shorthelp = "Output more verbose information.";
        $consoleInput->registerOption( $verbose );

        // Ansi option
        $ansi = new ezcConsoleOption( 'a', 'ansi', ezcConsoleInput::TYPE_NONE );
        $ansi->shorthelp = "Use ANSI colors in output. Needs PHPUnit 3.3 or newer.";
        $consoleInput->registerOption( $ansi );
    }

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
    }

    protected static function displayHelp( $consoleInput )
    {
        echo $consoleInput->getHelpText( 'eZ Publish Test Runner' );
    }

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
                $suite->addTestFile( $file );
            }
            else
            {
                print( "Unable to load $file\n" );
            }
        }

        return $suite;
    }

    protected function listGroups( $allSuites )
    {
        print "Available test group(s):\n";

        $groups = $allSuites->getGroups();
        sort( $groups );

        foreach ( $groups as $group ) 
        {
            print " - $group\n";
        }
    }

    protected function listTests( $allSuites )
    {
        $iterator = $allSuites->getIterator();
        print "Available test(s):\n";

        foreach ( $iterator as $test )
        {
            $reflectionClass = new ReflectionClass( $test );
            $currentTest = $reflectionClass->getName() . "::" . $test->getName();
            print( "- $currentTest\n" );
        }
    }

    protected function normalizeExtensionPath( $path )
    {
        if ( strpos( $path, eZExtension::baseDirectory() ) === false )
            $path = eZDir::path( array( eZExtension::baseDirectory(), $path ) );

        return $path;
    }

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
