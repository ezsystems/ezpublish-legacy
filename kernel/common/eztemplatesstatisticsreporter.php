<?php
//
// Definition of eZTemplatesStatisticsReporter class
//
// Created on: <18-Feb-2005 17:21:17 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file eztemplatesstatisticsreporter.php
*/

/*!
  \class eZTemplatesStatisticsReporter eztemplatesstatisticsreporter.php
  \brief Generates statistics of tempate usage.

*/

//include_once( 'kernel/common/eztemplatedesignresource.php' );
//include_once( 'lib/eztemplate/classes/eztemplate.php' );
//include_once( 'lib/ezutils/classes/ezsys.php' );

class eZTemplatesStatisticsReporter
{
    /*!
     static
    */
    static function generateStatistics( $as_html = true )
    {
        $statStartTime = microtime( true );
        $stats = '';

        if ( !eZTemplate::isTemplatesUsageStatisticsEnabled() )
            return $stats;

        if ( $as_html )
        {
            $stats .= "<h2>Templates used to render the page:</h2>";
            $stats .= ( "<table id='templateusage' summary='List of used templates' style='border: 1px dashed black;' cellspacing='0'>" .
                   "<tr><th>Usage count</th>" .
                   "<th>Requested template</th>" .
                   "<th>Template</th>" .
                   "<th>Template loaded</th>" .
                   "<th>Edit</th>" .
                   "<th>Override</th></tr>" );
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
            $templateViewFunction = 'visual/templateview';
            eZURI::transformURI( $templateViewFunction );
            $templateEditFunction = 'visual/templateedit';
            eZURI::transformURI( $templateEditFunction );
            $templateOverrideFunction = 'visual/templatecreate';
            eZURI::transformURI( $templateOverrideFunction );

            $std_base = eZTemplateDesignResource::designSetting( 'standard' );
            $wwwDir = eZSys::wwwDir();
            $editIconFile = "$wwwDir/design/$std_base/images/edit.gif";
            $overrideIconFile = "$wwwDir/design/$std_base/images/override-template.gif";

            $tdClass = 'used_templates_stats1';
            $j = 0;

            $currentSiteAccess = $GLOBALS['eZCurrentAccess']['name'];
        }

        $templatesUsageStatistics = eZTemplate::templatesUsageStatistics();

        $alreadyListedTemplate = $templateCounts = array();

        //Generate usage count for each unique template first.
        foreach( $templatesUsageStatistics as $templateInfo )
        {
            $actualTemplateName = $templateInfo['actual-template-name'];

            if ( !array_key_exists( $actualTemplateName, $templateCounts ) )
            {
                $templateCounts[$actualTemplateName] = 1;

            }
            else
            {
                ++$templateCounts[$actualTemplateName];
            }
        }

        //Then create the actual listing
        foreach ($templatesUsageStatistics as $templateInfo)
        {
            $actualTemplateName = $templateInfo['actual-template-name'];
            $requestedTemplateName = $templateInfo['requested-template-name'];
            $templateFileName = $templateInfo['template-filename'];

            if ( !in_array( $actualTemplateName, $alreadyListedTemplate ) )
            {
                $alreadyListedTemplate[] = $actualTemplateName;
                if ( $as_html )
                {
                    $tdClass = ( $j % 2 == 0 ) ? 'used_templates_stats1' : 'used_templates_stats2';

                    $requestedTemplateViewURI = $templateViewFunction . '/' . $requestedTemplateName;
                    $actualTemplateViewURI = $templateViewFunction . '/' . $actualTemplateName;

                    $templateEditURI = $templateEditFunction . '/' . $templateFileName;
                    $templateOverrideURI = $templateOverrideFunction . '/' . $actualTemplateName;

                    $actualTemplateNameOutput = ( $actualTemplateName == $requestedTemplateName ) ? "<span style=\"font-style: italic;\">&lt;No override&gt;</span>" : $actualTemplateName;

                    $stats .= (
                           "<tr><td class=\"$tdClass\" style=\"text-align: center;\">$templateCounts[$actualTemplateName]</td>" .
                           "<td class=\"$tdClass\"><a href=\"$requestedTemplateViewURI\">$requestedTemplateName</a></td>" .
                           "<td class=\"$tdClass\">$actualTemplateNameOutput</td>" .
                           "<td class=\"$tdClass\">$templateFileName</td>" .
                           "<td class=\"$tdClass\" align=\"center\"><a href=\"$templateEditURI/(siteAccess)/$currentSiteAccess\"><img src=\"$editIconFile\" width=\"$iconSizeX\" height=\"$iconSizeY\" alt=\"Edit template\" title=\"Edit template\" /></a></td>".
                           "<td class=\"$tdClass\" align=\"center\"><a href=\"$templateOverrideURI/(siteAccess)/$currentSiteAccess\"><img src=\"$overrideIconFile\" width=\"$iconSizeX\" height=\"$iconSizeY\" alt=\"Override template\" title=\"Override template\" /></a></td></tr>" );

                    $j++;
                }
                else
                {
                    $stats .= sprintf( $formatString, $requestedTemplateName, $actualTemplateName, $templateFileName );
                }
            }
        }

        $totalTemplatesCount = count( $templatesUsageStatistics );
        $totalUniqueTemplatesCopunt = count( array_keys( $alreadyListedTemplate ) );
        $statEndTime = microtime( true );
        $timeUsage = number_format( $statEndTime - $statStartTime, 4 );

        if ( $as_html )
        {
            $stats .= ( "<tr><td class=\"$tdClass\">&nbsp;</td>" .
                   "<td class=\"$tdClass\">&nbsp;</td>" .
                   "<td class=\"$tdClass\">&nbsp;</td>" .
                   "<td class=\"$tdClass\">&nbsp;</td>".
                   "<td class=\"$tdClass\">&nbsp;</td>".
                   "<td class=\"$tdClass\">&nbsp;</td></tr>" );
            $stats .= "<tr><td colspan=\"2\" style=\"text-align: left;\"><b>&nbsp;Number of times templates used: $totalTemplatesCount<br />&nbsp;Number of unique templates used: $totalUniqueTemplatesCopunt</b></td></tr>";
            $stats .= "<tr><td colspan=\"2\" style=\"text-align: left;\"><b>&nbsp;Time used to render template usage: $timeUsage secs</b></td></tr>";
            $stats .= "</table>";
        }
        else
        {
            $stats .= "\nTotal templates count: " . $totalTemplatesCount . "\n" . "Total unique templates count: " . $totalUniqueTemplatesCopunt . "\n";
        }

        return $stats;
    }
}

?>
