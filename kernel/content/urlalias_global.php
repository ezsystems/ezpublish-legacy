<?php
//
// Created on: <08-Aug-2003 11:27:10 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*! \file
*/

$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$tpl = eZTemplate::factory();
$limit = 20;

// TODO: For PHP 5, merge similar code in urlalias.php and urlalias_global.php into a function/class.

$infoCode = 'no-errors'; // This will be modified if info/warning is given to user.
$infoData = array(); // Extra parameters can be added to this array
$aliasText = false;
$aliasDestinationText = false;
$aliasOutputText = false;
$aliasOutputDestinationText = false;

if ( $Module->isCurrentAction( 'RemoveAllAliases' ) )
{
    $filter = new eZURLAliasQuery();
    $filter->actionTypesEx = array( 'eznode', 'nop' );
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
    $aliasText = trim( $Module->actionParameter( 'AliasSourceText' ) );
    $aliasDestinationTextUnmodified = $Module->actionParameter( 'AliasDestinationText' );
    $aliasDestinationText = trim( $aliasDestinationTextUnmodified, " \t\r\n\0\x0B/" );
    $isAlwaysAvailable = $http->hasPostVariable( 'AllLanguages' ) && strlen( trim( $http->postVariable( 'AllLanguages' ) ) ) > 0;
    $languageCode = $Module->actionParameter( 'LanguageCode' );
    $language = eZContentLanguage::fetchByLocale( $languageCode );
    $aliasRedirects  = $http->hasPostVariable( 'AliasRedirects' ) && $http->postVariable( 'AliasRedirects' );

    if ( !$language )
    {
        $infoCode = "error-invalid-language";
        $infoData['language'] = $languageCode;
    }
    else if ( strlen( $aliasText ) == 0 )
    {
        $infoCode = "error-no-alias-text";
    }
    else if ( strlen( trim( $aliasDestinationTextUnmodified ) ) == 0 )
    {
        $infoCode = "error-no-alias-destination-text";
    }
    else
    {
        $parentID = 0; // Start from the top
        $linkID   = 0;
        $mask = $language->attribute( 'id' );
        if ( $isAlwaysAvailable )
            $mask |= 1;

        $action = eZURLAliasML::urlToAction( $aliasDestinationText );
        if ( !$action )
        {
            $elements = eZURLAliasML::fetchByPath( $aliasDestinationText );
            if ( count( $elements ) > 0 )
            {
                $action = $elements[0]->attribute( 'action' );
                $linkID = $elements[0]->attribute( 'link' );
            }
        }
        if ( !$action )
        {
            $infoCode = "error-action-invalid";
            $infoData['aliasText'] = $aliasDestinationText;
        }
        else
        {
            $origAliasText = $aliasText;
            if ( $linkID == 0 )
                $linkID = true;
            $result = eZURLAliasML::storePath( $aliasText, $action,
                                               $language, $linkID, $isAlwaysAvailable, $parentID,
                                               true, false, false, $aliasRedirects );
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
                $aliasOutputText = false;
                $aliasOutputDestinationText = false;
            }
            if ( preg_match( "#^eznode:(.+)$#", $action, $matches ) )
            {
                $infoData['node_id'] = $matches[1];
            }
        }
    }
}

// User preferences
$limitList = array( array( 'id'    => 1,
                           'value' => 10 ),
                    array( 'id'    => 2,
                           'value' => 25 ),
                    array( 'id'    => 3,
                           'value' => 50 ),
                    array( 'id'    => 4,
                           'value' => 100 ) );
$limitID = eZPreferences::value( 'admin_urlalias_list_limit' );
foreach ( $limitList as $limitEntry )
{
    $limitIDs[]                     = $limitEntry['id'];
    $limitValues[$limitEntry['id']] = $limitEntry['value'];
}
if ( !in_array( $limitID, $limitIDs ) )
{
    $limitID = 2;
}

// Fetch global custom aliases (excluding eznode)
$filter = new eZURLAliasQuery();
$filter->actionTypesEx = array( 'eznode', 'nop' );
$filter->offset = $Offset;
$filter->limit = $limitValues[$limitID];

// Prime the internal data for the template, for PHP5 this is no longer needed since objects will not be copied anymore in the template code.
$count = $filter->count();
$aliasList = $filter->fetchAll();
$path = array();
$path[] = array( 'url'  => false,
                 'text' => ezpI18n::translate( 'kernel/content/urlalias_global', 'Global URL aliases' ) );

$languages = eZContentLanguage::prioritizedLanguages();

$tpl->setVariable( 'filter', $filter );
$tpl->setVariable( 'languages', $languages );
$tpl->setVariable( 'info_code', $infoCode );
$tpl->setVariable( 'info_data', $infoData );
$tpl->setVariable( 'aliasSourceText', $aliasOutputText );
$tpl->setVariable( 'aliasDestinationText', $aliasOutputDestinationText );
$tpl->setVariable( 'limitList', $limitList );
$tpl->setVariable( 'limitID', $limitID );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/urlalias_global.tpl' );
$Result['path'] = $path;

?>
