<?php
/**
 * File containing the eZImageGDFactory class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

class eZImageGDFactory extends eZImageFactory
{
    /*!
     Initializes the factory with the name \c 'shell'
    */
    function eZImageGDFactory()
    {
        $this->eZImageFactory( 'gd' );
    }

    /*!
     Creates eZImageGDHandler objects and returns them.
    */
    static function produceFromINI( $iniGroup, $iniFilename = false )
    {
        $convertHandler = eZImageGDHandler::createFromINI( $iniGroup, $iniFilename );
        return $convertHandler;
    }
}

?>
