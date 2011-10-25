<?php
/**
 * File containing the ezpTestRunner class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */


class ezpTestRunner extends PHPUnit_TextUI_Command
{
    protected $suite = null;
    static private $instance = null;
    
    public function __construct()
    {
        //parent::__construct();
        $this->longOptions['list-tests'] = 'handleListTests';
        $this->longOptions['dsn='] = 'handleDsn';
        $this->longOptions['db-per-test'] = 'handleDbPerTest';
        
/*        self::$consoleInput = new ezcConsoleInput();
        // Configuration XML File option
        $configuration = new ezcConsoleOption( '', 'configuration', ezcConsoleInput::TYPE_STRING );
        $configuration->shorthelp = "Read configuration from XML file.";
        self::$consoleInput->registerOption( $configuration );
        
        // DSN option
        $dsn = new ezcConsoleOption( 'D', 'dsn', ezcConsoleInput::TYPE_STRING );
        $dsn->shorthelp = "Use the database specified with a DSN: type://user:password@host/database.";
        $dsn->longhelp = "An example to connect with the local MySQL database is:\n";
        $dsn->longhelp .= "mysql://root@mypass@localhost/unittests";
        self::$consoleInput->registerOption( $dsn );

        // Database-per-test option
        $dbPerTest = new ezcConsoleOption( '', 'db-per-test', ezcConsoleInput::TYPE_NONE );
        $dbPerTest->shorthelp = "Use a clean database per test";
        self::$consoleInput->registerOption( $dbPerTest );

        self::$consoleInput->process(); */
    }

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
     **/
    public function getLongOption( $argumentName )
    {
        if( array_key_exists( $argumentName, $this->longOptions ) or array_key_exists( "$argumentName=", $this->longOptions ) )
        {
            if( array_key_exists( $argumentName, $this->arguments ) )
            {
                return $this->arguments[$argumentName];
            } else
            {
                return null;
            }
        } else
        {
            //fixme : throw exception
            var_dump( 'Error : invalid argument name : ' . $argumentName);
            var_dump( $this->longOptions );
            die;
        }
    }

    public function handleListTests()
    {
        $this->arguments['list-tests'] = true;
    }
    
    public function handleDsn( $value )
    {
        $this->arguments['dsn'] = $value;
    }
    
    public function handleDbPerTest()
    {
        $this->arguments['db-per-test'] = true;
    }
    
    protected function showHelp()
    {
        parent::showHelp();
        print <<<EOT

  --db-per-test             Use a clean database per test
  --dsn <resource>          Use the database specified with a DSN: type://user:password@host/database.
                            An example to connect with the local MySQL database is:
                            mysql://root@mypass@localhost/unittests
  --list-tests              Lists all tests

EOT;
    }
    
    /**
     * Scans a set of directories looking for suite.php to add to include.
     *
     * @param string $directories
     * @param string $params
     * @return ezpTestSuite $suite
     */
     //taken from old ezptestrunner
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
//  taken from old ezptestrunner
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

    protected function handleCustomTestSuite()
    {
        $this->suite = $this->prepareTests( null, null );

        return $this->suite;
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
    }

