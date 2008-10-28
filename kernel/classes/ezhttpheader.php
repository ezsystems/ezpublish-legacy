<?php
//
// Definition of eZHTTPHeader class
//
// Created on: <24-Nov-2005 12:34:48 hovik>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezhttpheader.php
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
            $GLOBALS['eZHTTPHeaderCustom'] = $ini->variable( 'HTTPHeaderSettings', 'CustomHeader' ) == 'enabled';
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
                     !$contentView &&
                     $uriString != '/' )
                {
                    continue;
                }

                if ( strpos( $uriString, $path ) === 0 )
                {
                    @list( $headerValue, $depth, $level ) = explode( ';', $value );

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
                        $pathLevel = count( explode( '/', $path ) );
                        $uriLevel = count( explode( '/', $uriString ) );
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
