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
    /**
     * Builds the configured DFSBackend handler
     * @return eZDFSFileHandlerDFSBackendInterface
     */
    public static function build()
    {
        return static::buildHandler(
            eZINI::instance( 'file.ini' )->variable( 'eZDFSClusteringSettings', 'DFSBackend' )
        );
    }

    /**
     * Builds a DFSBackend handler from $className
     *
     * @param string $className A classname, or a reference to a Symfony2 service ("@symfony2.service")
     * @return eZDFSFileHandlerDFSBackendInterface
     *
     * @throws InvalidArgumentException if $className doesn't implement eZDFSFileHandlerDFSBackendInterface
     * @throws InvalidArgumentException if no class $className exists
     */
    public static function buildHandler( $className )
    {
        // Symfony2 service
        if ( substr( $className, 0, 1 ) == '@' )
        {
            try
            {
                return ezpKernel::instance()->getServiceContainer()->get( substr( $className, 1 ) );
            }
            catch ( LogicException $e )
            {
                $className = 'eZDFSFileHandlerDFSBackend';
            }
        }

        if ( !class_exists( $className ) )
        {
            throw new InvalidArgumentException( "Invalid DFSBackend class $className. Were autoloads generated ?" );
        }

        // factory aware class
        if ( self::hasFactorySupport( $className ) )
        {
            /** @var $className eZDFSFileHandlerDFSBackendFactoryInterface */
            $handler = $className::build();
        }
        // lambda class
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
