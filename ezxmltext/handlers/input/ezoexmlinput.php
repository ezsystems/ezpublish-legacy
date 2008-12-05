<?php
//
// Created on: <06-Nov-2002 15:10:02 wy>
// Forked on: <20-Des-2007 13:02:06 ar> from eZDHTMLInput class
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor extension for eZ Publish
// SOFTWARE RELEASE: 5.0
// COPYRIGHT NOTICE: Copyright (C) 2008 eZ Systems AS
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


/*! \file ezoexmlinput.php
*/

/*!
  \class eZOEXMLInput
  \brief The class eZOEXMLInput does

*/
require_once( 'kernel/common/template.php' );
/*include_once( 'lib/eztemplate/classes/eztemplateincludefunction.php' );
include_once( "kernel/classes/datatypes/ezimage/ezimagevariation.php");
include_once( "kernel/classes/datatypes/ezimage/ezimage.php");
include_once( "lib/ezimage/classes/ezimagelayer.php" );
include_once( "lib/ezimage/classes/ezimagetextlayer.php" );
include_once( "lib/ezimage/classes/ezimagefont.php" );
include_once( "lib/ezimage/classes/ezimageobject.php" );
include_once( "lib/eztemplate/classes/eztemplateimageoperator.php" );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( "lib/ezutils/classes/ezsys.php" );
include_once( "kernel/classes/ezcontentobject.php");
include_once( 'kernel/classes/datatypes/ezurl/ezurlobjectlink.php' );
*/

class eZOEXMLInput extends eZXMLInputHandler
{
    /*!
     Constructor
    */
    function eZOEXMLInput( &$xmlData, $aliasedType, $contentObjectAttribute )
    {
        $this->eZXMLInputHandler( $xmlData, $aliasedType, $contentObjectAttribute );
        $contentIni = eZINI::instance( 'content.ini' );
        if ( $contentIni->hasVariable( 'header', 'UseStrictHeaderRule' ) === true )
        {
            if ( $contentIni->variable( 'header', 'UseStrictHeaderRule' ) === 'true' )
                $this->IsStrictHeader = true;
        }

        //include_once( 'lib/version.php' );
        $this->eZPublishVersion = eZPublishSDK::majorVersion() + eZPublishSDK::minorVersion() * 0.1;

        $this->browserSupportsDHTMLType();

        $ezxmlIni = eZINI::instance( 'ezxml.ini' );
        if ( $ezxmlIni->hasVariable( 'InputSettings', 'TrimSpaces' ) === true )
        {
            $trimSpaces = $ezxmlIni->variable( 'InputSettings', 'TrimSpaces' );
            $this->trimSpaces = $trimSpaces === 'true' ? true : false;
        }

        if ( $ezxmlIni->hasVariable( 'InputSettings', 'AllowMultipleSpaces' ) === true )
        {
            $allowMultipleSpaces = $ezxmlIni->variable( 'InputSettings', 'AllowMultipleSpaces' );
            $this->allowMultipleSpaces = $allowMultipleSpaces === 'true' ? true : false;
        }
    }
    
    /*!
     \static
     \return list of custom tags to native xhtml tags array
     \div is used by default.
     \eZOEInputParser::tagNameCustomHelper handles input
    */
    public static $nativeCustomTags = array(
                  'underline' => 'u',
                  'sup' => 'sup',
                  'sub' => 'sub'
                  );

    /*!
     \reimp
    */
    function hasAttribute( $name )
    {
        return ( $name === 'is_editor_enabled' or
                 $name === 'editor_layout_settings' or
                 $name === 'browser_supports_dhtml_type' or
                 $name === 'is_compatible_version' or
                 $name === 'version' or
                 $name === 'ezpublish_version' or
                 eZXMLInputHandler::hasAttribute( $name ) );
    }

    /*!
     \reimp
    */
    function attribute( $name )
    {
        if ( $name === 'is_editor_enabled' )
            $attr = eZOEXMLInput::isEditorEnabled();
        else if ( $name === 'editor_layout_settings' )
            $attr = $this->getEditorLayoutSettings();
        else if ( $name === 'browser_supports_dhtml_type' )
            $attr = eZOEXMLInput::browserSupportsDHTMLType();
        else if ( $name === 'is_compatible_version' )
            $attr = $this->isCompatibleVersion();
        else if ( $name === 'version' )
            $attr = eZOEXMLInput::version();
        else if ( $name === 'ezpublish_version' )
            $attr = $this->eZPublishVersion;
        else
            $attr = eZXMLInputHandler::attribute( $name );
        return $attr;
    }

    /*! 
     \static
     Identify browser by layout engine.
     \return string if browser supports dhtml editing, false if not.
    */
    static function browserSupportsDHTMLType()
    {
        if ( self::$browserType === null )
        {
            self::$browserType = false;
            $userAgent = eZSys::serverVariable( 'HTTP_USER_AGENT' );
            if ( strpos( $userAgent, 'Presto' ) !== false and
                 preg_match('/Presto\/([0-9\.]+)/i', $userAgent, $browserInfo ) )
            {
                if ( $browserInfo[1] >= 2.1 )
                    self::$browserType = 'Presto';
            }
            elseif ( strpos( $userAgent, 'Opera' ) !== false and
                 preg_match('/Opera\/([0-9\.]+)/i', $userAgent, $browserInfo ) )
            {
                // Presto is not part of the user agent string on Opera > 9.6
                if ( $browserInfo[1] >= 9.5 )
                    self::$browserType = 'Presto';
                // Experimental Wii support
                else if ( $browserInfo[1] >= 9.3 )
                    self::$browserType = 'Presto';
            }
            else if ( strpos( $userAgent, 'Trident' ) !== false and
                 preg_match('/Trident\/([0-9\.]+)/i', $userAgent, $browserInfo ) )
            {
                if ( $browserInfo[1] >= 4.0 )
                    self::$browserType = 'Trident';
            }
            else if ( strpos( $userAgent, 'MSIE' ) !== false and
                      preg_match('/MSIE[ \/]([0-9\.]+)/i', $userAgent, $browserInfo ) )
            {
                // IE didn't have Trident in it's user agent string untill IE 8.0
                if ( $browserInfo[1] >= 6.0 )
                    self::$browserType = 'Trident';
            }
            elseif ( strpos( $userAgent, 'Gecko' ) !== false and
                     preg_match('/rv:([0-9\.]+)/i', $userAgent, $browserInfo ) )
            {
                if ( $browserInfo[1] >= 1.8 )
                    self::$browserType = 'Gecko';
            }
            elseif ( strpos( $userAgent, 'WebKit' ) !== false and
                     preg_match('/WebKit\/([0-9\.]+)/i', $userAgent, $browserInfo ) )
            {
                if ( $browserInfo[1] >= 522.0 )
                    self::$browserType = 'WebKit';
            }
        }
        return self::$browserType;
    }

