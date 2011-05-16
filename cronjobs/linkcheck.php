<?php
/**
 * File containing the linkcheck.php cronjob
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */
$cli->output( "Checking link ..." );

$cronjobIni = eZINI::instance( 'cronjob.ini' );
$siteURLs = $cronjobIni->variable( 'linkCheckSettings', 'SiteURL' );
$linkList = eZURL::fetchList( array( 'only_published' => true ) );
foreach ( $linkList as $link )
{
    $linkID = $link->attribute( 'id' );
    $url = $link->attribute( 'url' );
    $isValid = $link->attribute( 'is_valid' );

    $cli->output( "check-" . $cli->stylize( 'emphasize', $url ) . " ", false );
    if ( preg_match("/^(http:)/i", $url ) or
         preg_match("/^(ftp:)/i", $url ) or
         preg_match("/^(https:)/i", $url ) or
         preg_match("/^(file:)/i", $url ) or
         preg_match("/^(mailto:)/i", $url ) )
    {
        if ( preg_match("/^(mailto:)/i", $url))
        {
            if ( eZSys::osType() != 'win32' )
            {
                $url = trim( preg_replace("/^mailto:(.+)/i", "\\1", $url));
                list($userName, $host) = explode( '@', $url );
                list($host, $junk) = explode( '?', $host );
                $dnsCheck = checkdnsrr( $host,"MX" );
                if ( !$dnsCheck )
                {
                    if ( $isValid )
                        eZURL::setIsValid( $linkID, false );
                    $cli->output( $cli->stylize( 'warning', "invalid" ) );
                }
                else
                {
                    if ( !$isValid )
                        eZURL::setIsValid( $linkID, true );
                    $cli->output( $cli->stylize( 'success', "valid" ) );
                }
            }
        }
        else if ( preg_match("/^(http:)/i", $url ) or
                  preg_match("/^(file:)/i", $url ) or
                  preg_match("/^(ftp:)/i", $url ) )
        {
            if ( !eZHTTPTool::getDataByURL( $url, true, 'eZ Publish Link Validator' ) )
            {
                if ( $isValid )
                    eZURL::setIsValid( $linkID, false );
                $cli->output( $cli->stylize( 'warning', "invalid" ) );
            }
            else
            {
                if ( !$isValid )
                    eZURL::setIsValid( $linkID, true );
                $cli->output( $cli->stylize( 'success', "valid" ) );
            }
        }
        else
        {
            $cli->output( "Couldn't check https protocol" );
        }
    }
    else
    {
        $translateResult = eZURLAliasML::translate( $url );

        if ( !$translateResult )
        {
              $isInternal = false;
              // Check if it is a valid internal link.
              foreach ( $siteURLs as $siteURL )
              {
                  $siteURL = preg_replace("/\/$/e", "", $siteURL );
                  $fp = @fopen( $siteURL . "/". $url, "r" );
                  if ( !$fp )
                  {
                      // do nothing
                  }
                  else
                  {
                      $isInternal = true;
                      fclose($fp);
                  }
              }
              $translateResult = $isInternal;
        }
        if ( $translateResult )
        {
            if ( !$isValid )
                eZURL::setIsValid( $linkID, true );
            $cli->output( $cli->stylize( 'success', "valid" ) );
        }
        else
        {
            if ( $isValid )
                eZURL::setIsValid( $linkID, false );
            $cli->output( $cli->stylize( 'warning', "invalid" ) );
        }
    }
    eZURL::setLastChecked( $linkID );
}

$cli->output( "All links have been checked!" );

?>
