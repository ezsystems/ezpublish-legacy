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
                if ( defined( 'EZP_INI_FILE_PERMISSION' ) )
                {
                    mkdir( $targetBasedir, EZP_INI_FILE_PERMISSION, true );
                }
                else
                {
                    mkdir( $targetBasedir, null, true );
                }
            }

            $filename = $this->nameTable( $location );
            $filePath = $targetBasedir . DIRECTORY_SEPARATOR . $filename;

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
        $excludeDirs = $this->options->excludeDirs;

        // make sure ezcBaseFile::findRecursive and the exclusion filters we pass to it
        // work correctly on systems with another file seperator than the forward slash
        $sanitisedBasePath = DIRECTORY_SEPARATOR == '/' ? $path : strtr( $path, DIRECTORY_SEPARATOR, '/' );
        $dirSep = preg_quote( DIRECTORY_SEPARATOR );

        $extraExcludeDirs = array();
        if ( $excludeDirs !== false and is_array( $excludeDirs ) )
        {
            foreach ( $excludeDirs as $dir )
            {
                $extraExcludeDirs[] = "@^{$sanitisedBasePath}{$dirSep}{$dir}{$dirSep}@";
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
                    $extraExcludeKernelDirs[] = "@^{$sanitisedBasePath}{$dirSep}extension{$dirSep}@";
                    $extraExcludeKernelDirs[] = "@^{$sanitisedBasePath}{$dirSep}tests{$dirSep}@";
                    $retFiles[self::MODE_KERNEL] = $this->buildFileList( $sanitisedBasePath, $extraExcludeKernelDirs );
                    break;

                case self::MODE_EXTENSION:
                case self::MODE_KERNEL_OVERRIDE:
                    $retFiles[$modusOperandi] = $this->buildFileList( "$sanitisedBasePath/extension", $extraExcludeDirs );
                    break;

                case self::MODE_TESTS:
                    $retFiles[self::MODE_TESTS] = $this->buildFileList( "$sanitisedBasePath/tests", $extraExcludeDirs );
                    break;

                case self::MODE_SINGLE_EXTENSION:
                    $retFiles = array( self::MODE_SINGLE_EXTENSION => $this->buildFileList( "$sanitisedBasePath", $extraExcludeDirs ) );
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
        $exclusionFilter = array( "@^{$path}{$dirSep}(var|settings|benchmarks|autoload|port_info|update|templates|tmp|UnitTest|lib{$dirSep}ezc){$dirSep}@" );
        if ( !empty( $extraFilter ) and is_array( $extraFilter ) )
        {
            foreach( $extraFilter as $filter )
            {
                $exclusionFilter[] = $filter;
            }
        }

        if (!empty( $path ) )
        {
            return ezcBaseFile::findRecursive( $path, array( '@\.php$@' ), $exclusionFilter );
        }
        return false;
    }

    /**
     * Extracts class information from PHP sourcecode.
     * @return array (className=>filename)
     */
    protected function getClassFileList( $fileList, $mode )
    {
        $retArray = array();
        foreach( $fileList as $file )
        {
            if ( $mode === self::MODE_SINGLE_EXTENSION )
            {
                $file = getcwd() . DIRECTORY_SEPARATOR . $this->options->basePath . DIRECTORY_SEPARATOR . $file;
            }

            $tokens = @token_get_all( file_get_contents( $file ) );
            foreach( $tokens as $key => $token )
            {
                if ( is_array( $token ) )
                {
                    switch( $token[0] )
                    {
                        case T_CLASS:
                        case T_INTERFACE:
                            // CLASS_TOKEN - WHITESPACE_TOKEN - TEXT_TOKEN (containing class name)
                            $className = $tokens[$key+2][1];

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
                                $retArray[$className] = $filePath;
                            }

                            break;
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
}
?>