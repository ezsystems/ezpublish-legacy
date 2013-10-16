<?php
/**
 * File containing the eZImageShellFactory class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

class eZImageShellFactory extends eZImageFactory
{
    public function __construct()
    {
        parent::__construct( 'shell' );
    }

    /*!
     Creates eZImageShellHandler objects and returns them.
    */
    static function produceFromINI( $iniGroup, $iniFilename = false )
    {
        return eZImageShellHandler::createFromINI( $iniGroup, $iniFilename );
    }
}

?>
