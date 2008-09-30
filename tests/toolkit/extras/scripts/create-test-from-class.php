#!/usr/bin/env php
<?php
/**
 * File containing the ezpGenerateTestCaseFromClass class
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

require_once 'autoload.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'tests/toolkit/ezptestrunner.php';


class ezpGenerateTestCaseFromClass
{
    public function __construct( $sourceFile, $destinationFile )
    {
        // Make sure file exists
        if ( !file_exists( $sourceFile ) )
            die( "File $sourceFile does not exists\n" );

        $this->sourceFile = $sourceFile;
        $this->destinationFile = $destinationFile;
    }

    public function generate()
    {
        $this->getClass();
        $this->getMethods();
        $this->generateTestClass();
    }

    protected function getClass()
    {
        require_once( $this->sourceFile );
        $this->class = ezpTestRunner::getClassName( $this->sourceFile );
        $this->testClass = $this->class . "Test";
    }

    protected function getMethods()
    {
        $this->methods = get_class_methods( $this->class );
    }

    protected function generateTestClass()
    {
        $generator = new ezcPhpGenerator( $this->destinationFile );
        $generator->niceIndentation = true;
        $generator->indentString = "    ";
        $generator->appendEmptyLines();

        $generator->appendCustomCode( 'class ' . $this->testClass . ' extends ezpTestCase' );
        $generator->appendCustomCode( '{' );
        $generator->indentLevel++;

        $this->insertConstructor( $generator );
        $this->insertSuite( $generator );
        $this->insertMethods( $generator );

        $generator->indentLevel--;
        $generator->appendCustomCode( '}' );
        $generator->appendEmptyLines();
        $generator->finish();
    }

    protected function insertConstructor( ezcPhpGenerator $generator )
    {
        $generator->appendCustomCode( 'public function __construct()' );
        $generator->appendCustomCode( '{' );
        $generator->indentLevel++;
        $generator->appendCustomCode( 'parent::__construct();' );
        $generator->appendCustomCode( '$this->setName( "' . $this->class . ' Unit Tests" );' );
        $generator->indentLevel--;
        $generator->appendCustomCode( '}' );
    }

    protected function insertSuite( ezcPhpGenerator $generator )
    {
        $generator->appendEmptyLines();
        $generator->appendCustomCode( 'public static function suite()' );
        $generator->appendCustomCode( '{' );
        $generator->indentLevel++;
        $generator->appendCustomCode( 'return new ezpTestSuite( __CLASS__ );' );
        $generator->indentLevel--;
        $generator->appendCustomCode( '}' );
    }

    protected function insertMethods( ezcPhpGenerator $generator )
    {
        foreach( $this->methods as $method )
        {
            $methodName = "test" . ucfirst( $method ) . "()";

            $generator->appendEmptyLines();
            $generator->appendCustomCode( "public function $methodName" );
            $generator->appendCustomCode( '{' );
            $generator->indentLevel++;
            $generator->appendCustomCode( 'self::markTestIncomplete( "Not implemented" );' );
            $generator->indentLevel--;
            $generator->appendCustomCode( '}' );
        }
    }
}


// Set input options
// ---------------------------------------------------------------------------
$input = new ezcConsoleInput();

$helpOption = $input->registerOption( new ezcConsoleOption( 'h', 'help' ) );
$helpOption->shorthelp = "Show help";
$helpOption->isHelpOption = true;

$sourceFileOption = $input->registerOption( new ezcConsoleOption( 's', 'source-file' ) );
$sourceFileOption->shorthelp = "File containing class to generate tests from";
$sourceFileOption->mandatory = true;
$sourceFileOption->type = ezcConsoleInput::TYPE_STRING;

$destinationFileOption = $input->registerOption( new ezcConsoleOption( 'd', 'dest-file' ) );
$destinationFileOption->shorthelp = "File to write the tests to";
$destinationFileOption->mandatory = true;
$destinationFileOption->type = ezcConsoleInput::TYPE_STRING;


// Set ouput formatting
// ---------------------------------------------------------------------------    
$out = new ezcConsoleOutput();
$out->formats->count->style = array( 'bold' );
$out->formats->source->color = 'green';
$out->formats->dest->color = 'yellow';


// Process options
// ---------------------------------------------------------------------------
try
{
    $input->process();
}
catch ( ezcConsoleOptionException $e )
{
    die( $e->getMessage() );
}


// Show help
// ---------------------------------------------------------------------------
if ( $helpOption->value === true )
{
    echo $input->getSynopsis() . "\n";
    foreach ( $input->getOptions() as $option )
    {
        echo "\t -{$option->short}, --{$option->long}\t {$option->shorthelp}\n";
    }
    die();
}


// Generate test file
// ---------------------------------------------------------------------------
$generator = new ezpGenerateTestCaseFromClass( $sourceFileOption->value, $destinationFileOption->value );
$generator->generate();

$testCount = count( $generator->methods );
$out->outputText( "Generated " );
$out->outputText( $testCount, 'count' );
$out->outputText( " tests from " );
$out->outputText( $sourceFileOption->value, 'source' );
$out->outputText( "\n" );

$out->outputText( "Tests written to " );
$out->outputText( $destinationFileOption->value, 'dest' );
$out->outputText( "\n" );


?>
