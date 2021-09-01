<?php
/**
 * File containing the eZAutoloadGenerator class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/**
 * Utility class for generating autoload arrays for eZ Publish.
 *
 * The class can handle classes from the kernel and extensions.
 *
 * @package kernel
 */
class eZAutoloadGenerator
{
    /**
     * eZAutoloadGenerator options
     *
     * @var ezpAutoloadGeneratorOptions
     */
    protected $options;

    /**
     * The bitmask containing all the operation modes.
     *
     * @var int
     */
    protected $mask;

    /**
     * Contains the contents of already created autoload files.
     *
     * If the files are not existing, the corresponding arrays will be empty.
     *
     * @var array
     */
    protected $existingAutoloadArrays;

    /**
     * Contains all the logged messages.
     *
     * @var array
     */
    protected $messages;

    /**
     * Contains all the logged warning messages.
     *
     * @var array
     */
    protected $warnings;

    /**
     * The callback to be used in when logging messages.
     *
     * @var callback
     */
    protected $outputCallback;

    /**
     * Object handling output of information for a given context.
     *
     * @var object
     */
    protected $output;


    /**
     * Nametable for each of the MODE_* modes defined in the class.
     *
     * @var array
     */
    protected $modeName = array(
                                    self::MODE_KERNEL => "Kernel",
                                    self::MODE_EXTENSION => "Extension",
                                    self::MODE_TESTS => "Test",
                                    self::MODE_SINGLE_EXTENSION =>"Single extension",
                                    self::MODE_KERNEL_OVERRIDE => "Kernel overrides",
                               );

    /**
     * Contains newly generated autoload data.
     *
     * Used for sharing data between the printAutloadArrays() and
     * writeAutoloadFiles() methods.
     *
     * @var array
     */
    protected $autoloadArrays;

    /**
     * Bitmask for searching in no files.
     */
    const MODE_NONE = 0;

    /**
     * Bitmask for searhing in kernel files
     */
    const MODE_KERNEL = 1;

    /**
     * Bitmask for search in extension files
     */
    const MODE_EXTENSION = 2;

    /**
     * Bitmask for searching in test files
     */
    const MODE_TESTS = 4;

    /**
     * Bitmask for searching in a single extension only.
     *
     * This mode is mutually exclusive from the other modes.
     */
    const MODE_SINGLE_EXTENSION = 8;

    /**
     * Bitmask for searching for kernel overrides.
     *
     * This mode is mutually excluse from the other modes.
     */
    const MODE_KERNEL_OVERRIDE = 16;

    /**
     * Represents the first phase of autoload generation, where the code
     * searches for PHP source files.
     */
    const OUTPUT_PROGRESS_PHASE1 = 1;

    /**
     * Represents the second phase of autoload generation, where the code
     * tokenizes the found PHP files to look for classes and interfaces.
     */
    const OUTPUT_PROGRESS_PHASE2 = 2;

    /**
     * The name of the file which contains default exclude directories for the
     * autoload generator.
     */
    const DEFAULT_EXCLUDE_FILE = '.autoloadignore';

    /**
     * Undefined token value
     */
    const UNDEFINED_TOKEN = -1;

    /**
     * Constructs class to generate autoload arrays.
     */
    function __construct( ezpAutoloadGeneratorOptions $options = null )
    {
        if ( $options === null )
        {
            $this->options = new ezpAutoloadGeneratorOptions();
        }
        else
        {
            $this->options = $options;
        }
        $this->mask = $this->runMode();

        // Set up arrays for existing autoloads, used to check for class name
        // collisions.
        $this->existingAutoloadArrays = array();
        $this->existingAutoloadArrays[self::MODE_KERNEL] = @include 'autoload/ezp_kernel.php';
        $this->existingAutoloadArrays[self::MODE_EXTENSION] = @include 'var/autoload/ezp_extension.php';
        $this->existingAutoloadArrays[self::MODE_TESTS] = @include 'var/autoload/ezp_tests.php';

        $this->messages = array();
        $this->warnings = array();
    }

    /**
     * Searches specified directories for classes, and build autoload arrays.
     *
     * @throws Exception if desired output directory is not a directory, or if
     *         the autoload arrays are not writeable by the script.
     * @return void
     */
    public function buildAutoloadArrays()
    {
        $phpFiles = $this->fetchFiles();

        $phpClasses = array();
        foreach ( $phpFiles as $mode => $fileList )
        {
            $phpClasses[$mode] = $this->getClassFileList( $fileList, $mode );
        }

        $maxClassNameLength = $this->checkMaxClassLength( $phpClasses );
        $this->autoloadArrays = $this->dumpArray( $phpClasses, $maxClassNameLength );

        if ( $this->options->writeFiles )
        {
            $this->writeAutoloadFiles();
        }
    }

