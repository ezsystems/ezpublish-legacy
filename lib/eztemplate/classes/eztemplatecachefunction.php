<?php
//
// Definition of eZTemplateCacheFunction class
//
// Created on: <28-Feb-2003 15:06:33 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*!
  \class eZTemplateCacheFunction eztemplatecachefunction.php
  \ingroup eZTemplateFunctions
  \brief Advanced cache handling

*/

//include_once( 'lib/eztemplate/classes/eztemplatecacheblock.php' );

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

    function functionTemplateHints()
    {
        return array( $this->BlockName => array( 'parameters' => true,
                                                 'static' => false,
                                                 'transform-children' => true,
                                                 'tree-transformation' => true,
                                                 'transform-parameters' => true ) );
    }

    function templateNodeTransformation( $functionName, &$node,
                                         $tpl, $parameters, $privateData )
    {
        $ini = eZINI::instance();
        $children = eZTemplateNodeTool::extractFunctionNodeChildren( $node );
        if ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) != 'enabled' )
        {
            return $children;
        }

        $functionPlacement = eZTemplateNodeTool::extractFunctionNodePlacement( $node );
        $placementKeyString = eZTemplateCacheBlock::placementString( $functionPlacement );

        $newNodes = array();
        $ignoreExpiry = false;
        $ignoreContentExpiry = false;

        $expiry = 60*60*2;
        if ( isset( $parameters['expiry'] ) )
        {
            if ( eZTemplateNodeTool::isStaticElement( $parameters['expiry'] ) )
            {
                $expiryValue = eZTemplateNodeTool::elementStaticValue( $parameters['expiry'] );

                if ( $expiryValue )
                {
                    $expiryText = eZPHPCreator::variableText( $expiryValue , 0, 0, false );
                }
                else
                {
                    $ignoreExpiry = true;
                }
            }
            else
            {
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['expiry'], false, array(), 'localExpiry' );
                $expiryText = "\$localExpiry";
            }
        }
        else
        {
            $expiryText = eZPHPCreator::variableText( $expiry , 0, 0, false );
        }

        if ( isset( $parameters['ignore_content_expiry'] ) )
        {
            $ignoreContentExpiry = eZTemplateNodeTool::elementStaticValue( $parameters['ignore_content_expiry'] );
        }

        $keysData = false;
        $hasKeys = false;
        $subtreeExpiryData = null;
        $subtreeValue = null;
        if ( isset( $parameters['keys'] ) )
        {
            $keysData = $parameters['keys'];
            $hasKeys = true;
        }
        if ( isset( $parameters['subtree_expiry'] ) )
        {
            $subtreeExpiryData = $parameters['subtree_expiry'];
            if ( !eZTemplateNodeTool::isStaticElement( $subtreeExpiryData ) )
                $hasKeys = true;
            else
                $subtreeValue = eZTemplateNodeTool::elementStaticValue( $subtreeExpiryData );

            $ignoreContentExpiry = true;
        }
        $accessName = false;
        if ( isset( $GLOBALS['eZCurrentAccess']['name'] ) )
            $accessName = $GLOBALS['eZCurrentAccess']['name'];
        if ( $hasKeys )
        {
            $placementKeyStringText = eZPHPCreator::variableText( $placementKeyString, 0, 0, false );
            $accessNameText = eZPHPCreator::variableText( $accessName, 0, 0, false );
            $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $keysData, false, array(), 'cacheKeys' );
            $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $subtreeExpiryData, false, array(), 'subtreeExpiry' );

            $code = ( "//include_once( 'lib/eztemplate/classes/eztemplatecacheblock.php' );\n" .
                      "\$cacheKeys = array( \$cacheKeys, $placementKeyStringText, $accessNameText );\n" );
            $cachePathText = "\$cachePath";
        }
        else
        {
            $nodeID = eZTemplateCacheBlock::decodeNodeID( $subtreeValue );
            $cachePath = eZTemplateCacheBlock::cachePath( eZTemplateCacheBlock::keyString( array( $placementKeyString, $accessName ) ), $nodeID );
            $code = ( "//include_once( 'lib/eztemplate/classes/eztemplatecacheblock.php' );\n" );
            $cachePathText = eZPHPCreator::variableText( $cachePath, 0, 0, false );
        }

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code );

        $code = '';

        $ttlCode = 'null';
        if ( !$ignoreExpiry )
        {
            $ttlCode = "$expiryText";
        }
        $codePlacementHash = md5( $placementKeyString );
        if ( $hasKeys )
        {
            $code .= "list(\$cacheHandler_{$codePlacementHash}, \$contentData) =\n  eZTemplateCacheBlock::retrieve( \$cacheKeys, \$subtreeExpiry, $ttlCode, " . ($ignoreContentExpiry ? "false" : "true") . " );\n";
        }
        else
        {
            $nodeIDText = var_export( $nodeID, true );
            $code .= "list(\$cacheHandler_{$codePlacementHash}, \$contentData) =\n  eZTemplateCacheBlock::handle( $cachePathText, $nodeIDText, $ttlCode, " . ($ignoreContentExpiry ? "false" : "true") . " );\n";
        }
        $code .=
            "if ( !( \$contentData instanceof eZClusterFileFailure ) )\n" .
            "{\n";

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code, array( 'spacing' => 0 ) );
        $newNodes[] = eZTemplateNodeTool::createWriteToOutputVariableNode( 'contentData', array( 'spacing' => 4 ) );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "    unset( \$contentData );\n" .
                                                               "}\n" .
                                                               "else\n" .
                                                               "{\n" .
                                                               "    unset( \$contentData );" );

        $newNodes[] = eZTemplateNodeTool::createOutputVariableIncreaseNode( array( 'spacing' => 4 ) );
        $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode( 4 );
        $newNodes = array_merge( $newNodes, $children );
        $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode( 4 );
        $newNodes[] = eZTemplateNodeTool::createAssignFromOutputVariableNode( 'cachedText', array( 'spacing' => 4 ) );

        $code =
            "\$cacheHandler_{$codePlacementHash}->storeCache( array( 'scope' => 'template-block', 'binarydata' => \$cachedText ) );\n";

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code, array( 'spacing' => 4 ) );
        $newNodes[] = eZTemplateNodeTool::createOutputVariableDecreaseNode( array( 'spacing' => 4 ) );
        $newNodes[] = eZTemplateNodeTool::createWriteToOutputVariableNode( 'cachedText', array( 'spacing' => 4 ) );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "    unset( \$cachedText, \$cacheHandler_{$codePlacementHash} );\n}\n" );

        return $newNodes;
    }

    /*!
     Processes the function with all it's children.
    */
    function process( $tpl, &$textElements, $functionName, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        switch ( $functionName )
        {
            case $this->BlockName:
            {
                // Check for disabled cache.
                $ini = eZINI::instance();
                if ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) != 'enabled' )
                {
                    $text = eZTemplateCacheFunction::processUncached( $tpl, $functionChildren,
                                                                      $rootNamespace, $currentNamespace );
                    $textElements[] = $text;
                    return;
                }
                else
                {
                    $text = eZTemplateCacheFunction::processCachedPreprocess( $tpl,  $functionChildren,
                                                                              $functionParameters, $functionPlacement,
                                                                              $rootNamespace, $currentNamespace );
                    $textElements[] = $text;
                }
            } break;
        }
    }

    function processCachedPreprocess( $tpl, $functionChildren, $functionParameters, $functionPlacement, $rootNamespace, $currentNamespace )
    {
        $keys                = null;
        $subtreeExpiry       = null;
        $expiry              = null;
        $ignoreContentExpiry = null;
        $subtreeExpiry       = null;

        if ( isset( $functionParameters["keys"] ) )
        {
            $keys = $tpl->elementValue( $functionParameters["keys"], $rootNamespace, $currentNamespace, $functionPlacement );
        }
        if ( isset( $functionParameters['subtree_expiry'] ) )
        {
            $subtreeExpiry = $tpl->elementValue( $functionParameters["subtree_expiry"], $rootNamespace, $currentNamespace, $functionPlacement );
        }
        if ( isset( $functionParameters["expiry"] ) )
        {
            $expiry = $tpl->elementValue( $functionParameters["expiry"], $rootNamespace, $currentNamespace, $functionPlacement );
        }
        if ( isset( $functionParameters["ignore_content_expiry"] ) )
        {
            $ignoreContentExpiry = $tpl->elementValue( $functionParameters["ignore_content_expiry"], $rootNamespace, $currentNamespace, $functionPlacement ) === true;
        }
        if ( isset( $functionParameters['subtree_expiry'] ) )
        {
            $ignoreContentExpiry = true;
        }

        $placementString = eZTemplateCacheBlock::placementString( $functionPlacement );

        return eZTemplateCacheFunction::processCached( $tpl, $functionChildren, $rootNamespace, $currentNamespace,
                                                       $placementString, $keys, $subtreeExpiry, $expiry, $ignoreContentExpiry, $subtreeExpiry
                       );
    }

    function processCached( $tpl, $functionChildren, $rootNamespace, $currentNamespace,
                            $placementString, $keys, $subtreeExpiry, $expiry, $ignoreContentExpiry, $subtreeExpiry )
    {
        // Fetch the current siteaccess
        $accessName = false;
        if ( isset( $GLOBALS['eZCurrentAccess']['name'] ) )
            $accessName = $GLOBALS['eZCurrentAccess']['name'];
        if ( $keys === null )
        {
            $keyArray = array( $placementString, $accessName );
        }
        else
        {
            $keyArray = array( $keys, $placementString, $accessName );
        }

        $nodeID = eZTemplateCacheBlock::decodeNodeID( $subtreeExpiry );
        $phpPath = eZTemplateCacheBlock::cachePath( eZTemplateCacheBlock::keyString( $keyArray ), $nodeID );

        // Check if a custom expiry time is defined
        if ( $expiry === null )
        {
            // Default expiry time is set to two hours
            $expiry = 60*60*2;
        }

        $ignoreContentExpiry = false;
        if ( $ignoreContentExpiry === null )
        {
            $ignoreContentExpiry = false;
        }
        if ( $subtreeExpiry !== null )
        {
            $ignoreContentExpiry = true;
        }

        $globalExpiryTime = -1;
        eZExpiryHandler::registerShutdownFunction();
        if ( $ignoreContentExpiry == false )
        {
            $globalExpiryTime = eZExpiryHandler::getTimestamp( 'template-block-cache', -1 );
        }
        $globalExpiryTime = max( eZExpiryHandler::getTimestamp( 'global-template-block-cache', -1 ), // This expiry value is the true global expiry for cache-blocks
                                 $globalExpiryTime );

        // Check if we can restore
        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $cacheFile = eZClusterFileHandler::instance( $phpPath );
        $args = array( "tpl" => $tpl,
                       "functionChildren" => $functionChildren,
                       "rootNamespace" => $rootNamespace,
                       "currentNamespace" => $currentNamespace );
        return $cacheFile->processCache( array( 'eZTemplateCacheBlock', 'retrieveContent' ),
                                         array( $this, 'generateProcessedContent' ),
                                         $expiry,
                                         $globalExpiryTime,
                                         $args );
    }

    function generateProcessedContent( $fname, $args )
    {
        extract( $args );
        $content = eZTemplateCacheFunction::processUncached( $tpl, $functionChildren, $rootNamespace, $currentNamespace );
        return array( 'scope'      => 'template-block',
                      'content'    => $content,
                      'binarydata' => $content );
    }

    /*!
     \private
     \static
     Performs processing of the cache-block using the non-compiled way and with caching off.
     */
    function processUncached( $tpl, $functionChildren, $rootNamespace, $currentNamespace )
    {
        $children = $functionChildren;

        $childTextElements = array();
        if ( is_array( $children ) )
        {
            foreach ( array_keys( $children ) as $childKey )
            {
                $child =& $children[$childKey];
                $tpl->processNode( $child, $childTextElements, $rootNamespace, $currentNamespace );
            }
        }
        $text = implode( '', $childTextElements );
        return $text;
    }

    /*!
     Returns true.
    */
    function hasChildren()
    {
        return true;
    }

    // Deprecated functions follow

    /*!
     \static
     \deprecated
     Returns base directory where 'subtree_expiry' caches are stored.
    */
    static function subtreeCacheBaseSubDir()
    {
        return eZTemplateCacheBlock::subtreeCacheBaseSubDir();
    }

    /*!
     \static
     \deprecated Does not seem to be used
     Returns base directory where expired 'subtree_expiry' caches are stored.
    */
    static function expiryTemplateBlockCacheDir()
    {
        //include_once( 'lib/ezutils/classes/ezsys.php' );
        $expiryCacheDir = eZSys::cacheDirectory() . '/' . 'template-block-expiry';
        return $expiryCacheDir;
    }

    /*!
     \static
     \deprecated
     Returns base directory where template block caches are stored.
    */
    static function templateBlockCacheDir()
    {
        return eZTemplateCacheBlock::templateBlockCacheDir();
    }

    /*!
     \static
     \deprecated
     Returns path of the directory where 'subtree_expiry' caches are stored.
    */
    static function subtreeCacheSubDir( $subtreeExpiryParameter, $cacheFilename )
    {
        return eZTemplateCacheBlock::subtreeCacheSubDir( $subtreeExpiryParameter, $cacheFilename );
    }

    /*!
     \static
     \deprecated
     Builds and returns path from $nodeID, e.g. if $nodeID = 23 then path = subtree/2/3
    */
    static function subtreeCacheSubDirForNode( $nodeID )
    {
        return eZTemplateCacheBlock::subtreeCacheSubDirForNode( $nodeID );
    }

    /// \privatesection
    /// Name of the function
    public $BlockName;
}

?>