    /*!
     \return boolean
    */
    function isCompatibleVersion()
    {
        return $this->eZPublishVersion >= 4.0;
    }

    /*!
     \static
     \return OE version
    */
    static function version()
    {
        include_once( 'extension/ezoe/ezinfo.php' );
        $info = ezoeInfo::info();
        $version = $info['version'];
        return $version;
    }

    /*!
     \static
     \return true if the editor is enabled. The editor can be enabled/disabled by a
             button in the web interface.
    */
    static function isEditorEnabled()
    {
        $dhtmlInput = true;
        $http = eZHTTPTool::instance();
        if ( $http->hasSessionVariable( 'eZOEXMLInputExtension' ) === true )
            $dhtmlInput = $http->sessionVariable( 'eZOEXMLInputExtension' );
        return $dhtmlInput;
    }

    /*!
     Sets whether the DHTML editor is enabled or not.
    */
    static function setIsEditorEnabled( $isEnabled )
    {
        $http = eZHTTPTool::instance();
        $http->setSessionVariable( 'eZOEXMLInputExtension', $isEnabled );
    }

    /*!
     \static
     \return true if the editor can be used. This is determinded by whether the browser supports DHTML
            , user has access to editor and if the editor is enabled.
    */
    static function isEditorActive()
    {
        if ( !eZOEXMLInput::browserSupportsDHTMLType() )
            return false;

       if ( !eZOEXMLInput::isEditorEnabled() )
            return false;

        return eZOEXMLInput::currentUserHasAccess();
    }
    
    /*!
     \static
     \return if user has access to editor 
     */
    static function currentUserHasAccess( $view = 'editor' )
    {
        if ( !isset( self::$userAccessHash[ $view ] ) )
        {
            self::$userAccessHash[ $view ] = false;
            $user = eZUser::currentUser();
            if ( $user instanceOf eZUser )
            {
                $result = $user->hasAccessTo( 'ezoe', $view );
                if ( $result['accessWord'] !== 'no'  )
                    self::$userAccessHash[ $view ] = true;
            }
        }
        return self::$userAccessHash[ $view ];
    }

    /*!
     \return global layout to use for editor
     */
    static function getEditorGlobalLayoutSettings()
    {    
        if ( self::$editorGlobalLayoutSettings === null )
        {
            $oeini = eZINI::instance( 'ezoe.ini' );
            self::$editorGlobalLayoutSettings = array(
                'buttons' => $oeini->variable('EditorLayout', 'Buttons' ),
                'toolbar_location' => $oeini->variable('EditorLayout', 'ToolbarLocation' ),
                'path_location' => $oeini->variable('EditorLayout', 'PathLocation' ),
            );
        }
        return self::$editorGlobalLayoutSettings;
    }

    /*!
     \return layout to use for editor
     */
    function getEditorLayoutSettings()
    {    
        if ( $this->editorLayoutSettings === null )
        {
            $oeini = eZINI::instance( 'ezoe.ini' );
            $xmlini = eZINI::instance( 'ezxml.ini' );

            // get global layout settings
            $editorLayoutSettings = self::getEditorGlobalLayoutSettings();

            // get custom layout features, works only in eZ Publish 4.1 and higher
            $contentClassAttribute = $this->ContentObjectAttribute->attribute('contentclass_attribute');
            $buttonPreset = $contentClassAttribute->attribute('data_text2');
            $buttonPresets = $xmlini->hasVariable( 'TagSettings', 'TagPresets' ) ? $xmlini->variable( 'TagSettings', 'TagPresets' ) : array();

            if( $buttonPreset && isset( $buttonPresets[$buttonPreset] ) )
            {
                if ( $oeini->hasSection( 'EditorLayout_' . $buttonPreset ) )
                {
                    if ( $oeini->hasVariable( 'EditorLayout_' . $buttonPreset , 'Buttons' ) )
                        $editorLayoutSettings['buttons'] = $oeini->variable( 'EditorLayout_' . $buttonPreset , 'Buttons' );

                    if ( $oeini->hasVariable( 'EditorLayout_' . $buttonPreset , 'ToolbarLocation' ) )
                        $editorLayoutSettings['toolbar_location'] = $oeini->variable( 'EditorLayout_' . $buttonPreset , 'ToolbarLocation' );

                    if ( $oeini->hasVariable( 'EditorLayout_' . $buttonPreset , 'PathLocation' ) )
                        $editorLayoutSettings['path_location'] = $oeini->variable( 'EditorLayout_' . $buttonPreset , 'PathLocation' );
                }
                else
                {
                    eZDebug::writeWarning( 'Undefined EditorLayout : EditorLayout_' . $buttonPreset, 'eZOEXMLInput' );
                }
            }

            $contentini = eZINI::instance( 'content.ini' );
            $tags = $contentini->variable('CustomTagSettings', 'AvailableCustomTags' );
            $hideButtons = array();
            $showButtons = array();

            // filter out custom tag icons if the custom tag is not enabled
            if ( !in_array('underline', $tags ) )
                $hideButtons[] = 'underline';

            if ( !in_array('sub', $tags ) )
                $hideButtons[] = 'sub';

            if ( !in_array('sup', $tags ) )
                $hideButtons[] = 'sup';

            if ( !in_array('pagebreak', $tags ) )
                $hideButtons[] = 'pagebreak';

            // filter out relations buttons if user dosn't have access to relations
            if ( !eZOEXMLInput::currentUserHasAccess( 'relations' ) )
            {
                $hideButtons[] = 'image';
                $hideButtons[] = 'object';
                $hideButtons[] = 'file';
                $hideButtons[] = 'media';
            }
             
            foreach( $editorLayoutSettings['buttons'] as $button )
            {
                if ( !in_array( $button, $hideButtons ) )
                    $showButtons[] = trim( $button );
            }

            $editorLayoutSettings['buttons'] = $showButtons;
            $this->editorLayoutSettings = $editorLayoutSettings;
        }
        return $this->editorLayoutSettings;
    }

    /*!
     \reimp
    */
    function isValid()
    {
        if ( !eZOEXMLInput::browserSupportsDHTMLType() )
            return false;

        return eZOEXMLInput::currentUserHasAccess();
    }

