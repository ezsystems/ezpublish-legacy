<?php
/**
 * File containing the eZShuffleTranslator class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZShuffleTranslator ez1337translator.php
  \ingroup eZTranslation
  \brief Translates text by moving characters around

*/

class eZShuffleTranslator extends eZTranslatorHandler
{
    public $MaxChars;
    /**
     * Constructor
     *
     * @param int $max_chars
     */
    public function __construct( $max_chars = 3 )
    {
        parent::__construct( false );

        $this->MaxChars = $max_chars;
        $this->Messages = array();
    }

    function findMessage( $context, $source, $comment = null )
    {
        $man = eZTranslatorManager::instance();
        $translation = $this->shuffleText( $source );
        return $man->createMessage( $context, $source, $comment, $translation );
    }

    /*!
     Reorders some of the characters in $text and returns the new text string.
     The maximum number of reorders is taken from MaxChars.
    */
    function &shuffleText( $text )
    {
        $num = mt_rand( 0, $this->MaxChars );
        for ( $i = 0; $i < $num; ++$i )
        {
            $len = strlen( $text );
            $offs = mt_rand( 0, $len - 1 );
            if ( $offs == 0 )
            {
                $tmp = $text[$offs];
                $text[$offs] = $text[$len - 1];
                $text[$len] = $tmp;
            }
            else
            {
                $delta = -1;
                if ( $text[$offs+$delta] == " " and
                     $offs + 1 < $len )
                    $delta = 1;
                $tmp = $text[$offs];
                $text[$offs] = $text[$offs+$delta];
                $text[$offs+$delta] = $tmp;
            }
        }
        return $text;
    }

    function translate( $context, $source, $comment = null )
    {
        $msg = $this->findMessage( $context, $source, $comment );
        if ( $msg !== null )
        {
            return $msg["translation"];
        }

        return null;
    }

    /// \privatesection
    /// Contains the hash table with cached 1337 translations
    public $Messages;
}

?>
