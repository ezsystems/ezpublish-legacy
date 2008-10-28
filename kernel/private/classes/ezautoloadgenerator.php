<?php
/**
 * File containing the eZAutoloadGenerator class.
 *
 * @copyright Copyright (C) 2005-2008 eZ Systems AS. All rights reserved.
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
    protected $basePath;

    /**
     * Flag for searching kernel files.
     *
     * @var bool
     */
    protected $searchKernelFiles;

    /**
     * Flag for searching in extension files.
     *
     * @var bool
     */
    protected $searchExtensionFiles;

    /**
     * Flag for verbose output
     *
     * @var bool
     */
    protected $verboseOutput;

    /**
     * Flag for writing autoload arrays
     *
     * @var bool
     */
    protected $writeFiles;

    /**
     * Holds the directory into which autoload arrays are written.
     *
     * @var string
     */
    protected $outputDir;

    /**
     * Holds the directories to exclude from search
     *
     * @var array
     */
    protected $excludeDirs;

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
     * Bitmask for searching in test files
     */
    const GENAUTOLOADS_TESTS = 3;

    /**
     * Bitmask for searching in kernel, extension and test files
     */
    const GENAUTOLOADS_ALL = 6;


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
    function __construct( $basePath, $searchKernelFiles, $searchExtensionFiles, $searchTestFiles, $verboseOutput = false, $writeFiles = true, $outputDir = false, $excludeDirs = false )
    {
        $this->basePath = $basePath;
        $this->searchKernelFiles = $searchKernelFiles;
        $this->searchExtensionFiles = $searchExtensionFiles;
        $this->searchTestFiles = $searchTestFiles;
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
        $runMode = $this->runMode( $this->searchKernelFiles, $this->searchExtensionFiles, $this->searchTestFiles );
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
                $file = fopen( $filePath, "w+" );
                if ( $file )
                {
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
    protected function fetchFiles( $path, $mask, $excludeDirs = false )
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

        switch( $this->checkMode( $mask ) )
        {
            case self::GENAUTOLOADS_KERNEL:
            {
                $extraExcludeDirs[] = "@^{$sanitisedBasePath}/extension/@";
                $extraExcludeDirs[] = "@^{$sanitisedBasePath}/tests/@";
                $retFiles = array( "kernel" => $this->buildFileList( $sanitisedBasePath, $extraExcludeDirs ) );
                break;
            }

            case self::GENAUTOLOADS_EXTENSION:
            {
                $retFiles = array( "extension" => $this->buildFileList( "$sanitisedBasePath/extension", $extraExcludeDirs ) );
                break;
            }

            case self::GENAUTOLOADS_TESTS:
            {
                $retFiles = array( "tests" => $this->buildFileList( "$sanitisedBasePath/tests", $extraExcludeDirs ) );
                break;
            }

            case self::GENAUTOLOADS_ALL:
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
    protected function buildFileList( $path, $extraFilter = null )
    {
        $exclusionFilter = array( "@^{$path}/(var|settings|benchmarks|autoload|port_info|templates|tmp|UnitTest)/@" );
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
    protected function getClassFileList( $fileList )
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
                            // make sure we store cross-platform file system paths,
                            // using a forward slash as directory separator
                            if ( DIRECTORY_SEPARATOR != '/' )
                            {
                                $file = str_replace( DIRECTORY_SEPARATOR, '/', $file );
                            }

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
    protected function checkMaxClassLength( $depData )
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
    protected function dumpArray( $sortedArray, $length )
    {
        $retArray = array();
        foreach ( $sortedArray as $location => $sorted )
        {
            $ret = '';
            $offset = $length[$location] + 2;
            foreach( $sorted as $class => $path )
            {
                $ret .= sprintf( "      %-{$offset}s => '%s'," . PHP_EOL, "'{$class}'", $path );
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
    protected function checkMode( $mask )
    {
        $modes = array( self::GENAUTOLOADS_KERNEL, self::GENAUTOLOADS_EXTENSION,
                        self::GENAUTOLOADS_TESTS, self::GENAUTOLOADS_ALL );
        foreach( $modes as $mode )
        {
            if ( ( $mask & $mode ) == $mask )
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
    protected function runMode( $useKernelFiles, $useExtensionFiles, $useTestFiles )
    {
        $mode = self::GENAUTOLOADS_NONE;
        // If no file selectors are chosen we will default to extension files.
        if ( !$useKernelFiles and !$useExtensionFiles and !$useTestFiles )
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

        if ( $useTestFiles )
        {
            $mode |= self::GENAUTOLOADS_TESTS;
        }

        return $mode;
    }

    /**
     * Table to look up file names to use for different run modes.
     *
     * @param string $lookup Mode to look up, can be extension, or kernel.
     * @return string
     */
    protected function nameTable( $lookup )
    {
        $names = array( "extension" => "ezp_extension.php",
                        "kernel"    => "ezp_kernel.php",
                        "tests"     => "ezp_tests.php" );

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
    protected function dumpArrayStart( $part )
    {
        return <<<ENDL
<?php
/**
 * Autoloader definition for eZ Publish $part files.
 *
 * @copyright Copyright (C) 2005-2008 eZ Systems AS. All rights reserved.
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
    protected function dumpArrayEnd()
    {
        return <<<END
    );

?>
END;
    }
}
?>
