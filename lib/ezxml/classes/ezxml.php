<?php
//
// $Id$
//
// Definition of eZXML class
//
// Created on: <13-Feb-2002 09:15:42 bf>
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

/*! \file ezxml.php
  XML DOM parser.
*/

/*! \defgroup eZXML XML parser and DOM library */

/*!
  \class eZXML ezxml.php
  \ingroup eZXML
  \brief eZXML handles parsing of well formed XML documents.

1  eZXML will create a DOM tree from well formed XML documents.

 \sa eZDOMDocument eZDOMNode
*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezxml/classes/ezdomnode.php" );
include_once( "lib/ezxml/classes/ezdomdocument.php" );

define( "EZ_NODE_TYPE_ELEMENT", 1 );
define( "EZ_NODE_TYPE_ATTRIBUTE", 2 );
define( "EZ_NODE_TYPE_TEXT", 3 );
define( "EZ_NODE_TYPE_CDATASECTION", 4 );

class eZXML
{
    /*!
      Constructor
    */
    function eZXML( )
    {

    }

    /*!
      Will return a DOM object tree from the well formed XML.

      $params["TrimWhiteSpace"] = false/true : if the XML parser should ignore whitespace between tags.
      $params["CharsetConversion"] = false/true : Whether charset conversion is done or not, default is true.
      $params["ConvertSpecialChars"] = false/true: whether to convert &lt; &gt; &amp; etc into < > &; default is true.
    */
    function &domTree( $xmlDoc, $params = array(), $native = false )
    {
        /* We remove all control chars from the text, although they
         * should have not be there in the first place. This is
         * iso-8859-1 and UTF-8 safe. Those characters might also never exist
         * in an XML document in the first place
         * (http://w3.org/TR/2004/REC-xml-20040204/#NT-Char) so it's safe to
         * remove them */
        $xmlDoc = preg_replace('/[\x00-\x08\x0b-\x0c\x0e-\x1f]/', '', $xmlDoc);

        if ( $native and function_exists( 'domxml_open_mem' ) )
        {
            $domDocument = domxml_open_mem( $xmlDoc );
            return $domDocument;
        }
        $params["TrimWhiteSpace"] = true;

        $schema = false;
        if ( isset( $params["Schema"] ) && get_class( $params["Schema"]  ) == "ezschema" )
        {
            $schema = $params["Schema"];
        }
        $charset = 'UTF-8';
        if ( isset( $params['CharsetConversion'] ) and
             !$params['CharsetConversion'] )
            $charset = false;
        if ( !isset( $params['ConvertSpecialChars'] ) )
        {
            $params['ConvertSpecialChars'] = true;
        }

        $TagStack = array();

        $xmlAttributes = array();

        // strip header
        if ( preg_match( "#<\?xml(.*?)\?>#", $xmlDoc, $matches ) )
        {
            $xmlAttributeText = $matches[1];
            $xmlAttributes = $this->parseAttributes( $xmlAttributeText );
            for ( $i = 0; $i < count( $xmlAttributes ); ++$i )
            {
                $xmlAttribute =& $xmlAttributes[$i];
                if ( $xmlAttribute->name() == 'encoding' )
                    $charset = $xmlAttribute->content();
                // This is required due to a bug in an old xml parser
                else if ( $xmlAttribute->name() == 'charset' )
                    $charset = $xmlAttribute->content();
            }
        }
        else if ( !preg_match( "#<[a-zA-Z0-9_-]+>#", $xmlDoc ) )
        {
            $retVal = null;
            return $retVal;
        }
        if ( $charset !== false )
        {
            include_once( 'lib/ezi18n/classes/eztextcodec.php' );
            $codec =& eZTextCodec::instance( $charset, false, false );
            if ( $codec )
            {
                $xmlDoc = $codec->convertString( $xmlDoc );
            }
        }

        $xmlDoc = preg_replace( "#<\?.*?\?>#", "", $xmlDoc );

        // get document version
        $xmlDoc = preg_replace( "%<\!DOCTYPE.*?>%is", "", $xmlDoc );

        // convert all newline types to unix newlines
        $xmlDoc = preg_replace( "#\n|\r\n|\r#", "\n", $xmlDoc );

        // strip comments
        $xmlDoc = $this->stripComments( $xmlDoc );

        // libxml compatible object creation
        $domDocument = new eZDOMDocument();

        $this->DOMDocument =& $domDocument;
        $currentNode =& $domDocument;

        $defaultNamespace = "";

        $pos = 0;
        $endTagPos = 0;
        while ( $pos < strlen( $xmlDoc ) )
        {
            $char = $xmlDoc[$pos];
            if ( $char == "<" )
            {
                // find tag name
                $endTagPos = strpos( $xmlDoc, ">", $pos );

                // tag name with attributes
                $tagName = substr( $xmlDoc, $pos + 1, $endTagPos - ( $pos + 1 ) );

                // check if it's an endtag </tagname>
                if ( $tagName[0] == "/" )
                {
                    $lastNodeArray = array_pop( $TagStack );
                    $lastTag = $lastNodeArray["TagName"];

                    $lastNode =& $lastNodeArray["ParentNodeObject"];

                    unset( $currentNode );
                    $currentNode =& $lastNode;

                    $tagName = substr( $tagName, 1, strlen( $tagName ) );

                    // strip out namespace; nameSpace:Name
                    $colonPos = strpos( $tagName, ":" );

                    if ( $colonPos > 0 )
                        $tagName = substr( $tagName, $colonPos + 1, strlen( $tagName ) );

                    if ( $lastTag != $tagName )
                    {
                        eZDebug::writeError( "Error parsing XML, unmatched tags $tagName" );
                        $retVal = false;
                        return $retVal;
                    }
                    else
                    {
                        //    print( "endtag name: $tagName ending: $lastTag <br> " );
                    }
                }
                else
                {
                    $firstSpaceEnd = strpos( $tagName, " " );
                    $firstNewlineEnd = strpos( $tagName, "\n" );

                    if ( $firstNewlineEnd != false )
                    {
                        if ( $firstSpaceEnd != false )
                        {
                            $tagNameEnd = min( $firstSpaceEnd, $firstNewlineEnd );
                        }
                        else
                        {
                            $tagNameEnd = $firstNewlineEnd;
                        }
                    }
                    else
                    {
                        if ( $firstSpaceEnd != false )
                        {
                            $tagNameEnd = $firstSpaceEnd;
                        }
                        else
                        {
                            $tagNameEnd = 0;
                        }
                    }

                    if ( $tagNameEnd > 0 )
                    {
                        $justName = substr( $tagName, 0, $tagNameEnd );
                    }
                    else
                        $justName = $tagName;


                    // strip out the namespace prefix
                    $colonPos = strpos( $justName, ":" );

                    $prefix = "";
                    if ( $colonPos > 0 )
                    {
                        $prefix = substr( $justName, 0, $colonPos );
                        $justName = substr( $justName, $colonPos + 1, strlen( $justName ) );
                    }


                    // remove trailing / from the name if exists
                    if ( $justName[strlen($justName) - 1]  == "/" )
                    {
                        $justName = substr( $justName, 0, strlen( $justName ) - 1 );
                    }


                    // create the new XML element node
                    unset( $subNode );
                    $subNode = new eZDOMNode();

                    // find attributes
                    if ( $tagNameEnd > 0 )
                    {
                        unset( $attributePart );
                        $attributePart = substr( $tagName, $tagNameEnd, strlen( $tagName ) );

                        // attributes
                        unset( $attr );
                        $attr = $this->parseAttributes( $attributePart );

                        if ( $attr != false )
                            $subNode->Attributes =& $attr;
                    }

                    if ( $prefix != false  )
                    {
                        $subNode->Prefix = $prefix;

                        // find prefix
                        if ( isSet( $this->NamespaceArray[$prefix] ) )
                        {
                            $subNode->setNamespaceURI( $this->NamespaceArray[$prefix] );
                        }
                        else
                        {
                            eZDebug::writeError( "Namespace: $prefix not defined", "eZ xml" );
                        }
                    }
                    else
                    {
                        // set the default namespace
                        if ( isset( $this->NamespaceStack[0] ) )
                        {
                            $subNode->setNamespaceURI( $this->NamespaceStack[0] );
                        }
                    }

                    // check for CDATA
                    $cdataSection = "";
                    $isCDATASection = false;
                    $cdataPos = strpos( $xmlDoc, "<![CDATA[", $pos );
                    if ( $cdataPos == $pos && $pos > 0)
                    {
                        $isCDATASection = true;
                        $endTagPos = strpos( $xmlDoc, "]]>", $cdataPos );
                        $cdataSection = substr( $xmlDoc, $cdataPos + 9, $endTagPos - ( $cdataPos + 9 ) );

                        // new CDATA node
                        $subNode->Name = "#cdata-section";
                        $subNode->Content = $cdataSection;
                        $subNode->Type = EZ_NODE_TYPE_CDATASECTION;

                        $pos = $endTagPos;
                        $endTagPos += 2;
                    }
                    else
                    {
                        // element start tag
                        $subNode->Name = $justName;
                        $subNode->LocalName = $justName;
                        $subNode->Type = EZ_NODE_TYPE_ELEMENT;

                        $domDocument->registerElement( $subNode );
                    }


                    $currentNode->appendChild( $subNode );


                    // check it it's a oneliner: <tagname /> or a cdata section
                    if ( $isCDATASection == false )
                        if ( $tagName[strlen($tagName) - 1]  != "/" )
                        {
                            $TagStack[] = array( "TagName" => $justName, "ParentNodeObject" => &$currentNode );

                            unset( $currentNode );
                            $currentNode =& $subNode;
                        }
                }
            }

            $pos = strpos( $xmlDoc, "<", $pos + 1 );

            if ( $pos == false )
            {
                // end of document
                $pos = strlen( $xmlDoc );
            }
            else
            {
                // content tag
                $tagContent = substr( $xmlDoc, $endTagPos + 1, $pos - ( $endTagPos + 1 ) );

                if ( !isset( $params["TrimWhiteSpace"] ) )
                    $params["TrimWhiteSpace"] = true;

                if ( ( ( $params["TrimWhiteSpace"] == true ) and ( trim( $tagContent ) != "" ) ) or ( $params["TrimWhiteSpace"] == false ) )
                {
                    unset( $subNode );
                    $subNode = new eZDOMNode();
                    $subNode->Name = "#text";
                    $subNode->Type = EZ_NODE_TYPE_TEXT;
//                    $subNode->NamespaceURI = $this->NamespaceStack[0];

                    // convert special chars
                    if ( $params["ConvertSpecialChars"] == true )
                    {
                        $tagContent = str_replace("&gt;", ">", $tagContent );
                        $tagContent = str_replace("&lt;", "<", $tagContent );
                        $tagContent = str_replace("&apos;", "'", $tagContent );
                        $tagContent = str_replace("&quot;", '"', $tagContent );
                        $tagContent = str_replace("&amp;", "&", $tagContent );
                    }

                    $subNode->Content = $tagContent;
//                    $subNode->Content = trim( $tagContent );

                    $domDocument->registerElement( $subNode );

                    $currentNode->appendChild( $subNode );
                }
            }
        }

        return $domDocument;
    }

    /*!
      \static
      \private
    */
    function stripComments( &$str )
    {
        return preg_replace( "#<\!--.*?-->#s", "", $str );
    }

    /*!
      \private
      Parses the attributes. Returns false if no attributes in the supplied string is found.
    */
    function parseAttributes( $attributeString )
    {
        $ret = false;

        preg_match_all( "/([a-zA-Z0-9:_-]+\s*=\s*(\"|').*?(\\2))/i",  $attributeString, $attributeArray );

        foreach ( $attributeArray[0] as $attributePart )
        {
            if ( trim( $attributePart ) != "" && trim( $attributePart ) != "/" )
            {
                $attributeNamespaceURI = false;
                $attributePrefix = false;
                $attributeTmpArray = preg_split ("#\s*(=\s*(\"|'))#", $attributePart );

                $attributeName = $attributeTmpArray[0];

                // strip out namespace; nameSpace:Name
                $colonPos = strpos( $attributeName, ":" );

                if ( $colonPos > 0 )
                {
                    $attributePrefix = substr( $attributeName, 0, $colonPos );
                    $attributeName = substr( $attributeName, $colonPos + 1, strlen( $attributeName ) );
                }
                else
                {
                    $attributePrefix = false;
                }

                $attributeValue = $attributeTmpArray[1];

                // remove " from value part
                $attributeValue = substr( $attributeValue, 0, strlen( $attributeValue ) - 1);

                $attributeValue = str_replace( "&gt;", ">", $attributeValue );
                $attributeValue = str_replace( "&lt;", "<", $attributeValue );
                $attributeValue = str_replace( "&apos;", "'", $attributeValue );
                $attributeValue = str_replace( "&quot;", '"', $attributeValue );
                $attributeValue = str_replace( "&amp;", "&", $attributeValue );

                // check for namespace definition
                if ( $attributePrefix == "xmlns" )
                {
                    $attributeNamespaceURI = $attributeValue;
                    $this->NamespaceArray[$attributeName] = $attributeValue;

                    $this->DOMDocument->registerNamespaceAlias( $attributeName, $attributeValue );
                }

                // check for default namespace definition
                if ( $attributeName == "xmlns" )
                {
                    $attributeNamespaceURI = $attributeValue;

                    // change the default namespace
                    $this->NamespaceStack[] = $attributeNamespaceURI;
                }

                unset( $attrNode );
                $attrNode = new eZDOMNode();
                $attrNode->Name = $attributeName;

                if ( $attributePrefix != false && $attributePrefix != "xmlns" )
                {
                    $attrNode->Prefix = $attributePrefix;
                    $attrNode->LocalName = $attributeName;

                    // find prefix
                    if ( isSet( $this->NamespaceArray["$attributePrefix"] ) )
                    {
                        $attrNode->NamespaceURI = $this->NamespaceArray["$attributePrefix"];
                    }
                    else
                    {
                        eZDebug::writeError( "Namespace: $attributePrefix not found", "eZ xml" );
                    }
                }
                else if ( $attributePrefix == "xmlns" )
                {
                    $attrNode->LocalName = $attributeName;
                    $attrNode->NamespaceURI = $attributeNamespaceURI;
                    $attrNode->Prefix = $attributePrefix;
                }
                else
                {
                    // check for default namespace definition
                    if ( $attributeName == "xmlns" )
                    {
                        $attrNode->LocalName = $attributeName;
                        $attrNode->NamespaceURI = $attributeNamespaceURI;
                    }
                    else
                    {
                        $attrNode->NamespaceURI = false;
                        $attrNode->LocalName = false;
                    }
                    $attrNode->Prefix = false;
                }

                $attrNode->Type = EZ_NODE_TYPE_ATTRIBUTE;
                $attrNode->Content = $attributeValue;


                $ret[] = $attrNode;

            }
        }
        return $ret;
    }

    /// Contains the namespaces
    var $NamespaceStack = array();

    /// Contains the available namespaces
    var $NamespaceArray = array();

    /// Contains the current namespace
    var $CurrentNameSpace;

    /// Contains a reference to the DOM document object
    var $DOMDocument;
}

?>
