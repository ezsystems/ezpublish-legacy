<?php
//
// Definition of eZTemplatesStatisticsReporter class
//
// Created on: <18-Feb-2005 17:21:17 dl>
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

/*! \file eztemplatesstatisticsreporter.php
*/

/*!
  \class eZTemplatesStatisticsReporter eztemplatesstatisticsreporter.php
  \brief Generates statistics of tempate usage.

*/

include_once( 'kernel/common/eztemplatedesignresource.php' );
include_once( 'lib/eztemplate/classes/eztemplate.php' );
include_once( 'lib/ezutils/classes/ezsys.php' );

class eZTemplatesStatisticsReporter
{
    function eZTemplatesStatisticsReporter()
    {
    }

    /*!
     static
    */
    function &generateStatistics( $as_html = true )
    {
        $stats = '';

        if ( !eZTemplate::isTemplatesUsageStatisticsEnabled() )
            return $stats;

        if ( $as_html )
        {
            $stats .= "<h2>Templates used to render the page:</h2>";
            $stats .= ( "<table style='border: 1px dashed black;' cellspacing='0'>" .
                   "<tr><th>&nbsp;Template</th>" .
                   "<th>&nbsp;Requested template</th>" .
                   "<th>&nbsp;Template loaded</th>" .
                   "<th>&nbsp;Edit</th>" .
                   "<th>&nbsp;Override</th></tr>" );
        }
        else
        {
            $formatString = "%-40s%-40s%-40s\n";
            $stats .= "Templates usage statistics\n";
            $stats .= sprintf( $formatString, 'Templates', 'Requested template', 'Template loaded' );
        }

        if ( $as_html )
        {
            $iconSizeX = 16;
            $iconSizeY = 16;
            $templateViewFunction = 'visual/templateview/';
            $templateEditFunction = 'visual/templateedit/';
            $templateOverrideFunction = 'visual/templatecreate/';

            $std_base = eZTemplateDesignResource::designSetting( 'standard' );
            $editIconFile = "/design/$std_base/images/edit.gif";
            $overrideIconFile = "/design/$std_base/images/override-template.gif";

            $tdClass = 'used_templates_stats1';
            $j = 0;

            $currentSiteAccess = $GLOBALS['eZCurrentAccess']['name'];
        }

        $templatesUsageStatistics =& eZTemplate::templatesUsageStatistics();
        foreach( $templatesUsageStatistics as $templateInfo )
        {
            $actualTemplateName =& $templateInfo['actual-template-name'];
            $requestedTemplateName =& $templateInfo['requested-template-name'];
            $templateFileName =& $templateInfo['template-filename'];

            if ( $as_html )
            {
                $tdClass = ( $j % 2 == 0 ) ? 'used_templates_stats1' : 'used_templates_stats2';

                $requestedTemplateViewURI = $templateViewFunction . $requestedTemplateName;
                $actualTemplateViewURI = $templateViewFunction . $actualTemplateName;

                $templateEditURI = $templateEditFunction . $templateFileName;
                $templateOverrideURI = $templateOverrideFunction . $actualTemplateName;

                $stats .= ( "<tr><td class=\"$tdClass\"><a href=\"$actualTemplateViewURI\">&nbsp;$actualTemplateName</a></td>" .
                       "<td class=\"$tdClass\"><a href=\"$requestedTemplateViewURI\">&nbsp;$requestedTemplateName</a></td>" .
                       "<td class=\"$tdClass\">&nbsp;$templateFileName</td>" .
                       "<td class=\"$tdClass\" align=\"center\"><a href=\"$templateEditURI/(siteAccess)/$currentSiteAccess\"><img src=\"$editIconFile\" width=\"$iconSizeX\" height=\"$iconSizeY\" alt=\"Edit template\" title=\"Edit template\" /></a></td>".
                       "<td class=\"$tdClass\" align=\"center\"><a href=\"$templateOverrideURI/(siteAccess)/$currentSiteAccess\"><img src=\"$overrideIconFile\" width=\"$iconSizeX\" height=\"$iconSizeY\" alt=\"Override template\" title=\"Override template\" /></a></td></tr>" );

                $j++;
            }
            else
            {
                $stats .= sprintf( $formatString, $actualTemplateName, $requestedTemplateName, $templateFileName );
            }
        }

        $totalTemplatesCount = count( $templatesUsageStatistics );

        if ( $as_html )
        {
            $stats .= ( "<tr><td class=\"$tdClass\">&nbsp;</td>" .
                   "<td class=\"$tdClass\">&nbsp;</td>" .
                   "<td class=\"$tdClass\">&nbsp;</td>".
                   "<td class=\"$tdClass\">&nbsp;</td>".
                   "<td class=\"$tdClass\">&nbsp;</td></tr>" );
            $stats .= "<tr><td><b>&nbsp;Total templates count: $totalTemplatesCount</b></td></tr>";
            $stats .= "</table>";
        }
        else
        {
            $stats .= "\nTotal templates count: " . $totalTemplatesCount . "\n";
        }

        return $stats;
    }
}

?>
