<?php
//
// Created on: <17-Apr-2002 11:05:08 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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

/*!
  Returns list of contributors;
  Searches all php files in \a $pathToDir and tries to fetch contributor's info
*/
function getContributors( $pathToDir )
{
    include_once( 'lib/ezfile/classes/ezdir.php' );
    $contribFiles = eZDir::recursiveFind( $pathToDir, "php" );
    $contributors = '';
    if ( count( $contribFiles ) )
    {
        $contributors = "<ul>";
        foreach ( $contribFiles as $contribFile )
        {
            include_once( $contribFile );
            if ( !isset( $contributorSettings ) )
                continue;

            $name = $contributorSettings['name'];
            $tmpFiles = explode( ',', $contributorSettings['files'] );
            $updatedFiles = array();
            foreach ( $tmpFiles as $file )
            {
                if ( trim( $file ) )
                    $updatedFiles[] = trim( $file,"\n\r" );
            }
            $files = implode( ', ', $updatedFiles );
            $contributors .="<li>$name: $files</li>";
        }
        $contributors .= "</ul>";
    }
    return $contributors;
}

/*!
  Returns third-party software from \a $pathToFile
*/
function getThirdPartySoftware( $pathToFile )
{
    if ( !file_exists( $pathToFile ) )
        return '';

    include_once( $pathToFile );
    if ( !isset( $thirdPartySoftware ) )
        return '';

    $thirdPartySoftware = array_unique( $thirdPartySoftware );

    $thirdParty = '<ul>';
    foreach ( $thirdPartySoftware as $part )
    {
        $thirdParty .= "<li>$part</li>";
    }
    $thirdParty .= '</ul>';
    return $thirdParty;
}

/*!
  Returns parsed array to str
*/
function parseArray( $array )
{
    if ( !is_array( $array ) )
        return $array;

    $string = '';
    $coma = '<br> ';
    foreach ( array_keys( $array ) as $itemKey )
    {
        $item = $array[$itemKey];
        $key = is_numeric( $itemKey ) ? '' :  $itemKey . ' : ';
        $parsed = parseArray( $item );
        $string .= $key . $parsed . $coma;
    }
    return $string;
}

/*!
  Returns active extentions info in run-time
*/
function getExtensionsInfo()
{
    include_once( 'lib/ezutils/classes/ezini.php' );
    $siteINI = eZINI::instance();
    $extensionDir = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' );
    $selectedExtensionArray       = $siteINI->variable( 'ExtensionSettings', "ActiveExtensions" );
    $selectedAccessExtensionArray = $siteINI->variable( 'ExtensionSettings', "ActiveAccessExtensions" );
    $selectedExtensions           = array_merge( $selectedExtensionArray, $selectedAccessExtensionArray );
    $selectedExtensions           = array_unique( $selectedExtensions );
    $result = '';
    foreach ( $selectedExtensions as $extension )
    {
        $pathToFile = $extensionDir . '/' . $extension . '/ezinfo.php';
        if ( !file_exists( $pathToFile ) )
            continue;

        include_once( $pathToFile );
        $className = $extension .'Info';
        $info = call_user_func_array( array( $className, 'info' ),array() );
        if ( !$info )
            continue;
        $result .= '<ul><li>';
        foreach ( array_keys( $info ) as $key )
        {
            $item = $info[$key];
            if ( !is_array( $item ) )
            {
                $result .= $key . ' : ' . $item . '<br>';
            }
            else
            {
                $str = parseArray( $item );
                $result .= "<b>$key:</b>
                            <ul><li>$str</li></ul>
                            ";

            }
        }
        $result .= '</li></ul>';
    }
    return $result;
}

$Module =& $Params['Module'];

include_once( 'lib/version.php' );

$ezinfo = array( 'version' => eZPublishSDK::version( true ),
                 'version_alias' => eZPublishSDK::version( true, true ) );

$header = "<a href=\"http://ez.no/developer\"><h3>About eZ publish " . eZPublishSDK::version( true )  ." ( " . eZPublishSDK::version( true, true ). " )</h3></a>
<hr noshade=\"noshade\"  />";

$whatIsEzPublish = '<h3>What is eZ publish?</h3>
<p>eZ Publish 3 is a professional PHP application framework with advanced
CMS (content management system) functionality. As a CMS its most notable
featureis its revolutionary, fully customizable and extendable content
model. Thisis also what makes eZ publish suitable as a platform for
general PHP  development,allowing you to rapidly create professional
web-based applications.</p>

<p>Standard CMS functionality (such as news publishing, e-commerce and
forums) are already implemented and ready to use. Standalone libraries
can be used for cross-platform database-independent browser-neutral
PHP projects. Because eZ publish 3 is a web-based application, it can
be accessed from anywhere you have an internet connection.</p>';

$license ='<h3>Licence</h3>' .
## BEGIN LICENSE INFO ##
'<p> This copy of eZ Publish is distributed under the terms and conditions of
the GNU General Public License (GPL). Briefly summarized, the GPL gives
you the right to use, modify and share this copy of eZ Publish. If you
choose to share eZ Publish, you may only share it under the terms and
conditions of the GPL. If you share a modified version of eZ Publish,
these modifications must also be placed under the GPL. Read the
complete legal terms and conditions of the GPL at
http://www.gnu.org/licenses/gpl.txt or see the file named LICENSE in
the root directory of this eZ Publish distribution.
</p>';
## END LICENSE INFO ##

### Contributor Credits ###
$contributors = '<h3>Contributors</h3>
<p>The following is a list of eZ Publish contributors who have licensed
their work for use by eZ systems AS under the terms and conditions of
the eZ Contributor Licensing Agreement. As permitted by this agreement
with the contributors, eZ systems AS is redistributing the
contribution under the same license as the file that the contribution
is included in. The list of contributors includes the contributors\'s
name, optional contact info and a list of files that they have
either contributed or contributed work to.</p>';

$contributors .= getContributors( EZ_ABOUT_CONTRIBUTORS_DIR );

### Copyright Notice ###
$copyrightNotice = '<h3>Copyright Notice</h3>
<p>Copyright &copy; 1999-2006 eZ systems AS, with portions copyright by
other parties. A complete list of all contributors and third-party
software follows.</p>';

### Third-Party Software ####
$thirdPartySoftware = '<h3>Third-Party Software</h3>
<p>The following is a list of the third-party software that is
distributed with this copy of eZ Publish. The list of third party
software includes the license for the software in question and the
directory or files that contain the third-party software.</p>';

$thirdPartySoftware .= getThirdPartySoftware( EZ_ABOUT_THIRDPARTY_SOFTWARE_FILE );

### Extensions ###
$extensions = '<h3>Extensions</H3>
<p>The following is a list of the extensions that have been loaded at
 run-time by this copy of eZ Publish. </p>';

$extensions .= getExtensionsInfo();

$text = $whatIsEzPublish . $license . $copyrightNotice . $contributors . $thirdPartySoftware . $extensions;

$Result = array();
$Result['content'] = $text;
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/ezinfo', 'Info' ) ),
                         array( 'url' => false,
                                'text' => ezi18n( 'kernel/ezinfo', 'About' ) ) );

?>
