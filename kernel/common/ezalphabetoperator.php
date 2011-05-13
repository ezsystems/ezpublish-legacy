<?php
/**
 * File containing the eZAlphabetOperator class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

//!! eZKernel
//! The class eZAlphabetOperator does
/*!

*/


class eZAlphabetOperator
{
    function eZAlphabetOperator( $alphabet = 'alphabet' )
    {
        $this->Operators = array( $alphabet );
        $this->Alphabet = $alphabet;
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
    {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list exists per operator type.
    */
    function namedParameterPerOperator()
    {
        return true;
    }

    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$value, $namedParameters )
    {
        switch ( $operatorName )
        {
            case $this->Alphabet:
            {
                $alphabet = eZAlphabetOperator::fetchAlphabet();
                $value = $alphabet;
            } break;
        }
    }

    /*!
      Static
      Returns alphabet.
    */
    static function fetchAlphabet()
    {
        $contentINI = eZINI::instance( 'content.ini' );

        $alphabetRangeList = $contentINI->hasVariable( 'AlphabeticalFilterSettings', 'AlphabetList' )
                             ? $contentINI->variable( 'AlphabeticalFilterSettings', 'AlphabetList' )
                             : array();

        $alphabetFromArray = $contentINI->hasVariable( 'AlphabeticalFilterSettings', 'ContentFilterList' )
                             ? $contentINI->variable( 'AlphabeticalFilterSettings', 'ContentFilterList' )
                             : array( 'default' );

        // If alphabet list is empty
        if ( count( $alphabetFromArray ) == 0 )
            return false;

        $alphabetRangeList = array_merge( $alphabetRangeList, array( 'default' => '97-122' ) );
        $alphabet = array();
        foreach ( $alphabetFromArray as $alphabetFrom )
        {
            // If $alphabetFrom exists in range array $alphabetRangeList
            if ( isset( $alphabetRangeList[$alphabetFrom] ) )
            {
                $lettersArray = explode( ',', $alphabetRangeList[$alphabetFrom] );
                foreach ( $lettersArray as $letter )
                {
                    $rangeArray =  explode( '-', $letter );
                    if ( isset( $rangeArray[1] ) )
                    {
                        $alphabet = array_merge( $alphabet, range( trim( $rangeArray[0] ), trim( $rangeArray[1] ) ) );
                    }
                    else
                        $alphabet = array_merge( $alphabet, array( trim( $letter ) ) );
                }
            }
        }
        // Get alphabet by default (eng-GB)
        if ( count( $alphabet ) == 0 )
        {
            $rangeArray = explode( '-', $alphabetRangeList['default'] );
            $alphabet = range( $rangeArray[0], $rangeArray[1] );
        }
        $resAlphabet = array();
        $i18nINI = eZINI::instance( 'i18n.ini' );
        $charset = $i18nINI->variable( 'CharacterSettings', 'Charset' );

        $codec = eZTextCodec::instance( 'utf-8', $charset );

        $utf8_codec = eZUTF8Codec::instance();
        // Convert all letters of alphabet from unicode to utf-8 and from utf-8 to current locale
        foreach ( $alphabet as $item )
        {
            $utf8Letter = $utf8_codec->toUtf8( $item );
            $resAlphabet[] = $codec ? $codec->convertString( $utf8Letter ) : $utf8Letter;
        }

        return $resAlphabet;
    }

    /// \privatesection
    public $Operators;
    public $Alphabet;
}

?>
