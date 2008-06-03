<?php
//
// $Id$
//
// Definition of eZXML class
//
// Created on: <13-Feb-2002 09:15:42 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

require_once( "lib/ezutils/classes/ezdebug.php" );
//include_once( "lib/ezxml/classes/ezdomnode.php" );
//include_once( "lib/ezxml/classes/ezdomdocument.php" );

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

      $params["SetParentNode"] = false/true : create eZDOMDocument with setParentNode parameter set to true or false.
      $params["TrimWhiteSpace"] = false/true : should the XML parser ignore whitespaces between tags.
      $params["CharsetConversion"] = false/true : Whether charset conversion is done or not, default is true.
      $params["ConvertSpecialChars"] = false/true: whether to convert &lt; &gt; &amp; etc into < > &; default is true.
    */
    function domTree( $xmlDoc, $params = array(), $native = false )
    {
        if ( !$xmlDoc )
        {
            $tmp = null;
            return $tmp;
        }

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

        if ( !isset( $params["TrimWhiteSpace"] ) )
            $params["TrimWhiteSpace"] = true;

        if ( !isset( $params["SetParentNode"] ) )
            $params["SetParentNode"] = false;

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

        if ( $charset !== false )
        {
            //include_once( 'lib/ezi18n/classes/eztextcodec.php' );
            $codec = eZTextCodec::instance( $charset, false, false );
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
        $domDocument = new eZDOMDocument( '', $params["SetParentNode"] );

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
                    // If $justname contains ![CDATA[ we should not set namespace prefix
                    $colonPos = strpos( $justName, "![CDATA[" ) === false ? strpos( $justName, ":" ) : false;

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
                    $subNode = $domDocument->createElementNode( $justName );

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
                        if ( $endTagPos == false )
                        {
                            eZDebug::writeError( "XML parser error: Closing tag \']]>\' for <![CDATA[ not found" , "eZ xml" );
                            $endTagPos = strlen($xmlDoc);
                        }
                        $cdataSection = substr( $xmlDoc, $cdataPos + 9, $endTagPos - ( $cdataPos + 9 ) );

                        // new CDATA node
                        $subNode->Name = $subNode->LocalName = "#cdata-section";
                        $subNode->Content = $cdataSection;
                        $subNode->Type = eZDOMNode::TYPE_CDATASECTION;

                        $pos = $endTagPos;
                        $endTagPos += 2;
                    }
                    else
                    {
                        // element start tag
                        //$subNode->Name = $justName;
                        //$subNode->LocalName = $justName;
                        //$subNode->Type = eZDOMNode::TYPE_ELEMENT;

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

                // Keep the whitespace consistent, parsing back and forward shouldn't change data
                $tagContent = preg_replace( "#[\n]+[\s]*$#", "", $tagContent, 1 );

                if ( ( $params["TrimWhiteSpace"] == true and trim( $tagContent ) != "" ) or ( $params["TrimWhiteSpace"] == false and $tagContent != "" ) )
                {
                    // convert special chars
                    if ( $params["ConvertSpecialChars"] == true )
                    {
                        $tagContent = str_replace("&gt;", ">", $tagContent );
                        $tagContent = str_replace("&lt;", "<", $tagContent );
                        $tagContent = str_replace("&apos;", "'", $tagContent );
                        $tagContent = str_replace("&quot;", '"', $tagContent );
                        $tagContent = str_replace("&amp;", "&", $tagContent );
                    }

                    unset( $subNode );
                    $subNode = $domDocument->createTextNode( $tagContent );

                    $domDocument->registerElement( $subNode );
                    $currentNode->appendChild( $subNode );
                }
            }
        }
        if ( !$domDocument->Root )
        {
            $tmp = null;
            return $tmp;
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

                $attrNode->Type = eZDOMNode::TYPE_ATTRIBUTE;
                $attrNode->Content = $attributeValue;


                $ret[] = $attrNode;

            }
        }
        return $ret;
    }

    /// Contains the namespaces
    public $NamespaceStack = array();

    /// Contains the available namespaces
    public $NamespaceArray = array();

    /// Contains the current namespace
    public $CurrentNameSpace;

    /// Contains a reference to the DOM document object
    public $DOMDocument;
}

?>
