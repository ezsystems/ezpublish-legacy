<?php
/**
 * This file is part of the eZ Publish Legacy package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributd with this source code.
 * @version //autogentag//
 */

/**
 * Instantiates the DFSBackend based on INI settings
 */
class eZDFSFileHandlerBackendFactory
{
    private static $instance;

    /**
     * Builds the configured DFSBackend handler
     * @return eZDFSFileHandlerDFSBackendInterface
     */
    public static function build()
    {
        if ( !isset( self::$instance ) )
        {
            self::$instance = self::buildHandler(
                eZINI::instance( 'file.ini' )->variable( 'eZDFSClusteringSettings', 'DFSBackend' )
            );
        }

        return self::$instance;
    }

    /**
     * Builds a DFSBackend handler from $className
     *
     * @param string $className
     * @return eZDFSFileHandlerDFSBackendInterface
     *
     * @throws InvalidArgumentException if $className doesn't implement eZDFSFileHandlerDFSBackendInterface
     * @throws InvalidArgumentException if no class $className exists
     */
    public static function buildHandler( $className )
    {
        if ( !class_exists( $className ) )
        {
            throw new InvalidArgumentException( "Invalid DFSBackend class $className. Were autoloads generated ?" );
        }

        if ( self::hasFactorySupport( $className ) )
        {
            $handler = $className::build();
        }
        else
        {
            $handler = new $className();
        }

        if ( !$handler instanceof eZDFSFileHandlerDFSBackendInterface )
        {
            throw new InvalidArgumentException( "$className doesn't implement eZDFSFileHandlerDFSBackendInterface" );
        }

        return $handler;
    }

    /**
     * Tests if $className supports the factory method.
     *
     * We can't use is_a or similar as they only accept a string as the classname starting from PHP 5.3.9.
     *
     * @return bool
     */
    private static function hasFactorySupport( $className )
    {
        $implementedClasses = class_implements( $className );
        return isset( $implementedClasses['eZDFSFileHandlerDFSBackendFactoryInterface'] );
    }
}
