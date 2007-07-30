<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
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

define( 'EZ_ABOUT_CONTRIBUTORS_DIR', 'var/storage/contributors' );
define( 'EZ_ABOUT_THIRDPARTY_SOFTWARE_FILE', 'var/storage/third_party_software.php' );

include_once( 'kernel/common/template.php' );
include_once( 'lib/version.php' );

/*!
  Returns list of contributors;
  Searches all php files in \a $pathToDir and tries to fetch contributor's info
*/
function getContributors( $pathToDir )
{
    include_once( 'lib/ezfile/classes/ezdir.php' );
    $contribFiles = eZDir::recursiveFind( $pathToDir, "php" );
    $contributors = array();
    if ( count( $contribFiles ) )
    {
        foreach ( $contribFiles as $contribFile )
        {
            include_once( $contribFile );
            if ( !isset( $contributorSettings ) )
                continue;

            $tmpFiles = explode( ',', $contributorSettings['files'] );
            $updatedFiles = array();
            foreach ( $tmpFiles as $file )
            {
                if ( trim( $file ) )
                    $updatedFiles[] = trim( $file,"\n\r" );
            }
            $files = implode( ', ', $updatedFiles );
            $contributorSettings['files'] = $files;
            $contributors[] = $contributorSettings;
        }
    }
    return $contributors;
}

/*!
  Returns third-party software from \a $pathToFile
*/
function getThirdPartySoftware( $pathToFile )
{
    if ( !file_exists( $pathToFile ) )
        return array();

    include_once( $pathToFile );
    if ( !isset( $thirdPartySoftware ) )
        return array();

    $thirdPartySoftware = array_unique( $thirdPartySoftware );
    return $thirdPartySoftware;
}

/*!
  Returns active extentions info in run-time
*/
function getExtensionsInfo()
{
    include_once( 'lib/ezutils/classes/ezini.php' );
    include_once( 'lib/ezutils/classes/ezextension.php' );

    $siteINI = eZINI::instance();
    $extensionDir = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' );
    $selectedExtensionArray       = $siteINI->variable( 'ExtensionSettings', "ActiveExtensions" );
    $selectedAccessExtensionArray = $siteINI->variable( 'ExtensionSettings', "ActiveAccessExtensions" );
    $selectedExtensions           = array_merge( $selectedExtensionArray, $selectedAccessExtensionArray );
    $selectedExtensions           = array_unique( $selectedExtensions );
    $result = array();
    foreach ( $selectedExtensions as $extension )
    {
        $extensionInfo = eZExtension::extensionInfo( $extension );
        if ( $extensionInfo )
            $result[$extension] = $extensionInfo;
    }
    return $result;
}

/*!
  Replaces all occurrences (in \a $subjects) of the search string (keys of \a $searches )
  with the replacement string (values of \a $searches)
*/
function strReplaceByArray( $searches = array(), $subjects = array() )
{
    foreach ( array_keys( $subjects ) as $subjectKey )
    {
        $subject =& $subjects[$subjectKey];
        foreach ( array_keys( $searches ) as $search )
        {
            $replace = $searches[$search];
            if ( is_array( $subject ) )
            {
                foreach ( array_keys( $subject ) as $sububjectItemKey )
                {
                    $sububjectItem =& $subject[$sububjectItemKey];
                    $sububjectItem = str_replace( $search, $replace, $sububjectItem );
                }
            }
            else
                $subject = str_replace( $search, $replace, $subject );
        }
    }
}

$ezinfo = eZPublishSDK::version( true );

$whatIsEzPublish = 'eZ publish 3 is a professional PHP application framework with advanced
CMS (content management system) functionality. As a CMS its most notable
featureis its revolutionary, fully customizable and extendable content
model. Thisis also what makes eZ publish suitable as a platform for
general PHP  development,allowing you to rapidly create professional
web-based applications.

Standard CMS functionality (such as news publishing, e-commerce and
forums) are already implemented and ready to use. Standalone libraries
can be used for cross-platform database-independent browser-neutral
PHP projects. Because eZ publish 3 is a web-based application, it can
be accessed from anywhere you have an internet connection.';

$license =
## BEGIN LICENSE INFO ##
'This copy of eZ publish is distributed under the terms and conditions of
the GNU General Public License (GPL). Briefly summarized, the GPL gives
you the right to use, modify and share this copy of eZ publish. If you
choose to share eZ publish, you may only share it under the terms and
conditions of the GPL. If you share a modified version of eZ publish,
these modifications must also be placed under the GPL. Read the
complete legal terms and conditions of the GPL at
http://www.gnu.org/licenses/gpl.txt or see the file named LICENSE in
the root directory of this eZ publish distribution.';
## END LICENSE INFO ##

$contributors = getContributors( EZ_ABOUT_CONTRIBUTORS_DIR );
$thirdPartySoftware = getThirdPartySoftware( EZ_ABOUT_THIRDPARTY_SOFTWARE_FILE );
$extensions = getExtensionsInfo();

strReplaceByArray( array( 'eZ Systems AS' => '<a href="http://ez.no/">eZ Systems AS</a>',
                          'eZ systems AS' => '<a href="http://ez.no/">eZ Systems AS</a>',
                          'eZ Publish' => '<a href="http://ez.no/ezpublish">eZ Publish</a>',
                          'eZ publish' => '<a href="http://ez.no/ezpublish">eZ Publish</a>' ),
                   array( &$whatIsEzPublish, &$license, &$contributors, &$thirdPartySoftware, &$extensions ) );

$tpl = templateInit();
$tpl->setVariable( 'ezinfo', $ezinfo );
$tpl->setVariable( 'what_is_ez_publish', $whatIsEzPublish );
$tpl->setVariable( 'license', $license );
$tpl->setVariable( 'contributors', $contributors );
$tpl->setVariable( 'third_party_software', $thirdPartySoftware );
$tpl->setVariable( 'extensions', $extensions );

$Result = array();
$Result['content'] = $tpl->fetch( "design:ezinfo/about.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/ezinfo', 'Info' ) ),
                         array( 'url' => false,
                                'text' => ezi18n( 'kernel/ezinfo', 'About' ) ) );

?>
