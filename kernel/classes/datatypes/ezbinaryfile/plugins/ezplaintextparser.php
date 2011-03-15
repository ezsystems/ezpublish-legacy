<?php
/**
 * File containing the eZPlainTextParser class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
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
