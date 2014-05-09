<?php
/**
 * File containing the eZRandomTranslator class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
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
}

?>
