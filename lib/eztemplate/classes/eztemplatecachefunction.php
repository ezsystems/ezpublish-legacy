<?php
//
// Definition of eZTemplateCacheFunction class
//
// Created on: <28-Feb-2003 15:06:33 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZTemplateCacheFunction eztemplatecachefunction.php
  \ingroup eZTemplateFunctions
  \brief Advanced cache handling

*/

class eZTemplateCacheFunction
{
    /*!
     Initializes the object with names.
    */
    function eZTemplateCacheFunction( $blockName = 'cache-block' )
    {
        $this->BlockName = $blockName;
    }

    /*!
     Returns an array containing the name of the block function, default is "block".
     The name is specified in the constructor.
    */
    function functionList()
    {
        return array( $this->BlockName );
    }

    /*!
     Processes the function with all it's children.
    */
    function process( &$tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        switch ( $functionName )
        {
            case $this->BlockName:
            {
                // Check for disabled cache.
                $ini =& eZINI::instance();
                if ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) == 'disabled' )
                {
                    $children = $functionChildren;

                    $childTextElements = array();
                    foreach ( array_keys( $children ) as $childKey )
                    {
                        $child =& $children[$childKey];
                        $tpl->processNode( $child, $childTextElements, $rootNamespace, $currentNamespace );
                    }
                    $text =& implode( '', $childTextElements );
                    $textElements[] = $text;
                }
                else
                {
                    $keyString = "";
                    // Get cache keys
                    if ( isset( $functionParameters["keys"] ) )
                    {
                        $keys = $tpl->elementValue( $functionParameters["keys"], $rootNamespace, $currentNamespace, $functionPlacement );

                        if ( is_array( $keys ) )
                        {
                            foreach ( $keys as $key )
                            {
                                $keyString .= $key . "_";
                            }
                        }
                        else
                        {
                            $keyString .= $keys . "_";
                        }
                    }

                    // Append keys from position in template
                    $keyString .= $functionPlacement[0][0] . "_";
                    $keyString .= $functionPlacement[0][1] . "_";
                    $keyString .= $functionPlacement[1][0] . "_";
                    $keyString .= $functionPlacement[1][1] . "_";
                    $keyString .= $functionPlacement[2] . "_";

                    // Fetch the current siteaccess
                    $keyString .= $GLOBALS['eZCurrentAccess']['name'];
                    include_once( 'lib/ezutils/classes/ezphpcreator.php' );
                    $md5Key = md5( $keyString );

                    $cacheDir = eZSys::cacheDirectory();
                    $phpCache = new eZPHPCreator( "$cacheDir/template-block/" . $md5Key[0] . "/" . $md5Key[1] . "/" . $md5Key[2], md5( $keyString ) . ".php" );

                    // Check if a custom expiry time is defined
                    if ( isset( $functionParameters["expiry"] ) )
                    {
                        $expiry = $tpl->elementValue( $functionParameters["expiry"], $rootNamespace, $currentNamespace, $functionPlacement );
                    }
                    else
                    {
                        // Default expiry time is set to two hours
                        $expiry = 60*60*2;
                    }

                    $localExpiryTime = mktime() - $expiry;

                    $ignoreContentExpiry = false;
                    if ( isset( $functionParameters["ignore_content_expiry"] ) )
                    {
                        $ignoreContentExpiry = $tpl->elementValue( $functionParameters["ignore_content_expiry"], $rootNamespace, $currentNamespace, $functionPlacement ) === true;
                    }

                    $expiryTime = $localExpiryTime;
                    if ( $ignoreContentExpiry == false )
                    {
                        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
                        $handler =& eZExpiryHandler::instance();
                        if ( $handler->hasTimestamp( 'content-cache' ) )
                        {
                            $globalExpiryTime = $handler->timestamp( 'content-cache' );
                            $expiryTime = max( $localExpiryTime, $globalExpiryTime );
                        }
                    }

                    // Check if we can restore
                    if ( $phpCache->canRestore( $expiryTime ) )
                    {
                        $variables = $phpCache->restore( array( 'contentdata' => 'contentData' )  );
                        $text =& $variables['contentdata'];
                        $textElements[] = $text;
                    }
                    else
                    {
                        // If no cache or expired cache, load data
                        $children = $functionChildren;

                        $childTextElements = array();
                        foreach ( array_keys( $children ) as $childKey )
                        {
                            $child =& $children[$childKey];
                            $tpl->processNode( $child, $childTextElements, $rootNamespace, $currentNamespace );
                        }
                        $text =& implode( '', $childTextElements );
                        $textElements[] = $text;

                        $phpCache->addVariable( 'contentData', $text );
                        $phpCache->store( true );
                    }
                }
            } break;
        }
    }

    /*!
     Returns true.
    */
    function hasChildren()
    {
        return true;
    }

    /// \privatesection
    /// Name of the function
    var $Name;
}

?>
