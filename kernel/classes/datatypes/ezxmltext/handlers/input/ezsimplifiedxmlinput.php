<?php
//
// Definition of eZSimplifiedXMLInput class
//
// Created on: <28-Jan-2003 13:28:39 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php' );
include_once( 'kernel/classes/datatypes/ezurl/ezurlobjectlink.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezutils/classes/ezini.php' );

class eZSimplifiedXMLInput extends eZXMLInputHandler
{
    function eZSimplifiedXMLInput( &$xmlData, $aliasedType, $contentObjectAttribute )
    {
        // Initialize size array for image.
        /*$imageIni =& eZINI::instance( 'image.ini' );
        if ( $imageIni->hasVariable( 'AliasSettings', 'AliasList' ) )
        {
            $sizeArray = $imageIni->variable( 'AliasSettings', 'AliasList' );
            $sizeArray[] = 'original';
        }
        else
            $sizeArray = array( 'original' );
        */

        $this->eZXMLInputHandler( $xmlData, $aliasedType, $contentObjectAttribute );

        $this->IsInputValid = true;
        $this->ContentObjectAttribute = $contentObjectAttribute;

        $contentIni =& eZINI::instance( 'content.ini' );

        /*
        if ( $contentIni->hasVariable( 'header', 'UseStrictHeaderRule' ) )
        {
            if ( $contentIni->variable( 'header', 'UseStrictHeaderRule' ) == "true" )
                $this->IsStrictHeader = true;
        }
        */
    }

    /*!
      Updates URL - object links.
    */
    function updateUrlObjectLinks( $contentObjectAttribute, $urlIDArray )
    {
        $objectAttributeID = $contentObjectAttribute->attribute( "id" );
        $objectAttributeVersion = $contentObjectAttribute->attribute('version');

        foreach( $urlIDArray as $urlID )
        {
            $linkObjectLink = eZURLObjectLink::fetch( $urlID, $objectAttributeID, $objectAttributeVersion );
            if ( $linkObjectLink == null )
            {
                $linkObjectLink = eZURLObjectLink::create( $urlID, $objectAttributeID, $objectAttributeVersion );
                $linkObjectLink->store();
            }
        }
    }

    /*!
     \reimp
     Validates the input and returns true if the input was valid for this datatype.
    */
    function validateInput( &$http, $base, &$contentObjectAttribute )
    {
        $contentObjectID = $contentObjectAttribute->attribute( "contentobject_id" );
        $contentObjectAttributeID = $contentObjectAttribute->attribute( "id" );
        $contentObjectAttributeVersion = $contentObjectAttribute->attribute('version');
        if ( $http->hasPostVariable( $base . "_data_text_" . $contentObjectAttributeID ) )
        {
            $data = $http->postVariable( $base . "_data_text_" . $contentObjectAttributeID );

            // Set original input to a global variable
            $originalInput = "originalInput_" . $contentObjectAttributeID;
            $GLOBALS[$originalInput] = $data;

            // Set input valid true to a global variable
            $isInputValid = "isInputValid_" . $contentObjectAttributeID;
            $GLOBALS[$isInputValid] = true;

            include_once( 'kernel/classes/datatypes/ezxmltext/handlers/input/ezsimplifiedxmlinputparser.php' );

            $text = $data;

            $text = preg_replace('/\r/', '', $text);
            $text = preg_replace('/\t/', ' ', $text);

            // first empty paragraph
            $text = preg_replace('/^\n/', '<p></p>', $text );

            $parser = new eZSimplifiedXMLInputParser( $contentObjectID, true, EZ_XMLINPUTPARSER_SHOW_ALL_ERRORS, true );
            $document = $parser->process( $text );

            if ( !is_object( $document ) )
            {
                $GLOBALS[$isInputValid] = false;
                $errorMessage = implode( ' ', $parser->getMessages() );
                $contentObjectAttribute->setValidationError( $errorMessage );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }

            if ( $contentObjectAttribute->validateIsRequired() )
            {
                $root =& $document->Root;
                if ( !count( $root->Children ) )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Content required' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
            }
            $contentObjectAttribute->setValidationLog( $parser->getMessages() );

            $xmlString = eZXMLTextType::domString( $document );
            //eZDebug::writeDebug( $xmlString, '$xmlString' );

            $urlIDArray = $parser->getUrlIDArray();

            if ( count( $urlIDArray ) > 0 )
            {
                $this->updateUrlObjectLinks( $contentObjectAttribute, $urlIDArray );
            }

            $contentObject =& $contentObjectAttribute->attribute( 'object' );
            $contentObject->appendInputRelationList( $parser->getRelatedObjectIDArray(), EZ_CONTENT_OBJECT_RELATION_EMBED );
            $contentObject->appendInputRelationList( $parser->getLinkedObjectIDArray(), EZ_CONTENT_OBJECT_RELATION_LINK );

            $contentObjectAttribute->setAttribute( "data_text", $xmlString );
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     \reimp
     Returns the input XML representation of the datatype.
    */
    function &inputXML()
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );

        $originalInput = "originalInput_" . $contentObjectAttributeID;
        $isInputValid = "isInputValid_" . $contentObjectAttributeID;

        if ( isset( $GLOBALS[$isInputValid] ) and $GLOBALS[$isInputValid] == false )
        {
            $output = $GLOBALS[$originalInput];
        }
        else
        {
            $xml = new eZXML();
            $dom =& $xml->domTree( $this->XMLData, array( 'CharsetConversion' => false, 'ConvertSpecialChars' => false, 'TrimWhiteSpace' => false, 'SetParentNode' => true ) );

            include_once( 'kernel/classes/datatypes/ezxmltext/handlers/input/ezsimplifiedxmleditoutput.php' );

            $editOutput = new eZSimplifiedXMLEditOutput();
            $output = $editOutput->performOutput( $dom );

            if ( $dom )
                $dom->cleanup();
            //eZDebug::writeDebug( $output, '$output' );
        }
        return $output;
    }

    //var $ContentObjectAttribute;

    var $IsInputValid;

    //var $IsStrictHeader = false;
}
?>
