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


//Setting this because of some strict warnings in the Structures_Graph package
error_reporting( E_ALL );

require_once 'Structures/Graph.php';
require_once 'Structures/Graph/Node.php';
require_once 'Structures/Graph/Manipulator/TopologicalSorter.php';

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
$targetOption->default = "autoloads";
$targetOption->shorthelp = "The directory to where the generated autoload file should be written.";
$params->registerOption( $targetOption );

$verboseOption = new ezcConsoleOption( 'v', 'verbose', ezcConsoleInput::TYPE_NONE );
$verboseOption->mandatory = false;
$verboseOption->shorthelp = "Whether or not to display more information.";
$params->registerOption( $verboseOption );

$depOption = new ezcConsoleOption( 'd', 'dep', ezcConsoleInput::TYPE_NONE );
$depOption->mandatory = false;
$depOption->shorthelp = "Whether or not to display the dependancy data.";
$params->registerOption( $depOption );

$dryrunOption = new ezcConsoleOption( 'n', 'dry-run', ezcConsoleInput::TYPE_NONE );
$dryrunOption->mandatory = false;
$dryrunOption->shorthelp = "Whether a new file autoload file should be written.";
$params->registerOption( $dryrunOption );

$reverseOption = new ezcConsoleOption( 'r', 'reverse', ezcConsoleInput::TYPE_NONE );
$reverseOption->mandatory = false;
$reverseOption->shorthelp = "Whether files without class definition should be reported.";
$params->registerOption( $reverseOption );

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
$dep = $depOption->value;
$dryRun = $dryrunOption->value;
$reverse = $reverseOption->value;
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

//Gather all class information from the php files
$depData = generateDependencyData( $phpFiles, $reverse );
if ( $dep )
{
    var_dump( $depData );
}

//Only to show the matching files
if ( $reverse )
{
    exit(0);
}

$maxClassNameLength = checkMaxClassLength( $depData );
$sorted = sortDependencyData( $depData );
$autoloadArrays = dumpSortedArray( $sorted, $maxClassNameLength, 2 );

//Write autoload array data into separate files
if ( !$dryRun )
{
    foreach( $autoloadArrays as $location => $data )
    {
        if ( $verbose )
        {
            var_dump( dumpArrayStart( $location) . $data . dumpArrayEnd() );
        }

        $filename = nameTable( $location );
        $file = fopen( "{$targetDir}/$filename", "w" );
        fwrite( $file, dumpArrayStart( $location ) );
        fwrite( $file, $data );
        fwrite( $file, dumpArrayEnd() );
        fclose( $file );
    }
}

function fetchFiles( $path, $mask )
{
    $retFiles = array();
    
    switch( checkMode( $mask) )
    {
        case EZP_GENAUTOLOADS_EXTENSION:
        {
            $retFiles = array( "extension" => buildFileList( "$path/extension" ) );
            break;
        }
        
        case EZP_GENAUTOLOADS_KERNEL:
        {
            $retFiles = array( "kernel" => buildFileList( $path, array( "@^{$path}/extension/@" ) ) );
            break;
        }
        
        case EZP_GENAUTOLOADS_BOTH:
        {
            $retFiles = array( "extension"  => buildFileList( "$path/extension" ),
                               "kernel"     => buildFileList( $path, array( "@^{$path}/extension/@" ) ) );
            break;
        }
    }
    
    //Make all the paths relative to $path
    foreach ( $retFiles as &$fileBundle )
    {
        foreach ( $fileBundle as $key => &$file )
        {
            $fileBundle[$key] = ezcFile::calculateRelativePath( $file, $path );
        }
    }
    unset( $file, $fileBundle );
    return $retFiles;
}