    /*!
     \reimp
    */
    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute )
    {
        switch ( $action )
        {
            case 'enable_editor':
            {
                eZOEXMLInput::setIsEditorEnabled( true );
            } break;
            case 'disable_editor':
            {
                eZOEXMLInput::setIsEditorEnabled( false );
            } break;
            default :
            {
                eZDebug::writeError( 'Unknown custom HTTP action: ' . $action, 'eZOEXMLInput' );
            } break;
        }
    }

    /*!
     \reimp
    */
    function editTemplateSuffix( &$contentobjectAttribute )
    {
        return 'ezoe';
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
    */
    function validateInput( $http, $base, $contentObjectAttribute )
    {
        $this->ContentObjectAttributeID = $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $this->ContentObjectAttributeVersion = $contentObjectAttributeVersion = $contentObjectAttribute->attribute('version');

        if ( !$this->isEditorEnabled() )
        {
            $aliasedHandler = $this->attribute( 'aliased_handler' );
            return $aliasedHandler->validateInput( $http, $base, $contentObjectAttribute );
        }
        if ( $http->hasPostVariable( $base . '_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $text = $http->postVariable( $base . '_data_text_' . $contentObjectAttribute->attribute( 'id' ) );

            $text = preg_replace( '#<!--.*?-->#s', '', $text ); // remove HTML comments
            $text = str_replace( "\r", '', $text);

            if ( self::$browserType === 'IE' )
            {
                $text = preg_replace( "/[\n\t]/", '', $text);
            }
            else
            {
                $text = preg_replace( "/[\n\t]/", ' ', $text);
            }

            //eZDebug::writeDebug( $text, 'eZ Online Editor HTML input' );

            include_once( 'extension/ezoe/ezxmltext/handlers/input/ezoeinputparser.php' );

            $parser = new eZOEInputParser();

            $document = $parser->process( $text );

            // Remove last empty paragraph (added in the output part)
            $parent = $document->documentElement;
            $lastChild = $parent->lastChild;
            while( $lastChild && $lastChild->nodeName !== 'paragraph' )
            {
                $parent = $lastChild;
                $lastChild = $parent->lastChild;
            }

            if ( $lastChild && $lastChild->nodeName === 'paragraph' )
            {
                $textChild = $lastChild->lastChild;
                // $textChild->textContent == " " : string(2) whitespace in Opera
                if ( !$textChild ||
                     ( $lastChild->childNodes->length == 1 &&
                       $textChild->nodeType == XML_TEXT_NODE &&
                       ( $textChild->textContent == " " || $textChild->textContent == ' ' ||$textChild->textContent == '' ) ) )
                {
                    $parent->removeChild( $lastChild );
                }
            }

            $oeini = eZINI::instance( 'ezoe.ini' );
            $validationParameters = $contentObjectAttribute->validationParameters();
            if ( !( isset( $validationParameters['skip-isRequired'] ) && $validationParameters['skip-isRequired'] === true )
              && $parser->getDeletedEmbedIDArray( $oeini->variable('EditorSettings', 'ValidateEmbedObjects' ) === 'enabled' ) )
            {
                self::$showEmbedValidationErrors = true;
                $contentObjectAttribute->setValidationError( ezi18n( 'design/standard/ezoe/handler',
                                                 'Some objects used in embed(-inline) tags have been deleted and are no longer available.' ) );
                return eZInputValidator::STATE_INVALID;
            }

            if ( $contentObjectAttribute->validateIsRequired() )
            {
                $root = $document->documentElement;
                if ( $root->childNodes->length == 0 )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Content required' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }

            // Update URL-object links
            $urlIDArray = $parser->getUrlIDArray();
            if ( count( $urlIDArray ) > 0 )
            {
                $this->updateUrlObjectLinks( $contentObjectAttribute, $urlIDArray );
            }

            $contentObject = $contentObjectAttribute->attribute( 'object' );
            $contentObject->appendInputRelationList( $parser->getEmbeddedObjectIDArray(), eZContentObject::RELATION_EMBED );
            $contentObject->appendInputRelationList( $parser->getLinkedObjectIDArray(), eZContentObject::RELATION_LINK );

            $xmlString = eZXMLTextType::domString( $document );

            $contentObjectAttribute->setAttribute( 'data_text', $xmlString );
            $contentObjectAttribute->setValidationLog( $parser->Messages );

            return eZInputValidator::STATE_ACCEPTED;
        }
        else
        {
            return eZInputValidator::STATE_ACCEPTED;
        }
        return eZInputValidator::STATE_INVALID;
    }


    /*

      Editor inner output implementation

    */

    // Get section level and reset cuttent node according to input header.
    function &sectionLevel( &$sectionLevel, $headerLevel, &$TagStack, &$currentNode, &$domDocument )
    {
        if ( $sectionLevel < $headerLevel )
        {
            if ( $this->IsStrictHeader )
            {
                $sectionLevel += 1;
            }
            else
            {
                if ( ( $sectionLevel + 1 ) == $headerLevel )
                {
                    $sectionLevel += 1;
                }
                else
                {
                    for ( $i=1;$i<=( $headerLevel - $sectionLevel - 1 );$i++ )
                    {
                        // Add section tag
                        unset( $subNode );
                        $subNode = new DOMElemenetNode( 'section' );
                        $currentNode->appendChild( $subNode );
                        $childTag = $this->SectionArray;
                        $TagStack[] = array( 'TagName' => 'section', 'ParentNodeObject' => &$currentNode, 'ChildTag' => $childTag );
                        $currentNode = $subNode;
                    }
                    $sectionLevel = $headerLevel;
                }
            }
        }
        elseif ( $sectionLevel == $headerLevel )
        {
            $lastNodeArray = array_pop( $TagStack );
            $lastNode = $lastNodeArray['ParentNodeObject'];
            $currentNode = $lastNode;
            $sectionLevel = $headerLevel;
        }
        else
        {
            for ( $i = 1; $i <= ( $sectionLevel - $headerLevel + 1 ); $i++ )
            {
                $lastNodeArray = array_pop( $TagStack );
                $lastTag = $lastNodeArray['TagName'];
                $lastNode = $lastNodeArray['ParentNodeObject'];
                $lastChildTag = $lastNodeArray['ChildTag'];
                $currentNode = $lastNode;
            }
            $sectionLevel = $headerLevel;
        }
        return $currentNode;
    }

    /*!
     Returns the input XML representation of the datatype.
    */
    function inputXML( )
    {
        $node = null;
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $dom->preserveWhiteSpace = false;
        $success = false;
        if ( $this->XMLData )
        {
            $success = $dom->loadXML( $this->XMLData );
        }

        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $this->XMLData, 'eZOEXMLInput::inputXML xml string stored in database' );

        $output = '';

        if ( $success )
        {
            $rootSectionNode = $dom->documentElement;
            $output .= $this->inputSectionXML( $rootSectionNode, 0 );
        }

        if ( self::$browserType === 'IE' )
        {
            $output = str_replace( '<p></p>', '<p>&nbsp;</p>', $output );
        }
        else
        {
            $output = str_replace( '<p></p>', '<p><br /></p>', $output );
        }

        $output = str_replace( "\n", '', $output );

        if ( self::$browserType === 'IE' )
        {
            $output .= '<p>&nbsp;</p>';
        }
        else
        {
            $output .= '<p><br /></p>';
        }

        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $output, 'eZOEXMLInput::inputXML xml output to return' );

        $output = htmlspecialchars( $output, ENT_COMPAT, 'UTF-8' );

        return $output;
    }

    /*!
     \private
     \return the user input format for the given section
    */
    function &inputSectionXML( &$section, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = '';

        foreach ( $section->childNodes as $sectionNode )
        {
            if ( $tdSectionLevel == null )
            {
                $sectionLevel = $currentSectionLevel;
            }
            else
            {
                $sectionLevel = $tdSectionLevel;
            }

            $tagName = $sectionNode instanceof DOMNode ? $sectionNode->nodeName : '';

            switch ( $tagName )
            {
                case 'header' :
                {
                    $headerClassName = $sectionNode->getAttribute( 'class' );
                    $headerClassString = $headerClassName != null ? " class='$headerClassName'" : '';

                    $tagContent = '';
                    // render children tags
                    $tagChildren = $sectionNode->childNodes;
                    foreach ( $tagChildren as $childTag )
                    {
                        $tagContent .= $this->inputTagXML( $childTag, $currentSectionLevel, $tdSectionLevel );
                        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $tagContent, 'eZOEXMLInput::inputSectionXML tag content of header' );

                    }

                    switch ( $sectionLevel )
                    {
                        case '2':
                        case '3':
                        case '4':
                        case '5':
                        case '6':
                        {
                            $level = $sectionLevel;
                        }break;
                        default:
                        {
                            $level = 1;
                        }break;
                    }
                    $archorName = $sectionNode->getAttribute( 'anchor_name' );
                    if ( $archorName != null )
                    {
                        $output .= "<h$level$headerClassString><a name=\"$archorName\" class=\"mceItemAnchor\"></a>" . $sectionNode->textContent. "</h$level>";
                    }
                    else
                    {
                        $output .= "<h$level$headerClassString>" . $tagContent . "</h$level>";
                    }

                }break;

                case 'paragraph' :
                {
                    if ( $tdSectionLevel == null )
                    {
                        $output .= $this->inputParagraphXML( $sectionNode, $currentSectionLevel );
                    }
                    else
                    {
                        $output .= $this->inputParagraphXML( $sectionNode, $currentSectionLevel, $tdSectionLevel );
                    }
                }break;

                case 'section' :
                {
                    $sectionLevel += 1;
                    if ( $tdSectionLevel == null )
                    {
                        $output .= $this->inputSectionXML( $sectionNode, $sectionLevel );
                    }
                    else
                    {
                        $output .= $this->inputSectionXML( $sectionNode, $currentSectionLevel, $sectionLevel );
                    }
                }break;

                default :
                {
                    eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZOEXMLInput::inputSectionXML()" );
                }break;
            }
        }
        return $output;
    }

    /*!
     \private
     \return the user input format for the given list item
    */
    function &inputListXML( &$listNode, $currentSectionLevel, $listSectionLevel = null, $noParagraphs = true )
    {
        $output = '';
        $tagName = $listNode instanceof DOMNode ? $listNode->nodeName : '';

        switch ( $tagName )
        {
            case 'paragraph' :
            {
                $output .= $this->inputParagraphXML( $listNode, $currentSectionLevel, $listSectionLevel, $noParagraphs );
            }break;

            case 'section' :
            {
                $listSectionLevel += 1;
                $output .= $this->inputSectionXML( $listNode, $currentSectionLevel, $listSectionLevel );
            }break;

            default :
            {
                eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZOEXMLInput::inputListXML()" );
            }break;
        }
        return $output;
    }

    /*!
     \private
     \return the user input format for the given table cell
    */
    function &inputTdXML( &$tdNode, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = '';
        $tagName = $tdNode instanceof DOMNode ? $tdNode->nodeName : '';

        switch ( $tagName )
        {
            case 'paragraph' :
            {
                $output .= $this->inputParagraphXML( $tdNode, $currentSectionLevel, $tdSectionLevel  );
            }break;

            case 'section' :
            {
                $tdSectionLevel += 1;
                $output .= $this->inputSectionXML( $tdNode, $currentSectionLevel, $tdSectionLevel );
            }break;

            default :
            {
                eZDebug::writeError( "Unsupported tag at this level: $tagName", "eZOEXMLInput::inputTdXML()" );
            }break;
        }
        return $output;
    }

    /*!
     \return the input xml of the given paragraph
    */
    function &inputParagraphXML( &$paragraph, $currentSectionLevel, $tdSectionLevel = null, $noRender = false )
    {
        $output = '';
        $children = $paragraph->childNodes;
        if ( $noRender )
        {
            foreach ( $children as $child )
            {
                $output .= $this->inputTagXML( $child, $currentSectionLevel, $tdSectionLevel );
            }
            return $output;
        }

        $paragraphClassName = $paragraph->getAttribute( 'class' );

        $customAttributePart = $this->getCustomAttrPart( $paragraph, $styleString );

        if ( $paragraphClassName != null )
        {
            $openPara = "<p class='$paragraphClassName'$customAttributePart$styleString>";
        }
        else
        {
            $openPara = "<p$customAttributePart$styleString>";
        }
        $closePara = '</p>';

        if ( $children->length == 0 )
        {
            $output = $openPara . $closePara;
            return $output;
        }

        $lastChildInline = null;
        $innerContent = '';
        foreach ( $children as $child )
        {
            $childOutput = $this->inputTagXML( $child, $currentSectionLevel, $tdSectionLevel );

            // Some tags in xhtml aren't allowed as child of paragraph
            $inline = !( $child->nodeName === 'ul'
                      || $child->nodeName === 'ol'
                      || $child->nodeName === 'literal'
                      || ( $child->nodeName === 'custom' && !self::customTagIsInline( $child->getAttribute( 'name' ) ) )
                      || ( $child->nodeName === 'embed' && !self::embedTagIsCompatibilityMode() && !self::embedTagIsImageByNode( $child ) )
                      );
            if ( $inline )
            {
                $innerContent .= $childOutput;
            }


            if ( ( !$inline && $lastChildInline ) ||
                 ( $inline && !$child->nextSibling ) )
            {
                $output .= $openPara . $innerContent . $closePara;
                $innerContent = '';
            }

            if ( !$inline )
            {
                $output .= $childOutput;
            }

            $lastChildInline = $inline;
        }

        eZDebugSetting::writeDebug( 'kernel-datatype-ezxmltext', $output, 'eZOEXMLInput::inputParagraphXML output' );
        return $output;
    }

    function getCustomAttrPart( $tag, &$styleString )
    {
        $customAttributePart = '';
        $styleString         = '';
        
        if ( self::$customAttributeStyleMap === null )
        {
            // filtered because the browser (ie,ff&opera) convert span tag to font tag in certain circumstances
            $oeini = eZINI::instance( 'ezoe.ini' );
            $styles = $oeini->variable('EditorSettings', 'CustomAttributeStyleMap' );
            $customAttributeStyleMap = array();
            foreach( $styles as $name => $style )
            {
                if ( preg_match("/(margin|border|padding|width|height)/", $style ) )
                {
                    self::$customAttributeStyleMap[$name] = $style;
                }
                else
                {
                	eZDebug::writeWarning( "Style not valid: $style, see ezoe.ini[EditorSettings]CustomAttributeStyleMap", "eZOEXMLInput::getCustomAttrPart()" );
                }
            }
        }

        foreach ( $tag->attributes as $attribute )
        {
            if ( $attribute->namespaceURI == 'http://ez.no/namespaces/ezpublish3/custom/' )
            {
                if ( $customAttributePart === '' )
                {
                    $customAttributePart = ' customattributes="';
                    $customAttributePart .= $attribute->name . '|' . $attribute->value;
                }
                else
                {
                   $customAttributePart .= 'attribute_separation' . $attribute->name . '|' . $attribute->value;
                }
                if ( isset( self::$customAttributeStyleMap[$attribute->name] ) )
                {
                    $styleString .= self::$customAttributeStyleMap[$attribute->name] . ': ' . $attribute->value . '; ';
                }
            }
        }

        if ( $customAttributePart !== '' )
        {
            $customAttributePart .= '"';
        }
        if ( $styleString !== '' )
        {
            $styleString = ' style="' . $styleString . '"';
        }
        return $customAttributePart;
    }

    /*!
     \return the input xml for the given tag
     \as in the xhtml used inside the editor
    */
    function &inputTagXML( &$tag, $currentSectionLevel, $tdSectionLevel = null )
    {
        $output = '';
        $tagName = $tag->nodeName;
        $childTagText = '';
        // render children tags
        if ( $tag->hasChildNodes() )
        {
            $tagChildren = $tag->childNodes;
            foreach ( $tagChildren as $childTag )
            {
                $childTagText .= $this->inputTagXML( $childTag, $currentSectionLevel, $tdSectionLevel );
            }
        }
        switch ( $tagName )
        {
            case '#text' :
            {
                //$tagContent = htmlspecialchars( $tag->textContent );
                $tagContent = $tag->textContent;
                if ( !strlen( $tagContent ) )
                {
                    break;
                }

                $tagContent = htmlspecialchars( $tagContent );

                if ( $this->allowMultipleSpaces )
                {
                    $tagContent = str_replace( '  ', ' &nbsp;', $tagContent );
                }
                else
                {
                    $tagContent = preg_replace( "/ {2,}/", ' ', $tagContent );
                }

                if ( $tagContent[0] === ' ' )
                {
                    $tagContent[0] = ';';
                    $tagContent = '&nbsp' . $tagContent;
                }

                $output .= $tagContent;

            }break;

            case 'embed' :
            case 'embed-inline' :
            {
                $view      = $tag->getAttribute( 'view' );
                $size      = $tag->getAttribute( 'size' );
                $alignment = $tag->getAttribute( 'align' );
                $objectID  = $tag->getAttribute( 'object_id' );
                $nodeID    = $tag->getAttribute( 'node_id' );
                $showPath  = $tag->getAttribute( 'show_path' );
                $htmlID    = $tag->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/xhtml/', 'id' );
                $className = $tag->getAttribute( 'class' );
                $idString  = '';
                $tplSuffix = '';

                if ( !$size ) $size = 'medium';
                if ( !$view ) $view = $tagName;

                $objectAttr = '';                
                $objectAttr .= ' alt="' . $size . '"';
                $objectAttr .= ' view="' . $view . '"';

                if ( $htmlID != '' )
                {
                    $objectAttr .= ' html_id="' . $htmlID . '"';
                }
                if ( $showPath === 'true' )
                {
                    $objectAttr .= ' show_path="true"';
                }
                
                if ( $tagName === 'embed-inline' )
                    $objectAttr .= ' inline="true"';
                else
                    $objectAttr .= ' inline="false"';

                if ( $alignment === 'center' )
                    $objectAttr .= ' align="middle"';
                else if ( $alignment )
                    $objectAttr .= ' align="' . $alignment . '"';

                $customAttributePart = $this->getCustomAttrPart( $tag, $styleString );
                $object              = false;

                if ( is_numeric( $objectID ) )
                {
                    $object = eZContentObject::fetch( $objectID );
                    $idString = 'eZObject_' . $objectID;
                }
                elseif ( is_numeric( $nodeID ) )
                {
                    $node      = eZContentObjectTreeNode::fetch( $nodeID );
                    $object    = $node instanceof eZContentObjectTreeNode ? $node->object() : false;
                    $idString  = 'eZNode_' . $nodeID;
                    $tplSuffix = '_node';
                }

                if ( $object instanceof eZContentObject )
                {
                    $objectName = $object->attribute( 'name' );
                    $classID = $object->attribute( 'contentclass_id' );
                    $classIdentifier = $object->attribute( 'class_identifier' );
                    if ( !$object->attribute( 'can_read' ) ||
                         !$object->attribute( 'can_view_embed' ) )
                    {
                        $tplSuffix = '_denied';
                    }
                    else if ( $object->attribute( 'status' ) == eZContentObject::STATUS_ARCHIVED )
                    {
                        $className .= ' mceItemObjectInTrash';
                        if ( self::$showEmbedValidationErrors )
                        {
                            $oeini = eZINI::instance( 'ezoe.ini' );
                            if ( $oeini->variable('EditorSettings', 'ValidateEmbedObjects' ) === 'enabled' )
                                $className .= ' mceItemValidationError';
                        }
                    }
                }
                else
                {
                    $objectName = 'Unknown';
                    $classID = 0;
                    $classIdentifier = false;
                    $tplSuffix = '_denied';
                    $className .= ' mceItemObjectDeleted';
                    if ( self::$showEmbedValidationErrors )
                    {
                        $className .= ' mceItemValidationError';
                    }
                }

                $embedContentType = self::embedTagContentType( $classIdentifier, $classID );
                if ( $embedContentType === 'images' )
                {
                    $ini = eZINI::instance();
                    $URL = self::getServerURL();
                    $contentObjectAttributes = $object->contentObjectAttributes();
                    $imageDatatypeArray = $ini->variable( 'ImageDataTypeSettings', 'AvailableImageDataTypes' );
                    $srcString = self::getDesignFile('images/tango/mail-attachment32.png');
                    $imageWidth = 32;
                    $imageHeight = 32;
                    // reverse the array so we are sure we get the first valid image
                    foreach ( array_reverse( $contentObjectAttributes, true ) as $contentObjectAttribute )
                    {
                        $classAttribute = $contentObjectAttribute->contentClassAttribute();
                        $dataTypeString = $classAttribute->attribute( 'data_type_string' );
                        if ( in_array ( $dataTypeString, $imageDatatypeArray ) )
                        {
                            $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
                            $contentObjectAttributeVersion = $contentObjectAttribute->attribute( 'version' );
                            $content = $contentObjectAttribute->content();
                            if ( $content != null && $content->hasAttribute( $size ) )
                            {
                                $imageAlias  = $content->imageAlias( $size );
                                $srcString   = $URL . '/' . $imageAlias['url'];
                                $imageWidth  = $imageAlias['width'];
                                $imageHeight = $imageAlias['height'];
                            }
                        }
                    }

                    if ( $className != '' )
                        $objectAttr .= ' class="' . $className . '"';

                    $output .= '<img id="' . $idString . '" title="' . $objectName . '" src="' . $srcString . '" width="' . $imageWidth . '" height="' . $imageHeight . '" ' . $objectAttr . $customAttributePart . $styleString . ' />';
                }
                else if ( self::embedTagIsCompatibilityMode() )
                {
                    $srcString = self::getDesignFile('images/tango/mail-attachment32.png');
                    if ( $className != '' )
                        $objectAttr .= ' class="' . $className . '"';

                    $output .= '<img id="' . $idString . '" title="' . $objectName . '" src="' . $srcString . '" width="32" height="32" ' . $objectAttr . $customAttributePart . $styleString . ' />';
                }
                else
                {
                    if ( $className )
                        $objectAttr .= ' class="mceNonEditable ' . $className . ' mceItemContentType' . ucfirst( $embedContentType ) . '"';
                    else
                        $objectAttr .= ' class="mceNonEditable mceItemContentType' . ucfirst( $embedContentType ) . '"';

                    if ( $tagName === 'embed-inline' )
                        $htmlTagName = 'span';
                    else
                        $htmlTagName = 'div';
                    
                    $objectParam = array( 'size' => $size, 'align' => $alignment, 'show_path' => $showPath );
                    if ( $htmlID ) $objectParam['id'] = $htmlID;
                    
                    $res = eZTemplateDesignResource::instance();
                    $res->setKeys( array( array('classification', $className) ) );

                    $tpl = templateInit();
                    $tpl->setVariable( 'view', $view );
                    $tpl->setVariable( 'object', $object );
                    $tpl->setVariable( 'link_parameters', array() );
                    $tpl->setVariable( 'classification', $className );
                    $tpl->setVariable( 'object_parameters', $objectParam );
                    if ( isset( $node ) ) $tpl->setVariable( 'node', $node );
                    $templateOutput = $tpl->fetch( 'design:content/datatype/view/ezxmltags/' . $tagName . $tplSuffix . '.tpl' );
                    $output .= '<' . $htmlTagName . ' id="' . $idString . '" title="' . $objectName . '"' . $objectAttr . $customAttributePart . $styleString . '>' . $templateOutput . '</' . $htmlTagName . '>';
                }
            }break;

            case 'custom' :
            {
                $name = $tag->getAttribute( 'name' );
                $customAttributePart = $this->getCustomAttrPart( $tag, $styleString );

                if ( isset( self::$nativeCustomTags[ $name ] ))
                {
                    if ( !$childTagText ) $childTagText = '&nbsp;';
                    $output .= '<' . self::$nativeCustomTags[ $name ] . $customAttributePart . $styleString . '>' . $childTagText . '</' . self::$nativeCustomTags[ $name ] . '>';
                }
                else if ( self::customTagIsInline( $name ) )
                {
                    if ( !$childTagText ) $childTagText = '&nbsp;';
                    $output .= '<span class="mceItemCustomTag ' . $name . '" type="custom"' . $customAttributePart . $styleString . '>' . $childTagText . '</span>';
                }
                else
                {
                    $customTagContent = $this->inputSectionXML( $tag, $currentSectionLevel, 1 );
                    /*foreach ( $tag->childNodes as $tagChild )
                    {
                        $customTagContent .= $this->inputTdXML( $tagChild, $currentSectionLevel, 1 );
                    }*/
                    $output .= '<div class="mceItemCustomTag ' . $name . '" type="custom"' . $customAttributePart . '>' . $customTagContent . '</div>';
                }
            }break;

            case 'literal' :
            {
                $literalText = '';
                foreach ( $tagChildren as $childTag )
                {
                    $literalText .= $childTag->textContent;
                }
                $className = $tag->getAttribute( 'class' );

                $customAttributePart = $this->getCustomAttrPart( $tag, $styleString );

                $literalText = htmlspecialchars( $literalText );
                $literalText = str_replace( '  ', ' &nbsp;', $literalText );
                //$literalText = str_replace( "\n\n", '</p><p>', $literalText );
                $literalText = str_replace( "\n", '<br />', $literalText );

                if ( $className != '' )
                    $customAttributePart .= ' class="' . $className . '"';

                $output .= '<pre' . $customAttributePart . $styleString . '>' . $literalText . '</pre>';

            }break;

            case 'ul' :
            case 'ol' :
            {
                $listContent = '';

                $customAttributePart = $this->getCustomAttrPart( $tag, $styleString );

                // find all list elements
                foreach ( $tag->childNodes as $listItemNode )
                {
                    $LIcustomAttributePart = $this->getCustomAttrPart( $listItemNode, $listItemStyleString );

                    $noParagraphs = $listItemNode->childNodes->length <= 1;
                    $listItemContent = '';
                    foreach ( $listItemNode->childNodes as $itemChildNode )
                    {
                        $listSectionLevel = $currentSectionLevel;
                        if ( $itemChildNode->nodeName === 'section' or $itemChildNode->nodeName === 'paragraph' )
                        {
                            $listItemContent .= $this->inputListXML( $itemChildNode, $currentSectionLevel, $listSectionLevel, $noParagraphs );
                        }
                        else
                        {
                            $listItemContent .= $this->inputTagXML( $itemChildNode, $currentSectionLevel, $tdSectionLevel );
                        }
                    }
                    
                    $LIclassName = $listItemNode->getAttribute( 'class' );
                    
                    if ( $LIclassName )
                        $LIcustomAttributePart .= ' class="' . $LIclassName . '"';

                    $listContent .= '<li' . $LIcustomAttributePart . $listItemStyleString . '>' . $listItemContent . '</li>';
                }
                $className = $tag->getAttribute( 'class' );
                if ( $className != '' )
                    $customAttributePart .= ' class="' . $className . '"';

                $output .= '<' . $tagName . $customAttributePart . $styleString . '>' . $listContent . '</' . $tagName . '>';
            }break;

            case 'table' :
            {
                $tableRows = '';
                $border = $tag->getAttribute( 'border' );
                $width = $tag->getAttribute( 'width' );
                $tableClassName = $tag->getAttribute( 'class' );

                $customAttributePart = $this->getCustomAttrPart( $tag, $styleString );

                // find all table rows
                foreach ( $tag->childNodes as $tableRow )
                {
                    $TRcustomAttributePart = $this->getCustomAttrPart( $tableRow, $tableRowStyleString );
                    $TRclassName = $tableRow->getAttribute( 'class' );

                    $tableData = '';
                    foreach ( $tableRow->childNodes as $tableCell )
                    {
                        $TDcustomAttributePart = $this->getCustomAttrPart( $tableCell, $tableCellStyleString );

                        $cellAttribute = '';
                        $className = $tableCell->getAttribute( 'class' );

                        $colspan = $tableCell->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/xhtml/', 'colspan' );
                        $rowspan = $tableCell->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/xhtml/', 'rowspan' );
                        $cellWidth = $tableCell->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/xhtml/', 'width' );
                        if ( $className != '' )
                        {
                            $cellAttribute .= ' class="' . $className . '"';
                        }
                        if ( $cellWidth != '' )
                        {
                            $cellAttribute .= ' width="' . $cellWidth . '"';
                        }
                        if ( $colspan && $colspan !== '1' )
                        {
                            $cellAttribute .= ' colspan="' . $colspan . '"';
                        }
                        if ( $rowspan && $rowspan !== '1' )
                        {
                            $cellAttribute .= ' rowspan="' . $rowspan . '"';
                        }
                        $cellContent = '';
                        $tdSectionLevel = $currentSectionLevel;
                        foreach ( $tableCell->childNodes as $tableCellChildNode )
                        {
                            $cellContent .= $this->inputTdXML( $tableCellChildNode, $currentSectionLevel, $tdSectionLevel - $currentSectionLevel );
                        }
                        if ( $cellContent === '' )
                        {
                            $cellContent = '<br mce_bogus="1"/>';// tinymce has some issues with empty content in some browsers
                        }
                        if ( $tableCell->nodeName === 'th' )
                        {
                            $tableData .= '<th' . $cellAttribute . $TDcustomAttributePart . $tableCellStyleString . '>' . $cellContent . '</th>';
                        }
                        else
                        {
                            $tableData .= '<td' . $cellAttribute . $TDcustomAttributePart . $tableCellStyleString . '>' . $cellContent . '</td>';
                        }
                    }
                    if ( $TRclassName )
                        $TRcustomAttributePart .= ' class="' . $TRclassName . '"';

                    $tableRows .= '<tr' . $TRcustomAttributePart . $tableRowStyleString . '>' . $tableData . '</tr>';
                }
                //if ( self::$browserType === 'IE' )
                //{
                    $customAttributePart .= ' width="' . $width . '"';
                /*}
                else
                {
                    $customAttributePart .= ' style="width:' . $width . ';"';// if this is reenabled merge it with $styleString
                }*/

                if ( is_string( $border ) )
                {
                    $customAttributePart .= ' border="' . $border . '"';
                }

                if ( $tableClassName )
                {
                    $customAttributePart .= ' class="' . $tableClassName . '"';
                }

                $output .= '<table' . $customAttributePart . $styleString . '><tbody>' . $tableRows . '</tbody></table>';
            }break;

            // normal content tags
            case 'emphasize' :
            {
                $customAttributePart = $this->getCustomAttrPart( $tag, $styleString );

                $className = $tag->getAttribute( 'class' );
                if ( $className )
                {
                    $customAttributePart .= ' class="' . $className . '"';
                }
                $output .= '<i' . $customAttributePart . $styleString . '>' . $childTagText  . '</i>';
            }break;

            case 'strong' :
            {
                $customAttributePart = $this->getCustomAttrPart( $tag, $styleString );

                $className = $tag->getAttribute( 'class' );
                if ( $className  )
                {
                    $customAttributePart .= ' class="' . $className . '"';
                }
                $output .= '<b' . $customAttributePart . $styleString . '>' . $childTagText  . '</b>';
            }break;

            case 'line' :
            {
                $output .= $childTagText . '<br />';
            }break;

            case 'anchor' :
            {
                $name = $tag->getAttribute( 'name' );

                $customAttributePart = $this->getCustomAttrPart( $tag, $styleString );

                $output .= '<a id="' . $name . '" class="mceItemAnchor"' . $customAttributePart . $styleString . '></a>';
            }break;

            case 'link' :
            {
                $customAttributePart = $this->getCustomAttrPart( $tag, $styleString );

                $linkID = $tag->getAttribute( 'url_id' );
                $target = $tag->getAttribute( 'target' );
                $className = $tag->getAttribute( 'class' );
                $viewName = $tag->getAttribute( 'view' );
                $objectID = $tag->getAttribute( 'object_id' );
                $nodeID = $tag->getAttribute( 'node_id' );
                $anchorName = $tag->getAttribute( 'anchor_name' );
                $showPath = $tag->getAttribute( 'show_path' );
                $htmlID = $tag->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/xhtml/', 'id' );
                $htmlTitle = $tag->getAttributeNS( 'http://ez.no/namespaces/ezpublish3/xhtml/', 'title' );
                $attributes = array();

                if ( $objectID != null )
                {
                    $href = 'ezobject://' .$objectID;
                }
                elseif ( $nodeID != null )
                {
                    if ( $showPath === 'true' )
                    {
                        $node = eZContentObjectTreeNode::fetch( $nodeID );
                        $href = $node ? 'eznode://' . $node->attribute('path_identification_string') : 'eznode://' . $nodeID;
                    }
                    else
                    {
                        $href = 'eznode://' . $nodeID;
                    }
                }
                elseif ( $linkID != null )
                {
                    $href = eZURL::url( $linkID );
                }
                else
                {
                    $href = $tag->getAttribute( 'href' );
                }

                if ( $anchorName != null )
                {
                    $href .= '#' . $anchorName;
                }

                if ( $className != '' )
                {
                    $attributes[] = 'class="' . $className . '"';
                }

                if ( $viewName != '' )
                {
                    $attributes[] = 'view="' . $viewName . '"';
                }

                $attributes[] = 'href="' . $href . '"';
                if ( $target != '' )
                {
                    $attributes[] = 'target="' . $target . '"';
                }
                if ( $htmlTitle != '' )
                {
                    $attributes[] = 'title="' . $htmlTitle . '"';
                }
                if ( $htmlID != '' )
                {
                   $attributes[] = 'id="' . $htmlID . '"';
                }

                $attributeText = '';
                if ( count( $attributes ) > 0 )
                {
                    $attributeText = ' ' .implode( ' ', $attributes );
                }
                $output .= '<a' . $attributeText . $customAttributePart . $styleString . '>' . $childTagText . '</a>';
            }break;
            case 'tr' :
            case 'td' :
            case 'th' :
            case 'li' :
            case 'paragraph' :
            {
            }break;
            default :
            {

            }break;
        }
        return $output;
    }
    
    static public function getServerURL()
    {
        if ( self::$serverURL === null  )
        {
            $oeini = eZINI::instance( 'ezoe.ini' );
            if ( $oeini->hasVariable( 'SystemSettings', 'RelativeURL' ) &&
                 $oeini->variable( 'SystemSettings', 'RelativeURL' ) === 'enabled' )
            {
                self::$serverURL = eZSys::wwwDir();
                if ( self::$serverURL === '/'  )
                    self::$serverURL = '';
            }
            else
            {
                $domain = eZSys::hostname();
                $protocol = 'http';
                
                // Default to https if SSL is enabled
                // Check if SSL port is defined in site.ini
                $sslPort = 443;
                $ini = eZINI::instance();
                if ( $ini->hasVariable( 'SiteSettings', 'SSLPort' ) )
                    $sslPort = $ini->variable( 'SiteSettings', 'SSLPort' );
                
                if ( eZSys::serverPort() == $sslPort )
                    $protocol = 'https';

                self::$serverURL = $protocol . '://' . $domain . eZSys::wwwDir();
            }
        }
        return self::$serverURL;
    }

    static public function getDesignFile( $file, $triedFiles = array() )
    {
        if ( self::$designBases === null )
        {
            self::$designBases = eZTemplateDesignResource::allDesignBases();
        }

        $match = eZTemplateDesignResource::fileMatch( self::$designBases, '', $file, $triedFiles );

        if ( $match === false )
        {
            eZDebug::writeWarning( "Could not find: $file", "eZOEXMLInput::getDesignFile()" );
            return $file;
        }
        return htmlspecialchars( self::getServerURL() . '/' . $match['path'] );
    }

    static public function customTagIsInline( $name )
    {
        if ( self::$customInlineTagList === null )
        {
            $ini = eZINI::instance( 'content.ini' );
            self::$customInlineTagList = $ini->variable( 'CustomTagSettings', 'IsInline' );   
        }

        foreach ( self::$customInlineTagList as $key => $isInlineTagValue )
        {
            if ( $name === $key && $isInlineTagValue === 'true' )
            {
                return true;
            }
        }
        return false;
    }

    static public function embedTagIsImageByNode( $node )
    {
        $objectID  = $node->getAttribute( 'object_id' );
        $nodeID    = $node->getAttribute( 'node_id' );
        $object    = false;
        $classID   = 0;
        $classIdentifier = false;
        
        if ( is_numeric( $objectID ) )
        {
            $object = eZContentObject::fetch( $objectID );
        }
        elseif ( is_numeric( $nodeID ) )
        {
            $node      = eZContentObjectTreeNode::fetch( $nodeID );
            $object    = $node->object();
        }
        
        if ( $object instanceof eZContentObject )
        {
            $classID = $object->attribute( 'contentclass_id' );
            $classIdentifier = $object->attribute( 'class_identifier' );
        }

        return self::embedTagContentType(  $classIdentifier, $classID ) === 'images';
    }

    static public function embedTagContentType( $classIdentifier, $classID = 0  )
    {
        $contentIni = eZINI::instance('content.ini');         

        foreach ( $contentIni->variable( 'RelationGroupSettings', 'Groups' ) as $group )
        {
            $settingName = ucfirst( $group ) . 'ClassList';
            if ( $contentIni->hasVariable( 'RelationGroupSettings', $settingName ) )
            {
                if ( in_array( $classIdentifier, $contentIni->variable( 'RelationGroupSettings', $settingName ) ) )
                    return $group;
            }
            else
                eZDebug::writeDebug( "Missing content.ini[RelationGroupSettings]$settingName setting.", "eZOEXMLInput::embedTagContentType()" );
        }

        $ini = eZINI::instance();
        if ( $ini->hasVariable('MediaClassSettings', 'ImageClassID' ) and
           in_array( $classID, $ini->variable('MediaClassSettings', 'ImageClassID' ) ))
        {
            eZDebug::writeNotice( "site.ini[MediaClassSettings]ImageClassID is depricated, use content.ini[RelationGroupSettings] instead.", "eZOEXMLInput::embedTagContentType()" );
            return 'images';
        }
        return $contentIni->variable( 'RelationGroupSettings', 'DefaultGroup' );
    }

    static public function embedTagIsCompatibilityMode()
    {
        if ( self::$embedIsCompatibilityMode === null )
        {
            $ezoeIni = eZINI::instance('ezoe.ini');
            self::$embedIsCompatibilityMode = $ezoeIni->variable('EditorSettings', 'CompatibilityMode' ) === 'enabled';
        }
        return self::$embedIsCompatibilityMode;
    }

    static protected $serverURL   = null;
    static protected $browserType = null;
    static protected $designBases = null;
    static protected $userAccessHash = array();
    static protected $customInlineTagList = null;
    static protected $customAttributeStyleMap = null;
    static protected $embedIsCompatibilityMode = null;
    
    protected $editorLayoutSettings = null;
    static protected $editorGlobalLayoutSettings = null;

    static protected $showEmbedValidationErrors = null;

    public $LineTagArray = array( 'emphasize', 'strong', 'link', 'a', 'em', 'i', 'b', 'bold', 'anchor' );
    /// Contains the XML data
    public $XMLData;

    public $ContentObjectAttributeID;
    public $ContentObjectAttributeVersion;

    public $IsStrictHeader = false;
    public $SectionArray = array(  'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'section' );

    public $eZPublishVersion;

    public $trimSpaces = false;
    public $allowMultipleSpaces = true;
}

?>