    /**
     * Writes the autoload data in <var>$data</var> for the mode/location
     * <var>$location</var> to actual files.
     *
     * This method also make sure that the target directory exists, and directs
     * the different autoload arrays to different destinations, depending on
     * the operation mode.
     *
     * @param int $location
     * @param string $data
     * @return void
     */
    protected function writeAutoloadFiles()
    {
        $directorySeparators = "/\\";

        $mode = 0777;
        if ( defined( 'EZP_INI_FILE_PERMISSION' ) )
        {
            $mode = EZP_INI_FILE_PERMISSION;
        }

        foreach( $this->autoloadArrays as $location => $data )
        {
            if ( $this->options->outputDir )
            {
                $targetBasedir = rtrim( $this->options->outputDir, $directorySeparators );
            }
            else
            {
                $targetBasedir = $this->targetTable( $location );
            }

            if( file_exists( $targetBasedir ) && !is_dir( $targetBasedir ) )
            {
                throw new Exception( "Specified target: {$targetBasedir} is not a directory." );
            }
            else if ( !file_exists( $targetBasedir ) )
            {
                if ( !@mkdir( $targetBasedir, $mode, true ) )
                {
                    throw new Exception( __METHOD__ . ": The directory {$targetBasedir} cannot be created by the system." );
                }
            }

            $filename = $this->nameTable( $location );
            $filePath = $targetBasedir . DIRECTORY_SEPARATOR . $filename;

             $file = @fopen( $filePath, "w+" );
             if ( $file )
             {
                 fwrite( $file, $this->dumpArrayStart( $location ) );
                 fwrite( $file, $data );
                 fwrite( $file, $this->dumpArrayEnd() );
                 fclose( $file );
                 // No need to update the mode if the latter is already the good one
                 if ( fileperms( $filePath ) & octdec( $mode ) !== octdec( $mode ) && !@chmod( $filePath, $mode ) )
                 {
                    throw new Exception( __METHOD__ . ": The mode of file {$filePath} cannot be changed by the system." );
                 }
             }
             else
             {
                 throw new Exception( __METHOD__ . ": The file {$filePath} is not writable by the system." );
             }
         }
    }

    /**
     * Adds exclude directories specified in the default excludes files, to the exclude array.
     *
     * If the default exclude file '.autoloadignore' does not exist, the
     * function will just return the user specified exclude directories. This function relies on the options
     * object being available in the instance.
     *
     * @return array The exclude directories
     */
    protected function handleDefaultExcludeFile()
    {
        $defaultExcludeFile = $this->options->basePath . DIRECTORY_SEPARATOR . self::DEFAULT_EXCLUDE_FILE;
        if ( !file_exists( $defaultExcludeFile ) )
        {
            return $this->options->excludeDirs;
        }

        $defaultExcludeArray = explode( "\n", trim( file_get_contents( $defaultExcludeFile ) ) );
        return array_merge( $defaultExcludeArray, $this->options->excludeDirs );
    }

