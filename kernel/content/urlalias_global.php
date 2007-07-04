<?php
//
// Created on: <08-Aug-2003 11:27:10 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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
include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezurlaliasml.php' );
include_once( 'kernel/classes/ezpathelement.php' );

$Module =& $Params['Module'];
$http =& eZHTTPTool::instance();

$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$tpl =& templateInit();
$limit = 20;

// TODO: For PHP 5, merge similar code in urlalias.php and urlalias_global.php into a function/class.

$infoCode = 'no-errors'; // This will be modified if info/warning is given to user.
$infoData = array(); // Extra parameters can be added to this array
$aliasText = false;
$aliasDestinationText = false;
$aliasOutputText = false;
$aliasOutputDestinationText = false;

if ( $Module->isCurrentAction( 'RemoveAlias' ) )
{
    if ( $http->hasPostVariable( 'ElementList' ) )
    {
        $elementList = $http->postVariable( 'ElementList' );
        if ( !is_array( $elementList ) )
            $elementList = array();
        foreach ( $elementList as $element )
        {
            if ( preg_match( "#^([0-9]+)-([0-9]+)$#", $element, $matches ) )
            {
                $elementID = (int)$matches[1];
                $parentID = (int)$matches[2];
                eZURLAliasML::removeByIDParentID( $elementID, $parentID );
            }
        }
        $infoCode = "feedback-removed";
    }
}
else if ( $Module->isCurrentAction( 'NewAlias' ) )
{
    $aliasText = trim( $Module->actionParameter( 'AliasSourceText' ) );
    $aliasDestinationText = trim( $Module->actionParameter( 'AliasDestinationText' ), " \t\r\n\0\x0B/" );
    $isAlwaysAvailable = $http->hasPostVariable( 'AllLanguages' ) && strlen( trim( $http->postVariable( 'AllLanguages' ) ) ) > 0;
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
    else if ( strlen( $aliasDestinationText ) == 0 )
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
            $path = explode( "/", $aliasText );
            $topEntry = array_pop( $path );
            foreach ( $path as $index => $entry )
            {
                $entry = eZURLAliasML::convertToAlias( $entry );
                $newEntries[$index] = array( 'action' => 'nop:',
                                             'text'   => $entry );
            }
            $topEntry = eZURLAliasML::convertToAlias( $topEntry );
            // TODO: If $topEntry becomes empty give error message
            $newEntries[] = array( 'action' => $action,
                                   'text'   => $topEntry );
            $path[] = $topEntry;
            $createCount = 0;
            $lastElement = false;
            $path = array();
            foreach ( $newEntries as $entry )
            {
                $action = $entry['action'];
                $text   = $entry['text'];
                $rows   = eZPathElement::fetchNamedByParentID( $parentID, $text );
                if ( count( $rows ) == 0 )
                {
                    $text = eZURLAliasML::findUniqueText( $parentID, $text, '' );
                    $element = eZUrlAliasML::create( $text, $action, $parentID, $mask, $languageCode );
                    if ( $action != "nop:" )
                    {
                        if ( $linkID == 0 )
                            $linkID = null;
                        $element->setAttribute( 'link', $linkID );
                        $element->setAttribute( 'is_alias', true );
                    }
                    $element->store();
                    ++$createCount;
                    $parentID = (int)$element->attribute( 'link' );
                }
                else
                {
                    $parentID = (int)$rows[0]->attribute( 'link' );
                    $lastElement = $rows[0];
                }
                $path[] = $text;
            }
            $aliasText = join( "/", $path );
            if ( strcmp( $aliasText, $origAliasText ) != 0 )
            {
                $infoCode = "feedback-alias-cleanup";
                $infoData['orig_alias']  = $origAliasText;
                $infoData['new_alias'] = $aliasText;
            }
            if ( $createCount > 0 )
            {
                if ( $infoCode == 'no-errors' )
                {
                    $infoCode = "feedback-alias-created";
                    $infoData['new_alias'] = $aliasText;
                }
                $aliasText = false;
                $aliasOutputText = false;
                $aliasOutputDestinationText = false;
            }
            else
            {
                $infoCode = "feedback-alias-exists";
                $infoData['new_alias'] = $aliasText;
                $infoData['url'] = $lastElement->getPath();
                $infoData['action_url'] = $lastElement->actionURL();
                $aliasText = $origAliasText;
            }
        }
    }
}

// Fetch global custom aliases (excluding eznode)
include_once( 'kernel/classes/ezurlaliasquery.php' );
$filter = new eZURLAliasQuery();
$filter->actionTypesEx = array( 'eznode', 'nop' );
$filter->offset = $Offset;
$filter->limit = 15;

// Prime the internal data for the template, for PHP5 this is no longer needed since objects will not be copied anymore in the template code.
$count = $filter->count();
$aliasList = $filter->fetchAll();

$path = array();
$path[] = array( 'url'  => false,
                 'text' => ezi18n( 'kernel/content/urlalias_global', 'Global URL aliases' ) );

$languages = eZContentLanguage::prioritizedLanguages();

$tpl->setVariable( 'filter', $filter );
$tpl->setVariable( 'languages', $languages );
$tpl->setVariable( 'info_code', $infoCode );
$tpl->setVariable( 'info_data', $infoData );
$tpl->setVariable( 'aliasSourceText', $aliasOutputText );
$tpl->setVariable( 'aliasDestinationText', $aliasOutputDestinationText );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/urlalias_global.tpl' );
$Result['path'] = $path;

?>
