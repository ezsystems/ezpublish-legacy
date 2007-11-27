<?php
//
// Definition of eZSimplifiedXMLInput class
//
// Created on: <28-Jan-2003 13:28:39 bf>
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

//include_once( 'kernel/classes/datatypes/ezxmltext/ezxmlinputhandler.php' );
//include_once( 'kernel/classes/datatypes/ezurl/ezurlobjectlink.php' );
//include_once( 'lib/ezutils/classes/ezhttptool.php' );
//include_once( 'lib/ezutils/classes/ezini.php' );

class eZSimplifiedXMLInput extends eZXMLInputHandler
{
    function eZSimplifiedXMLInput( &$xmlData, $aliasedType, $contentObjectAttribute )
    {
        $this->eZXMLInputHandler( $xmlData, $aliasedType, $contentObjectAttribute );

        $this->IsInputValid = true;
        $this->ContentObjectAttribute = $contentObjectAttribute;

        $contentIni = eZINI::instance( 'content.ini' );
    }

    /*!
      Updates URL - object links.
    */
    function updateUrlObjectLinks( $contentObjectAttribute, $urlIDArray )
    {
        $objectAttributeID = $contentObjectAttribute->attribute( 'id' );
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
    function validateInput( $http, $base, $contentObjectAttribute )
    {
        $contentObjectID = $contentObjectAttribute->attribute( 'contentobject_id' );
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $contentObjectAttributeVersion = $contentObjectAttribute->attribute('version');
        if ( $http->hasPostVariable( $base . '_data_text_' . $contentObjectAttributeID ) )
        {
            $data = $http->postVariable( $base . '_data_text_' . $contentObjectAttributeID );

            // Set original input to a global variable
            $originalInput = 'originalInput_' . $contentObjectAttributeID;
            $GLOBALS[$originalInput] = $data;

            // Set input valid true to a global variable
            $isInputValid = 'isInputValid_' . $contentObjectAttributeID;
            $GLOBALS[$isInputValid] = true;

            //include_once( 'kernel/classes/datatypes/ezxmltext/handlers/input/ezsimplifiedxmlinputparser.php' );

            $text = $data;

            $text = preg_replace('/\r/', '', $text);
            $text = preg_replace('/\t/', ' ', $text);

            // first empty paragraph
            $text = preg_replace('/^\n/', '<p></p>', $text );

            eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $text, 'eZSimplifiedXMLInput::validateInput text' );

            $parser = new eZSimplifiedXMLInputParser( $contentObjectID, true, eZXMLInputParser::ERROR_ALL, true );
            $document = $parser->process( $text );

            if ( !is_object( $document ) )
            {
                $GLOBALS[$isInputValid] = false;
                $errorMessage = implode( ' ', $parser->getMessages() );
                $contentObjectAttribute->setValidationError( $errorMessage );
                return eZInputValidator::STATE_INVALID;
            }

            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            if ( $classAttribute->attribute( 'is_required' ) == true )
            {
                $root = $document->documentElement;
                if ( !$root->hasChildNodes() )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Content required' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }
            $contentObjectAttribute->setValidationLog( $parser->getMessages() );

            $xmlString = eZXMLTextType::domString( $document );

            $urlIDArray = $parser->getUrlIDArray();

            if ( count( $urlIDArray ) > 0 )
            {
                $this->updateUrlObjectLinks( $contentObjectAttribute, $urlIDArray );
            }

            $contentObject = $contentObjectAttribute->attribute( 'object' );
            $contentObject->appendInputRelationList( $parser->getRelatedObjectIDArray(), eZContentObject::RELATION_EMBED );
            $contentObject->appendInputRelationList( $parser->getLinkedObjectIDArray(), eZContentObject::RELATION_LINK );

            $contentObjectAttribute->setAttribute( 'data_text', $xmlString );
            return eZInputValidator::STATE_ACCEPTED;
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     \reimp
     Returns the input XML representation of the datatype.
    */
    function inputXML()
    {
        $contentObjectAttribute = $this->ContentObjectAttribute;
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );

        $originalInput = 'originalInput_' . $contentObjectAttributeID;
        $isInputValid = 'isInputValid_' . $contentObjectAttributeID;

        if ( isset( $GLOBALS[$isInputValid] ) and $GLOBALS[$isInputValid] == false )
        {
            $output = $GLOBALS[$originalInput];
        }
        else
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $this->XMLData );

            //include_once( 'kernel/classes/datatypes/ezxmltext/handlers/input/ezsimplifiedxmleditoutput.php' );

            $editOutput = new eZSimplifiedXMLEditOutput();
            $dom->formatOutput = true;
            eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $dom->saveXML(), 'eZSimplifiedXMLInput::inputXML xml string stored in database' );
            $output = $editOutput->performOutput( $dom );
        }
        return $output;
    }

    public $IsInputValid;
}

?>
