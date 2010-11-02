<?php
/**
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version //autogentag//
 * @filesource
 * @package Template
 * @subpackage Tests
 */

/**
 * @package Template
 * @subpackage Tests
 *
 * @note To turn on interactive mode set the environment variable
 *       EZC_TEST_INTERACTIVE to 1 (0 is off)
 * @note To control sorting of .in files set the environment variable
 *       EZC_TEST_TEMPLATE_SORT to: 'mtime', ''
 */
#include_once ("custom_blocks/testblocks.php");
#include_once ("custom_blocks/links.php");
#include_once ("custom_blocks/cblock.php");
#include_once ("custom_blocks/sha1.php");
#include_once ("override.php");



include_once ("regression_suite.php");
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

include_once( 'kernel/common/eztemplatedesignresource.php' );
include_once( 'lib/ezutils/classes/ezextension.php' );




class TemplateTest extends PHPUnit_Framework_TestCase
{
    public $interactiveMode = false;

    static $skipMissingTests = false;

    public $directories = array();

    public $currentFile = false;

    public $regressionDir = '';

    private $retryTest = false;

    private $oldTpl;
    private $newTpl;

    public function __construct()
    {
        parent::__construct();

        $cli = eZCLI::instance();
        $script = eZScript::instance( array( 'use-session' => false, 'use-modules' => true, 'use-extensions' => true ) );
        $script->startup();
        $script->initialize();

        #include "config.php";
        #include "Base/src/base.php";

        # Our autoloads are stored in the 'autoload' directory.
        ezcBase::addClassRepository( ".", "autoload" );
        
        include_once("lib/eztemplate/classes/eztemplate.php");

        $this->regressionDir = dirname(__FILE__) . "/template_tests";

        $directories = array();
        $this->readDirRecursively( $this->regressionDir, $directories, "in" );

        if ( isset( $_ENV['EZC_TEST_TEMPLATE_SORT'] ) &&
             $_ENV['EZC_TEST_TEMPLATE_SORT'] == 'mtime' )
        {
            // Sort by modification time to get updated tests first
            usort( $directories,
                   array( $this, 'sortTestsByMtime' ) );
        }
        else
        {
            // Sort it, then the file a.in will be processed first. Handy for development.
            usort( $directories,
                   array( $this, 'sortTestsByName' ) );
        }

        $this->directories = $directories;

        // Check for environment variables which turns on special features
        if ( isset( $_ENV['EZC_TEST_INTERACTIVE'] ) )
        {
            $this->interactiveMode = (bool)$_ENV['EZC_TEST_INTERACTIVE'];
        }

        $this->initializeOldTemplateEngine();
        $this->initializeNewTemplateEngine();
    }

    public function __destruct()
    {
        $script = eZScript::instance();
        $script->shutdown();
    }

    private function initializeOldTemplateEngine()
    {
        $ini = eZINI::instance();
        $compatAutoLoadPath = $ini->variableArray( 'TemplateSettings', 'AutoloadPath' );
        $autoLoadPathList = $ini->variable( 'TemplateSettings', 'AutoloadPathList' );

        $extensionAutoloadPath = $ini->variable( 'TemplateSettings', 'ExtensionAutoloadPath' );
        $extensionPathList = eZExtension::expandedPathList( $extensionAutoloadPath, 'autoloads' );

        $autoLoadPathList = array_unique( array_merge( $compatAutoLoadPath, $autoLoadPathList, $extensionPathList ) );

        $this->oldTpl = new OldeZTemplate();
        $this->oldTpl->setAutoloadPathList( $autoLoadPathList );
        $this->oldTpl->autoload();

        $this->oldTpl->registerResource( eZTemplateDesignResource::instance() );
        $this->oldTpl->registerResource( eZTemplateDesignResource::standardInstance() );
    }


    public function initializeNewTemplateEngine()
    { 
        # Set the config.
        include_once("kernel/common/template.php");
        neoTemplateInit("var/runtime_unittest.txt");
 
        $tc = ezcTemplateConfiguration::getInstance();
        $tc->compilePath = "var/test_compiled_templates";
        $tc->templatePath = $this->regressionDir;

        $this->newTpl = new ezcTemplate();
    }

