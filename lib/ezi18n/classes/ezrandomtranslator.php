<?php
//
// Definition of eZRandomTranslator class
//
// Created on: <10-Jun-2002 11:05:00 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file
*/

/*!
  \class eZRandomTranslator eztranslatorgroup.php
  \ingroup eZTranslation
  \brief Translates text by picking randomly among it's sub handlers

*/

class eZRandomTranslator extends eZTranslatorGroup
{
    /*!
     Constructor
    */
    function eZRandomTranslator( $is_key_based )
    {
        $this->eZTranslatorGroup( $is_key_based );
        mt_srand();
    }

    /*!
     Returns a random pick from the registered handlers.
    */
    function keyPick( $key )
    {
        if ( $this->handlerCount() == 0 )
            return -1;
        return mt_rand( 0, $this->handlerCount() - 1 );
    }

    /*!
     Returns a random pick from the registered handlers.
    */
    function pick( $context, $source, $comment )
    {
        if ( $this->handlerCount() == 0 )
            return -1;
        return mt_rand( 0, $this->handlerCount() - 1 );
    }

    /*!
     \private
     Generates a seed usable for srand() and returns it.
     DEPRECATED: as of eZ Publish 4.2 (seeding is not needed as of PHP 4.2.0)
    */
    function makeSeed()
    {
        $seed = microtime( true ) * 100000;
        return $seed;
    }
}

?>
