<?php
/**
 * File containing the eZTemplateCacheFunction class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZTemplateCacheFunction eztemplatecachefunction.php
  \ingroup eZTemplateFunctions
  \brief Advanced cache handling

*/

class eZTemplateCacheFunction
{
    const DEFAULT_TTL = 7200; // 2 hours = 60*60*2

    /**
     * Initializes the object with names.
     *
     * @param string $blockName
     */
    public function __construct( $blockName = 'cache-block' )
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
        $ignoreContentExpiry = false;

        if ( isset( $parameters['expiry'] ) )
        {
            if ( eZTemplateNodeTool::isConstantElement( $parameters['expiry'] ) )
            {
                $expiryValue = eZTemplateNodeTool::elementConstantValue( $parameters['expiry'] );
                $ttlCode = $expiryValue > 0 ? eZPHPCreator::variableText( $expiryValue , 0, 0, false ) : 'null';
            }
            else
            {
                $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $parameters['expiry'], false, array(), 'localExpiry' );
                $ttlCode = "( \$localExpiry > 0 ? \$localExpiry : null )";
            }
        }
        else
        {
            $ttlCode = eZPHPCreator::variableText( self::DEFAULT_TTL, 0, 0, false );
        }

        if ( isset( $parameters['ignore_content_expiry'] ) )
        {
            $ignoreContentExpiry = eZTemplateNodeTool::elementConstantValue( $parameters['ignore_content_expiry'] );
        }

        $name = '';
        $keysData = false;
        $hasKeys = false;
        $subtreeExpiryData = null;
        $subtreeValue = null;
        if ( isset( $parameters['name'] ) )
        {
            $name = $parameters['name'];
        }
        if ( isset( $parameters['keys'] ) )
        {
            $keysData = $parameters['keys'];
            $hasKeys = true;
        }
        if ( isset( $parameters['subtree_expiry'] ) )
        {
            $subtreeExpiryData = $parameters['subtree_expiry'];
            if ( !eZTemplateNodeTool::isConstantElement( $subtreeExpiryData ) )
                $hasKeys = true;
            else
                $subtreeValue = eZTemplateNodeTool::elementConstantValue( $subtreeExpiryData );

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
            $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $name, false, array(), 'name' );

            $code = '';
            if ( $name == '')
            {
                $code = "\$cacheKeys = array( \$cacheKeys, $placementKeyStringText, $accessNameText );\n";
            }
            else
            {
                $code = "\$cacheKeys = array( \$cacheKeys, $accessNameText );\n";
            }
            
            $cachePathText = "\$cachePath";
        }
        else
        {
            $nodeID = $subtreeValue ? eZTemplateCacheBlock::decodeNodeID( $subtreeValue ) : false;
            $cachePath = eZTemplateCacheBlock::cachePath( eZTemplateCacheBlock::keyString( array( $placementKeyString, $accessName ) ), $nodeID, $name );
            $code = "";
            $cachePathText = eZPHPCreator::variableText( $cachePath, 0, 0, false );
        }

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code );

        $code = '';

        $codePlacementHash = md5( $placementKeyString );
        if ( $hasKeys )
        {
            $code .= "list(\$cacheHandler_{$codePlacementHash}, \$contentData) =\n  eZTemplateCacheBlock::retrieve( \$cacheKeys, \$subtreeExpiry, $ttlCode, " . ($ignoreContentExpiry ? "false" : "true") . ", \$name );\n";
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
        $name                = '';
        $keys                = null;
        $subtreeExpiry       = null;
        $expiry              = self::DEFAULT_TTL;
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
        if ( isset( $functionParameters["name"] ) )
        {
            $name = $tpl->elementValue( $functionParameters["name"], $rootNamespace, $currentNamespace, $functionPlacement );
        }

        $placementString = eZTemplateCacheBlock::placementString( $functionPlacement );

        return eZTemplateCacheFunction::processCached( $tpl, $functionChildren, $rootNamespace, $currentNamespace,
                                                       $placementString, $name, $keys, $subtreeExpiry, $expiry, $ignoreContentExpiry );
    }

    function processCached( $tpl, $functionChildren, $rootNamespace, $currentNamespace,
                            $placementString, $name, $keys, $subtreeExpiry, $expiry, $ignoreContentExpiry )
    {
        // Fetch the current siteaccess
        $accessName = false;
        if ( isset( $GLOBALS['eZCurrentAccess']['name'] ) )
            $accessName = $GLOBALS['eZCurrentAccess']['name'];

        if ( $name == '')
        {
            if ( $keys === null )
            {
                $keyArray = array( $placementString, $accessName );
            }
            else
            {
                $keyArray = array( $keys, $placementString, $accessName );
            }
        }
        else
        {
            if ( $keys === null )
            {
                $keyArray = array( $accessName );
            }
            else
            {
                $keyArray = array( $keys, $accessName );
            }
        }

        $nodeID = $subtreeExpiry ? eZTemplateCacheBlock::decodeNodeID( $subtreeExpiry ) : false;
        $phpPath = eZTemplateCacheBlock::cachePath( eZTemplateCacheBlock::keyString( $keyArray ), $nodeID, $name );

        $ttl = $expiry > 0 ? $expiry : null;

        if ( $subtreeExpiry !== null )
        {
            $ignoreContentExpiry = true;
        }
        else if ( $ignoreContentExpiry === null )
        {
            $ignoreContentExpiry = false;
        }

        $globalExpiryTime = -1;
        if ( $ignoreContentExpiry == false )
        {
            $globalExpiryTime = eZExpiryHandler::getTimestamp( 'template-block-cache', -1 );
        }
  
        $globalExpiryTime = max( eZExpiryHandler::getTimestamp( 'global-template-block-cache', -1 ), // This expiry value is the true global expiry for cache-blocks
                                 $globalExpiryTime );

        // Check if we can restore
        $cacheFile = eZClusterFileHandler::instance( $phpPath );
        $args = array( "tpl" => $tpl,
                       "functionChildren" => $functionChildren,
                       "rootNamespace" => $rootNamespace,
                       "currentNamespace" => $currentNamespace );
        return $cacheFile->processCache( array( 'eZTemplateCacheBlock', 'retrieveContent' ),
                                         array( $this, 'generateProcessedContent' ),
                                         $ttl,
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

    /// \privatesection
    /// Name of the function
    public $BlockName;
}

?>
