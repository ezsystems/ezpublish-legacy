<?php
//
// $Id: ezmail.php,v 1.44.2.7 2002/06/10 16:41:45 fh Exp $
//
// Definition of eZMail class
//
// Created on: <15-Mar-2001 20:40:06 fh>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2003 eZ Systems.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

/*! \defgroup eZUtils Utility classes */

/*!
  \class eZMail ezmail.php
  \ingroup eZUtils
  \brief Mail object

  Class for storing the details about en email and providing
  text serialization.
*/


// The line separator as defined by RFC 2045
// Using \n as a separator is not correct
define( 'EZ_MAIL_LINE_SEPARATOR', "\r\n" );

class eZMail
{
    /*!
      Constructs a new eZMail object.
    */
    function eZMail()
    {
        $this->ReceiverElements = array();
        $this->From = false;
        $this->CcElements = array();
        $this->BccElements = array();
        $this->ReplyTo = false;
        $this->Subject = false;
        $this->BodyText = false;
        $this->ExtraHeaders = array();
    }

    /*!
      Returns the receiver addresses as text.
    */
    function receiverEmailText()
    {
        return $this->composeEmailItems( $this->ReceiverElements, true, 'email' );
    }

    /*!
      Returns the receiver addresses as text.
    */
    function receiverText()
    {
        return $this->composeEmailItems( $this->ReceiverElements );
    }

    /*!
      Returns the receiver addresses as an array with texts.
    */
    function receiverTextList()
    {
        return $this->composeEmailItems( $this->ReceiverElements, false );
    }

    /*!
      Returns the receiver addresses.
    */
    function receiverElements()
    {
        return $this->ReceiverElements;
    }

    /*!
      Returns the addresses which should get a carbon copy.
     */
    function ccElements()
    {
        return $this->CcElements;
    }

    /*!
      Returns the addresses which should get a blind carbon copy.
     */
    function bccElements()
    {
        return $this->BccElements;
    }


    /*!
      Returns the receiver address.
    */
    function replyTo()
    {
        return $this->ReplyTo;
    }

    /*!
      Returns the sender address.
    */
    function sender()
    {
        return $this->From;
    }

    /*!
      Returns the sender address as text.
    */
    function senderText()
    {
        return eZMail::composeEmailName( $this->From );
    }

    /*!
      Sets the receiver addresses.
    */
    function setReceiverElements( $toElements )
    {
        $this->ReceiverElements = $toElements;
    }

    /*!
      Sets the receiver address.
      \note This will remove all other receivers
      \sa addReceiver, setReceiverElements
    */
    function setReceiver( $email, $name = false )
    {
        $this->ReceiverElements = array( array( 'name' => $name,
                                                'email' => $email ) );
    }

    /*!
      Sets the receiver address, the email and name will be extracted from \a $text.
      \note This will remove all other receivers
      \sa addReceiver, setReceiverElements
    */
    function setReceiverText( $text )
    {
        $this->extractEmail( $text, $email, $name );
        $this->ReceiverElements = array( array( 'name' => $name,
                                                'email' => $email ) );
    }

    /*!
      Adds a new receiver address.
    */
    function addReceiver( $email, $name = false )
    {
        $this->ReceiverElements[] = array( 'name' => $name,
                                           'email' => $email );
    }


    /*!
      Sets the receiver address.
    */
    function setReplyTo( $email, $name = false )
    {
        $this->ReplyTo = array( 'name' => $name,
                                'email' => $email );
    }

    /*!
      Sets the sender address.
    */
    function setSender( $email, $name = false )
    {
        $this->From = array( 'name' => $name,
                             'email' => $email );
    }

    /*!
      Sets the sender address, the email and name will be extracted from \a $text.
    */
    function setSenderText( $text )
    {
        $this->extractEmail( $text, $email, $name );
        $this->From = array( 'name' => $name,
                             'email' => $email );
    }

    /*!
      Sets the cc addresses.
     */
    function setCcElements( $newCc )
    {
        $this->CcElements = $newCc;
    }

    /*!
      Adds a new Cc address.
    */
    function addCc( $email, $name = false )
    {
        $this->CcElements[] = array( 'name' => $name,
                                     'email' => $email );
    }

    /*!
      Sets the bcc addresses.
     */
    function setBccElements( $newBcc )
    {
        $this->BccElements = $newBcc;
    }

    /*!
      Adds a new Bcc address.
    */
    function addBcc( $email, $name = false )
    {
        $this->BccElements[] = array( 'name' => $name,
                                      'email' => $email );
    }

    /*!
     Sets the extra headers to \a $headers
    */
    function extraHeaders( $headers )
    {
        return $this->ExtraHeaders;
    }

    /*!
     Adds the headers \a $headerName with header value \a $headerValue to the extra headers.
    */
    function addExtraHeader( $headerName, $headerValue )
    {
        return $this->ExtraHeaders[] = array( 'name' => $headerName,
                                              'content' => $headerValue );
    }

