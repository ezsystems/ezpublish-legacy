<?php
//
// Definition of  class
//
// Created on: <07-Jul-2003 10:06:19 wy>
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

/*! \file linkcheck.php
*/
//include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
//include_once( "lib/ezutils/classes/ezini.php" );
//include_once( "lib/ezutils/classes/ezhttptool.php" );

if ( !$isQuiet )
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
                list($userName, $host) = split("@", $url);
                list($host, $junk)= split("\?", $host);
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
        //include_once( 'kernel/classes/ezurlaliasml.php' );
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

if ( !$isQuiet )
    $cli->output( "All links have been checked!" );

?>
