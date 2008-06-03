<?php
//
// Created on: <08-Aug-2003 11:27:10 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

/*! \file urltranslator.php
*/
require_once( 'kernel/common/template.php' );
//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
//include_once( 'kernel/classes/ezurlaliasml.php' );
//include_once( 'kernel/classes/ezpathelement.php' );

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$NodeID = $Params['NodeID'];
$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

//include_once( 'kernel/classes/ezsslzone.php' );
eZSSLZone::checkNodeID( 'content', 'urlalias', $NodeID );

$tpl = templateInit();
$limit = 20;

$node = eZContentObjectTreeNode::fetch( $NodeID );
if ( !$node )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$infoCode = 'no-errors'; // This will be modified if info/warning is given to user.
$infoData = array(); // Extra parameters can be added to this array
$aliasText = false;

if ( $Module->isCurrentAction( 'RemoveAllAliases' ) )
{
    //include_once( 'kernel/classes/ezurlaliasquery.php' );
    $filter = new eZURLAliasQuery();
    $filter->actions = array( 'eznode:' . $node->attribute( 'node_id' ) );
    $filter->type = 'alias';
    $filter->offset = 0;
    $filter->limit = 50;

    while ( true )
    {
        $aliasList = $filter->fetchAll();
        if ( count( $aliasList ) == 0 )
            break;
        foreach ( $aliasList as $alias )
        {
            $parentID = (int)$alias->attribute( 'parent' );
            $textMD5  = $alias->attribute( 'text_md5' );
            $language = $alias->attribute( 'language_object' );
            eZURLAliasML::removeSingleEntry( $parentID, $textMD5, $language );
        }
        $filter->prepare();
    }
    $infoCode = "feedback-removed-all";
}
else if ( $Module->isCurrentAction( 'RemoveAlias' ) )
{
    if ( $http->hasPostVariable( 'ElementList' ) )
    {
        $elementList = $http->postVariable( 'ElementList' );
        if ( !is_array( $elementList ) )
            $elementList = array();
        foreach ( $elementList as $element )
        {
            if ( preg_match( "#^([0-9]+).([a-fA-F0-9]+).([a-zA-Z0-9-]+)$#", $element, $matches ) )
            {
                $parentID = (int)$matches[1];
                $textMD5  = $matches[2];
                $language = $matches[3];
                eZURLAliasML::removeSingleEntry( $parentID, $textMD5, $language );
            }
        }
        $infoCode = "feedback-removed";
    }
}
else if ( $Module->isCurrentAction( 'NewAlias' ) )
{
    $aliasText = trim( $Module->actionParameter( 'AliasText' ) );
    $isRelative = $http->hasPostVariable( 'RelativeAlias' ) && strlen( trim( $http->postVariable( 'RelativeAlias' ) ) ) > 0;
    $languageCode = $Module->actionParameter( 'LanguageCode' );
    $language = eZContentLanguage::fetchByLocale( $languageCode );
    if ( !$language )
    {
        $infoCode = "error-invalid-language";
        $infoData['language'] = $languageCode;
    }
    else if ( strlen( $aliasText ) == 0 )
    {
        $infoCode = "error-no-alias-text";
    }
    else
    {
        $parentID = 0;
        $linkID   = 0;
        //include_once( 'kernel/classes/ezurlaliasquery.php' );
        $filter = new eZURLAliasQuery();
        $filter->actions = array( 'eznode:' . $node->attribute( 'node_id' ) );
        $filter->type = 'name';
        $filter->limit = false;
        $existingElements = $filter->fetchAll();
        // TODO: add error handling when $existingElements is empty
        if ( count( $existingElements ) > 0 )
        {
            $parentID = (int)$existingElements[0]->attribute( 'parent' );
            $linkID   = (int)$existingElements[0]->attribute( 'id' );
        }
        if ( !$isRelative )
        {
            $parentID = 0; // Start from the top
        }
        $mask = $language->attribute( 'id' );
        $obj = $node->object();
        $alwaysMask = ($obj->attribute( 'language_mask' ) & 1);
        $mask |= $alwaysMask;

        $origAliasText = $aliasText;
        $result = eZURLAliasML::storePath( $aliasText, 'eznode:' . $node->attribute( 'node_id' ),
                                           $language, $linkID, $alwaysMask, $parentID,
                                           true, false, false );
        if ( $result['status'] === eZURLAliasML::LINK_ALREADY_TAKEN )
        {
            $lastElements = eZURLAliasML::fetchByPath( $result['path'] );
            if ( count ( $lastElements ) > 0 )
            {
                $lastElement  = $lastElements[0];
                $infoCode = "feedback-alias-exists";
                $infoData['new_alias'] = $aliasText;
                $infoData['url'] = $lastElement->attribute( 'path' );
                $infoData['action_url'] = $lastElement->actionURL();
                $aliasText = $origAliasText;
            }
        }
        else if ( $result['status'] === true )
        {
            $aliasText = $result['path'];
            if ( strcmp( $aliasText, $origAliasText ) != 0 )
            {
                $infoCode = "feedback-alias-cleanup";
                $infoData['orig_alias']  = $origAliasText;
                $infoData['new_alias'] = $aliasText;
            }
            else
            {
                $infoData['new_alias'] = $aliasText;
            }
            if ( $infoCode == 'no-errors' )
            {
                $infoCode = "feedback-alias-created";
            }
            $aliasText = false;
        }
    }
}

// Fetch generated names of node
//include_once( 'kernel/classes/ezurlaliasquery.php' );
$filter = new eZURLAliasQuery();
$filter->actions = array( 'eznode:' . $node->attribute( 'node_id' ) );
$filter->type = 'name';
$filter->limit = false;
$elements = $filter->fetchAll();

// Fetch custom aliases for node
$limit = 25;
$filter->prepare(); // Reset SQLs from previous calls
$filter->actions = array( 'eznode:' . $node->attribute( 'node_id' ) );
$filter->type = 'alias';
$filter->offset = $Offset;
$filter->limit = $limit;
$count = $filter->count();
$aliasList = $filter->fetchAll();

$path = array();
$nodePath = $node->attribute( 'path' );
foreach ( $nodePath as $pathEntry  )
{
    $url = $pathEntry->attribute( 'url_alias' );
    if ( strlen( $url ) == 0 )
        $url = 'content/view/full/' . $pathEntry->attribute( 'node_id' );
    $path[] = array( 'url'  => $url,
                     'text' => $pathEntry->attribute( 'name' ) );
}
$path[] = array( 'url'  => false,
                 'text' => $node->attribute( 'name' ) );

$languages = eZContentLanguage::prioritizedLanguages();

$tpl->setVariable( 'node', $node );
$tpl->setVariable( 'filter', $filter );
$tpl->setVariable( 'elements', $elements );
$tpl->setVariable( 'languages', $languages );
$tpl->setVariable( 'info_code', $infoCode );
$tpl->setVariable( 'info_data', $infoData );
$tpl->setVariable( 'aliasText', $aliasText );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/urlalias.tpl' );
$Result['path'] = $path;

?>
