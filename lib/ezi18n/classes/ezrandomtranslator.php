<?php
//
// Definition of eZRandomTranslator class
//
// Created on: <10-Jun-2002 11:05:00 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file eztranslatorgroup.php
*/

/*!
  \class eZRandomTranslator eztranslatorgroup.php
  \ingroup eZTranslation
  \brief Translates text by picking randomly among it's sub handlers

*/

include_once( "lib/ezi18n/classes/eztranslatorgroup.php" );

class eZRandomTranslator extends eZTranslatorGroup
{
    /*!
     Constructor
    */
    function eZRandomTranslator( $is_key_based )
    {
        $this->eZTranslatorGroup( $is_key_based );
        srand( $this->makeSeed() );
    }

    /*!
     \reimp
     Returns a random pick from the registered handlers.
    */
    function keyPick( $key )
    {
        if ( $this->handlerCount() == 0 )
            return -1;
        return rand( 0, $this->handlerCount() - 1 );
    }

    /*!
     \reimp
     Returns a random pick from the registered handlers.
    */
    function pick( $context, $source, $comment )
    {
        if ( $this->handlerCount() == 0 )
            return -1;
        return rand( 0, $this->handlerCount() - 1 );
    }

    /*!
     \private
     Generates a seed usable for srand() and returns it.
    */
    function makeSeed()
    {
        list( $usec, $sec ) = explode( ' ', microtime() );
        return (float) $sec + ( (float) $usec * 100000 );
    }
}

?>
