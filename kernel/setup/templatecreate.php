<?php
//
// Created on: <09-May-2003 10:44:02 bf>
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

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];
$parameters =& $Params["Parameters"];

include_once( "kernel/common/template.php" );
include_once( "kernel/common/eztemplatedesignresource.php" );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( "kernel/classes/ezcontentclass.php" );

$ini =& eZINI::instance();
$tpl =& templateInit();

// Todo: read from siteaccess settings
$siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );

$siteBase = $siteAccess;

$siteINI = eZINI::instance( 'site.ini', 'settings', null, null, true );
$siteINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
$siteINI->loadCache();
$siteDesign = $siteINI->variable( "DesignSettings", "SiteDesign" );

$template = "";
foreach ( $parameters as $param )
{
    $template .= "/$param";
}


$templateType = 'default';
if ( strpos( $template, "node/view" ) )
{
    $templateType = 'node_view';
}
else if ( strpos( $template, "content/view" ) )
{
    $templateType = 'object_view';
}
else if ( strpos( $template, "content/edit" ) )
{
    $templateType = 'object_view';
}
else if ( strpos( $template, "pagelayout.tpl" ) )
{
    $templateType = 'pagelayout';
}

if ( $module->isCurrentAction( 'CreateOverride' ) )
{
    $templateName = trim( $http->postVariable( 'TemplateName' ) );

    $error = false;
    if ( preg_match( "#^[0-9a-z_]+$#", $templateName ) )
    {
        $templateName = trim( $http->postVariable( 'TemplateName' ) );
        $fileName = "design/$siteDesign/override/templates/" . $templateName . ".tpl";
        $filePath = "design/$siteDesign/override/templates";

        $templateCode = "";
        switch ( $templateType )
        {
            case "node_view":
            {
                $templateCode =& generateNodeViewTemplate( $http, $template, $fileName );
            }break;

            case "object_view":
            {
                $templateCode =& generateObjectViewTemplate( $http, $template, $fileName );
            }break;

            case "pagelayout":
            {
                $templateCode =& generatePagelayoutTemplate( $http, $template, $fileName );
            }break;

            default:
            {
                $templateCode =& generateDefaultTemplate( $http, $template, $fileName );
            }break;
        }

        if ( !file_exists( $filePath ) )
        {
            $dirPermission = $ini->variable( 'FileSettings', 'StorageDirPermissions' );

            eZDir::mkdir( $filePath, eZDir::directoryPermission(), true );
        }


        $fp = fopen( $fileName, "w+" );
        if ( $fp )
        {
            $filePermission = $ini->variable( 'FileSettings', 'StorageFilePermissions' );
            $oldumask = umask( 0 );
            fwrite( $fp, $templateCode );
            fclose( $fp );
            chmod( $fileName, octdec( $filePermission ) );
            umask( $oldumask );

            // Store override.ini.append file
            $overrideINI = eZINI::instance( 'override.ini', 'settings', null, null, true );
            $overrideINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            $overrideINI->loadCache();

            $templateFile = preg_replace( "#^/(.*)$#", "\\1", $template );

            $overrideINI->setVariable( $templateName, 'Source', $templateFile );
            $overrideINI->setVariable( $templateName, 'MatchFile', $templateName . ".tpl" );
            $overrideINI->setVariable( $templateName, 'Subdir', "templates" );

            if ( $http->hasPostVariable( 'Match' ) )
            {
                $matchArray = $http->postVariable( 'Match' );

                foreach ( array_keys( $matchArray ) as $matchKey )
                {
                    if ( $matchArray[$matchKey] == -1 or trim( $matchArray[$matchKey] ) == "" )
                        unset( $matchArray[$matchKey] );
                }
                $overrideINI->setVariable( $templateName, 'Match', $matchArray );
            }

            $oldumask = umask( 0 );
            $overrideINI->save( "siteaccess/$siteAccess/override.ini.append" );
            chmod( "settings/siteaccess/$siteAccess/override.ini.append", octdec( $filePermission ) );
            umask( $oldumask );

            // Expire content view cache
            $viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled' );
            if ( $viewCacheEnabled )
            {
                eZContentObject::expireAllCache();
            }

            // Clear override cache
            $cachedDir = eZSys::cacheDirectory();
            $cachedDir .= "/override/";
            eZDir::recursiveDelete( $cachedDir );
        }
        else
        {
            $error = "permission_denied";
            eZDebug::writeError( "Could not create override template, check permissions on $fileName", "Template override" );
        }
    }
    else
    {
        $error = "invalid_name";
    }

    if ( $error == false )
    {
        $module->redirectTo( '/setup/templateview'. $template );
        return EZ_MODULE_HOOK_STATUS_CANCEL_RUN;
    }
}


