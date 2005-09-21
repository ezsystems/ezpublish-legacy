<?php
//
// Definition of eZTemplateCacheFunction class
//
// Created on: <28-Feb-2003 15:06:33 bf>
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

    function functionTemplateHints()
    {
        return array( $this->BlockName => array( 'parameters' => true,
                                                 'static' => false,
                                                 'transform-children' => true,
                                                 'tree-transformation' => true,
                                                 'transform-parameters' => true ) );
    }

    function templateNodeTransformation( $functionName, &$node,
                                         &$tpl, $parameters, $privateData )
    {
        $ini =& eZINI::instance();
        $children = eZTemplateNodeTool::extractFunctionNodeChildren( $node );
        if ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) != 'enabled' )
        {
            return $children;
        }

        $functionPlacement = eZTemplateNodeTool::extractFunctionNodePlacement( $node );
        $placementKeyString  = $functionPlacement[0][0] . "_";
        $placementKeyString .= $functionPlacement[0][1] . "_";
        $placementKeyString .= $functionPlacement[1][0] . "_";
        $placementKeyString .= $functionPlacement[1][1] . "_";
        $placementKeyString .= $functionPlacement[2] . "_";

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
        $keyValue = false;
        $keyValueText = false;
        $useDynamicKeys = false;
        $subtreeExpiryData = false;
        if ( isset( $parameters['keys'] ) )
        {
            $keysData = $parameters['keys'];
            if ( !eZTemplateNodeTool::isStaticElement( $keysData ) )
            {
                $useDynamicKeys = true;
            }
            else
            {
                $keyValue = eZTemplateNodeTool::elementStaticValue( $keysData );
                $keyValueText = $keyValue . '_';
            }
        }
        if ( isset( $parameters['subtree_expiry'] ) )
        {
            $subtreeExpiryData = $parameters['subtree_expiry'];
            if ( !eZTemplateNodeTool::isStaticElement( $subtreeExpiryData ) )
                $useDynamicKeys = true;
            else
                $subtreeValue = eZTemplateNodeTool::elementStaticValue( $subtreeExpiryData );

            $ignoreContentExpiry = true;
        }
        if ( $useDynamicKeys )
        {
            $accessName = false;
            if ( isset( $GLOBALS['eZCurrentAccess']['name'] ) )
                $accessName = $GLOBALS['eZCurrentAccess']['name'];
            $extraKeyString = $placementKeyString . $accessName;
            $extraKeyText = eZPHPCreator::variableText( $extraKeyString, 0, 0, false );
            $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $keysData, false, array(), 'cacheKeys' );
            $newNodes[] = eZTemplateNodeTool::createVariableNode( false, $subtreeExpiryData, false, array(), 'subtreeExpiry' );
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( is_array( \$cacheKeys ) )\n    \$cacheKeys = implode( '_', \$cacheKeys ) . '_';\nelse\n    \$cacheKeys .= '_';" );
            $cacheDir = eZTemplateCacheFunction::templateBlockCacheDir();
            $cachePathText = eZPHPCreator::variableText( "$cacheDir", 0, 0, false );
            $code = ( "\$keyString = sprintf( '%u', crc32( \$cacheKeys . $extraKeyText ) );\n" .
                      "\$cacheFilename = \$keyString . '.cache';\n" .
                      "if ( isset( \$subtreeExpiry ) && \$subtreeExpiry !== false )\n" .
                      "{\n" .
                      "    include_once( 'lib/eztemplate/classes/eztemplatecachefunction.php' );\n" .
                      "    \$cacheDir = $cachePathText . eZTemplateCacheFunction::subtreeCacheSubDir( \$subtreeExpiry, \$cacheFilename );\n" .
                      "}\n" .
                      "else\n" .
                      "{\n" .
                      "    \$cacheDir = $cachePathText . \$keyString[0] . '/' . \$keyString[1] . '/' . \$keyString[2];\n" .
                      "}\n" .
                      "\$cachePath = \$cacheDir . '/' . \$cacheFilename;" );
        }
        else
        {
            $accessName = false;
            if ( isset( $GLOBALS['eZCurrentAccess']['name'] ) )
                $accessName = $GLOBALS['eZCurrentAccess']['name'];
            $keyString = sprintf( '%u', crc32( $keyValueText . $placementKeyString . $accessName ) );
            $cacheFilename = $keyString . '.cache';
            $cacheDir = eZTemplateCacheFunction::templateBlockCacheDir();
            if ( isset( $subtreeValue ) )
                $cacheDir = "$cacheDir" . eZTemplateCacheFunction::subtreeCacheSubDir( $subtreeValue, $cacheFilename );
            else
                $cacheDir = "$cacheDir" . $keyString[0] . '/' . $keyString[1] . '/' . $keyString[2];

            $cachePath = "$cacheDir" . '/' . $cacheFilename;
            $code = ( "\$keyString = '$keyString';\n" .
                      "\$cacheDir = '$cacheDir';\n" .
                      "\$cachePath = '$cachePath';" );
        }

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code );
        $filedirText = "\$cacheDir";
        $filepathText = "\$cachePath";

        $code = '';

        if ( !$ignoreContentExpiry )
        {
            $code .= <<<ENDADDCODE
include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
\$handler =& eZExpiryHandler::instance();
\$globalExpiryTime = -1;
if ( \$handler->hasTimestamp( 'template-block-cache' ) )
{
    \$globalExpiryTime = \$handler->timestamp( 'template-block-cache' );
}

ENDADDCODE;
        }

        $code .= "if ( file_exists( $filepathText )";
        if ( !$ignoreExpiry ) {
            $code .= "\n    and filemtime( $filepathText ) >= ( time() - $expiryText )";
        }
        if ( !$ignoreContentExpiry ) {
            $code .= "\n    and ( ( filemtime( $filepathText ) > \$globalExpiryTime ) or ( \$globalExpiryTime == -1 ) )";
        }
        $code .= " )\n" .
                 "{\n" .
                 "    \$fp = fopen( $filepathText, 'r' );\n" .
                 "    \$size = filesize( $filepathText );\n" .
                 "    \$contentData = \$size ? fread( \$fp, \$size ) : '';\n" .
                 "    fclose( \$fp );\n";

        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code, array( 'spacing' => 0 ) );
        $newNodes[] = eZTemplateNodeTool::createWriteToOutputVariableNode( 'contentData', array( 'spacing' => 4 ) );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "    unset( \$contentData );\n" .
                                                               "}\n" .
                                                               "else\n" .
                                                               "{" );
        $newNodes[] = eZTemplateNodeTool::createOutputVariableIncreaseNode( array( 'spacing' => 4 ) );
        $newNodes[] = eZTemplateNodeTool::createSpacingIncreaseNode( 4 );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if ( !isset( \$cacheStack ) )\n" .
                                                               "    \$cacheStack = array();\n" .
                                                               "\$cacheEntry = array( \$cacheDir, \$cachePath, \$keyString, false );\n" .
                                                               "if ( isset( \$subtreeExpiry ) )\n" .
                                                               "    \$cacheEntry[3] = \$subtreeExpiry;\n" .
                                                               "\$cacheStack[] = \$cacheEntry;" );
        $newNodes = array_merge( $newNodes, $children );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "list( \$cacheDir, \$cachePath, \$keyString, \$subtreeExpiry ) = array_pop( \$cacheStack );" );
        $newNodes[] = eZTemplateNodeTool::createSpacingDecreaseNode( 4 );
        $newNodes[] = eZTemplateNodeTool::createAssignFromOutputVariableNode( 'cachedText', array( 'spacing' => 4 ) );
        $ini =& eZINI::instance();
        $perm = octdec( $ini->variable( 'FileSettings', 'StorageDirPermissions' ) );
        $code = ( "include_once( 'lib/ezfile/classes/ezdir.php' );\n" .
                  "\$uniqid = md5( uniqid( 'ezpcache'. getmypid(), true ) );\n" .
                  "eZDir::mkdir( $filedirText, $perm, true );\n" .
                  "\$fd = fopen( $filedirText. '/'. \$uniqid, 'w' );\n" .
                  "fwrite( \$fd, \$cachedText );\n" .
                  "fclose( \$fd );\n" );
        /* On windows we need to unlink the destination file first */
        if ( strtolower( substr( PHP_OS, 0, 3 ) ) == 'win' )
        {
            $code .= "@unlink( $filepathText );\n";
        }
        $code .= "rename( $filedirText. '/'. \$uniqid, $filepathText );\n";
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code, array( 'spacing' => 4 ) );
        $newNodes[] = eZTemplateNodeTool::createOutputVariableDecreaseNode( array( 'spacing' => 4 ) );
        $newNodes[] = eZTemplateNodeTool::createWriteToOutputVariableNode( 'cachedText', array( 'spacing' => 4 ) );
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "    unset( \$cachedText );\n}" );
        return $newNodes;
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
                if ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) != 'enabled' )
                {
                    $children = $functionChildren;

                    $childTextElements = array();
                    foreach ( array_keys( $children ) as $childKey )
                    {
                        $child =& $children[$childKey];
                        $tpl->processNode( $child, $childTextElements, $rootNamespace, $currentNamespace );
                    }
                    $text = implode( '', $childTextElements );
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
                    $accessName = false;
                    if ( isset( $GLOBALS['eZCurrentAccess']['name'] ) )
                        $accessName = $GLOBALS['eZCurrentAccess']['name'];
                    $keyString .= $accessName;
                    $hashedKey = sprintf( '%u', crc32( $keyString ) );
                    $cacheFilename = $hashedKey . ".cache";

                    $phpDir = eZTemplateCacheFunction::templateBlockCacheDir();
                    if ( isset( $functionParameters['subtree_expiry'] ) )
                        $phpDir .= eZTemplateCacheFunction::subtreeCacheSubDir( $tpl->elementValue( $functionParameters["subtree_expiry"], $rootNamespace, $currentNamespace, $functionPlacement ), $cacheFilename );
                    else
                        $phpDir .= $hashedKey[0] . '/' . $hashedKey[1] . '/' . $hashedKey[2];

                    $phpPath = $phpDir . '/' . $cacheFilename;

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

                    $localExpiryTime = time() - $expiry;

                    $ignoreContentExpiry = false;
                    if ( isset( $functionParameters["ignore_content_expiry"] ) )
                    {
                        $ignoreContentExpiry = $tpl->elementValue( $functionParameters["ignore_content_expiry"], $rootNamespace, $currentNamespace, $functionPlacement ) === true;
                    }
                    if ( isset( $functionParameters['subtree_expiry'] ) )
                    {
                        $ignoreContentExpiry = true;
                    }

                    $expiryTime = $localExpiryTime;
                    if ( $ignoreContentExpiry == false )
                    {
                        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
                        $handler =& eZExpiryHandler::instance();
                        if ( $handler->hasTimestamp( 'template-block-cache' ) )
                        {
                            $globalExpiryTime = $handler->timestamp( 'template-block-cache' );
                            $expiryTime = max( $localExpiryTime, $globalExpiryTime );
                        }
                    }

                    // Check if we can restore
                    if ( file_exists( $phpPath ) and
                         filemtime( $phpPath ) >= $expiryTime )
                    {
                        $fp = fopen( $phpPath, 'r' );
                        $textElements[] = fread( $fp, filesize( $phpPath ) );;
                        fclose( $fp );
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
                        $text = implode( '', $childTextElements );
                        $textElements[] = $text;

                        include_once( 'lib/ezfile/classes/ezfile.php' );
                        $ini =& eZINI::instance();
                        $perm = octdec( $ini->variable( 'FileSettings', 'StorageDirPermissions' ) );
                        $uniqid = md5( uniqid( 'ezpcache'. getmypid(), true ) );
                        eZDir::mkdir( $phpDir, $perm, true );
                        $fd = fopen( "$phpDir/$uniqid", 'w' );
                        fwrite( $fd, $text );
                        fclose( $fd );
                        eZFile::rename( "$phpDir/$uniqid", $phpPath );
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

    /*!
     \static
     Returns base directory where 'subtree_expiry' caches are stored.
    */
    function subtreeCacheBaseSubDir()
    {
        return 'subtree';
    }

    /*!
     \static
     Returns base directory where expired 'subtree_expiry' caches are stored.
    */
    function expiryTemplateBlockCacheDir()
    {
        $expiryCacheDir = eZSys::cacheDirectory() . '/' . 'template-block-expiry';
        return $expiryCacheDir;
    }

    /*!
     \static
     Returns base directory where template block caches are stored.
    */
    function templateBlockCacheDir()
    {
        $cacheDir = eZSys::cacheDirectory() . '/template-block/' ;
        return $cacheDir;
    }

    /*!
     \static
     Returns path of the directory where 'subtree_expiry' caches are stored.
    */
    function subtreeCacheSubDir( $subtreeExpiryParameter, $cacheFilename )
    {
        $nodePathString = '';

        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();

        // clean up $subtreeExpiryParameter
        $subtreeExpiryParameter = trim( $subtreeExpiryParameter, '/' );

        // get 'path_stirng' attribute for node.
        $nodeID = false;
        $subtree = $db->escapeString( $subtreeExpiryParameter );

        if ( $subtree == '' )
        {
            // 'subtree_expiry' is empty => use root node.
            $nodeID = 2;
        }
        else
        {
            $nonAliasPath = 'content/view/full/';

            if ( strpos( $subtree, $nonAliasPath ) === 0 )
            {
                // 'subtree_expiry' is like 'content/view/full/2'
                $nodeID = substr( $subtree, strlen( $nonAliasPath ) );
            }
            else
            {
                // 'subtree_expiry' is url_alias
                $nodePathStringSQL = "SELECT node_id FROM ezcontentobject_tree WHERE path_identification_string='$subtree'";
                $nodes = $db->arrayQuery( $nodePathStringSQL );
                if ( count( $nodes ) != 1 )
                {
                    eZDebug::writeError( 'Could not find path_string for \'subtree_expiry\' node.', 'eZTemplateCacheFunction::subtreeExpiryCacheDir()' );
                }
                else
                {
                    $nodeID = $nodes[0]['node_id'];
                }
            }
        }

        $cacheDir = eZTemplateCacheFunction::subtreeCacheSubDirForNode( $nodeID );
        $cacheDir .= '/' . $cacheFilename[0] . '/' . $cacheFilename[1] . '/' . $cacheFilename[2];

        return $cacheDir;
    }

    /*!
     \static
     Builds and returns path from $nodeID, e.g. if $nodeID = 23 then path = subtree/2/3
    */
    function subtreeCacheSubDirForNode( $nodeID )
    {
        $cacheDir = eZTemplateCacheFunction::subtreeCacheBaseSubDir();

        if ( is_numeric( $nodeID ) )
        {
            $nodeID = (string)$nodeID;
            $length = strlen( $nodeID );
            $pos = 0;
            while ( $pos < $length )
            {
                $cacheDir .= '/' . $nodeID[$pos];
                ++$pos;
            }
        }
        else
        {
            eZDebug::writeWarning( "Unable to determine cacheDir for nodeID = $nodeID", 'eZtemplateCacheFunction::subtreeCacheSubDirForNode' );
        }

        $cacheDir .= '/cache';
        return $cacheDir;
    }

    /// \privatesection
    /// Name of the function
    var $BlockName;
}

?>
