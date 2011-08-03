<?php
/**
 * File containing the eZSimplifiedXMLInput class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

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
    static function updateUrlObjectLinks( $contentObjectAttribute, $urlIDArray )
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

            if ( $contentObjectAttribute->validateIsRequired() )
            {
                $root = $document->documentElement;
                if ( !$root->hasChildNodes() )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
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

            $editOutput = new eZSimplifiedXMLEditOutput();
            $dom->formatOutput = true;
            if ( eZDebugSetting::isConditionTrue( 'kernel-datatype-ezxmltext', eZDebug::LEVEL_DEBUG ) )
                eZDebug::writeDebug( $dom->saveXML(), eZDebugSetting::changeLabel( 'kernel-datatype-ezxmltext', __METHOD__ . ' xml string stored in database' ) );
            $output = $editOutput->performOutput( $dom );
        }
        return $output;
    }

    public $IsInputValid;
}

?>
