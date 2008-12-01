<?php
/**
 * File containing the options object for the autoload generator
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package kernel
 *
 */

/**
 * Class containing options for eZAutoloadGenerator
 * 
 * @property string $basePath
 *      Contains the base path from which to root the search, and from which
 *      to create relative paths
 * 
 * @property bool $searchKernelFiles
 *      Control whether to search the kernel classes
 * 
 * @property bool $searchKernelOverride
 *      Control whether to search for kernel overrides in extensions.
 * 
 * @property bool $searchExtensionFiles
 *      Control whether to search for classes in extensions
 * 
 * @property bool $searchTestFiles
 *      Control whether to search for classes in the test system
 *
 * @property bool $writeFiles
 *      Controls whether the the resulting autoload arrays are written to disc.
 *
 * @property string $outputDir
 *      Is the directory into which the autoload arrays should be written,
 *      defaults to 'autoload'
 *
 * @property array $excludeDirs
 *      Arrays of which paths should not be included in the search for PHP
 *      classes.
 *
 * @throws ezcBasePropertyNotFoundException
 *         If $options contains an undefined property
 * @throws ezcBaseValueException
 *         If $options contains a property with an illegal value
 *
 * @param array $options
 *
 * @package Kernel
 */

class ezpAutoloadGeneratorOptions extends ezcBaseOptions
{
    public function __construct( array $options = array() )
    {
        $this->basePath = getcwd();
        $this->searchKernelFiles = false;
        $this->searchKernelOverride = false;
        $this->searchExtensionFiles = true;
        $this->searchTestFiles = false;
        $this->writeFiles = true;
        $this->outputDir = '';
        $this->excludeDirs = array();

        parent::__construct( $options );
    }

    public function __set( $name, $value )
    {
        switch ( $name )
        {
            case 'basePath':
            case 'outputDir':
                if ( !is_string( $value ) )
                {
                    throw new ezcBaseValueException( $name, $value );
                }
                $this->properties[$name] = $value;
                break;

            case 'searchKernelFiles':
            case 'searchKernelOverride':
            case 'searchExtensionFiles':
            case 'searchTestFiles':
            case 'writeFiles':
                if ( !is_bool( $value ) )
                {
                    throw new ezcBaseValueException( $name, $value );
                }
                $this->properties[$name] = $value;
                break;

            case 'excludeDirs':
                if ( !is_array( $value ) )
                {
                    throw new ezcBaseValueException( $name, $value );
                }
                $this->properties[$name] = $value;
                break;
            default:
                throw new ezcBasePropertyNotFoundException( $name );
        }
    }
}

?>