<?php
/**
 * File containing the eZImageShellFactory class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
