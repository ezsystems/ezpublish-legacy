<?php
//
// Definition of eZTemplatedesignresource class
//
// Created on: <14-Sep-2002 15:37:17 amos>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file eztemplatedesignresource.php
*/

/*!
  \class eZTemplatedesignresource eztemplatedesignresource.php
  \brief Handles template file loading with override support

*/

include_once( "lib/eztemplate/classes/eztemplatefileresource.php" );
include_once( "lib/ezutils/classes/ezini.php" );

class eZTemplateDesignResource extends eZTemplateFileResource
{
    /*!
     Initializes with a default resource name "design".
    */
    function eZTemplateDesignResource( $name = "design", $onlyStandard = false )
    {
        $this->eZTemplateFileResource( $name, true );
        $this->Keys = array();
        $this->OnlyStandard = $onlyStandard;
    }

    /*!
     \static
     \return the sitedesign for the design type \a $type, currently \c standard and \c site is allowed.
             If no sitedesign is set it will fetch it from site.ini.
    */
    function designSetting( $type = 'standard' )
    {
        if ( $type != 'standard' and
             $type != 'site' )
        {
            eZDebug::writeWarning( "Cannot retrieve designsetting for type '$type'", 'eZTemplateDesignResource::designSetting' );
            return null;
        }
        $designSettings =& $GLOBALS['eZTemplateDesignSetting'];
        if ( !isset( $designSettings ) )
            $designSettings = array();
        $designSetting =& $designSettings[$type];
        $siteBasics =& $GLOBALS['eZSiteBasics'];
        if ( $type == 'site' and
             is_string( $siteBasics['site-design-override'] ) )
            return $siteBasics['site-design-override'];

        if ( isset( $designSetting ) )
            return $designSetting;
        $ini =& eZINI::instance();
        if ( $type == 'standard' )
            $designSetting = $ini->variable( "DesignSettings", "StandardDesign" );
        else if ( $type == 'site' )
            $designSetting = $ini->variable( "DesignSettings", "SiteDesign" );
        return $designSetting;
    }

    /*!
     Sets the sitedesign for the design type \a $type, currently \c standard and \c site is allowed.
     The design is set to \a $designSetting.
    */
    function setDesignSetting( $designSetting, $type = 'standard' )
    {
        if ( $type != 'standard' and
             $type != 'site' )
        {
            eZDebug::writeWarning( "Cannot set designsetting '$designSetting' for type '$type'", 'eZTemplateDesignResource::setDesignSetting' );
            return;
        }
        $designSettings =& $GLOBALS['eZTemplateDesignSetting'];
        if ( !isset( $designSettings ) )
            $designSettings = array();
        $designSettings[$type] = $designSetting;
    }

    /*!
     \return the rules used for matching design elements. \a $element defines the element type.
    */
    function fileMatchingRules( $element, $path, $onlyStandard = false )
    {
        $standardBase = eZTemplateDesignResource::designSetting( 'standard' );
        if ( !$onlyStandard )
            $siteBase = eZTemplateDesignResource::designSetting( 'site' );

        include_once( 'kernel/classes/ezextension.php' );
        $extensionDirectory = eZExtension::baseDirectory();

        $designINI =& eZINI::instance( 'design.ini' );
        $extensions = $designINI->variable( 'ExtensionSettings', 'DesignExtensions' );

        $matches = array();

        $elementText = '';
        if ( $element !== false )
            $elementText = $element . '/';

        // Override
        if ( !$onlyStandard )
            $matches[] = array( "file" => "design/$siteBase/override/$elementText$path",
                                "type" => "override" );
        $matches[] = array( "file" => "design/$standardBase/override/$elementText$path",
                            "type" => "override" );
        foreach ( $extensions as $extension )
        {
            if ( !$onlyStandard )
                $matches[] = array( 'file' => "$extensionDirectory/$extension/design/$siteBase/override/$elementText$path",
                                    'type' => 'override' );
            $matches[] = array( 'file' => "$extensionDirectory/$extension/design/$standardBase/override/$elementText$path",
                                'type' => 'override' );
        }

        // Normal
        if ( !$onlyStandard )
            $matches[] = array( "file" => "design/$siteBase/$elementText$path",
                                "type" => "normal" );
        $matches[] = array( "file" => "design/$standardBase/$elementText$path",
                            "type" => "normal" );
        foreach ( $extensions as $extension )
        {
            if ( !$onlyStandard )
                $matches[] = array( 'file' => "$extensionDirectory/$extension/design/$siteBase/$elementText$path",
                                    'type' => 'normal' );
            $matches[] = array( 'file' => "$extensionDirectory/$extension/design/$standardBase/$elementText$path",
                                'type' => 'normal' );
        }

        return $matches;
    }

