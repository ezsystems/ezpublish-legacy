<?php
/**
 * File containing the eZPlainTextParser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZPlainTextParser ezplaintextparser.php
  \ingroup eZKernel
  \brief The class eZPlainTextParser handles parsing of text files and returns the metadata

*/

class eZPlainTextParser
{
    function parseFile( $fileName )
    {
        $metaData = "";
        if ( file_exists( $fileName ) )
        {
            $fp = fopen( $fileName, "r" );
            $metaData = fread( $fp, filesize( $fileName ) );
            fclose( $fp );
        }

        return $metaData;
    }
}

?>
