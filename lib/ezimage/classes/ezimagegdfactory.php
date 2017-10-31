<?php
/**
 * File containing the eZImageGDFactory class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

class eZImageGDFactory extends eZImageFactory
{
    public function __construct()
    {
        parent::__construct( 'gd' );
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
