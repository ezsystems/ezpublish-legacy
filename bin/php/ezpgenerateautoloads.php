#!/usr/bin/env php

<?php
if ( file_exists( "config.php" ) )
{
    require "config.php";
}

//Setup, includes and constants
//{
if ( !@include( 'ezc/Base/base.php' ) )
{
    require "Base/src/base.php";
}

define( 'EZP_GENAUTOLOADS_NONE', 0 );
define( 'EZP_GENAUTOLOADS_KERNEL', 1 << 0 );
define( 'EZP_GENAUTOLOADS_EXTENSION', 1 << 1 );
define( 'EZP_GENAUTOLOADS_BOTH', EZP_GENAUTOLOADS_NONE | EZP_GENAUTOLOADS_KERNEL | EZP_GENAUTOLOADS_EXTENSION );

function __autoload( $className )
{
    ezcBase::autoload( $className );
}
//}

// Setup console parameters
//{
$params = new ezcConsoleInput();

$helpOption = new ezcConsoleOption( 'h', 'help' );
$helpOption->mandatory = false;
$helpOption->shorthelp = "Show help information";
$params->registerOption( $helpOption );

$targetOption = new ezcConsoleOption( 't', 'target', ezcConsoleInput::TYPE_STRING );
$targetOption->mandatory = false;
$targetOption->default = "autoload";
$targetOption->shorthelp = "The directory to where the generated autoload file should be written.";
$params->registerOption( $targetOption );

$verboseOption = new ezcConsoleOption( 'v', 'verbose', ezcConsoleInput::TYPE_NONE );
$verboseOption->mandatory = false;
$verboseOption->shorthelp = "Whether or not to display more information.";
$params->registerOption( $verboseOption );

$dryrunOption = new ezcConsoleOption( 'n', 'dry-run', ezcConsoleInput::TYPE_NONE );
$dryrunOption->mandatory = false;
$dryrunOption->shorthelp = "Whether a new file autoload file should be written.";
$params->registerOption( $dryrunOption );

$kernelFilesOption = new ezcConsoleOption( 'k', 'kernel', ezcConsoleInput::TYPE_NONE );
$kernelFilesOption->mandatory = false;
$kernelFilesOption->shorthelp = "If an autoload array for the kernel files should be generated.";
$params->registerOption( $kernelFilesOption );

$extensionFilesOption = new ezcConsoleOption( 'e', 'extension', ezcConsoleInput::TYPE_NONE );
$extensionFilesOption->mandatory = false;
$extensionFilesOption->shorthelp = "If an autoload array for the extensions should be generated.";
$params->registerOption( $extensionFilesOption );
// Process console parameters
try
{
    $params->process();
}
catch ( ezcConsoleOptionException $e )
{
    print( $e->getMessage(). "\n" );
    print( "\n" );

    echo $params->getHelpText( 'Autoload file generator.' ) . "\n";

    echo "\n";
    exit();
}

if ( $helpOption->value === true )
{
    echo $params->getHelpText( 'Autoload file generator.' ) . "\n";
    exit();
}

$targetDir = $targetOption->value;
$verbose = $verboseOption->value;
$dryRun = $dryrunOption->value;
//}

// Check the targetDir
if( file_exists( $targetDir ) && !is_dir( $targetDir ) )
{
    throw new Exception("Specified target: $targetDir is not a directory.");
}
elseif ( !file_exists( $targetDir ) )
{
    mkdir( $targetDir );
}


$basePath = getcwd();
$runMode = runMode( $kernelFilesOption->value, $extensionFilesOption->value );
$phpFiles = fetchFiles( $basePath, $runMode );

$phpClasses = array();
foreach ($phpFiles as $mode => $fileList) {
    $phpClasses[$mode] = getClassFileList( $fileList );
}

$maxClassNameLength = checkMaxClassLength( $phpClasses );
$autoloadArrays = dumpArray( $phpClasses, $maxClassNameLength );

//Write autoload array data into separate files
foreach( $autoloadArrays as $location => $data )
{
    if ( $verbose )
    {
        var_dump( dumpArrayStart( $location) . $data . dumpArrayEnd() );
    }

    if ( !$dryRun )
    {
        $filename = nameTable( $location );
        $file = fopen( "{$targetDir}/$filename", "w" );
        fwrite( $file, dumpArrayStart( $location ) );
        fwrite( $file, $data );
        fwrite( $file, dumpArrayEnd() );
        fclose( $file );
    }
}

/**
 * Returns an array indexed by location for classes and their filenames.
 *
 * @param string $path The base path to start the search from.
 * @param string $mask A binary mask which instructs the function whether to fetch kernel-related or extension-related files.
 * @return array
 */
function fetchFiles( $path, $mask )
{
    // make sure ezcFile::findRecursive and the exclusion filters we pass to it
    // work correctly on systems with another file seperator than the forward slash
    $sanitisedBasePath = DIRECTORY_SEPARATOR == '/' ? $path : strtr( $path, DIRECTORY_SEPARATOR, '/' );

    $retFiles = array();

    switch( checkMode( $mask) )
    {
        case EZP_GENAUTOLOADS_EXTENSION:
        {
            $retFiles = array( "extension" => buildFileList( "$sanitisedBasePath/extension" ) );
            break;
        }

        case EZP_GENAUTOLOADS_KERNEL:
        {
            $retFiles = array( "kernel" => buildFileList( $sanitisedBasePath, array( "@^{$sanitisedBasePath}/extension/@" ) ) );
            break;
        }

        case EZP_GENAUTOLOADS_BOTH:
        {
            $retFiles = array( "extension"  => buildFileList( "$sanitisedBasePath/extension" ),
                               "kernel"     => buildFileList( $sanitisedBasePath, array( "@^{$sanitisedBasePath}/extension/@" ) ) );
            break;
        }
    }

    //Make all the paths relative to $path
    foreach ( $retFiles as &$fileBundle )
    {
        foreach ( $fileBundle as $key => &$file )
        {
            // ezcFile::calculateRelativePath only works correctly with paths where DIRECTORY_SEPARATOR is used
            // so we need to correct the results of ezcFile::findRecursive again
            if ( DIRECTORY_SEPARATOR != '/' )
            {
                $file = strtr( $file, '/', DIRECTORY_SEPARATOR );
            }
            $fileBundle[$key] = ezcFile::calculateRelativePath( $file, $path );
        }
    }
    unset( $file, $fileBundle );
    return $retFiles;
}


