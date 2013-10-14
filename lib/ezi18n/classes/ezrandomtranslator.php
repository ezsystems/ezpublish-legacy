<?php
/**
 * File containing the eZRandomTranslator class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
    public function __construct( $is_key_based )
    {
        parent::__construct( $is_key_based );
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
