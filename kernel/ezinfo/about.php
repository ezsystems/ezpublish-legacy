<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

define( 'EZ_ABOUT_CONTRIBUTORS_DIR', 'var/storage/contributors' );
define( 'EZ_ABOUT_THIRDPARTY_SOFTWARE_FILE', 'var/storage/third_party_software.php' );


/*!
  Returns contents of LICENSE file in ezp legacy root directory, or false on failure.
*/
function getLicense()
{
    return file_get_contents( 'LICENSE' );
}

/*!
  Returns list of contributors;
  Searches all php files in \a $pathToDir and tries to fetch contributor's info
*/
function getContributors( $pathToDir )
{
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

  Returns array with replacements
*/
function strReplaceByArray( $searches = array(), $subjects = array() )
{
    $retArray = array();
    foreach( $subjects as $key => $subject )
    {
        if ( is_array( $subject ) )
        {
            $retArray[$key] = strReplaceByArray( $searches, $subject );
        }
        else
        {
            $retArray[$key] = str_replace( array_keys( $searches ), $searches, $subject );
        }
    }
    return $retArray;
}

$ezinfo = eZPublishSDK::version( true );

$whatIsEzPublish = '<p>eZ Publish is a professional PHP application framework with advanced
CMS (content management system) functionality. As a CMS, its most notable
feature is its revolutionary, fully customizable and extendable content
model. This is also what makes eZ Publish suitable as a platform for
general PHP development, allowing you to rapidly create professional
web-based applications.</p>

<p>Standard CMS functionality (such as news publishing, e-commerce and
forums) are already implemented and ready to use. Standalone libraries
can be used for cross-platform database-independent browser-neutral
PHP projects. Because eZ Publish is a web-based application, it can
be accessed from anywhere you have an internet connection.</p>';

$license = getLicense();
$contributors = getContributors( EZ_ABOUT_CONTRIBUTORS_DIR );
$thirdPartySoftware = getThirdPartySoftware( EZ_ABOUT_THIRDPARTY_SOFTWARE_FILE );
$extensions = getExtensionsInfo();

list( $whatIsEzPublish,
      $contributors,
      $thirdPartySoftware,
      $extensions ) = strReplaceByArray( array( 'eZ Systems AS' => '<a href="http://ez.no/">eZ Systems AS</a>',
                                                'eZ Systems as' => '<a href="http://ez.no/">eZ Systems AS</a>',
                                                'eZ systems AS' => '<a href="http://ez.no/">eZ Systems AS</a>',
                                                'eZ systems as' => '<a href="http://ez.no/">eZ Systems AS</a>',
                                                'eZ Publish' => '<a href="http://ez.no/ezpublish">eZ Publish</a>',
                                                'eZ publish' => '<a href="http://ez.no/ezpublish">eZ Publish</a>' ),
                                         array( $whatIsEzPublish, $contributors, $thirdPartySoftware, $extensions ) );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'ezinfo', $ezinfo );
$tpl->setVariable( 'what_is_ez_publish', $whatIsEzPublish );
$tpl->setVariable( 'license', $license );
$tpl->setVariable( 'contributors', $contributors );
$tpl->setVariable( 'third_party_software', $thirdPartySoftware );
$tpl->setVariable( 'extensions', $extensions );

$Result = array();
$Result['content'] = $tpl->fetch( "design:ezinfo/about.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/ezinfo', 'Info' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/ezinfo', 'About' ) ) );

?>