function &generateNodeViewTemplate( &$http, $template, $fileName )
{
    $matchArray = $http->postVariable( 'Match' );

    $templateCode = "";
    $classIdentifier = $matchArray['class_identifier'];

    $class = eZContentClass::fetchByIdentifier( $classIdentifier );

    // Check what kind of contents we should create in the template
    switch ( $http->postVariable( 'TemplateContent' ) )
    {
        case 'DefaultCopy' :
        {
            $siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );
            $overrideArray =& eZTemplatedesignresource::overrideArray( $siteAccess );
            $fileName = $overrideArray[$template]['base_dir'] . $overrideArray[$template]['template'];
            $fp = fopen( $fileName, 'rb' );
            if ( $fp )
            {
                $codeFromFile = fread( $fp, filesize( $fileName ) );

                // Remove the "{* DO NOT EDIT... *}" first line (if exists).
                $templateCode = preg_replace('@^{\*\s*DO\sNOT\sEDIT.*?\*}\n(.*)@s', '$1', $codeFromFile);
            }
            else
            {
                eZDebug::writeError( "Could not open file $fileName, check read permissions" );
            }
            fclose( $fp );
        }break;

        case 'ContainerTemplate' :
        {
            $templateCode = "<h1>{\$node.name}</h1>\n\n";

            // Append attribute view
            if ( get_class( $class ) == "ezcontentclass" )
            {
                $attributes =& $class->fetchAttributes();
                foreach ( $attributes as $attribute )
                {
                    $identifier = $attribute->attribute( 'identifier' );
                    $name = $attribute->attribute( 'name' );
                    $templateCode .= "<h2>$name</h2>\n";
                    $templateCode .= "{attribute_view_gui attribute=\$node.object.data_map.$identifier}\n\n";
                }
            }

            $templateCode .= "" .
                 "{let page_limit=20\n" .
                 "    children=fetch('content','list',hash(parent_node_id,\$node.node_id,sort_by,\$node.sort_array,limit,\$page_limit,offset,\$view_parameters.offset))" .
                 "    list_count=fetch('content','list_count',hash(parent_node_id,\$node.node_id))}\n" .
                 "\n" .
                 "{section name=Child loop=\$children sequence=array(bglight,bgdark)}\n" .
                 "{node_view_gui view=line content_node=\$Child:item}\n" .
                 "{/section}\n" .

                 "{include name=navigator\n" .
                 "    uri='design:navigator/google.tpl'\n" .
                 "    page_uri=concat('/content/view','/full/',\$node.node_id)\n" .
                 "    item_count=\$list_count\n" .
                 "    view_parameters=\$view_parameters\n" .
                 "    item_limit=\$page_limit}\n";
            "{/let}\n";
        }break;

        case 'ViewTemplate' :
        {
            $templateCode = "<h1>{\$node.name}</h1>\n\n";

            // Append attribute view
            if ( get_class( $class ) == "ezcontentclass" )
            {
                $attributes =& $class->fetchAttributes();
                foreach ( $attributes as $attribute )
                {
                    $identifier = $attribute->attribute( 'identifier' );
                    $name = $attribute->attribute( 'name' );
                    $templateCode .= "<h2>$name</h2>\n";
                    $templateCode .= "{attribute_view_gui attribute=\$node.object.data_map.$identifier}\n\n";
                }
            }

        }break;

        default:
        case 'EmptyFile' :
        {
        }break;
    }

    return $templateCode;
}


function &generateObjectViewTemplate( &$http, $template, $fileName )
{
    $matchArray = $http->postVariable( 'Match' );

    $templateCode = "";
    $classID = $matchArray['class'];

    $class = eZContentClass::fetch( $classID );

    // Check what kind of contents we should create in the template
    switch ( $http->postVariable( 'TemplateContent' ) )
    {
        case 'DefaultCopy' :
        {
            $siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );
            $overrideArray =& eZTemplatedesignresource::overrideArray( $siteAccess );
            $fileName = $overrideArray[$template]['base_dir'] . $overrideArray[$template]['template'];
            $fp = fopen( $fileName, 'rb' );
            if ( $fp )
            {
                $codeFromFile = fread( $fp, filesize( $fileName ) );

                // Remove the "{* DO NOT EDIT... *}" first line (if exists).
                $templateCode = preg_replace('@^{\*\s*DO\sNOT\sEDIT.*?\*}\n(.*)@s', '$1', $codeFromFile);
            }
            else
            {
                eZDebug::writeError( "Could not open file $fileName, check read permissions" );
            }
            fclose( $fp );
        }break;

        case 'ViewTemplate' :
        {
            $templateCode = "<h1>{\$object.name}</h1>\n\n";

            // Append attribute view
            if ( get_class( $class ) == "ezcontentclass" )
            {
                $attributes =& $class->fetchAttributes();
                foreach ( $attributes as $attribute )
                {
                    $identifier = $attribute->attribute( 'identifier' );
                    $name = $attribute->attribute( 'name' );
                    $templateCode .= "<h2>$name</h2>\n";
                    $templateCode .= "{attribute_view_gui attribute=\$object.data_map.$identifier}\n\n";
                }
            }

        }break;

        default:
        case 'EmptyFile' :
        {
        }break;
    }
    return $templateCode;
}