    /*!
     Loads the template file if it exists, also sets the modification timestamp.
     Returns true if the file exists.
    */
//     function handleResource( &$tpl, &$templateRoot, &$text, &$tstamp, $uri, $resourceName, &$path, &$keyData, $method, &$extraParameters )
    function handleResource( &$tpl, &$resourceData, $method, &$extraParameters )
    {
        $templateRoot =& $resourceData['template-root'];
        $text =& $resourceData['text'];
        $tstamp =& $resourceData['time-stamp'];
        $uri =& $resourceData['uri'];
        $resourceName =& $resourceData['resource'];
        $path =& $resourceData['template-name'];
        $keyData =& $resourceData['key-data'];

        $matches = $this->fileMatchingRules( 'templates', $path, $this->OnlyStandard );

        $matchKeys = $this->Keys;
        $matchedKeys = array();

        if ( is_array( $extraParameters ) and
             isset( $extraParameters['ezdesign:keys'] ) )
        {
            $this->mergeKeys( $matchKeys, $extraParameters['ezdesign:keys'] );
        }

        include_once( 'kernel/common/ezoverride.php' );
        $match = eZOverride::selectFile( $matches, $matchKeys, $matchedKeys, "#^(.+)/(.+)(\.tpl)$#" );
        if ( $match === null )
            return false;

        $file = $match["file"];

        $usedKeys = array();
        foreach ( $matchKeys as $matchKeyName => $matchKeyValue )
        {
            $usedKeys[$matchKeyName] = $matchKeyValue;
        }
        $extraParameters['ezdesign:used_keys'] = $usedKeys;
        $extraParameters['ezdesign:matched_keys'] = $matchedKeys;
        $tpl->setVariable( 'used', $usedKeys, 'DesignKeys' );
        $tpl->setVariable( 'matched', $matchedKeys, 'DesignKeys' );
        $resourceData['template-filename'] = $file;
        $result = eZTemplateFileResource::handleResourceData( $tpl, $this, $resourceData, $method, $extraParameters );
        return $result;
    }

//     function cacheKey( $uri, $res, $templatePath, &$extraParameters )
//     {
//         $matches = $this->fileMatchingRules( 'templates', $templatePath );

//         $matchKeys = $this->Keys;
//         $matchedKeys = array();

//         if ( is_array( $extraParameters ) and
//              isset( $extraParameters['ezdesign:keys'] ) )
//         {
//             $this->mergeKeys( $matchKeys, $extraParameters['ezdesign:keys'] );
//         }

//         include_once( 'kernel/common/ezoverride.php' );
//         $match = eZOverride::selectFile( $matches, $matchKeys, $matchedKeys, "#^(.+)/(.+)(\.tpl)$#" );
//         if ( $match === null )
//             return false;

//         $file = $match["file"];
//         $key = md5( $file );
//         return $key;
//     }

    /*!
     Sets the override keys to \a $keys, if some of the keys already exists they are overriden
     by the new keys.
     \sa clearKeys
    */
    function setKeys( $keys )
    {
        $this->mergeKeys( $this->Keys, $keys );
    }

    /*!
     \private
     Merges keys set in \a $keys with the array in \a $originalKeys.
    */
    function mergeKeys( &$originalKeys, $keys )
    {
        foreach ( $keys as $key )
        {
            if ( count( $key ) >= 2 )
                $originalKeys[$key[0]] = $key[1];
        }
    }

    /*!
     Removes all override keys.
     \sa setKeys
    */
    function clearKeys()
    {
        $this->Keys = array();
    }

    /*!
     \return the match keys.
     \sa setKeys
    */
    function keys()
    {
        return $this->Keys;
    }

    /*!
     \return the unique instance of the design resource.
    */
    function &instance()
    {
        $instance =& $GLOBALS["eZTemplateDesignResourceInstance"];
        if ( get_class( $instance ) != "eztemplatedesignresource" )
        {
            $instance = new eZTemplateDesignResource();
        }
        return $instance;
    }

    /*!
     \return the unique instance of the standard resource.
    */
    function &standardInstance()
    {
        $instance =& $GLOBALS["eZTemplateStandardResourceInstance"];
        if ( get_class( $instance ) != "eztemplatedesignresource" )
        {
            $instance = new eZTemplateDesignResource( 'standard', true );
        }
        return $instance;
    }

    var $Keys;
    var $OnlyStandard;
}

?>
