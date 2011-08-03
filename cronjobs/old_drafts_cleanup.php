<?php
/**
 * File containing the old_drafts_cleanup.php cronjob
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$cli->output( "Cleaning up user's drafts..." );

// Cleaning up usual drafts
$ini = eZINI::instance( 'content.ini' );
$draftsCleanUpLimit = $ini->hasVariable( 'VersionManagement', 'DraftsCleanUpLimit' ) ?
                         $ini->variable( 'VersionManagement', 'DraftsCleanUpLimit' ) : 0;
$durationSetting = $ini->hasVariable( 'VersionManagement', 'DraftsDuration' ) ?
                      $ini->variable( 'VersionManagement', 'DraftsDuration' ) : array( 'days' => 90 );

$isDurationSet = false;
$duration = 0;
if ( is_array( $durationSetting ) )
{
    if ( isset( $durationSetting[ 'days' ] ) and is_numeric( $durationSetting[ 'days' ] ) )
    {
        $duration += $durationSetting[ 'days' ] * 60 * 60 * 24;
        $isDurationSet = true;
    }
    if ( isset( $durationSetting[ 'hours' ] ) and is_numeric( $durationSetting[ 'hours' ] ) )
    {
        $duration += $durationSetting[ 'hours' ] * 60 * 60;
        $isDurationSet = true;
    }
    if ( isset( $durationSetting[ 'minutes' ] ) and is_numeric( $durationSetting[ 'minutes' ] ) )
    {
        $duration += $durationSetting[ 'minutes' ] * 60;
        $isDurationSet = true;
    }
    if ( isset( $durationSetting[ 'seconds' ] ) and is_numeric( $durationSetting[ 'seconds' ] ) )
    {
        $duration += $durationSetting[ 'seconds' ];
        $isDurationSet = true;
    }
}

if ( $isDurationSet )
{
    $expiryTime = time() - $duration;
    $processedCount = eZContentObjectVersion::removeVersions( eZContentObjectVersion::STATUS_DRAFT, $draftsCleanUpLimit, $expiryTime );

    $cli->output( "Cleaned up " . $processedCount . " drafts" );
}
else
{
    $cli->output( "Lifetime is not set for user's drafts (see your ini-settings, content.ini, VersionManagement section)." );
}

?>
