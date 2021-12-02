<?php
/**
 * File containing the eZHTTPHeader class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZHTTPHeader ezhttpheader.php
  \brief The class eZHTTPHeader does

*/

class eZHTTPHeader
{
    /*!
     * \static
     * Returns true if the custom HTTP headers are enabled, false otherwise.
     * The result is cached in memory to save time on multiple invocations.
     */
    static function enabled()
    {
        if ( isset( $GLOBALS['eZHTTPHeaderCustom'] ) )
        {
            return $GLOBALS['eZHTTPHeaderCustom'];
        }

        $ini = eZINI::instance();
        if ( !$ini->hasVariable( 'HTTPHeaderSettings', 'CustomHeader' ) )
        {
            $GLOBALS['eZHTTPHeaderCustom'] = false;
        }
        else
        {
            if ( $ini->variable( 'HTTPHeaderSettings', 'CustomHeader' ) === 'enabled'
                 && $ini->hasVariable( 'HTTPHeaderSettings', 'OnlyForAnonymous' )
                 && $ini->variable( 'HTTPHeaderSettings', 'OnlyForAnonymous' ) === 'enabled' )
            {
                $GLOBALS['eZHTTPHeaderCustom'] = !eZUser::isCurrentUserRegistered();
            }
            else
            {
                $GLOBALS['eZHTTPHeaderCustom'] = $ini->variable( 'HTTPHeaderSettings', 'CustomHeader' ) == 'enabled';
            }
        }

        return $GLOBALS['eZHTTPHeaderCustom'];
    }

    /*!
     \static
     Get Header override array by requested URI
    */
    static function headerOverrideArray( $uri )
    {
        $headerArray = array();

        if ( !eZHTTPHeader::enabled() )
        {
            return $headerArray;
        }

        $contentView = false;

        $uriString = eZURLAliasML::cleanURL( $uri->uriString() );

        // If content/view used, get url alias for node
        if ( strpos( $uriString, 'content/view/' ) === 0 )
        {
            $urlParts = explode( '/', $uriString );
            $nodeID = $urlParts[3];
            if ( !$nodeID )
            {
                return $headerArray;
            }

            $node = eZContentObjectTreeNode::fetch( $nodeID );
            if ( !$node )
            {
                return $headerArray;
            }

            $uriString = $node->pathWithNames();
            $contentView = true;
        }
        else
        {
            $uriCopy = clone $uri;
            eZURLAliasML::translate( $uriCopy );
            if ( strpos( $uriCopy->uriString(), 'content/view' ) === 0 )
            {
                $contentView = true;
            }
        }

        $uriString = '/' . eZURLAliasML::cleanURL( $uriString );
        $ini = eZINI::instance();

        foreach( $ini->variable( 'HTTPHeaderSettings', 'HeaderList' ) as $header )
        {
            foreach( $ini->variable( 'HTTPHeaderSettings', $header ) as $path => $value )
            {
                $path = '/' . eZURLAliasML::cleanURL( $path );
                if ( strlen( $path ) == 1 &&
                     ( !$contentView && ( $ini->variable( 'HTTPHeaderSettings', 'OnlyForContent' ) === 'enabled' ) ) &&
                     $uriString != '/' )
                {
                    continue;
                }

                if ( strpos( $uriString, $path ) === 0 )
                {
                    $config = eZStringUtils::explodeStr( $value, ';' );
                    $headerValue = $config[0];
                    $depth = isset( $config[1] ) ? $config[1] : null;
                    $level = isset( $config[2] ) ? $config[2] : null;

                    if ( $header == 'Expires' )
                    {
                        $headerValue = gmdate( 'D, d M Y H:i:s', time() + $headerValue ) . ' GMT';
                    }

                    if ( $depth === null )
                    {
                        $headerArray[$header] = $headerValue;
                    }
                    else
                    {
                        $pathLevel = $path == '/' ? 1 : count( explode( '/', $path ) );
                        $uriLevel = $uriString == '/' ? 1 : count( explode( '/', $uriString ) );

                        if ( $level === null )
                        {
                            if ( $uriLevel <= $pathLevel + $depth )
                            {
                                $headerArray[$header] = $headerValue;
                            }
                        }
                        else
                        {
                            if ( $uriLevel <= $pathLevel + $depth &&
                                 $uriLevel >= $pathLevel + $level )
                            {
                                $headerArray[$header] = $headerValue;
                            }
                        }
                    }
                }
            }
        }

        return $headerArray;
    }
}

?>