    /*!
     Sets the extra headers to \a $headers.
    */
    function setExtraHeaders( $headers )
    {
        return $this->ExtraHeaders = $headers;
    }

    /*!
      Returns the message ID format : <number@serverID>
      Read in the RFC's if you want to know more about it..
     */
    function messageID()
    {
        return $this->MessageID;
    }

    /*!
      Sets the message ID. This is a server setting only so BE CAREFULL WITH THIS.
     */
    function setMessageID( $newMessageID )
    {
        $this->MessageID = $newMessageID;
    }

    /*!
      Returns the messageID that this message is a reply to.
     */
    function references()
    {
        return $this->References;
    }

    /*!
      Sets the messageID that this message is a reply to.
     */
    function setReferences( $newReference )
    {
        $this->References = $newReference;
    }

    /*!
      Returns the subject.
    */
    function subject()
    {
        return $this->Subject;
    }

    /*!
      Sets the subject of the mail.
    */
    function setSubject( $newSubject )
    {
        $this->Subject = trim( $newSubject );
    }

    /*!
      returns the body.
    */
    function body()
    {
        return $this->BodyText;
    }

    /*!
      Sets the body.
    */
    function setBody( $newBody )
    {
        $newBody = preg_replace( "/\r\n|\r|\n/", EZ_MAIL_LINE_SEPARATOR, $newBody );
        $this->BodyText = $newBody;
    }

    /*!
      \static
      Splits a list of email addresses into an array where each entry is an email address.
    */
    function &splitList( $emails )
    {
        $emails =& preg_split( "/[,;]/", $emails );
        return $emails;
    }

    /*!
      \static
      Static function for validating e-mail addresses.

      Returns true if successful, false if not.
    */
    function validate( $address )
    {
        $pos = ( ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $address) );
        return $pos;
    }

    function extractEmail( $text, &$email, &$name )
    {
        if ( preg_match( "/([^<]+)<([a-zA-Z0-9_-]+@([a-zA-Z0-9_-]+\\.)*[a-zA-Z0-9_-]+)>/", $text, $matches ) )
        {
            $email = $matches[1];
            $name = $matches[2];
        }
        $email = $text;
        $name = false;
    }

    /*!
      \static
      Static function for extracting an e-mail from text

      Returns the first valid e-mail in address, returns false if no e-mail addresses found
    */
    function stripEmail( $address )
    {
        $res = ereg( '[/0-9A-Za-z\.\?\-\_]+' . '@' .
                     '[/0-9A-Za-z\.\?\-\_]+', $address, $email );
        if ( $res )
            return $email[0];
        else
            return 0;
    }

    /*!
     \returns a text which does not contain newlines, newlines are converted to spaces.
    */
    function blankNewlines( $text )
    {
        return preg_replace( "/\r\n|\r|\n/", ' ', $text );
    }

    /*!
     \static
     Composes a text out of the email and name and returns it.

     Example: John Doe <john@doe.com> or just john@doe.com
    */
    function composeEmailName( $item, $key = false )
    {
        if ( $key !== false and
             isset( $item[$key] ) )
            return $item[$key];
        if ( $item['name'] )
            $text = $item['name'] . ' <' . $item['email'] . '>';
        else
            $text = $item['email'];
        return $text;
    }

    /*!
     \static
     Composes an email text out of all items in \a $items and returns it.
     All items are comma separated.
    */
    function composeEmailItems( $items, $join = true, $key = false )
    {
        $textElements = array();
        foreach ( $items as $item )
        {
            $textElements[] = eZMail::composeEmailName( $item, $key );
        }
        if ( $join )
            return implode( ', ', $textElements );
        else
            return $textElements;
    }

    /*!
     \return an array with headers, each header item is an associative array with the keys \c name and \c content.

     The parameter \a $parameters contains optional parameters, they can be:
     - exclude-headers - \c Array of header names which will not be included in the result array.
    */
    function headers( $parameters = array() )
    {
        $parameters = array_merge( array( 'exclude-headers' => false ),
                                   $parameters );
        $excludeHeaders = array();
        if ( $parameters['exclude-headers'] )
        {
            foreach ( $parameters['exclude-headers'] as $excludeHeader )
            {
                $excludeHeaders[] = strtolower( $excludeHeader );
            }
        }
        $headers = array();
        if ( !in_array( 'to', $excludeHeaders ) )
            $headers[] = array( 'name' => 'To',
                                'content' => $this->composeEmailItems( $this->ReceiverElements ) );
        if ( count( $this->CcElements ) > 0 and
             !in_array( 'cc', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'Cc',
                                'content' => $this->composeEmailItems( $this->CcElements ) );
        }
        if ( count( $this->BccElements ) > 0 and
             !in_array( 'bcc', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'Bcc',
                                'content' => $this->composeEmailItems( $this->BccElements ) );
        }
        if ( $this->From !== false and
             !in_array( 'from', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'From',
                                'content' => $this->composeEmailName( $this->From ) );
        }
        if ( $this->ReplyTo !== false and
             !in_array( 'reply-to', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'Reply-To',
                                'content' => $this->composeEmailName( $this->ReplyTo ) );
        }
        if ( $this->Subject !== false and
             !in_array( 'subject', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'Subject',
                                'content' => $this->Subject );
        }
        $extraHeaders = $this->ExtraHeaders;
        foreach ( $extraHeaders as $extraHeader )
        {
            if ( !in_array( strtolower( $extraHeader['name'] ), $excludeHeaders ) )
                $headers[] = $extraHeader;
        }
        return $headers;
    }

    /*!
     Extracts all headers and generates a text string out of it.
     The parameter \a $parameters will be passed to the headers() function.
    */
    function headerTextList( $parameters = array() )
    {
        $textElements = array();
        $headers = $this->headers( $parameters );
        foreach ( $headers as $header )
        {
            $textElements[] = $this->blankNewlines( $header['name'] ) . ': ' . $this->blankNewlines( $header['content'] );
        }
        return $textElements;
    }

    /*!
     Composes a text field out of all the headers and returns it.
     The parameter \a $parameters will be passed to the headers() function.
    */
    function headerText( $parameters = array() )
    {
        $text = '';
        $headers = $this->headers( $parameters );
        foreach ( $headers as $header )
        {
            $text .= $this->blankNewlines( $header['name'] ) . ': ' . $this->blankNewlines( $header['content'] ) . EZ_MAIL_LINE_SEPARATOR;
        }
        return $text;
    }

