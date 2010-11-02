<?php
//
// Created on: <16-Apr-2002 12:37:51 amos>
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

/**
 * Function to get template instance, load autoloads (operators) and set default settings.
 *
 * @deprecated Deprecated as of 4.3, use {@see eZTemplate::factory()} instead
 * @param string $name (Not supported as it was prevoisly set on same instance anyway)
 * @return eZTemplate
 */
function templateInit( $name = false )
{
    return eZTemplate::factory();
}

class Locator implements ezcTemplateLocator
{
    public function translatePath($path)
    {
        include_once( 'lib/eztemplate/classes/eztemplate.php' );
        include_once( 'kernel/common/eztemplatedesignresource.php' );
        include_once( 'lib/ezutils/classes/ezextension.php' );

        $ezt = eZTemplate::instance();
        //print "FOUND PATH: $path";
        $returnIt = false;
        $resourceData = $ezt->oldTpl->loadURIRoot( $path, true, $returnIt);
        $templateFile = $resourceData["template-filename"];


 
        #$resourceData = $ezt->oldTpl->fetch($path, false, true);
        #$templateFile = $resourceData["template-filename"];
        //print "TRANSLATED: " . $templateFile;

        return $templateFile;
    }
}



function neoTemplateInit( $runtimeFile = "var/runtime.txt" )
{
    $tc = ezcTemplateConfiguration::getInstance();
    $tc->compilePath = "var/compiled_templates";
    $tc->templatePath = "new_templates";
    $tc->addExtension("TemporaryFunctions");
    $tc->addExtension("TemplateConversionFunctions");
    $tc->addExtension("TemplateConversionBlocks");
    $tc->addExtension("TemplateEscapeBlock");
    $tc->addExtension("ezpTemplateGlobalFunctions");
    $tc->addExtension("ezpTemplateDeprecatedFunctions");
    $tc->addExtension("ezpTemplateArrayFunctions");
    $tc->addExtension("ezpTemplateCalendarFunctions");
    $tc->addExtension("ezpTemplateUrlFunctions");
    $tc->addExtension("ezpTemplateStringFunctions");
    $tc->addExtension("ezpTemplateIconFunctions");
    $tc->addExtension("ezpTemplateDateFunctions");
    $tc->addExtension("ezpTemplateSystemFunctions");
    $tc->addExtension("ezpTemplateImageFunctions");
    $tc->addExtension("ezpTemplateHtmlFunctions");
    $tc->addExtension("ezpTemplateMiscFunctions");
    $tc->addExtension("ezpTemplateLogicFunctions");
    TemplateConversionFunctions::$runtime = new Runtime($runtimeFile);

    $tc->locator = new Locator();
}

?>
