<?php
/**
 * File containing the eZMD5 class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZMD5 ezmd5.php
  \brief Class handling MD5 file operations
*/

class eZMD5
{
    const CHECK_SUM_LIST_FILE = 'share/filelist.md5';

    /**
     * Check MD5 sum file to check if files have changed. Return array of changed files.
     *
     * @param string $file File name of md5 check sums file
     * @param string $subDirStr Sub dir where files in md5 check sum file resides
     *        e.g. '' (default) if root and 'extension/ezoe/' for ezoe extension.
     * @return array List of miss-matching files.
    */
    static function checkMD5Sums( $file, $subDirStr = '' )
    {
        $result = array();
        $lines  = file( $file, FILE_IGNORE_NEW_LINES );

        if ( $lines !== false && isset( $lines[0] ) )
        {
            foreach ( $lines as $key => $line )
            {
                if ( isset( $line[34] ) )
                {
                    $md5Key = substr( $line, 0, 32 );
                    $filename = $subDirStr . substr( $line, 34 );
                    if ( !file_exists( $filename ) || $md5Key != md5_file( $filename ) )
                    {
                        $result[] = $filename;
                    }
                }
            }
        }

        return $result;
    }
}
?>
