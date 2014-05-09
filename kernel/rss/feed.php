<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];

if ( !isset ( $Params['RSSFeed'] ) )
{
    eZDebug::writeError( 'No RSS feed specified' );
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$feedName = $Params['RSSFeed'];
$RSSExport = eZRSSExport::fetchByName( $feedName );

// Get and check if RSS Feed exists
if ( !$RSSExport )
{
    eZDebug::writeError( 'Could not find RSSExport : ' . $Params['RSSFeed'] );
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$config = eZINI::instance( 'site.ini' );
$cacheTime = intval( $config->variable( 'RSSSettings', 'CacheTime' ) );

$lastModified = gmdate( 'D, d M Y H:i:s', time() ) . ' GMT';

eZURI::setTransformURIMode( 'full' );

if ( $cacheTime <= 0 )
{
    $xmlDoc = $RSSExport->attribute( 'rss-xml-content' );
    $rssContent = $xmlDoc;
}
else
{
    $cacheDir = eZSys::cacheDirectory();
    $currentSiteAccessName = $GLOBALS['eZCurrentAccess']['name'];
    $cacheFilePath = $cacheDir . '/rss/' . md5( $currentSiteAccessName . $feedName ) . '.xml';

    if ( !is_dir( dirname( $cacheFilePath ) ) )
    {
        eZDir::mkdir( dirname( $cacheFilePath ), false, true );
    }

    $cacheFile = eZClusterFileHandler::instance( $cacheFilePath );

    if ( !$cacheFile->exists() or ( time() - $cacheFile->mtime() > $cacheTime ) )
    {
        $xmlDoc = $RSSExport->attribute( 'rss-xml-content' );
        // Get current charset
        $charset = eZTextCodec::internalCharset();
        $rssContent = trim( $xmlDoc );
        $cacheFile->storeContents( $rssContent, 'rsscache', 'xml' );
    }
    else
    {
        $lastModified = gmdate( 'D, d M Y H:i:s', $cacheFile->mtime() ) . ' GMT';

        if( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) )
        {
            $ifModifiedSince = $_SERVER['HTTP_IF_MODIFIED_SINCE'];

            // Internet Explorer specific
            $pos = strpos($ifModifiedSince,';');
            if ( $pos !== false )
                $ifModifiedSince = substr( $ifModifiedSince, 0, $pos );

            if( strcmp( $lastModified, $ifModifiedSince ) == 0 )
            {
                header( 'HTTP/1.1 304 Not Modified' );
                header( 'Last-Modified: ' . $lastModified );
                header( 'X-Powered-By: ' . eZPublishSDK::EDITION );
                eZExecution::cleanExit();
           }
        }
        $rssContent = $cacheFile->fetchContents();
    }
}

// Set header settings
$httpCharset = eZTextCodec::httpCharset();
header( 'Last-Modified: ' . $lastModified );

if ( $RSSExport->attribute( 'rss_version' ) === 'ATOM' )
    header( 'Content-Type: application/xml; charset=' . $httpCharset );
else
    header( 'Content-Type: application/rss+xml; charset=' . $httpCharset );

header( 'Content-Length: ' . strlen( $rssContent ) );
header( 'X-Powered-By: ' . eZPublishSDK::EDITION );

for ( $i = 0, $obLevel = ob_get_level(); $i < $obLevel; ++$i )
{
    ob_end_clean();
}

echo $rssContent;

eZExecution::cleanExit();


?>
