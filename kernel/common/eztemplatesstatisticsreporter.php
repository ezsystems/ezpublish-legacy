<?php
/**
 * File containing the eZTemplatesStatisticsReporter class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZTemplatesStatisticsReporter eztemplatesstatisticsreporter.php
  \brief Generates statistics of tempate usage.

*/

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
            $stats .= "<h3>Templates used to render the page:</h3>";
            $stats .= ( "<table id='templateusage' class='debug_resource_usage' title='List of used templates'>" .
                   "<tr><th title='Usage count of this particular template'>Usage</th>" .
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
                    $requestedTemplateViewURI = $templateViewFunction . '/' . $requestedTemplateName;
                    $actualTemplateViewURI = $templateViewFunction . '/' . $actualTemplateName;

                    $templateEditURI = $templateEditFunction . '/' . $templateFileName;
                    $templateOverrideURI = $templateOverrideFunction . '/' . $actualTemplateName;

                    $actualTemplateNameOutput = ( $actualTemplateName == $requestedTemplateName ) ? "<em>&lt;No override&gt;</em>" : $actualTemplateName;

                    $stats .= (
                           "<tr class='data'><td>$templateCounts[$actualTemplateName]</td>" .
                           "<td><a href=\"$requestedTemplateViewURI\">$requestedTemplateName</a></td>" .
                           "<td>$actualTemplateNameOutput</td>" .
                           "<td>$templateFileName</td>" .
                           "<td><a href=\"$templateEditURI/(siteAccess)/$currentSiteAccess\"><img src=\"$editIconFile\" width=\"$iconSizeX\" height=\"$iconSizeY\" alt=\"Edit template\" title=\"Edit template\" /></a></td>".
                           "<td><a href=\"$templateOverrideURI/(siteAccess)/$currentSiteAccess\"><img src=\"$overrideIconFile\" width=\"$iconSizeX\" height=\"$iconSizeY\" alt=\"Override template\" title=\"Override template\" /></a></td></tr>" );

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
            $stats .= "<tr><td colspan=\"6\"><b>&nbsp;Number of times templates used: $totalTemplatesCount<br />&nbsp;Number of unique templates used: $totalUniqueTemplatesCopunt</b></td></tr>";
            $stats .= "<tr><td colspan=\"6\"><b>&nbsp;Time used to render template usage: $timeUsage secs</b></td></tr>";
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
