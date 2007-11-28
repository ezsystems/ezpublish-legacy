<?php
/**
 * File containing the eZAutoloadGenerator class.
 *
 * @copyright Copyright (C) 2005-2007 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
* Utility class for generating autoload arrays for eZ Publish. The class can
* handle classes from the kernel and extensions.
*
* @package kernel
* @version //autogentag//
*/
class eZAutoloadGenerator
{
    /**
     * Contains the base path from which to root the search, and from which
     * to create relative paths
     *
     * @var string
     */
    private $basePath;

    /**
     * Flag for searching kernel files.
     *
     * @var bool
     */
    private $searchKernelFiles;

    /**
     * Flag for searching in extension files.
     *
     * @var bool
     */
    private $searchExtensionFiles;

    /**
     * Flag for verbose output
     *
     * @var bool
     */
    private $verboseOutput;

    /**
     * Flag for writing autoload arrays
     *
     * @var bool
     */
    private $writeFiles;

    /**
     * Holds the directory into which autoload arrays are written.
     *
     * @var string
     */
    private $outputDir;

    /**
     * Holds the directories to exclude from search
     *
     * @var array
     */
    private $excludeDirs;

    /**
     * Bitmask for searching in no files.
     */
    const GENAUTOLOADS_NONE = 0;

    /**
     * Bitmask for searhing in kernel files
     */
    const GENAUTOLOADS_KERNEL = 1;

    /**
     * Bitmask for search in extension files
     */
    const GENAUTOLOADS_EXTENSION = 2;

    /**
     * Bitmask for searching in both kernel and extension files
     */
    const GENAUTOLOADS_BOTH = 3;


    /**
     * Constructs class to generate autoload arrays.
     * File search is rooted in $basePath, and the parameters
     * $searchKernelFiles and $searchExtensionFiles control which part of the
     * installation is searched for classes.
     *
     * $verboseOutput controls whether autoload arrays will be printed on
     * STDOUT.
     *
     * $writeFiles controls whether the the resulting autoload arrays are
     * written to disc.
     *
     * $outputDir is the directory into which the autoload arrays should be
     * written, defaults to 'autoload'
     *
     * $excludeDirs are the arrays which should not be included in the search
     * for PHP classes.
     *
     * @param string $basePath
     * @param bool $searchKernelFiles
     * @param bool $searchExtensionFiles
     * @param bool $verboseOutput
     * @param bool $writeFiles
     * @param string $outputDir
     * @param array $excludeDirs
     */
    function __construct( $basePath, $searchKernelFiles, $searchExtensionFiles, $verboseOutput = false, $writeFiles = true, $outputDir = false, $excludeDirs = false )
    {
        $this->basePath = $basePath;
        $this->searchKernelFiles = $searchKernelFiles;
        $this->searchExtensionFiles = $searchExtensionFiles;
        $this->verboseOutput = $verboseOutput;
        $this->writeFiles = $writeFiles;

        if ( $outputDir === false )
        {
            $this->outputDir = 'autoload';
        }
        else
        {
            $this->outputDir = $outputDir;
        }

        $this->excludeDirs = $excludeDirs;

    }

    /**
     * Searches specified directories for classes, and build autoload arrays.
     *
     * @throws Exception if desired output directory is not a directory, or if the autoload arrays are not writeable by the script.
     * @return void
     */
    public function buildAutoloadArrays()
    {
        $runMode = $this->runMode( $this->searchKernelFiles, $this->searchExtensionFiles );
        $phpFiles = $this->fetchFiles( $this->basePath, $runMode, $this->excludeDirs );

        $phpClasses = array();
        foreach ($phpFiles as $mode => $fileList) {
            $phpClasses[$mode] = $this->getClassFileList( $fileList );
        }

        $maxClassNameLength = $this->checkMaxClassLength( $phpClasses );
        $autoloadArrays = $this->dumpArray( $phpClasses, $maxClassNameLength );

        //Write autoload array data into separate files
        foreach( $autoloadArrays as $location => $data )
        {
            if ( $this->verboseOutput )
            {
                var_dump( $this->dumpArrayStart( $location) . $data . $this->dumpArrayEnd() );
            }

            if ( $this->writeFiles )
            {
                // Check the targetDir
                if( file_exists( $this->outputDir ) && !is_dir( $this->outputDir ) )
                {
                    throw new Exception( "Specified target: {$this->outputDir} is not a directory." );
                }
                elseif ( !file_exists( $this->outputDir ) )
                {
                    mkdir( $this->outputDir );
                }

                $filename = $this->nameTable( $location );
                $filePath = "{$this->outputDir}/$filename";
                if ( is_writable( $filePath ) )
                {
                    $file = fopen( $filePath, "w" );
                    fwrite( $file, $this->dumpArrayStart( $location ) );
                    fwrite( $file, $data );
                    fwrite( $file, $this->dumpArrayEnd() );
                    fclose( $file );
                }
                else
                {
                    throw new Exception( __CLASS__ . ' - ' . __FUNCTION__ . ": The file {$filePath} is not writable by the system." );
                }
            }
        }
    }