    /**
     * Returns a ezpDsn object created from the dsn console input
     *
     * @throws ezcConsoleOptionMandatoryViolationException if no dsn input option is found
     * @return ezpDsn dsn
     */
    static public function dsn()
    {
        $testRunner = ezpTestRunner::instance();
        if( array_key_exists( 'dsn', $testRunner->arguments ) )
        {
            $dsnOption = $testRunner->arguments['dsn'];
            $dsn = new ezpDsn( $dsnOption );
        }
        else
        {
            var_dump("Warning : dsn parameter mandatory");
            //fixme
            //throw new ezcConsoleOptionMandatoryViolationException( $dsnOption );
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
        $testRunner = ezpTestRunner::instance();
        if( array_key_exists( 'db-per-test', $testRunner->arguments ) )
        {
            return $testRunner->arguments['db-per-test'];
        }

        return false;
    }
    
    /**
     * This function is cut&paste from PHPUnit_TextUI_Command::run
     * - We don't require the 'test' parameter (default unnamed parameter ). Instead we'll run the whole test suite (unless filters are applied)
     * - Added support for --list-tests parameter
     **/
    /**
     * @param array   $argv
     * @param boolean $exit
     */
    public function run(array $argv, $exit = TRUE)
    {
        $this->handleArguments($argv);
        
        // *** BEGIN ezp custom code BEGIN ***
        if( isset($this->arguments['list-tests']) )
        {
            $this->listTests( $this->suite );
            exit(PHPUnit_TextUI_TestRunner::SUCCESS_EXIT);
        }
        // *** END ezp custom code END***
        

        $runner = new PHPUnit_TextUI_TestRunner($this->arguments['loader']);

        // *** BEGIN ezp custom code BEGIN ***
        // **Following code is removed compared to the original implementation
        /*if (is_object($this->arguments['test']) &&
            $this->arguments['test'] instanceof PHPUnit_Framework_Test) {
            $suite = $this->arguments['test'];
        } else {
            $suite = $runner->getTest(
              $this->arguments['test'],
              $this->arguments['testFile'],
              $this->arguments['syntaxCheck']
            );
        }*/
        // **And this line is added
        $suite = $this->suite;
        // *** END ezp custom code END ***

        if (count($suite) == 0) {
            $skeleton = new PHPUnit_Util_Skeleton_Test(
              $suite->getName(),
              $this->arguments['testFile']
            );

            $result = $skeleton->generate(TRUE);

            if (!$result['incomplete']) {
                eval(str_replace(array('<?php', '?>'), '', $result['code']));
                $suite = new PHPUnit_Framework_TestSuite(
                  $this->arguments['test'] . 'Test'
                );
            }
        }

        if ($this->arguments['listGroups']) {
            PHPUnit_TextUI_TestRunner::printVersionString();

            print "Available test group(s):\n";

            $groups = $suite->getGroups();
            sort($groups);

            foreach ($groups as $group) {
                print " - $group\n";
            }

            exit(PHPUnit_TextUI_TestRunner::SUCCESS_EXIT);
        }

        unset($this->arguments['test']);
        unset($this->arguments['testFile']);

        try {
            $result = $runner->doRun($suite, $this->arguments);
        }

        catch (PHPUnit_Framework_Exception $e) {
            print $e->getMessage() . "\n";
        }

        if ($exit) {
            if (isset($result) && $result->wasSuccessful()) {
                exit(PHPUnit_TextUI_TestRunner::SUCCESS_EXIT);
            }

            else if (!isset($result) || $result->errorCount() > 0) {
                exit(PHPUnit_TextUI_TestRunner::EXCEPTION_EXIT);
            }

            else {
                exit(PHPUnit_TextUI_TestRunner::FAILURE_EXIT);
            }
        }
    }



    /**
     * This function is cut&paste from PHPUnit_TextUI_Command::handleArguments
     * Removed the need for a required unnamed command option ( 'test')
     **/
    protected function handleArguments(array $argv)
    {
        try {
            $this->options = PHPUnit_Util_Getopt::getopt(
              $argv,
              'd:c:',
              array_keys($this->longOptions)
            );
        }

        catch (RuntimeException $e) {
            PHPUnit_TextUI_TestRunner::showError($e->getMessage());
        }

        $skeletonClass = FALSE;
        $skeletonTest  = FALSE;

        foreach ($this->options[0] as $option) {
            switch ($option[0]) {
                case '--colors': {
                    $this->arguments['colors'] = TRUE;
                }
                break;

                case '--bootstrap': {
                    $this->arguments['bootstrap'] = $option[1];
                }
                break;

                case 'c':
                case '--configuration': {
                    $this->arguments['configuration'] = $option[1];
                }
                break;

                case '--coverage-clover': {
                    if (extension_loaded('tokenizer') &&
                        extension_loaded('xdebug')) {
                        $this->arguments['coverageClover'] = $option[1];
                    } else {
                        if (!extension_loaded('tokenizer')) {
                            $this->showMessage(
                              'The tokenizer extension is not loaded.'
                            );
                        } else {
                            $this->showMessage(
                              'The Xdebug extension is not loaded.'
                            );
                        }
                    }
                }
                break;

                case '--coverage-html': {
                    if (extension_loaded('tokenizer') &&
                        extension_loaded('xdebug')) {
                        $this->arguments['reportDirectory'] = $option[1];
                    } else {
                        if (!extension_loaded('tokenizer')) {
                            $this->showMessage(
                              'The tokenizer extension is not loaded.'
                            );
                        } else {
                            $this->showMessage(
                              'The Xdebug extension is not loaded.'
                            );
                        }
                    }
                }
                break;

                case 'd': {
                    $ini = explode('=', $option[1]);

                    if (isset($ini[0])) {
                        if (isset($ini[1])) {
                            ini_set($ini[0], $ini[1]);
                        } else {
                            ini_set($ini[0], TRUE);
                        }
                    }
                }
                break;

                case '--debug': {
                    $this->arguments['debug'] = TRUE;
                }
                break;

                case '--help': {
                    $this->showHelp();
                    exit(PHPUnit_TextUI_TestRunner::SUCCESS_EXIT);
                }
                break;

                case '--filter': {
                    $this->arguments['filter'] = $option[1];
                }
                break;

                case '--group': {
                    $this->arguments['groups'] = explode(',', $option[1]);
                }
                break;

                case '--exclude-group': {
                    $this->arguments['excludeGroups'] = explode(
                      ',', $option[1]
                    );
                }
                break;

                case '--include-path': {
                    $includePath = $option[1];
                }
                break;

                case '--list-groups': {
                    $this->arguments['listGroups'] = TRUE;
                }
                break;

                case '--loader': {
                    $this->arguments['loader'] = $option[1];
                }
                break;

                case '--log-dbus': {
                    $this->arguments['logDbus'] = TRUE;
                }
                break;

                case '--log-json': {
                    $this->arguments['jsonLogfile'] = $option[1];
                }
                break;

                case '--log-junit': {
                    $this->arguments['junitLogfile'] = $option[1];
                }
                break;

                case '--log-tap': {
                    $this->arguments['tapLogfile'] = $option[1];
                }
                break;

                case '--process-isolation': {
                    $this->arguments['processIsolation'] = TRUE;
                    $this->arguments['syntaxCheck']      = FALSE;
                }
                break;

                case '--repeat': {
                    $this->arguments['repeat'] = (int)$option[1];
                }
                break;

                case '--stderr': {
                    $this->arguments['printer'] = new PHPUnit_TextUI_ResultPrinter(
                      'php://stderr',
                      isset($this->arguments['verbose']) ? $this->arguments['verbose'] : FALSE
                    );
                }
                break;

                case '--stop-on-error': {
                    $this->arguments['stopOnError'] = TRUE;
                }
                break;

                case '--stop-on-failure': {
                    $this->arguments['stopOnFailure'] = TRUE;
                }
                break;

                case '--stop-on-incomplete': {
                    $this->arguments['stopOnIncomplete'] = TRUE;
                }
                break;

                case '--stop-on-skipped': {
                    $this->arguments['stopOnSkipped'] = TRUE;
                }
                break;

                case '--skeleton-test': {
                    $skeletonTest  = TRUE;
                    $skeletonClass = FALSE;
                }
                break;

                case '--skeleton-class': {
                    $skeletonClass = TRUE;
                    $skeletonTest  = FALSE;
                }
                break;

                case '--tap': {
                    $this->arguments['printer'] = new PHPUnit_Util_Log_TAP;
                }
                break;

                case '--story': {
                    $this->showMessage(
                      'The --story functionality is deprecated and ' .
                      'will be removed in the future.',
                      FALSE
                    );

                    $this->arguments['printer'] = new PHPUnit_Extensions_Story_ResultPrinter_Text;
                }
                break;

                case '--story-html': {
                    $this->showMessage(
                      'The --story-html functionality is deprecated and ' .
                      'will be removed in the future.',
                      FALSE
                    );

                    $this->arguments['storyHTMLFile'] = $option[1];
                }
                break;

                case '--story-text': {
                    $this->showMessage(
                      'The --story-text functionality is deprecated and ' .
                      'will be removed in the future.',
                      FALSE
                    );

                    $this->arguments['storyTextFile'] = $option[1];
                }
                break;

                case '--syntax-check': {
                    $this->arguments['syntaxCheck'] = TRUE;
                }
                break;

                case '--testdox': {
                    $this->arguments['printer'] = new PHPUnit_Util_TestDox_ResultPrinter_Text;
                }
                break;

                case '--testdox-html': {
                    $this->arguments['testdoxHTMLFile'] = $option[1];
                }
                break;

                case '--testdox-text': {
                    $this->arguments['testdoxTextFile'] = $option[1];
                }
                break;

                case '--no-configuration': {
                    $this->arguments['useDefaultConfiguration'] = FALSE;
                }
                break;

                case '--no-globals-backup': {
                    $this->arguments['backupGlobals'] = FALSE;
                }
                break;

                case '--static-backup': {
                    $this->arguments['backupStaticAttributes'] = TRUE;
                }
                break;

                case '--verbose': {
                    $this->arguments['verbose'] = TRUE;
                }
                break;

                case '--version': {
                    PHPUnit_TextUI_TestRunner::printVersionString();
                    exit(PHPUnit_TextUI_TestRunner::SUCCESS_EXIT);
                }
                break;

                case '--wait': {
                    $this->arguments['wait'] = TRUE;
                }
                break;

                case '--strict': {
                    $this->arguments['strict'] = TRUE;
                }
                break;
                
                default: {
                    $optionName = str_replace('--', '', $option[0]);

                    if (isset($this->longOptions[$optionName])) {
                        $handler = $this->longOptions[$optionName];
                    }

                    else if (isset($this->longOptions[$optionName . '='])) {
                        $handler = $this->longOptions[$optionName . '='];
                    }

                    if (isset($handler) && is_callable(array($this, $handler))) {
                        $this->$handler($option[1]);
                    }
                }
            }
        }

        if (isset($this->arguments['printer']) &&
            $this->arguments['printer'] instanceof PHPUnit_Extensions_Story_ResultPrinter_Text &&
            isset($this->arguments['processIsolation']) &&
            $this->arguments['processIsolation']) {
            $this->showMessage(
              'The story result printer cannot be used in process isolation.'
            );
        }

        $this->handleCustomTestSuite();

        if (!isset($this->arguments['test'])) {
            if (isset($this->options[1][0])) {
                $this->arguments['test'] = $this->options[1][0];
            }

            if (isset($this->options[1][1])) {
                $this->arguments['testFile'] = $this->options[1][1];
            } else {
                $this->arguments['testFile'] = '';
            }

            if (isset($this->arguments['test']) && is_file($this->arguments['test'])) {
                $this->arguments['testFile'] = realpath($this->arguments['test']);
                $this->arguments['test']     = substr($this->arguments['test'], 0, strrpos($this->arguments['test'], '.'));
            }
        }

        if (isset($includePath)) {
            ini_set(
              'include_path',
              $includePath . PATH_SEPARATOR . ini_get('include_path')
            );
        }

        if (isset($this->arguments['bootstrap'])) {
            $this->handleBootstrap($this->arguments['bootstrap'], $this->arguments['syntaxCheck']);
        }

        if ($this->arguments['loader'] !== NULL) {
            $this->arguments['loader'] = $this->handleLoader($this->arguments['loader']);
        }

        if (isset($this->arguments['configuration']) &&
            is_dir($this->arguments['configuration'])) {
            $configurationFile = $this->arguments['configuration'] .
                                 '/phpunit.xml';

            if (file_exists($configurationFile)) {
                $this->arguments['configuration'] = realpath(
                  $configurationFile
                );
            }

            else if (file_exists($configurationFile . '.dist')) {
                $this->arguments['configuration'] = realpath(
                  $configurationFile . '.dist'
                );
            }
        }

        else if (!isset($this->arguments['configuration']) &&
                 $this->arguments['useDefaultConfiguration']) {
            if (file_exists('phpunit.xml')) {
                $this->arguments['configuration'] = realpath('phpunit.xml');
            } else if (file_exists('phpunit.xml.dist')) {
                $this->arguments['configuration'] = realpath(
                  'phpunit.xml.dist'
                );
            }
        }

        if (isset($this->arguments['configuration'])) {
            try {
                $configuration = PHPUnit_Util_Configuration::getInstance(
                  $this->arguments['configuration']
                );
            }

            catch (Exception $e) {
                print $e->getMessage() . "\n";
                exit(PHPUnit_TextUI_TestRunner::FAILURE_EXIT);
            }

            $phpunit = $configuration->getPHPUnitConfiguration();

            if (isset($phpunit['syntaxCheck'])) {
                $this->arguments['syntaxCheck'] = $phpunit['syntaxCheck'];
            }

            if (isset($phpunit['testSuiteLoaderClass'])) {
                if (isset($phpunit['testSuiteLoaderFile'])) {
                    $file = $phpunit['testSuiteLoaderFile'];
                } else {
                    $file = '';
                }

                $this->arguments['loader'] = $this->handleLoader(
                  $phpunit['testSuiteLoaderClass'], $file
                );
            }

            $configuration->handlePHPConfiguration();

            if (!isset($this->arguments['bootstrap'])) {
                $phpunitConfiguration = $configuration->getPHPUnitConfiguration();

                if (isset($phpunitConfiguration['bootstrap'])) {
                    $this->handleBootstrap($phpunitConfiguration['bootstrap'], $this->arguments['syntaxCheck']);
                }
            }

            $browsers = $configuration->getSeleniumBrowserConfiguration();

            if (!empty($browsers)) {
                PHPUnit_Extensions_SeleniumTestCase::$browsers = $browsers;
            }

            if (!isset($this->arguments['test'])) {
                $testSuite = $configuration->getTestSuiteConfiguration(
                  $this->arguments['syntaxCheck']
                );

                if ($testSuite !== NULL) {
                    $this->arguments['test'] = $testSuite;
                }
            }
        }

        if (isset($this->arguments['test']) && is_string($this->arguments['test']) && substr($this->arguments['test'], -5, 5) == '.phpt') {
            $test = new PHPUnit_Extensions_PhptTestCase($this->arguments['test']);

            $this->arguments['test'] = new PHPUnit_Framework_TestSuite;
            $this->arguments['test']->addTest($test);
        }

        // *** BEGIN ezp custom code BEGIN ***
        // Commented out this stuff
/*        if (!isset($this->arguments['test']) ||
            (isset($this->arguments['testDatabaseLogRevision']) && !isset($this->arguments['testDatabaseDSN']))) {
            $this->showHelp();
            exit(PHPUnit_TextUI_TestRunner::EXCEPTION_EXIT);
        }*/
        // *** END ezp custom code END ***

        if (!isset($this->arguments['syntaxCheck'])) {
            $this->arguments['syntaxCheck'] = FALSE;
        }

        if ($skeletonClass || $skeletonTest) {
            if (isset($this->arguments['test']) && $this->arguments['test'] !== FALSE) {
                PHPUnit_TextUI_TestRunner::printVersionString();

                if ($skeletonClass) {
                    $class = 'PHPUnit_Util_Skeleton_Class';
                } else {
                    $class = 'PHPUnit_Util_Skeleton_Test';
                }

                try {
                    $args      = array();
                    $reflector = new ReflectionClass($class);

                    for ($i = 0; $i <= 3; $i++) {
                        if (isset($this->options[1][$i])) {
                            $args[] = $this->options[1][$i];
                        }
                    }

                    $skeleton = $reflector->newInstanceArgs($args);
                    $skeleton->write();
                }

                catch (Exception $e) {
                    print $e->getMessage() . "\n";
                    exit(PHPUnit_TextUI_TestRunner::FAILURE_EXIT);
                }

                printf(
                  'Wrote skeleton for "%s" to "%s".' . "\n",
                  $skeleton->getOutClassName(),
                  $skeleton->getOutSourceFile()
                );

                exit(PHPUnit_TextUI_TestRunner::SUCCESS_EXIT);
            } else {
                $this->showHelp();
                exit(PHPUnit_TextUI_TestRunner::EXCEPTION_EXIT);
            }
        }
    }
    
}



?>
