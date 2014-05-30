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
     * @return eZDFSFileHandlerDFSBackendInterface
     */
    public static function build()
    {
        if ( !isset( self::$instance ) )
        {
            $dfsBackend = eZINI::instance( 'file.ini' )->variable( 'eZDFSClusteringSettings', 'DFSBackend' );
            if ( !class_exists( $dfsBackend ) )
            {
                throw new InvalidArgumentException( "Invalid DFSBackend class $dfsBackend. Were autoloads generated ?" );
            }

            if ( !self::implementsInterface( $dfsBackend, 'eZDFSFileHandlerDFSBackendInterface' ) )
            {
                throw new InvalidArgumentException( "$dfsBackend must implement eZDFSFileHandlerDFSBackendInterface" );
            }

            if ( self::implementsInterface( $dfsBackend, 'eZDFSFileHandlerFactoryDFSBackendInterface' ) )
            {
                self::$instance = $dfsBackend::factory();
            }
            else
            {
                self::$instance = new $dfsBackend();
            }
        }

        return self::$instance;
    }

    /**
     * Tests if $className implements $interfaceName
     * @return bool
     */
    private static function implementsInterface( $className, $interfaceName )
    {
        if ( version_compare( PHP_VERSION, '5.3.9' ) >= 0 )
        {
            return is_a( $className, $interfaceName, true );
        }
        else
        {
            $implementedClasses = class_implements( $className );
            return isset( $implementedClasses[$interfaceName] );
        }
    }
}
