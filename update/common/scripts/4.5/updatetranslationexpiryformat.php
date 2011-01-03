#!/usr/bin/env php
<?php
/**
 * File containing the translation expiry format upgrade script.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package update
 */

foreach ( glob( "var/*/cache/expiry.php" ) as $expiryFile )
{
    include $expiryFile;
    if ( isset( $Timestamps["ts-translation-cache"] ) )
    {
        $Timestamps["ts-translation-cache"] = array();
    }
    file_put_contents( $expiryFile, "<?php\n\$Timestamps = " . var_export( $Timestamps, true ) . ";\n?>" );
}
