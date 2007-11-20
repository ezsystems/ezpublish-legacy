<?php
//
// Definition of eZi18nOperator class
//
// Created on: <15-Aug-2006 12:15:07 vd>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

//!! eZKernel
//! The class eZAlphabetOperator does
/*!

*/


class eZAlphabetOperator
{
    /*!
    */
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

    /*!
     \reimp
    */
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
        //include_once( "lib/ezutils/classes/ezini.php" );
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

        //include_once( 'lib/ezi18n/classes/eztextcodec.php' );
        $codec = eZTextCodec::instance( 'utf-8', $charset );

        //include_once( "lib/ezi18n/classes/ezutf8codec.php" );
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
    var $Operators;
    var $Alphabet;
};

?>
