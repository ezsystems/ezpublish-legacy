<?php
//
// Created on: <17-Jul-2002 12:44:19 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available a
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "lib/ezxml/classes/ezxml.php" );

include_once( "kernel/classes/eztextinputparser.php" );

$textParser = new eZTextInputParser();

$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( "InputText" ) )
{
    $inputText = $http->postVariable( "InputText" );
    $chunkArray = $textParser->parseText( $inputText );

    $inlineTags = array( "bold", "br" );
    $flowBreakTags = array( "image", "blockquote" );

    $doc = new eZDOMDocument();
    $mainNode = $doc->createElementNode( "document" );
    $doc->appendChild( $mainNode );
    $currentNode =& $mainNode;

    $outputText = "";
    foreach ( $chunkArray as $chunk )
    {
        $outputText .= " " . $chunk["Type"] . " ";
        $outputText .= " " . $chunk['TagName'] . " ";
        $outputText .= " " . $chunk["Text"] . "\n";

        {
            if ( !isset( $currentParagraph ) )
            {
                $currentParagraph =& $doc->createElementNode( "paragraph" );
                $currentNode->appendChild( $currentParagraph );
            }

            if ( $chunk["Type"] == EZ_INPUT_CHUNK_TEXT )
            {
                $currentParagraph->appendChild( $doc->createTextNode( trim( $chunk["Text"] ) ) );
            }
            else
            {
                if ( in_array( $chunk['TagName'], $inlineTags ) )
                {
                    unset( $tmpNode );
                    $tmpNode =& $doc->createElementNode( $chunk['TagName'] );
                    $currentParagraph->appendChild( $tmpNode );
                    $currentParagraph =& $tmpNode;
                }
                else if ( in_array( $chunk['TagName'], $flowBreakTags ) )
                {
                    unset( $tmpNode );
                    $tmpNode =& $doc->createElementNode( $chunk['TagName'] );
                    $mainNode->appendChild( $tmpNode );
                    unset( $currentParagraph );
                }
            }
        }
    }
    eZDebug::writeNotice( $doc->toString(), "XML doc"  );
}

?>

<p>
When a user inputs data into an XML field eZ publish will automatically convert
the input to valid XML.
</p>

<p>
There are two different types of tags
</p>
<ul>
  <li>Self terminated tags, e.g. &lt;br/&gt;</li>
  <li>Begin / end tags, e.g. &lt;em&gt;text&lt;/em&gt;</li>
</ul>


<b>Input test</b>:<br />
<form method="post">

<textarea rows="15" cols="80" name="InputText" ><? print( $inputText );?></textarea><br /><br />
<textarea rows="15" cols="80" name="OutputText" ><? print( ( htmlspecialchars( $outputText )  ) );?></textarea>
<br /><br />

<input type="submit" name="ConvertButton" value="Convert" />

</form>
