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
        if ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) == 'disabled' )
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
        $subtreeExpiryCode = false;
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
            {
                $useDynamicKeys = true;
            }
            else
            {
                $subtreeValue = eZTemplateNodeTool::elementStaticValue( $subtreeExpiryData );
                if ( substr( $subtreeValue, -1 ) != '/')
                {
                    $subtreeValue .= '/';
                }
            }
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
            $cacheDir = eZSys::cacheDirectory();
            $cachePathText = eZPHPCreator::variableText( "$cacheDir/template-block/", 0, 0, false );
            $code = "\$keyString = sprintf( '%u', crc32( \$cacheKeys . $extraKeyText ) );\n\$cacheDir = $cachePathText . \$keyString[0] . '/' . \$keyString[1] . '/' . \$keyString[2];\n\$cachePath = \$cacheDir . '/' . \$keyString . '.cache';";
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code );
            $filedirText = "\$cacheDir";
            $filepathText = "\$cachePath";

            $subtreeExpiryCode = ( "if ( isset( \$subtreeExpiry ) && \$subtreeExpiry )\n" .
                                   "{\n" .
                                   "    include_once( 'lib/ezdb/classes/ezdb.php' );\n" .
                                   "    \$db =& eZDB::instance();\n" .
                                   "\n" .
                                   "    if ( substr( \$subtreeExpiry, -1 ) == '/'  )\n" .
                                   "    {\n" .
                                   "        \$subtreeExpiry .= substr( \$subtreeExpiry, 0, -1 );\n" .
                                   "    }\n" .
                                   "    \$subtree =& \$db->escapeString( \$subtreeExpiry );\n" .
                                   "    \$nonAliasPath = 'content/view/full/';\n" .
                                   "    if ( strpos( \$subtreeExpiry, \$nonAliasPath ) === 0 )\n" .
                                   "    {\n" .
                                   "        \$subtreeNodeID = substr( \$subtree, strlen( \$nonAliasPath ) );\n" .
                                   "    }\n" .
                                   "    else\n" .
                                   "    {\n" .
                                   "        \$subtreeNodeIDSQL = \"SELECT node_id FROM ezcontentobject_tree WHERE path_identification_string='\$subtree'\";\n" .
                                   "        \$subtreeNodeID =& \$db->arrayQuery( \$subtreeNodeIDSQL );\n" .
                                   "        if ( count( \$subtreeNodeID ) == 1 )\n" .
                                   "        {\n" .
                                   "            \$subtreeNodeID = \$subtreeNodeID[0]['node_id'];\n" .
                                   "        }\n" .
                                   "    }\n" .
                                   "    \$cacheKey =& \$db->escapeString( \$cachePath );\n" .
                                   "\n" .
                                   "    \$insertQuery = \"INSERT INTO ezsubtree_expiry ( subtree, cache_file )\n" .
                                   "                VALUES ( '\$subtreeNodeID', '\$cacheKey' )\";\n" .
                                   "    \$db->query( \$insertQuery );\n" .
                                   "}" );
        }
        else
        {
            $accessName = false;
            if ( isset( $GLOBALS['eZCurrentAccess']['name'] ) )
                $accessName = $GLOBALS['eZCurrentAccess']['name'];
            $keyString = sprintf( '%u', crc32( $keyValueText . $placementKeyString . $accessName ) );
            $cacheDir = eZSys::cacheDirectory();
            $dirString = "$cacheDir/template-block/" . $keyString[0] . '/' . $keyString[1] . '/' . $keyString[2];
            $keyString = "$cacheDir/template-block/" . $keyString[0] . '/' . $keyString[1] . '/' . $keyString[2] . '/' . $keyString . '.cache';
            $filedirText = eZPHPCreator::variableText( $dirString, 0, 0, false );
            $filepathText = eZPHPCreator::variableText( $keyString, 0, 0, false );

            if ( isset( $parameters['subtree_expiry'] ) && ( $parameters['subtree_expiry'] ) )
            {
                include_once( 'lib/ezdb/classes/ezdb.php' );
                $db =& eZDB::instance();

                if ( substr( $subtreeValue, -1 ) == '/' )
                {
                    $subtreeValue = substr( $subtreeValue, 0, -1 );
                }
                $subtree =& $db->escapeString( $subtreeValue );
                $nonAliasPath = 'content/view/full/';
                if ( strpos( $subtree, $nonAliasPath ) === 0 )
                {
                    $subtreeNodeID = substr( $subtree, strlen( $nonAliasPath ) );
                }
                else
                {
                    $subtreeNodeIDSQL = "SELECT node_id FROM ezcontentobject_tree WHERE path_identification_string='$subtree'";
                    $subtreeNodeID =& $db->arrayQuery( $subtreeNodeIDSQL );
                    if ( count( $subtreeNodeID ) != 1 )
                    {
                        eZDebug::writeError( 'Could not find path_string for node.', 'eZTemplateCacheFunction::process()' );
                        break;
                    }
                    else
                    {
                        $subtreeNodeID = $subtreeNodeID[0]['node_id'];
                    }
                }
                $cacheKey =& $db->escapeString( $keyString );

                $insertQuery = "INSERT INTO ezsubtree_expiry ( subtree, cache_file )
                            VALUES ( '$subtreeNodeID', '$cacheKey' )";

                $subtreeExpiryCode = ( "include_once( 'lib/ezdb/classes/ezdb.php' );\n" .
                                       "\$db =& eZDB::instance();\n" .
                                       "\n" .
                                       "\$db->query( \"$insertQuery\" );\n" .
                                       "            " );
            }
        }

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
                 "    \$contentData = fread( \$fp, filesize( $filepathText ) );\n" .
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
        if ( $subtreeExpiryCode )
        {
            $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $subtreeExpiryCode, array( 'spacing' => 4 ) );
        }
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
                    $accessName = false;
                    if ( isset( $GLOBALS['eZCurrentAccess']['name'] ) )
                        $accessName = $GLOBALS['eZCurrentAccess']['name'];
                    $keyString .= $accessName;
                    $hashedKey = sprintf( '%u', crc32( $keyString ) );

                    $cacheDir = eZSys::cacheDirectory();
                    $phpDir = "$cacheDir/template-block/" . $hashedKey[0] . "/" . $hashedKey[1] . "/" . $hashedKey[2];
                    $phpPath = $phpDir . '/' . $hashedKey . ".cache";

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
                        $text =& implode( '', $childTextElements );
                        $textElements[] = $text;

                        include_once( 'lib/ezfile/classes/ezdir.php' );
                        include_once( 'lib/ezfile/classes/ezfile.php' );
                        $ini =& eZINI::instance();
                        $perm = octdec( $ini->variable( 'FileSettings', 'StorageDirPermissions' ) );
                        $uniqid = md5( uniqid( 'ezpcache'. getmypid(), true ) );
                        eZDir::mkdir( $phpDir, $perm, true );
                        $fd = fopen( "$phpDir/$uniqid", 'w' );
                        fwrite( $fd, $text );
                        fclose( $fd );
                        eZFile::rename( "$phpDir/$uniqid", $phpPath );
                        if ( isset( $functionParameters['subtree_expiry'] ) )
                        {
                            include_once( 'lib/ezdb/classes/ezdb.php' );
                            $db =& eZDB::instance();

                            $subtreeExpiry = $tpl->elementValue( $functionParameters["subtree_expiry"], $rootNamespace, $currentNamespace, $functionPlacement );
                            $subtreeValue =& $db->escapeString( $subtreeExpiry );
                            if ( substr( $subtreeExpiry, -1 ) == '/' )
                            {
                                $subtreeExpiry = substr( $subtreeExpiry, 0, -1 );
                            }
                            $subtree =& $db->escapeString( $subtreeExpiry );
                            $nonAliasPath = 'content/view/full/';
                            if ( strpos( $subtree, $nonAliasPath ) === 0 )
                            {
                                $subtreeNodeID = substr( $subtree, strlen( $nonAliasPath ) );
                            }
                            else
                            {
                                $subtreeNodeIDSQL = "SELECT node_id FROM ezcontentobject_tree WHERE path_identification_string='$subtree'";
                                $subtreeNodeID =& $db->arrayQuery( $subtreeNodeIDSQL );
                                if ( count( $subtreeNodeID ) != 1 )
                                {
                                    eZDebug::writeError( 'Could not find path_string for node.', 'eZTemplateCacheFunction::process()' );
                                    break;
                                }
                                else
                                {
                                    $subtreeNodeID = $subtreeNodeID[0]['node_id'];
                                }
                            }

                            $cacheKey =& $db->escapeString( $phpPath );

                            $insertQuery = "INSERT INTO ezsubtree_expiry ( subtree, cache_file )
                                        VALUES ( '$subtreeNodeID', '$cacheKey' )";
                            $db->query( $insertQuery );
                        }
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
    var $BlockName;
}

?>
