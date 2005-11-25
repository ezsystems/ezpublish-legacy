<?php
//
// Definition of eZHTTPHeader class
//
// Created on: <24-Nov-2005 12:34:48 hovik>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
    function enabled()
    {
        if ( isset( $GLOBALS['eZHTTPHeaderCustom'] ) )
        {
            return $GLOBALS['eZHTTPHeaderCustom'];
        }

        $ini =& eZINI::instance();
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
    function headerOverrideArray( $uri )
    {
        $headerArray = array();

        if ( !eZHTTPHeader::enabled() )
        {
            return $headerArray;
        }

        $contentView = false;

        include_once( 'kernel/classes/ezurlalias.php' );
        $uriString = eZURLAlias::cleanURL( $uri->uriString() );

        // If content/view used, get path_identification_string.
        if ( strpos( $uriString, 'content/view/' ) === 0 )
        {
            $urlParts = explode( '/', $uriString );
            $nodeID = $urlParts[3];
            if ( !$nodeID )
            {
                return $headerArray;
            }

            $resultSet = eZPersistentObject::fetchObject( eZContentObjectTreeNode::definition(),
                                                          array( 'path_identification_string' ),
                                                          array( 'node_id' => $nodeID ),
                                                          false );
            if ( !$resultSet )
            {
                return $headerArray;
            }

            $uriString = $resultSet['path_identification_string'];
            $contentView = true;
        }
        else
        {
            $uriCopy = $uri;
            eZURLAlias::translate( $uriCopy );
            if ( strpos( $uriCopy->uriString(), 'content/view' ) === 0 )
            {
                $contentView = true;
            }
        }

        $uriString = '/' . eZURLAlias::cleanURL( $uriString );
        $ini = eZINI::instance();

        foreach( $ini->variable( 'HTTPHeaderSettings', 'HeaderList' ) as $header )
        {
            foreach( $ini->variable( 'HTTPHeaderSettings', $header ) as $path => $value )
            {
                $path = '/' . eZURLAlias::cleanURL( $path );
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
                        $headerValue = gmdate( 'D, d M Y H:i:s', gmmktime() + $headerValue ) . ' GMT';
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