function &generatePagelayoutTemplate( &$http, $template, $fileName )
{
    $templateCode = "";
    // Check what kind of contents we should create in the template
    switch ( $http->postVariable( 'TemplateContent' ) )
    {
        case 'DefaultCopy' :
        {
            $siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );
            $overrideArray =& eZTemplatedesignresource::overrideArray( $siteAccess );
            $fileName = $overrideArray[$template]['base_dir'] . $overrideArray[$template]['template'];
            $fp = fopen( $fileName, 'rb' );
            if ( $fp )
            {
                $codeFromFile = fread( $fp, filesize( $fileName ) );

                // Remove the "{* DO NOT EDIT... *}" first line (if exists).
                $templateCode = preg_replace('@^{\*\s*DO\sNOT\sEDIT.*?\*}\n(.*)@s', '$1', $codeFromFile);
            }
            else
            {
                eZDebug::writeError( "Could not open file $fileName, check read permissions" );
            }
            fclose( $fp );
        }break;

        default:
        case 'EmptyFile' :
        {
            $templateCode = '{*?template charset=latin1?*}' .
                 '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" ' .
                 '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n" .
                 '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">' .
                 '<head>' . "\n" .
                 '    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />' . "\n" .
                 '    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />' . "\n" .
                 '    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />' . "\n" .
                 '    {include uri="design:page_head.tpl"}' . "\n" .
                 '</head>' . "\n" .
                 '<body>' . "\n" .
                 '{$module_result.content}' . "\n" .
                 '<!--DEBUG_REPORT-->' . "\n" .
                 '</body>' . "\n" .
                 '</html>' . "\n";
        }break;
    }
    return $templateCode;
}

function &generateDefaultTemplate( &$http, $template, $fileName )
{
    $templateCode = "";
    // Check what kind of contents we should create in the template
    switch ( $http->postVariable( 'TemplateContent' ) )
    {
        case 'DefaultCopy' :
        {
            $siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );
            $overrideArray =& eZTemplatedesignresource::overrideArray( $siteAccess );
            $fileName = $overrideArray[$template]['base_dir'] . $overrideArray[$template]['template'];
            $fp = fopen( $fileName, 'rb' );
            if ( $fp )
            {
                $codeFromFile = fread( $fp, filesize( $fileName ) );

                // Remove the "{* DO NOT EDIT... *}" first line (if exists).
                $templateCode = preg_replace('@^{\*\s*DO\sNOT\sEDIT.*?\*}\n(.*)@s', '$1', $codeFromFile);
            }
            else
            {
                eZDebug::writeError( "Could not open file $fileName, check read permissions" );
            }
            fclose( $fp );
        }break;

        default:
        case 'EmptyFile' :
        {
            $templateCode = '{*?template charset=latin1?*}' .
                 '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" ' .
                 '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n" .
                 '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">' .
                 '<head>' . "\n" .
                 '    <link rel="stylesheet" type="text/css" href={"stylesheets/core.css"|ezdesign} />' . "\n" .
                 '    <link rel="stylesheet" type="text/css" href={"stylesheets/admin.css"|ezdesign} />' . "\n" .
                 '    <link rel="stylesheet" type="text/css" href={"stylesheets/debug.css"|ezdesign} />' . "\n" .
                 '    {include uri="design:page_head.tpl"}' . "\n" .
                 '</head>' . "\n" .
                 '<body>' . "\n" .
                 '{$module_result.content}' . "\n" .
                 '<!--DEBUG_REPORT-->' . "\n" .
                 '</body>' . "\n" .
                 '</html>' . "\n";
        }break;
    }
    return $templateCode;
}


$tpl->setVariable( 'error', $error );
$tpl->setVariable( 'template', $template );
$tpl->setVariable( 'template_type', $templateType );
$tpl->setVariable( 'template_name', $templateName );
$tpl->setVariable( 'site_base', $siteBase );
$tpl->setVariable( 'site_design', $siteDesign );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:setup/templatecreate.tpl" );
$Result['path'] = array( array( 'url' => "/setup/templatelist/",
                                'text' => ezi18n( 'kernel/setup', 'Template list' ) ),
                         array( 'url' => "/setup/templateview". $template,
                                'text' => ezi18n( 'kernel/setup', 'Template view' ) ),
                         array( 'url' => false,
                                'text' => ezi18n( 'kernel/setup', 'Create new template' ) ) );
?>