    public function sortTestsByMtime( $a, $b )
    {
        if ( $a['mtime'] != $b['mtime'] )
        {
            return $a['mtime'] < $b['mtime'] ? 1 : -1;
        }
        return strnatcmp( $a['file'], $b['file'] );
    }

    public function sortTestsByName( $a, $b )
    {
        return strnatcmp( $a['file'], $b['file'] );
    }

    public function count()
    {
        // We return 1 here since we have startTest/endTest for each .in file
        return 1;
    }

    public function getFiles()
    {
        return $this->directories;
    }

    public function setCurrentFile( $file )
    {
        $this->currentFile = $file;
    }

    // This method overrides the default run() in PHPUnit to allowed data-driven testing.
    public function runTest()
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
            catch (Exception $e)
            {
                $exception = $e;
            }
        }

        if ( $exception !== null )
        {
            throw $exception;
        }
    }

    protected function setUp()
    {
        #$this->createTempDir( "regression_compiled_" );
        date_default_timezone_set( "UTC" );
    }

    protected function tearDown()
    {
        #$this->removeTempDir();
    }

    public static function suite()
    {
         return new RegressionSuite( __CLASS__ );
    }

    private function removeTags( $str )
    {
        $str = str_replace( '<' . '?php', '<' . '?', $str );
        $str= '?' . '>' . trim( $str ) . '<' . '?';
        return $str;
    }

    private function readDirRecursively( $dir, &$total, $onlyWithExtension = false)
    {
        $extensionLength = strlen( $onlyWithExtension );
        $path = opendir( $dir );

        while ( false !== ( $file = readdir( $path ) ) )
        {
            if ( $file != "." && $file != ".." )
            {
                $new = $dir . "/" . $file;

                if ( is_file( $new ) )
                {
                    if ( !$onlyWithExtension ||
                         substr( $file,  -$extensionLength - 1 ) == ".$onlyWithExtension" )
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

    public function interact( $template, $tplSource, $expected, $actual, $expectedFile, $help, $exceptionText )
    {

        while ( true )
        {
            echo "Action (g/c/c!/r/d/do/de/ds/dc/dta/dtt/dd/dx/ge/ee/es/ir/v/q/?): ";

            $reply = strtolower( trim( fgets( STDIN ) ) );

            if ( $reply == "q" )
            {
                exit(0);
            }
            elseif( $reply == "v" )
            {
                echo ( "\n" );
                echo ( str_pad( "Compile path: ", 17) . $template->configuration->compilePath . "\n" );
                echo ( str_pad( "Template path: ", 17 ) . $template->configuration->templatePath . "\n" );
                echo ( str_pad( "Source path: ", 17 ) . $tplSource . " \n" );
                echo ( str_pad( "Expected output: ", 17 ) . $expectedFile . " \n" );
                echo ( "\n" );
                echo (  str_pad( "Context: ", 17 ) . get_class( $template->configuration->context ) );

                echo( "\n" );

                continue;
            }
            elseif ( $reply == "r" )
            {
                $this->retryTest = true;
                return;
            }
            elseif ( $reply == "g" )
            {
                file_put_contents( $expectedFile, $actual );
                return;
            }
            elseif ( $reply == "do" || $reply == "de" || $reply == "dx" || $reply == "ds" || $reply == "dc" || $reply == "dta" || $reply == "dtt" || $reply == "dd" || $reply == "d" )
            {
                $displayText = false;

                if ( $reply == "ds" || $reply == "d" )
                {
                    if ( $reply == "d" )
                    {
                        $displayText .= "------Source template------\n";
                    }
                    $displayText .= file_get_contents( $tplSource );
                }
                if ( $reply == "do" || $reply == "d" )
                {
                    if ( $reply == "d" )
                    {
                        $displayText .= "------Generated output------\n";
                    }
                    $displayText .= $actual;
                }
                if ( $reply == "de" || $reply == "d" )
                {
                    if ( $reply == "d" )
                    {
                        if ( file_exists( $expectedFile ) )
                        {
                            $displayText .= "------Expected output------\n";
                        }
                        else
                        {
                            $displayText .= "------Expected output (file missing)------\n";
                        }
                    }
                    $displayText .= $expected;
                }
                if ( $reply == "dx" || $reply == "d" )
                {
                    if ( $reply == "d" )
                    {
                        $displayText .= "------Expection------\n";
                    }
                    $displayText .= $exceptionText;
                }
                if ( $reply == "dd" || $reply == "d" )
                {
                    if ( $reply == "d" )
                    {
                        $displayText .= "------Diff of output------\n";
                    }
                    if ( PHP_OS == 'Linux' )
                    {
                        $expectedTmp = "var/test_compiled_templates/expected.tmp";
                        $actualTmp = "var/test_compiled_templates/actual.tmp";
                        file_put_contents( $expectedTmp, $expected );
                        file_put_contents( $actualTmp, $actual );
                        $displayText .= shell_exec( "diff -U3 '$expectedTmp' '$actualTmp'" );
                        unlink( $expectedTmp );
                        unlink( $actualTmp );
                    }
                    else
                    {
                    }
                }
                if ( $reply == "dc" || $reply == "d" )
                {
                    if ( file_exists( $template->compiledTemplatePath ) )
                    {
                        if ( $reply == "d" )
                        {
                            $displayText .= "------Compiled PHP code------\n";
                        }
                        $code = file_get_contents( $template->compiledTemplatePath );
                        $code = str_replace( "<"."?php", "", $code );
                        $displayText .= str_replace( "?" . ">", "", $code );
                    }
                    else
                    {
                        if ( $reply == "d" )
                        {
                            $displayText .= "------Compiled PHP code not found------\n";
                        }
                        else
                        {
                            echo "The compiled file <" . $template->compiledTemplatePath . "> was not found\n";
                            echo "This usually means the template file contained syntax errors\n";
                            continue;
                        }
                    }
                }

                if ( $reply == "dtt" || $reply == "d" )
                {
                    // NOTE: This currently fails due to missing implementations in the Tst class.
                    if ( $template->tstTree instanceof ezcTemplateTstNode )
                    {
                        if ( $reply == "d" )
                        {
                            $displayText .= "------TST------\n";
                        }

                        $displayText .= ezcTemplateTstTreeOutput::output( $template->tstTree );
                        $displayText .= "\n";
                    }
                    else
                    {
                        if ( $reply == "d" )
                        {
                            $displayText .= "------TST tree not available------\n";
                        }
                        else
                        {
                            echo "The TST tree is not available\n";
                            continue;
                        }
                    }
                }
                if ( $reply == "dta" || $reply == "d" )
                {
                    if ( $template->astTree instanceof ezcTemplateAstNode )
                    {
                        if ( $reply == "d" )
                        {
                            $displayText .= "------AST------\n";
                        }
                        $displayText .= ezcTemplateAstTreeOutput::output( $template->astTree );
                    }
                    else
                    {
                        if ( $reply == "d" )
                        {
                            $displayText .= "------AST tree not available------\n";
                        }
                        else
                        {
                            echo "The AST tree is not available\n";
                            continue;
                        }
                    }
                }

                if ( PHP_OS == 'Linux' )
                {
                    // Pipe the text to less
                    $l = popen( "less", "w" );

                    if ( $l )
                    {
                        fwrite( $l, $displayText );
                        pclose( $l );
                        continue;
                    }
                }

                echo $displayText, "\n";
                continue;
            }
            elseif ( $reply == 'c' )
            {
                throw new PHPUnit_Framework_ExpectationFailedException( $help );
            }
            elseif ( $reply == 'c!' )
            {
                self::$skipMissingTests = true;
                throw new PHPUnit_Framework_ExpectationFailedException( $help );
            }
            elseif ($reply == "ir" )
            {
                if ( file_exists( $tplSource.".tmp" ) )
                {
                    echo "Cannot rename the file to: {$tplSource}.tmp because the file already exists\n";
                    continue;
                }

                echo ( "renaming: " .  $tplSource . " to: " . $tplSource.".tmp\n"   );
                rename ( $tplSource, $tplSource.".tmp" );

                throw new PHPUnit_Framework_ExpectationFailedException( $help );
            }
            elseif ( $reply == "ge" || $reply == "ee" )
            {
                $editor = ( isset($_ENV["EDITOR"] ) && $_ENV["EDITOR"] != "" ) ? $_ENV["EDITOR"] : "vi";

                if ( $reply == "ge" )
                {
                    file_put_contents( $expectedFile, $actual );
                }

                passthru( $editor . " " . escapeshellcmd( $expectedFile ) );
                continue;
            }
            elseif ( $reply == "es" )
            {
                $editor = ( isset($_ENV["EDITOR"] ) && $_ENV["EDITOR"] != "" ) ? $_ENV["EDITOR"] : "vi";

                passthru( $editor . " " . escapeshellcmd( $tplSource ) );
                continue;
            }
            elseif ( $reply == '?' )
            {
                echo "The actions are:\n",
                    "g   - Generate output file (Implies success of test)\n",
                    "c   - Continue, Skip the current test (Implies failure of test)\n",
                    "c!  - Continue, Skip all test with a missing out file.\n",
                    "r   - Retry the test\n",

                    "do  - Display the generated output\n",
                    "de  - Display the expected output\n",
                    "dx  - Display the exception\n",
                    "ds  - Display source template\n",
                    "dc  - Display generated/compiled PHP code\n",
                    "d   - Display all\n",

                    "dta - Display the AST tree\n",
                    "dtt - Display the TST tree\n",

                    "dd  - Display difference between generated output and expected output\n",

                    "v   - Display verbose template information\n",
                    "q   - Quit\n",

                    "ir  - Input Rename. Rename the input file so that it won't be available in the next run.\n",

                    "ge  - Generate and edit the expected output file.\n";
                    "ee  - Edit the expected output file.\n";
                    "es  - Edit the source file.\n";
                continue;
            }
            else
            {
                continue;
            }

            return; // No more testing to be done now since the file is generated
        }
    }

    public function testRunRegression( $directory )
    {
        $dir = dirname( $directory );
        $base = basename( $directory );

        $send = substr( $directory, 0, -3 ) . ".send";
        if ( file_exists( $send ) )
        {
            $variables = include ($send);
            $this->newTpl->send = new ezcTemplateVariableCollection($variables);
        }

        $exceptionText = "";
        try
        {
            $out = $this->newTpl->process( $directory );
        }
        catch ( Exception $e )
        {
            $out = $e->getMessage();
            $exceptionText = get_class( $e ) . "(" . $e->getCode() . "):\n" . $out;

            // Begin of the error message contains the full path. We replace this with 'mock' so that the
            // tests work on other systems as well.
            # if ( strncmp( $out, $directory, strlen( $directory ) ) == 0 )
            # {
            #     $out = "mock" . substr( $out, strlen( $directory ) );
            # }

            $exceptionText .= "\n" . $e->getTraceAsString();
        }

        $expected = substr( $directory, 0, -3 ) . ".out";
        if ( !file_exists( $expected ) )
        {
            $oldTemplateFile = substr( $directory, 0, -3 ) . ".tpl";
            if ( !file_exists( $oldTemplateFile ) )
            {
                $help = "The out file: '$expected' and old template file: '$oldTemplateFile' could not be found.";

                if ( !self::$skipMissingTests && $this->interactiveMode )
                {
                    echo "\n", $help, "\n";

                    self::interact( $this->newTpl, $directory, false, $out, $expected, $help, $exceptionText );
                    return;
                }
                else
                {
                    throw new PHPUnit_Framework_ExpectationFailedException( $help );
                }
            }

            // There is a template available with the old template syntax, run that one and capture the output.
            
            if( isset($variables) )
            {
                foreach( $variables as $name => $value )
                {
                    $this->oldTpl->setVariable($name, $value);
                }
            }

            $expectedText = $this->oldTpl->fetch($oldTemplateFile);
            # Create the out file.
            # XXX skip the out file creation.
            #file_put_contents($expected, $expectedText);
        }
        else
        {
            $expectedText = file_get_contents( $expected );
        }

        try
        {

            $this->assertEquals( $expectedText, $out, "In:  <$expected>\nOut: <$directory>" );
        }
        catch ( PHPUnit_Framework_ExpectationFailedException $e )
        {
            $help  = "The evaluated template <".$this->regressionDir . "/current.tmp> differs ";
            $help .= "from the expected output: <$expected>.";
            if ( $this->interactiveMode )
            {
                // Touch the file. It will be run first, next time.
                if ( isset( $_ENV['EZC_TEST_TEMPLATE_SORT'] ) && $_ENV['EZC_TEST_TEMPLATE_SORT'] == 'mtime' )
                {
                    touch( $directory );
                }

                $exceptionText = get_class( $e ) . "(" . $e->getCode() . "):\n" . $e->getMessage();
                $exceptionText .= "\n" . $e->getTraceAsString();

                echo "\n", $help, "\n";
                self::interact( $this->newTpl, $directory, $expectedText, $out, $expected, $help, $exceptionText );
                return;
            }

            // Rethrow with new and more detailed message
            throw new PHPUnit_Framework_ExpectationFailedException( $help, $e->getComparisonFailure() );
        }

        // check the receive variables.
        #$receive = substr( $directory, 0, -3 ) . ".receive";
        #if ( file_exists( $receive ) )
        #{
        #    $expectedVar = include( $receive );
        #    $foundVar = $template->receive;
        #    $this->assertEquals( $expectedVar, $foundVar, "Received variables does not match" );
        #}
 

        /*
        $template = new ezcTemplate();
        $dir = dirname( $directory );
        $base = basename( $directory );

        $template->configuration = new ezcTemplateConfiguration( $dir, $this->getTempDir() );
        $template->configuration->targetCharset = "Latin1"; 

        $template->configuration->addExtension( "TestBlocks" );
        $template->configuration->addExtension( "LinksCustomBlock" );
        $template->configuration->addExtension( "cblockTemplateExtension" );
        $template->configuration->addExtension( "Sha1CustomBlock" );

        if ( preg_match("#^(\w+)@(\w+)\..*$#", $base, $match ) )
        {
            $contextClass = "ezcTemplate". ucfirst( strtolower( $match[2] ) ) . "Context";
            $template->configuration->context = new $contextClass();
        }
        else
        {
            $template->configuration->context = new ezcTemplateNoContext();
        }

        $send = substr( $directory, 0, -3 ) . ".send";
        if ( file_exists( $send ) )
        {
            $template->send = include ($send);
        }

        $out = "";
        $exceptionText = "";

        try
        {
            $out = $template->process( $base );
        }
        catch ( Exception $e )
        {
            $out = $e->getMessage();
            $exceptionText = get_class( $e ) . "(" . $e->getCode() . "):\n" . $out;

            // Begin of the error message contains the full path. We replace this with 'mock' so that the
            // tests work on other systems as well.
            if ( strncmp( $out, $directory, strlen( $directory ) ) == 0 )
            {
                $out = "mock" . substr( $out, strlen( $directory ) );
            }

            $exceptionText .= "\n" . $e->getTraceAsString();
        }

        $expected = substr( $directory, 0, -3 ) . ".out";

        if ( !file_exists( $expected ) )
        {
            $help = "The out file: '$expected' could not be found.";

            if ( !self::$skipMissingTests && $this->interactiveMode )
            {
                echo "\n", $help, "\n";

                self::interact( $template, $directory, false, $out, $expected, $help, $exceptionText );
                return;
            }
            else
            {
                throw new PHPUnit_Framework_ExpectationFailedException( $help );
            }
        }

        $expectedText = file_get_contents( $expected );

        try
        {

            $this->assertEquals( $expectedText, $out, "In:  <$expected>\nOut: <$directory>" );
        }
        catch ( PHPUnit_Framework_ExpectationFailedException $e )
        {
            $help  = "The evaluated template <".$this->regressionDir . "/current.tmp> differs ";
            $help .= "from the expected output: <$expected>.";
            if ( $this->interactiveMode )
            {
                // Touch the file. It will be run first, next time.
                if ( isset( $_ENV['EZC_TEST_TEMPLATE_SORT'] ) && $_ENV['EZC_TEST_TEMPLATE_SORT'] == 'mtime' )
                {
                    touch( $directory );
                }

                $exceptionText = get_class( $e ) . "(" . $e->getCode() . "):\n" . $e->getMessage();
                $exceptionText .= "\n" . $e->getTraceAsString();

                echo "\n", $help, "\n";
                self::interact( $template, $directory, file_get_contents( $expected ), $out, $expected, $help, $exceptionText );
                return;
            }

            // Rethrow with new and more detailed message
            throw new PHPUnit_Framework_ExpectationFailedException( $help, $e->getComparisonFailure() );
        }

        // check the receive variables.
        $receive = substr( $directory, 0, -3 ) . ".receive";
        if ( file_exists( $receive ) )
        {
            $expectedVar = include( $receive );
            $foundVar = $template->receive;
            $this->assertEquals( $expectedVar, $foundVar, "Received variables does not match" );
        }
        */ 
    }
}



?>