    /**
     * Returns an array indexed by location for classes and their filenames.
     *
     * @param string $path The base path to start the search from.
     * @param string $mask A binary mask which instructs the function whether to fetch kernel-related or extension-related files.
     * @return array
     */
    protected function fetchFiles()
    {
        $path = $this->options->basePath;
        $excludeDirs = $this->handleDefaultExcludeFile();

        // make sure ezcBaseFile::findRecursive and the exclusion filters we pass to it
        // work correctly on systems with another file seperator than the forward slash
        $sanitisedBasePath = DIRECTORY_SEPARATOR == '/' ? $path : strtr( $path, DIRECTORY_SEPARATOR, '/' );
        $dirSep = preg_quote( DIRECTORY_SEPARATOR );

        $extraExcludeDirs = array();
        if ( $excludeDirs !== false and is_array( $excludeDirs ) )
        {
            foreach ( $excludeDirs as $dir )
            {
                $extraExcludeDirs[] = "@^{$sanitisedBasePath}{$dirSep}{$dir}@";
            }
        }

        $retFiles = array();

        $activeModes = $this->checkMode();

        foreach( $activeModes as $modusOperandi )
        {
            switch( $modusOperandi )
            {
                case self::MODE_KERNEL:
                    $extraExcludeKernelDirs = $extraExcludeDirs;
                    $extraExcludeKernelDirs[] = "@^{$sanitisedBasePath}{$dirSep}extension@";
                    $extraExcludeKernelDirs[] = "@^{$sanitisedBasePath}{$dirSep}tests@";
                    $retFiles[$modusOperandi] = $this->buildFileList( $sanitisedBasePath, $extraExcludeKernelDirs );
                    break;

                case self::MODE_EXTENSION:
                case self::MODE_KERNEL_OVERRIDE:
                    $extraExcludeExtensionDirs = $extraExcludeDirs;
                    $extraExcludeExtensionDirs[] = "@^{$sanitisedBasePath}{$dirSep}extension{$dirSep}[^{$dirSep}]+{$dirSep}tests@";
                    $retFiles[$modusOperandi] = $this->buildFileList( "$sanitisedBasePath/extension", $extraExcludeExtensionDirs );
                    break;

                case self::MODE_TESTS:
                    $retFiles[$modusOperandi] = $this->buildFileList( "$sanitisedBasePath/tests", $extraExcludeDirs );
                    $extraExcludeExtensionDirs = $extraExcludeDirs;
                    $extraExcludeExtensionDirs[] = "@^{$sanitisedBasePath}{$dirSep}extension{$dirSep}[^{$dirSep}]+{$dirSep}(?!tests)@";
                    $extensionTestFiles = $this->buildFileList("$sanitisedBasePath/extension", $extraExcludeExtensionDirs );
                    $retFiles[$modusOperandi] = array_merge( $retFiles[$modusOperandi], $extensionTestFiles );
                    break;

                case self::MODE_SINGLE_EXTENSION:
                    $retFiles = array( $modusOperandi => $this->buildFileList( "$sanitisedBasePath", $extraExcludeDirs ) );
                    break;
            }
        }

        //Make all the paths relative to $path
        foreach ( $retFiles as &$fileBundle )
        {
            foreach ( $fileBundle as $key => &$file )
            {
                // ezcBaseFile::calculateRelativePath only works correctly with paths where DIRECTORY_SEPARATOR is used
                // so we need to correct the results of ezcBaseFile::findRecursive again
                if ( DIRECTORY_SEPARATOR != '/' )
                {
                    $file = strtr( $file, '/', DIRECTORY_SEPARATOR );
                }
                $fileBundle[$key] = ezcBaseFile::calculateRelativePath( $file, $path );
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
        $dirSep = preg_quote( DIRECTORY_SEPARATOR );
        $exclusionFilter = array( "@^{$path}{$dirSep}(var|settings|benchmarks|bin|autoload|port_info|update|templates|tmp|UnitTest|lib{$dirSep}ezc)@" );

        if ( !empty( $extraFilter ) and is_array( $extraFilter ) )
        {
            foreach( $extraFilter as $filter )
            {
                $exclusionFilter[] = $filter;
            }
        }

        if (!empty( $path ) )
        {
            return self::findRecursive( $path, array( '@\.php$@' ), $exclusionFilter, $this );
        }
        return false;
    }

    /**
     * Uses the walker in ezcBaseFile to find files.
     *
     * This also uses the callback to get progress information about the file search.
     *
     * @param string $sourceDir
     * @param array $includeFilters
     * @param array $excludeFilters
     * @param eZAutoloadGenerator $gen
     * @return array
     */
    public static function findRecursive( $sourceDir, array $includeFilters = array(), array $excludeFilters = array(), eZAutoloadGenerator $gen )
    {
        $gen->log( "Scanning for PHP-files." );
        $gen->startProgressOutput( self::OUTPUT_PROGRESS_PHASE1 );

        // create the context, and then start walking over the array
        $context = new ezpAutoloadFileFindContext();
        $context->generator = $gen;

        self::walkRecursive( $sourceDir, $includeFilters, $excludeFilters,
                array( 'eZAutoloadGenerator', 'findRecursiveCallback' ), $context );

        // return the found and pattern-matched files
        sort( $context->elements );

        $gen->stopProgressOutput( self::OUTPUT_PROGRESS_PHASE1 );
        $gen->log( "Scan complete. Found {$context->count} PHP files." );

        return $context->elements;
    }

    /**
     * Walks files and directories recursively on a file system. This methods
     * is the same as ezcBaseFile::walkRecursive() except that it applies
     * $excludeFilters directories where the original only applies
     * $excludeFilters on files. As soon as
     * https://issues.apache.org/jira/browse/ZETACOMP-85 is implemented, this
     * method could be removed.
     *
     * @param mixed $sourceDir
     * @param array $includeFilters
     * @param array $excludeFilters
     * @param mixed $callback
     * @param mixed $callbackContext
     *
     * @throws ezcBaseFileNotFoundException if the $sourceDir directory is not
     *         a directory or does not exist.
     * @throws ezcBaseFilePermissionException if the $sourceDir directory could
     *         not be opened for reading.
     * @return array
     */
    static protected function walkRecursive( $sourceDir, array $includeFilters = array(), array $excludeFilters = array(), $callback, &$callbackContext )
    {
        if ( !is_dir( $sourceDir ) )
        {
            throw new ezcBaseFileNotFoundException( $sourceDir, 'directory' );
        }
        $elements = array();

        // Iterate over the $excludeFilters and prohibit the directory from
        // being scanned when atleast one of them matches
        foreach ( $excludeFilters as $filter )
        {
            if ( preg_match( $filter, $sourceDir ) )
            {
                return $elements;
            }
        }

        $d = @dir( $sourceDir );
        if ( !$d )
        {
            throw new ezcBaseFilePermissionException( $sourceDir, ezcBaseFileException::READ );
        }

        while ( ( $entry = $d->read() ) !== false )
        {
            if ( $entry == '.' || $entry == '..' )
            {
                continue;
            }

            $fileInfo = @stat( $sourceDir . DIRECTORY_SEPARATOR . $entry );
            if ( !$fileInfo )
            {
                $fileInfo = array( 'size' => 0, 'mode' => 0 );
            }

            if ( $fileInfo['mode'] & 0x4000 )
            {
                // We need to ignore the Permission exceptions here as it can
                // be normal that a directory can not be accessed. We only need
                // the exception if the top directory could not be read.
                try
                {
                    call_user_func_array( $callback, array( $callbackContext, $sourceDir, $entry, $fileInfo ) );
                    $subList = self::walkRecursive( $sourceDir . DIRECTORY_SEPARATOR . $entry, $includeFilters, $excludeFilters, $callback, $callbackContext );
                    $elements = array_merge( $elements, $subList );
                }
                catch ( ezcBaseFilePermissionException $e )
                {
                }
            }
            else
            {
                // By default a file is included in the return list
                $ok = true;
                // Iterate over the $includeFilters and prohibit the file from
                // being returned when atleast one of them does not match
                foreach ( $includeFilters as $filter )
                {
                    if ( !preg_match( $filter, $sourceDir . DIRECTORY_SEPARATOR . $entry ) )
                    {
                        $ok = false;
                        break;
                    }
                }
                // Iterate over the $excludeFilters and prohibit the file from
                // being returns when atleast one of them matches
                foreach ( $excludeFilters as $filter )
                {
                    if ( preg_match( $filter, $sourceDir . DIRECTORY_SEPARATOR . $entry ) )
                    {
                        $ok = false;
                        break;
                    }
                }

                // If everything's allright, call the callback and add the
                // entry to the elements array
                if ( $ok )
                {
                    call_user_func( $callback, $callbackContext, $sourceDir, $entry, $fileInfo );
                    $elements[] = $sourceDir . DIRECTORY_SEPARATOR . $entry;
                }
            }
        }
        sort( $elements );
        return $elements;
    }

    /**
     * Callback used ezcBaseFile
     *
     * @param string $ezpAutoloadFileFindContext
     * @param string $sourceDir
     * @param string $fileName
     * @param string $fileInfo
     * @return void
     */

    public static function findRecursiveCallback( ezpAutoloadFileFindContext $context, $sourceDir, $fileName, $fileInfo )
    {
        if ( $fileInfo['mode'] & 0x4000 )
        {
            return;
        }

        // update the statistics
        $context->elements[] = $sourceDir . DIRECTORY_SEPARATOR . $fileName;
        $context->count++;

        $context->generator->updateProgressOutput( eZAutoloadGenerator::OUTPUT_PROGRESS_PHASE1 );
    }

    /**
     * Extracts class information from PHP sourcecode.
     * @return array (className=>filename)
     */
    protected function getClassFileList( $fileList, $mode )
    {
        $retArray = array();
        $this->log( "Searching for classes (tokenizing)." );
        $statArray = array( 'nFiles' => count( $fileList ),
                            'classCount' => 0,
                            'classAdded' => 0,
                           );
        $this->setStatArray( self::OUTPUT_PROGRESS_PHASE2, $statArray );

        if ( count( $fileList ) )
        {
            $this->startProgressOutput( self::OUTPUT_PROGRESS_PHASE2 );

            // Compatibility with PHP 5.2 where T_NAMESPACE constant is not available
            // Assigning the constant value to $tNamespace
            // 377 is the value for T_NAMESPACE in PHP 5.3.x
            $tNamespace = defined( 'T_NAMESPACE' ) ? T_NAMESPACE : self::UNDEFINED_TOKEN;

            // Traits support, see http://issues.ez.no/19028
            $tTrait = defined( 'T_TRAIT' ) ? T_TRAIT : self::UNDEFINED_TOKEN;

            foreach( $fileList as $file )
            {
                $this->updateProgressOutput( self::OUTPUT_PROGRESS_PHASE2 );
                if ( $mode === self::MODE_SINGLE_EXTENSION )
                {
                    $file = getcwd() . DIRECTORY_SEPARATOR . $this->options->basePath . DIRECTORY_SEPARATOR . $file;
                }

                $tokens = @token_get_all( file_get_contents( $file ) );
                $namespace = null;
                foreach( $tokens as $key => $token )
                {
                    if ( is_array( $token ) )
                    {
                        switch( $token[0] )
                        {
                            case self::UNDEFINED_TOKEN:
                                // Unsupported token, do nothing
                                break;

                            // Store namespace name, if applicable, to concatenate with class name
                            case $tNamespace:
                                // NAMESPACE_TOKEN - WHITESPACE_TOKEN - TEXT_TOKENS (containing namespace name)
                                $offset = $key + 2;
                                $namespace = "";
                                while ( $tokens[$offset] !== ";" && $tokens[$offset] !== "{" )
                                {
                                    if ( is_array( $tokens[$offset] ) )
                                    {
                                        $namespace .= $tokens[$offset][1];
                                    }

                                    $offset++;
                                }

                                $namespace = trim( addcslashes( $namespace, '\\' ) );
                                break;

                            case T_CLASS:
                            case T_INTERFACE:
                            case $tTrait:
                                // Ignore token if prefixed by a double colon: "<ClassName>::class"
                                // @see http://php.net/manual/en/language.oop5.basic.php#language.oop5.basic.class.class
                                // TEXT_TOKEN - "::"-TEXT_TOKEN - CLASS_TOKEN
                                if ($tokens[$key-1][1] === '::') {
                                    break;
                                }
                                /**
                                 * Ignore token if class is anonymous: "new Class() {}"
                                 * @see https://www.php.net/manual/en/language.oop5.anonymous.php
                                 * NEW_TOKEN - WHITESPACE_TOKEN - CLASS_TOKEN
                                 */
                                if(isset($tokens[$key-2][0]) && $tokens[$key-2][0] === T_NEW) {
                                    break;
                                }

                                // Increment stat for found class.
                                $this->incrementProgressStat( self::OUTPUT_PROGRESS_PHASE2, 'classCount' );

                                // CLASS_TOKEN - WHITESPACE_TOKEN - TEXT_TOKEN (containing class name)
                                $className = $tokens[$key+2][1];
                                if ( $namespace !== null )
                                {
                                    $className = "$namespace\\\\$className";
                                }

                                $filePath = $file;

                                if ( $mode === self::MODE_SINGLE_EXTENSION )
                                {
                                    $filePath = ezcBaseFile::calculateRelativePath( $filePath, getcwd() . DIRECTORY_SEPARATOR . $this->options->basePath );
                                }

                                // make sure we store cross-platform file system paths,
                                // using a forward slash as directory separator
                                if ( DIRECTORY_SEPARATOR != '/' )
                                {
                                    $filePath = str_replace( DIRECTORY_SEPARATOR, '/', $filePath );
                                }
                                // Here there are two code paths.
                                // MODE_KERNEL_OVERRIDE will only add a class if
                                // it exists in the MODE_KERNEL autoload array.
                                // All other modes will only add a class if the
                                // class name is unique.

                                $addClass = $this->classCanBeAdded( $className, $filePath, $mode, $retArray );

                                if ( $addClass )
                                {
                                    // increment stat for actually added number of classes.
                                    $this->incrementProgressStat( self::OUTPUT_PROGRESS_PHASE2, 'classAdded' );

                                    $retArray[$className] = $filePath;
                                }

                                break;
                        }
                    }
                }
            }

            $this->stopProgressOutput( self::OUTPUT_PROGRESS_PHASE2 );
            ksort( $retArray );
        }

        if ( $this->output !== null )
        {
            extract( $this->getStatArray( self::OUTPUT_PROGRESS_PHASE2 ) );
            $this->log( "Found {$classCount} classes, added {$classAdded} of them to the autoload array." );
        }

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
    protected function checkMode()
    {
        $modes = array( self::MODE_KERNEL, self::MODE_EXTENSION,
                        self::MODE_TESTS, self::MODE_SINGLE_EXTENSION, self::MODE_KERNEL_OVERRIDE );

        $activeModes = array();

        foreach( $modes as $mode )
        {
            if ( ( $this->mask & $mode ) == $mode )
            {
                $activeModes[] = $mode;
            }
        }
        return $activeModes;
    }

    /**
     * Generates the active bitmask for this instance of the autoload generation script
     * depending on the parameters it sets the corresponding flags.
     *
     * @return int
     */
    protected function runMode()
    {
        $mode = self::MODE_NONE;

        // If an extension is given as an argument, that will override other
        // mode options
        if ( $this->options->basePath !== getcwd() )
        {
            $mode |= self::MODE_SINGLE_EXTENSION;
            return $mode;
        }

        if ( $this->options->searchKernelOverride )
        {
            $mode |= self::MODE_KERNEL_OVERRIDE;
            return $mode;
        }

        // If no file selectors are chosen we will default to extension files.
        if ( !$this->options->searchKernelFiles and !$this->options->searchExtensionFiles and !$this->options->searchTestFiles )
        {
            $mode |= self::MODE_EXTENSION;
        }

        if ( $this->options->searchKernelFiles )
        {
            $mode |= self::MODE_KERNEL;
        }

        if ( $this->options->searchExtensionFiles )
        {
            $mode |= self::MODE_EXTENSION;
        }

        if ( $this->options->searchTestFiles )
        {
            $mode |= self::MODE_TESTS;
        }

        return $mode;
    }

    /**
     * Convenience method to set the mode directly.
     *
     * This is a method which allow you to set the operation mode directly and
     * bypass the options object. The bitmask <var>$modeValue</var> can be set
     * using the MODE_* class constants.
     *
     * <code>
     * $gen = new eZAutoloadGenerator();
     * $gen->setMode( eZAutoloadGenerator::MODE_EXTENSION | eZAutoloadGenerator::MODE_TESTS );
     * </code>
     *
     * @param int $modeValue
     * @return void
     */
    public function setMode( $modeValue )
    {
        if ( is_numeric( $modeValue ) and $modeValue > 0 )
        {
            $this->mask = $modeValue;
        }
    }

    /**
     * Table to look up file names to use for different run modes.
     *
     * @param string $lookup Mode to look up, can be extension, or kernel.
     * @return string
     */
    protected function nameTable( $lookup )
    {
        $names = array( self::MODE_EXTENSION => "ezp_extension.php",
                        self::MODE_SINGLE_EXTENSION => basename( $this->options->basePath ) . '_autoload.php',
                        self::MODE_KERNEL    => "ezp_kernel.php",
                        self::MODE_TESTS     => "ezp_tests.php",
                        self::MODE_KERNEL_OVERRIDE => "ezp_override.php",
                      );

        if ( array_key_exists( $lookup, $names ) )
        {
            return $names[$lookup];
        }
        return false;
    }

    /**
     * Provides a look-up for which base directory to use depending on mode.
     *
     * @param int $lookup
     * @return string
     */
    protected function targetTable( $lookup )
    {
        $targets = array(
                            self::MODE_EXTENSION => "var/autoload",
                            self::MODE_TESTS     => "var/autoload",
                            self::MODE_KERNEL    => "autoload",
                            self::MODE_SINGLE_EXTENSION => $this->options->basePath . DIRECTORY_SEPARATOR . 'autoload',
                            self::MODE_KERNEL_OVERRIDE => "var/autoload",
                        );

        if ( array_key_exists( $lookup, $targets ) )
        {
            return $targets[$lookup];
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
        $description = "";
        switch( $part )
        {
            case self::MODE_SINGLE_EXTENSION:
                $description = basename( $this->options->basePath ) . ' extension';
                break;

            case self::MODE_EXTENSION:
            case self::MODE_KERNEL:
            case self::MODE_TESTS:
            case self::MODE_KERNEL_OVERRIDE:
                $description = $this->modeName[$part];
                break;
        }
        return <<<ENDL
<?php
/**
 * Autoloader definition for eZ Publish $description files.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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

    /**
     * Determines if a class can be added to the autoload array.
     *
     * 1. When regenerating array for MODE, do not check for existance of
     *    class duplicates in the existing array for the same mode.
     *
     * 2. When adding a new class to the in-progress autoload array,
     *    check for matching keys, before adding. If match is found then
     *    add a warning message to the warnings stack, and mark the class
     *    not to be added.
     *
     * 3. If kernel array is not present, issue warning that class name
     *    collisions cannot be checked until kernel array is generated.
     *
     * 4. Class collisions with kernel classes is only allowed for
     *    MODE_KERNEL_OVERRIDE
     *
     * @param string $class The name of the class being checked.
     * @param string $file The filename where the class is found.
     * @param int   $mode The mode representing the current run mode.
     * @param array $inProgressAutoloadArray Array of the already detected
     *              classes for the current mode.
     * @return boolean
     */
    protected function classCanBeAdded( $class, $file, $mode, $inProgressAutoloadArray )
    {
        $addClass = true;
        $sameRepositoryConflict = false;
        $kernelRepositoryConflict = false;

        if ( empty( $this->existingAutoloadArrays[self::MODE_KERNEL] ) )
        {
            $this->logWarning( "No existing kernel override found. Please generate kernel autoloads first." );
        }

        switch( $mode )
        {
            case self::MODE_KERNEL:
                $sameRepositoryConflict = $this->classExistsInArray( $class, $mode, $file, $inProgressAutoloadArray, $mode );

                if ( $sameRepositoryConflict )
                {
                    $addClass = false;
                }
                break;

            case self::MODE_EXTENSION:
            case self::MODE_TESTS:
            case self::MODE_SINGLE_EXTENSION:
                $kernelRepositoryConflict = $this->classExistsInArray( $class, self::MODE_KERNEL, $file, null, $mode  );
                $sameRepositoryConflict = $this->classExistsInArray( $class, $mode, $file, $inProgressAutoloadArray, $mode );

                if ( $kernelRepositoryConflict or $sameRepositoryConflict )
                {
                    $addClass = false;
                }
                break;

            case self::MODE_KERNEL_OVERRIDE:
                // For kernel overrides we only want class name collisions in
                // the kernel array to trigger a addClass hit. However we
                // like to emit warnings about duplicateses found in the current
                // generated array. Only one class may be overriding a kernel
                // at a time.

                $kernelRepositoryConflict = $this->classExistsInArray( $class, self::MODE_KERNEL, $file, $inProgressAutoloadArray, $mode  );
                $sameRepositoryConflict = $this->classExistsInArray( $class, $mode, $file, $inProgressAutoloadArray, $mode );

                if ( ( $sameRepositoryConflict and !$kernelRepositoryConflict ) or
                     ( $sameRepositoryConflict and $kernelRepositoryConflict ) or
                     ( !$kernelRepositoryConflict and !$sameRepositoryConflict )
                   )
                {
                    $addClass = false;
                }
                break;
        }
        return $addClass;
    }

    /**
     * Internal method used to check if an class exist autoload arrays.
     *
     * @param string $class The name of the class being checked.
     * @param int $checkMode The mode whose autoload arrays will be checked.
     * @param string $file Filename containing the class.
     * @param array $inProgressAutoloadArray The autoload array generated so far.
     * @param int $generatingMode The mode we are generating for autoloads for.
     * @return boolean
     */
    protected function classExistsInArray( $class, $checkMode, $file, $inProgressAutoloadArray = null, $generatingMode = null )
    {
        if ( ( $checkMode === $generatingMode ) and $inProgressAutoloadArray !== null )
        {
            $classCollision = array_key_exists( $class, $inProgressAutoloadArray );
        }
        else
        {
            $classCollision = array_key_exists( $class, $this->existingAutoloadArrays[$checkMode] );
        }

        if ( $classCollision )
        {
            // If there is a class collisions we want to give feedback to the user.
            $this->logIssue( $class, $checkMode, $file, $inProgressAutoloadArray, $generatingMode );
            return true;
        }
        return false;
    }

    /**
     * Helper method for giving user feedback when check for class collisions.
     *
     * The params are the same as for classExistsInArray().
     *
     *@return void
     */
    protected function logIssue( $class, $checkMode, $file, $inProgressAutoloadArray, $generatingMode )
    {
        $conflictFile = ($checkMode === $generatingMode) ?
                        $inProgressAutoloadArray[$class] :
                        $this->existingAutoloadArrays[$checkMode][$class];

        if ( $generatingMode === self::MODE_KERNEL_OVERRIDE and $checkMode === self::MODE_KERNEL )
        {
            if ( $inProgressAutoloadArray !== null and array_key_exists( $class, $inProgressAutoloadArray ) )
            {
                return;
            }

            $message = "Class {$class}";
            $message .= " in file {$file}";
            $message .= " will override:\n";
            $message .= "{$conflictFile} ({$this->targetTable($checkMode)}/{$this->nameTable($checkMode)})";
            $this->log( $message );
        }
        else
        {
            $message = "Class {$class}";
            $message .= " in file {$file}";
            $message .= " is already defined in:\n";
            $message .= "{$conflictFile} ({$this->targetTable($checkMode)}/{$this->nameTable($checkMode)})";
            $message .= "\nThis class was not added to the autoload array.";
            $this->logWarning( $message );
        }
    }

    /**
     * Pushes <var>$message</var> to the messages stack.
     *
     * The <var>$message</var> will also tried to be emitted.
     *
     * @param string $message
     * @return void
     */
    protected function log( $message )
    {
        $this->messages[] = $message;
        $this->emit( $message, 'normal' );
    }

    /**
     * Pushes <var>$message</var> to the warning stack.
     *
     * The warning <var>$message</var> will also tried to be emitted.
     *
     * @param string $message
     * @return void
     */
    protected function logWarning( $message )
    {
        $this->warnings[] = $message;

        // If we are showing progress output, we display the warnings
        // summarized after the other output (CLI)
        if ( $this->options->displayProgress )
        {
            return;
        }
        $this->emit( $message, 'warning' );
    }

    /**
     * Sets callback for outputting messages.
     *
     * @param callback $callback
     * @return void
     */
    public function setOutputCallback( $callback )
    {
        $this->outputCallback = $callback;
    }

    /**
     * Will call output callback if defined.
     *
     * The purpose of this function is to directly emit messages, for instance
     * when the class is being used from shell scripts. If a valid callback
     * has been setup with @see setOutputCallback(), that method will be called
     * with <var>$message</var> and <var>$messageType</var>
     *
     * @param string $message
     * @param string $messageType
     * @return void
     */
    protected function emit( $message, $messageType )
    {
        if ( $this->outputCallback === null )
        {
            return;
        }
        call_user_func( $this->outputCallback , $message, $messageType );
    }

    /**
     * Get the array of logged messaages
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Get the array of logged warnings
     *
     * @return array
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * Prints out the generated autoload arrays.
     *
     * Meant to provide a user-viewable output of the defined autoload arrays.
     * If <var>$printForMode</var> is provided, only the array for that mode
     * will be printed.
     *
     * @param string $printForMode Run mode specified by the MODE_* constants.
     * @return mixed
     */
    public function printAutoloadArray( $printForMode = null )
    {
        if ( $printForMode !== null )
        {
            if ( array_key_exists( $printForMode, $this->autoloadArrays ) )
            {
                $this->log( $this->dumpArrayStart( $printForMode ) . $this->autoloadArrays[$printForMode] . $this->dumpArrayEnd() );
            }
            return;
        }

        foreach( $this->autoloadArrays as $location => $data )
        {
            $this->log( $this->dumpArrayStart( $location ) . $data . $this->dumpArrayEnd() );
        }
    }

    public function setOptions( $options )
    {
        $this->options = $options;
        $this->mask = $this->runMode();
    }

    /**
     * Calls updateProgress on the output object.
     *
     * If progress output is not enabled or the output object is not set, this
     * method will not do anything.
     *
     * @param int $phase
     * @param string $array
     * @return void
     */
    protected function updateProgressOutput( $phase )
    {
        if ( !$this->options->displayProgress || $this->output === null )
        {
            return;
        }
        $this->output->updateProgress( $phase );
    }

    /**
     * Increment counters used for statistics in the progress output.
     *
     * If the output object is not set, the method will not do anything.
     *
     * @param int $phase
     * @param array $stat
     * @return void
     */
    protected function incrementProgressStat( $phase, $stat )
    {
        if ( $this->output === null )
        {
            return;
        }
        $statArray = $this->output->getData( $phase );
        $statArray[$stat]++;
        $this->output->updateData( $phase, $statArray );
    }

    /**
     * Initializes progress output for <var>$phase</var>
     *
     * @param int $phase
     * @return void
     */
    protected function startProgressOutput( $phase )
    {
        if ( !$this->options->displayProgress || $this->output === null )
        {
            return;
        }
        $func = 'initPhase' . $phase;
        $this->output->$func();
    }

    /**
     * Stops progress output for <var>$phase</var>
     *
     * @param int $phase
     * @return void
     */
    protected function stopProgressOutput( $phase )
    {
        if ( !$this->options->displayProgress || $this->output === null )
        {
            return;
        }
        $func = 'finishPhase' . $phase;
        $this->output->$func();
    }

    /**
     * Fetches statistics array for $phase form the output object.
     *
     * @param int $phase
     * @return void
     */
    protected function getStatArray( $phase )
    {
        if ( $this->output === null )
        {
            return;
        }
        return $this->output->getData( $phase );
    }

    /**
     * Updates internal statistics data for <var>$phase</var>, with new array <var>$data</var>.
     *
     * @param int $phase
     * @param array $data
     * @return void
     */
    protected function setStatArray( $phase, $data )
    {
        if ( $this->output === null )
        {
            return;
        }
        $this->output->updateData( $phase, $data );
    }

    /**
     * Sets the object to handle out from the autoload generation.
     *
     * Currently this is only handled for the CLI.
     *
     * @see ezpAutoloadCliOutput
     * @param object $outputObject
     * @return void
     */
    public function setOutputObject( $outputObject )
    {
        $this->output = $outputObject;
    }
}
?>