    /**
     * Returns an array indexed by location for classes and their filenames.
     *
     * @param string $path The base path to start the search from.
     * @param string $mask A binary mask which instructs the function whether to fetch kernel-related or extension-related files.
     * @return array
     */
    private function fetchFiles( $path, $mask, $excludeDirs = false )
    {
        // make sure ezcFile::findRecursive and the exclusion filters we pass to it
        // work correctly on systems with another file seperator than the forward slash
        $sanitisedBasePath = DIRECTORY_SEPARATOR == '/' ? $path : strtr( $path, DIRECTORY_SEPARATOR, '/' );

        $extraExcludeDirs = array();
        if ( $excludeDirs !== false and is_array( $excludeDirs ) )
        {
            foreach ( $excludeDirs as $dir )
            {
                $extraExcludeDirs[] = "@^{$sanitisedBasePath}/{$dir}/@";
            }
        }

        $retFiles = array();

        switch( $this->checkMode( $mask) )
        {
            case self::GENAUTOLOADS_EXTENSION:
            {
                $retFiles = array( "extension" => $this->buildFileList( "$sanitisedBasePath/extension", $extraExcludeDirs ) );
                break;
            }

            case self::GENAUTOLOADS_KERNEL:
            {
                $extraExcludeDirs[] = "@^{$sanitisedBasePath}/extension/@";
                $retFiles = array( "kernel" => $this->buildFileList( $sanitisedBasePath, $extraExcludeDirs ) );
                break;
            }

            case self::GENAUTOLOADS_BOTH:
            {
                $extraExcludeKernelDirs = $extraExcludeDirs;
                $extraExcludeKernelDirs[] = "@^{$sanitisedBasePath}/extension/@";
                $retFiles = array( "extension"  => $this->buildFileList( "$sanitisedBasePath/extension", $extraExcludeDirs ),
                                   "kernel"     => $this->buildFileList( $sanitisedBasePath, $extraExcludeKernelDirs ) );
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
    private function buildFileList( $path, $extraFilter = null )
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
     * @return array (className=>filename)
     */
    private function getClassFileList( $fileList )
    {
        $retArray = array();
        foreach( $fileList as $file )
        {
            $tokens = @token_get_all( file_get_contents( $file ) );
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
    private function checkMaxClassLength( $depData )
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
    private function dumpArray( $sortedArray, $length )
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
            $retArray[$location] = $ret;
        }
        return $retArray;
    }

    /**
     * Checks which runmode the script should operate in: kernel-mode, extension-mode or both.
     *
     * @param int $mask Bitmask to check for run mode.
     * @return int
     */
    private function checkMode( $mask )
    {
        $modes = array( self::GENAUTOLOADS_KERNEL, self::GENAUTOLOADS_EXTENSION, self::GENAUTOLOADS_BOTH );
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
    private function runMode( $useKernelFiles, $useExtensionFiles )
    {
        $mode = self::GENAUTOLOADS_NONE;
        //If no file selectors are chosen we will default to extension files.
        if ( !$useKernelFiles and !$useExtensionFiles )
        {
            $mode |= self::GENAUTOLOADS_EXTENSION;
        }

        if ( $useKernelFiles )
        {
            $mode |= self::GENAUTOLOADS_KERNEL;
        }

        if ( $useExtensionFiles )
        {
            $mode |= self::GENAUTOLOADS_EXTENSION;
        }
        return $mode;
    }

    /**
     * Table to look up file names to use for different run modes.
     *
     * @param string $lookup Mode to look up, can be extension, or kernel.
     * @return string
     */
    private function nameTable( $lookup )
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
    private function dumpArrayStart( $part )
    {
        return <<<ENDL
<?php
/**
 * Autoloader definition for eZ Publish $part files.
 *
 * @copyright Copyright (C) 2005-2007 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 *
 */

return array(

ENDL;
    }

    /**
     * Prints generated code for end of the autoload files
     *
     * @return void
     */
    private function dumpArrayEnd()
    {
        return <<<END
    );

?>
END;
    }
}
?>