function buildFileList( $path, $extraFilter = null )
{
    $exclusionFilter = array( "@^{$path}/(var|settings|benchmarks|autoloads|port_info|templates|tmp)/@" );
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
 * Method not used at this point.
 */
function figureOutPrefix( $className )
{
    if ( preg_match( "/^([a-z]*)([A-Z][a-z0-9]*)([A-Z][a-z0-9]*)?/", $className, $matches ) !== false )
    {
        return strtolower( $matches[2] );
    }
    return '';
}

function generateDependencyData( $files, $reverse )
{
    $fullDepArray = array();
    foreach ( $files as $location => $fileArray )
    {
        $depArray = array();
        foreach( $fileArray as $file )
        {
            $name = '';
            $depData = getClassDependencies( $file, $name, $extends, $implements, $functions, $type, $relation );
            if ( $name and !$reverse )
            {
                $depArray[$name] = array( 'file' => $file, 'class' =>
                        $name, 'deps' => $depData, 'functions' => $functions,
                        'type' => $type, 'extends' => $extends, 'implements' => $implements );
            }
            else if ( $reverse and !$name )
            {
                $depArray[$file] = array( 'file' => $file,
                                          'functions' => $functions );
            }
        }
        $fullDepArray[$location] = $depArray;
    }
    return $fullDepArray;
}

function getClassDependencies( $file, &$name, &$extends, &$implements, &$functions, &$type, &$relation )
{
    $extends = $implements = array();
    $info = $functions = array();
    $visibility = "public";

    $tokens = token_get_all( file_get_contents( $file ) );
    $lastKeyword = null;
    foreach( $tokens as $token )
    {
        if ( $lastKeyword === null && is_array( $token ) )
        {
            switch( $token[0] )
            {
                case T_CLASS:
                case T_INTERFACE:
                    $type = $token[1];
                    $lastKeyword = $token[1];
                    break;
                case T_EXTENDS:
                case T_IMPLEMENTS:
                    $lastKeyword = $token[1];
                    $relation = $token[1];
                    break;
                case T_FUNCTION:
                    $lastKeyword = $token[1];
                    break;
                case T_PROTECTED:
                    $visibility = "protected";
                    break;
                case T_PRIVATE:
                    $visibility = "private";
                    break;
            }

        }
        else if ( is_array( $token ) && $token[0] == T_WHITESPACE )
        {
            continue;
        }
        else if ( !is_array( $token ) && $token == ',' )
        {
            continue;
        }
        else if ( is_array( $token ) && $token[0] == T_STRING )
        {
            if ( $lastKeyword === 'extends' )
            {
                $extends[] = $token[1];
                $info[] = $token[1];
                $lastKeyword = null;
            }
            else if ( $lastKeyword === 'implements' )
            {
                $implements[] = $token[1];
                $info[] = $token[1];
            }
            else if ( $lastKeyword === 'function' )
            {
                switch( $visibility )
                {
                    case 'public':
                        $char = "+";
                        break;
                    case 'protected':
                        $char = "#";
                        break;
                    case 'private':
                        $char = "-";
                        break;
                }
                $functions[] = $char . $token[1];
                $visibility = "public";
            }
            else
            {
                $name = $token[1];

                $lastKeyword = null;
            }
        }
        else
        {
            $lastKeyword = null;
        }
    }
    return $info;
}

function checkMaxClassLength( $depData )
{
    $max = array();
    foreach( array_keys( $depData) as $key )
    {
        $max[$key] = 0;
    }
    
    foreach( $depData as $location => $locationBundle )
    {
        foreach ( $locationBundle as $data )
        {
            if ( strlen( $data['class'] ) > $max[$location] )
            {
                $max[$location] = strlen( $data['class'] );
            }
        }
    }
    return $max;
}

function sortDependencyData( $depDataArray )
{
    $return = array();
    
    foreach ($depDataArray as $location => $locData )
    {
        $nodes = array();
        $graph = new Structures_Graph();
    
        foreach ( $locData as $className => $depData )
        {
            /* Create all nodes and add them to the graph */
            $nodes[$className] = new Structures_Graph_Node();
            $nodes[$className]->setData( $depData );
            $graph->addNode( $nodes[$className] );

            /* Add arcs */
            if ( array_key_exists( 'deps', $depData ) )
            {
                foreach( $depData['deps'] as $dependency )
                {
                    if ( array_key_exists( $dependency, $nodes ) )
                    {
                        $nodes[$className]->connectTo( $nodes[$dependency] );
                    }
                }
            }
        }
        /* Sort */
        $m = new Structures_Graph_Manipulator_TopologicalSorter();
        $sorted = $m->sort( $graph );
        $return[$location] = $sorted;
    }
    return $return;
}

function dumpSortedArray( $sortedArray, $length, $extraOffset )
{
    $retArray = array();
    foreach ( $sortedArray as $location => $sorted )
    {
        $ret = '';
        $offset = $length[$location] + $extraOffset;
        for ( $i = count( $sorted ) - 1; $i >= 0; $i-- )
        {
            usort( $sorted[$i], 'sortByClassName' );
            foreach( $sorted[$i] as $node )
            {
                $data = $node->getData();

                if ( !class_exists( $data['class'], false ) && !interface_exists( $data['class'], false ) )
                {
    //                require $data['file'];
                }

                $file = preg_replace( '@.*trunk/@', '', $data['file'] );
                $fileParts = explode( '/', $file );
                // unset($fileParts[1]);
                $file = implode( '/', $fileParts );

                $ret .= sprintf( "      %-{$offset}s => '%s',\n", "'{$data['class']}'", $file );
            }
        }
        $retArray[$location] = $ret;
    }
    return $retArray;
}

function dumpSortedPreloadArray( $sortedArray, $length )
{
    $retArray = array();
    foreach ( $sortedArray as $location => $sorted )
    {
        $ret = '';
        for ( $i = count( $sorted ) - 1; $i >= 0; $i-- )
        {
            usort( $sorted[$i], 'sortByClassName' );
            foreach( $sorted[$i] as $node )
            {
                $data = $node->getData();

                if ( !class_exists( $data['class'], false ) && !interface_exists( $data['class'], false ) )
                {
    //                require $data['file'];
                }

                $fileParts = explode( '/', $data['file'] );
                unset($fileParts[0]);
                $file = implode( '/', $fileParts );

                $ret .= "require '{$file}';\n";
            }
        }
        $retArray[$location] = $ret;
    }
    return $retArray;
}

function sortByClassName( $a, $b )
{
    $aa = $a->getData();
    $bb = $b->getData();
    return strcmp( $aa['class'], $bb['class'] );
}

function dumpLicense()
{
    return <<<ENDL
<?php
/**
 * Autoloader definition for eZ Publish
 *
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL
 * @version //autogentag//
 * @filesource
 *
 */

ENDL;
}

function dumpHeader()
{
    return dumpLicense() . <<<END

require 'Base/src/base.php';

function __autoload( \$className )
{

    static \$ezpClasses = null;
    if ( is_null( \$ezpClasses ) )
    {
        \$ezpClasses = array(

END;
}

function dumpFooter()
{
    return <<<END
        );
    }

    if ( array_key_exists( \$className, \$ezpClasses ) )
    {
        require( \$ezpClasses[\$className] );
    }
    else
    {
        ezcBase::autoload( \$className );
    }
}
?>

END;
}

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

function dumpArrayEnd()
{
    return <<<END
    );

?>
END;
}

?>
