#!/usr/bin/env php
<?php
require("autoload.php");

include( "neo_lib/console_input.php");

$usage = <<<EOT
%prog [file1] [file2] [...]

Compile the template files.
The template directory (basedir) is added by default.
EOT;

$ci = new neoConsoleInput($usage);
$ci->addOption("e", "executable", array( "action" => "store", "default" => "php", "dest" => "executable", "help" => "php executable calling this script"));
list($opt, $args) = $ci->process();

// Setup the template engine.
include_once("kernel/common/template.php");
neoTemplateInit("var/runtime_script.txt");

$tc = ezcTemplateConfiguration::getInstance();
$tc->executeTemplate = false;
$tc->locator = null;
$basedir = $tc->templatePath;

function compileFile($file, $basedir)
{
    $relFile = ezcBaseFile::calculateRelativePath($file, $basedir);
    print "Compiling: $relFile\n";

    $t = new ezcTemplate();
    $t->process( $relFile );
}

function callScript($files, $basedir, $executable)
{
    // Due to PHP bad memory management, we need to spawn a new process.
    exec("$executable bin/neo/eztc.php $files", $out, $retVal);
    print implode("\n", $out) . "\n";

    if ( $retVal != 0 )
    {
        exit($retVal);
    }

}

// If files given, go over them.
if( count( $args ) )
{
    foreach ($args as $file )
    {
        compileFile( $basedir ."/". $file, $basedir );
    }
    exit(0);
}
else
{
    // Check the executable.
    exec("{$opt->executable}  --version", $out, $code);
    if( substr($out[0], 0, 3) != "PHP")
    {
        throw new Exception("Executable is not a PHP script. Did you use -e?");
    }

    //Go over all files.
    $fileList = ezcBaseFile::findRecursive($basedir, array("#.*\.tpl#"));

    $sum = 0;
    $total = 0;
    $bucket = array();
    foreach ( $fileList as $file )
    {
        $bucket[] = ezcBaseFile::calculateRelativePath($file, $basedir);
        if( $sum == 29)
        {
            callScript( implode(" ", $bucket), $basedir, $opt->executable);
            $total += $sum;
            $sum = 0;
            $bucket = array();
        }
        else
        {
            $sum++;
        }
    }
    
    if( sizeof($bucket) > 0 )
    {
        $total += sizeof($bucket);
        callScript( implode(" ", $bucket), $basedir, $opt->executable);
    }

    print ("Compiled $total templates.\n");

    exit(0);
}

