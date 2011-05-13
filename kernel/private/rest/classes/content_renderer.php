<?php
/**
 * File containing the ezpRestContentRenderer class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

class ezpRestContentRenderer
{
    /**
     * @var ezpRestContentRendererInterface The content renderer provider object container
     */
    protected static $renderer = null;

    const DEFAULT_RENDERER = 'xhtml';

    /**
     * Returns ezpRestContentProviderInterface object for requested renderer
     *
     * @param string $renderer
     * @param ezpContent $content
     * @return ezpRestContentProviderInterface
     */
    protected static function createRenderer( $renderer, ezpContent $content, ezpRestMvcController $controller )
    {
        $rendererOptions = new ezpExtensionOptions();
        $rendererOptions->iniFile = 'rest.ini';
        $rendererOptions->iniSection = 'OutputSettings';
        $rendererOptions->iniVariable = 'RendererClass';
        $rendererOptions->handlerIndex = $renderer;
        $rendererOptions->handlerParams = array( $content, $controller );

        $rendererInstance = eZExtension::getHandlerClass( $rendererOptions );

        if ( !( $rendererInstance instanceof ezpRestContentRendererInterface ) )
            throw new ezpRestContentRendererNotFoundException( $renderer );

        return $rendererInstance;
    }

    /**
     * Returns ezpRestContentProviderInterface object for given renderer and content
     *
     * @static
     * @param string $renderer
     * @param ezpContent $content
     * @return bool|ezpRestContentProviderInterface
     */
    public static function getRenderer( $renderer, ezpContent $content, ezpRestMvcController $controller )
    {
        // If no content renderer has been given, we fall back to built-in 'xhtml' renderer.
        // Note: empty string is not a valid input.
        if ( empty( $renderer ) )
        {
            $renderer = self::DEFAULT_RENDERER;
        }

        if ( !( self::$renderer instanceof ezpRestContentProviderInterface ) )
        {
            self::$renderer = self::createRenderer( $renderer, $content, $controller );
        }

        return self::$renderer;
    }
}