/*
    function send()
    {
        if ( $this->FilesAttached == true )
        {
            $files = $this->files();
            if( count( $files ) )
            {
                foreach ( $files as $file )
                {
                    echo "Added attachment";
                    $filename = "ezfilemanager/files/" . $file->fileName();
                    $attachment = fread( eZFile::fopen( $filename, "r" ), eZFile::filesize( $filename ) );
                    $this->add_attachment( $attachment, $file->originalFileName(), "image/jpeg" );
                }
            }
        }

        $mime = "";
        if ( !empty( $this->From ) )
        {
            $mime .= "From: "  . $this->From . "\n";
        }
        if ( !empty( $this->CcElements ) )
            $mime .= "Cc: " . $this->CcElements . "\n";
        if ( !empty( $this->BccElements ) )
            $mime .= "Bcc: " . $this->BccElements . "\n";
        if ( !empty( $this->ReplyTo ) )
            $mime .= "Reply-To: " . $this->ReplyTo . "\n";
        if ( !empty( $this->BodyText ) )
        {
            $body = preg_replace( "/(?<![\r])\n(?![\r])/", "\r\n", $this->BodyText );
            $this->add_attachment( $body, "", "text/plain");
        }

        $mime .= "MIME-Version: 1.0\n" . $this->build_multipart();
        mail( $this->ReceiverElements, $this->Subject, "", $mime );
        $this->parts = array();
    }

    function add_attachment( $message, $name = "", $ctype = "application/octet-stream" )
    {
        $this->parts[] = array (
            "ctype" => $ctype,
            "message" => $message,
            "encode" => $encode,
            "name" => $name
            );
    }

    function build_message( $part )
    {
        $message = $part["message"];
        $message = chunk_split( base64_encode( $message ) );
        $encoding = "base64";
    //EP - different charsets for the MIME mail ---------------
//        global $GlobalSectionID;
//
//        include_once("ezsitemanager/classes/ezsection.php");
//        $sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
//        $Locale = new eZLocale( $sectionObject->language() );
//        $iso =& $Locale->languageISO();

//        $subj = $this->subject();
//        $subj = "=?$iso?B?" . trim( chunk_split( base64_encode( $subj ))) . "?=";
//        $this->setSubject ( $subj );

//        return "Content-Type: " . $part["ctype"] . ";\n\tcharset=\"$iso\"" .
//            ( $part["name"] ? "; name = \"" . $part["name"] . "\"" : "" ) .
//            "\nContent-Transfer-Encoding: $encoding\n\n$message\n";
    //EP    ---------------------------------------------------
        return "Content-Type: " . $part["ctype"] .
            ( $part["name"] ? "; name = \"" . $part["name"] . "\"" : "" ) .
            "\nContent-Transfer-Encoding: $encoding\n\n$message\n";
    }

    function build_multipart()
    {
        $boundary = "b" . md5( uniqid( time() ) );
        $multipart = "Content-Type: multipart/mixed;\n   boundary=$boundary\n\nThis is a MIME encoded message.\n\n--$boundary";
        for ( $i = count( $this->parts ) - 1; $i >= 0; $i-- )
        {
            $multipart .= "\n" . $this->build_message( $this->parts[$i] ) . "--$boundary";
        }
        return $multipart .= "--\n";
    }
*/


    /// \privatesection
    var $ReceiverElements;
    var $From;
    var $CcElements;
    var $BccElements;
    var $ReplyTo;
    var $Subject;
    var $BodyText;
    var $ExtraHeaders;
}

?>