/**
 * Builds a filelist of all PHP files in $path.
 *
 * @param string $path 
 * @param array $extraFilter 
 * @return array
 */
function buildFileList( $path, $extraFilter = null )
{
    $exclusionFilter = array( "@^{$path}/(var|settings|benchmarks|autoload|port_info|templates|tmp|UnitTest|tests)/@" );
    if ( !empty( $extraFilter ) and is_array( $extraFilter ) )
    {
        foreach( $extraFilter as $filter )
        {
            $exclusionFilter[] = $filter;
        }
    }

    if (!empty( $path ) )
    {
        return ezcFile::findRecursive( $path, array( '@\.php$@' ), $exclusionFilter );
    }
    return false;
}

/**
 * Extracts class information from PHP sourcecode.
 * @return array <className> => <filename> array
 */
function getClassFileList( $fileList )
{
    $retArray = array();
    foreach( $fileList as $file )
    {
        $tokens = token_get_all( file_get_contents( $file ) );
        foreach( $tokens as $key => $token )
        {
            if ( is_array( $token ) )
            {
                switch( $token[0] )
                {
                    case T_CLASS:
                    case T_INTERFACE:
                    {
                        $retArray[$tokens[$key+2][1]] = $file;
                    } break;
                }
            }
        }
    }
    ksort( $retArray );
    return $retArray;
}

/**
 * Calculates the length of the longest class name present in $depdata
 *
 * @param array $depData 
 * @return mixed
 */
function checkMaxClassLength( $depData )
{
    $max = array();
    foreach( array_keys( $depData) as $key )
    {
        $max[$key] = 0;
    }

    foreach( $depData as $location => $locationBundle )
    {
        foreach ( $locationBundle as $className => $path )
        {
            if ( strlen( $className ) > $max[$location] )
            {
                $max[$location] = strlen( $className );
            }
        }
    }
    return $max;
}

/**
 * Build string version of the autoload array with correct indenting.
 *
 * @param array $sortedArray 
 * @param int $length 
 * @return string
 */
function dumpArray( $sortedArray, $length )
{
    $retArray = array();
    foreach ( $sortedArray as $location => $sorted )
    {
        $ret = '';
        $offset = $length[$location] + 2;
        foreach( $sorted as $class => $path )
        {
            $ret .= sprintf( "      %-{$offset}s => '%s',\n", "'{$class}'", $path );
        }
    }
    $retArray[$location] = $ret;
    return $retArray;
}

/**
 * Checks which runmode the script should operate in: kernel-mode, extension-mode or both.
 *
 * @param int $mask Bitmask to check for run mode.
 * @return int
 */
function checkMode( $mask )
{
    $modes = array( EZP_GENAUTOLOADS_KERNEL, EZP_GENAUTOLOADS_EXTENSION, EZP_GENAUTOLOADS_BOTH );
    foreach( $modes as $mode )
    {
        if ( ($mask & $mode)==$mask )
        {
            return $mode;
        }
    }
    return false;
}

/**
 * Generates the active bitmask for this instance of the autoload generation script
 * depending on the parameters it sets the corresponding flags.
 *
 * @param bool $useKernelFiles Whether kernel files should be checked
 * @param bool $useExtensionFiles Whether extension files should be checked
 * @return int
 */
function runMode( $useKernelFiles, $useExtensionFiles )
{
    $mode = EZP_GENAUTOLOADS_NONE;
    //If no file selectors are chosen we will default to extension files.
    if ( !$useKernelFiles and !$useExtensionFiles )
    {
        $mode |= EZP_GENAUTOLOADS_EXTENSION;
    }

    if ( $useKernelFiles )
    {
        $mode |= EZP_GENAUTOLOADS_KERNEL;
    }

    if ( $useExtensionFiles )
    {
        $mode |= EZP_GENAUTOLOADS_EXTENSION;
    }
    return $mode;
}

/**
 * Table to look up file names to use for different run modes.
 *
 * @param string $lookup Mode to look up, can be extension, or kernel.
 * @return string
 */
function nameTable( $lookup )
{
    $names = array( "extension" => "ezp_extension.php",
                    "kernel"    => "ezp_kernel.php" );

    if ( array_key_exists( $lookup, $names ) )
    {
        return $names[$lookup];
    }
    return false;
}

/**
 * Prints generated code used for the autoload files
 *
 * @param string $part 
 * @return string
 */
function dumpArrayStart( $part )
{
    return <<<ENDL
<?php
/**
 * Autoloader definition for eZ Publish $part files.
 *
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL
 * @version //autogentag//
 * @filesource
 *
 */

return array(

ENDL;
}

/**
 * Prints generated code for end of the autoload files
 *
 * @return void
 * @author Ole Marius Smestad
 */
function dumpArrayEnd()
{
    return <<<END
    );

?>
END;
}

?>