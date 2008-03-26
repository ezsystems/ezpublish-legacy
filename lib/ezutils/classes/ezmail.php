<?php
//
// $Id: ezmail.php,v 1.44.2.7 2002/06/10 16:41:45 fh Exp $
//
// Definition of eZMail class
//
// Created on: <15-Mar-2001 20:40:06 fh>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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

/*! \defgroup eZUtils Utility classes */

/*!
  \class eZMail ezmail.php
  \ingroup eZUtils
  \brief Mail handler

  Class for storing the details about en email and providing
  text serialization.

 \note It's important to note that most methods that return values do an automatic conversion if not specified.

*/

include_once( 'lib/ezi18n/classes/eztextcodec.php' );
include_once( 'lib/ezutils/classes/ezini.php' );

define( 'EZ_MAIL_REGEXP', '([0-9a-zA-Z]([-+.\w]*[0-9a-zA-Z_])*@(((([0-9a-zA-Z])+([-\w]*[0-9a-zA-Z])*\.)+[a-zA-Z]{2,9})|(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)))' );

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
        $this->TextCodec = false;
        $this->MessageID = false;

        // Sets some default values
        include_once( 'lib/version.php' );
        $version = eZPublishSDK::version();

        $this->MIMEVersion = '1.0';
        $this->ContentType = array( 'type' => 'text/plain',
                                    'charset' => eZTextCodec::internalCharset(),
                                    'transfer-encoding' => '8bit',
                                    'disposition' => 'inline',
                                    'boundary' => false );
        $this->UserAgent = "eZ publish, Version $version";

        $ini =& eZINI::instance();

        if ( $ini->hasVariable( 'MailSettings', 'ContentType' ) )
            $this->setContentType( $ini->variable( 'MailSettings', 'ContentType' ) );

        if (! defined( 'EZ_MAIL_LINE_SEPARATOR' ) )
        {
            $ini =& eZINI::instance( 'site.ini' );
            $ending = $ini->variable( 'MailSettings', 'HeaderLineEnding' );
            if ( $ending == 'auto' )
            {
                $sys =& eZSys::instance();
                // For windows we use \r\n which is the endline defined in RFC 2045
                if ( $sys->osType() == 'win32' )
                {
                    $separator = "\r\n";
                }
                else // for linux/unix/mac we use \n only due to some mail transfer agents destroying
                     // emails containing \r\n
                {
                    $separator = "\n";
                }
            }
            else
            {
                $separator = urldecode( $ending );
            }
            define( 'EZ_MAIL_LINE_SEPARATOR', $separator );
        }
    }

    /*!
      Returns the receiver addresses as text with only the email address.
    */
    function receiverEmailText( $convert = true )
    {
        return $this->composeEmailItems( $this->ReceiverElements, true, 'email', $convert );
    }

    /*!
      Returns the receiver addresses as text.
    */
    function receiverText( $convert = true )
    {
        return $this->composeEmailItems( $this->ReceiverElements, true, false, $convert );
    }

    /*!
      Returns the receiver cc addresses as an array with texts.
    */
    function ccReceiverTextList( $convert = true )
    {
        return $this->composeEmailItems( $this->CcElements, false, 'email', $convert );
    }

    function bccReceiverTextList( $convert = true )
    {
        return $this->composeEmailItems( $this->BccElements, false, 'email', $convert );
    }

    /*!
      Returns the receiver addresses as an array with texts.
    */
    function receiverTextList( $convert = true )
    {
        return $this->composeEmailItems( $this->ReceiverElements, false, 'email', $convert );
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
    function replyTo( $convert = true )
    {
        if ( !$convert )
            return $this->ReplyTo;
        return $this->convertHeaderText( $this->ReplyTo );
    }

    /*!
      Returns the sender address.
    */
    function sender( $convert = true )
    {
        if ( !$convert )
            return $this->From;

        if ( is_array( $this->From ) )
        {
            $convertedSender = $this->From;
            if ( $this->From['name'] )
            {
                $convertedSender['name'] = $this->convertHeaderText( $this->From['name'] );
            }
            return $convertedSender;
        }
        else if ( is_string( $this->From ) )
        {
            return $this->convertHeaderText( $this->From );
        }

        return $this->From;
    }

    /*!
      Returns the sender address as text.
    */
    function senderText( $convert = true )
    {
        $text = eZMail::composeEmailName( $this->From );
        if ( !$convert )
            return $text;
        return $this->convertHeaderText( $text );
    }

    /*!
     \return the MIME version for this email, normally this is 1.0.
     \note The value is returned as a string.
    */
    function mimeVersion()
    {
        return $this->MIMEVersion;
    }

    /*!
     \return the content type for this email, this is normally text/plain.
    */
    function contentType()
    {
        return $this->ContentType['type'];
    }

    /*!
     \return the charset for this email, this is normally taken from the internal charset.
     \sa usedCharset
    */
    function contentCharset()
    {
        return $this->ContentType['charset'];
    }

    /*!
     \return the content transfer encoding, normally this is 8bit.
    */
    function contentTransferEncoding()
    {
        return $this->ContentType['transfer-encoding'];
    }

    /*!
     \return the content disposition, normally this is inline.
    */
    function contentDisposition()
    {
        return $this->ContentType['disposition'];
    }

    /*!
     \return the user agent for this email, the user agent is automatically created if not specfied.
    */
   function userAgent( $convert = true )
    {
        if ( !$convert )
            return $this->UserAgent;
        return $this->convertHeaderText( $this->UserAgent );
    }

    /*!
     Sets the MIME version to \a $version.
    */
    function setMIMEVersion( $version )
    {
        $this->MIMEVersion = $version;
    }

    /*!
     Sets the various content variables, any parameter which is set to something other than \c false
     will overwrite the old value.
    */
    function setContentType( $type = false, $charset = false,
                             $transferEncoding = false, $disposition = false, $boundary = false )
    {
        if ( $type )
            $this->ContentType['type'] = $type;
        if ( $charset )
            $this->ContentType['charset'] = $charset;
        if ( $transferEncoding )
            $this->ContentType['transfer-encoding'] = $transferEncoding;
        if ( $disposition )
            $this->ContentType['disposition'] = $disposition;
        if ( $boundary )
            $this->ContentType['boundary'] = $boundary;
    }

    /*!
     Sets the user agent for the email to \a $agent.
    */
    function setUserAgent( $agent )
    {
        $this->UserAgent = $agent;
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
     Return the extra headers
    */
    function extraHeaders()
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
     Similar to addExtraHeader() but will overwrite existing entries.
    */
    function setExtraHeader( $headerName, $headerValue )
    {
        for ( $i = 0; $i < count( $this->ExtraHeaders ); ++$i )
        {
            $extraHeader =& $this->ExtraHeaders[$i];
            if ( isset( $extraHeader['name'] ) and
                 $extraHeader['name'] == $headerName )
            {
                $extraHeader = array( 'name' => $headerName,
                                      'content' => $headerValue );
                return true;
            }
        }
        $this->addExtraHeader( $headerName, $headerValue );
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
      Sets the message ID. This is a server setting only so BE CAREFUL WITH THIS.
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
    function subject( $convert = true )
    {
        if ( !$convert )
            return $this->Subject;
        return $this->convertHeaderText( $this->Subject );
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
    function body( $convert = true )
    {
        if ( !$convert )
            return $this->BodyText;
        return $this->convertText( $this->BodyText );
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
        $emails = preg_split( "/[,;]/", $emails );
        return $emails;
    }

    /*!
      \static
      Static function for validating e-mail addresses.

      Returns true if successful, false if not.
    */
    function validate( $address )
    {
        $pos = ( ereg( '^' . EZ_MAIL_REGEXP . '$', $address) );
        return $pos;
    }

    function extractEmail( $text, &$email, &$name )
    {
        if ( preg_match( "/([^<]+)<" . EZ_MAIL_REGEXP . ">/", $text, $matches ) )
        {
            $email = $matches[2];
            $name = $matches[1];
        }
        else
        {
            $email = $text;
            $name = false;
        }
    }

    /*!
      \static
      Static function for extracting an e-mail from text

      Returns the first valid e-mail in address, returns false if no e-mail addresses found
    */
    function stripEmail( $address )
    {
        $res = ereg( EZ_MAIL_REGEXP, $address, $email );
        if ( $res )
            return $email[0];
        else
            return 0;
    }

    /*!
     \static
     \returns a text which does not contain newlines, newlines are converted to spaces.
    */
    function blankNewlines( $text )
    {
        return preg_replace( "/\r\n|\r|\n/", ' ', $text );
    }

    /*!
     \static
     \returns the header content as a simple string, will deflate arrays.
     \sa blankNewLines
    */
    function contentString( $content )
    {
        if ( is_array( $content ) )
            return implode( '; ', $content );
        else
            return (string)$content;
    }

    /*!
     \static
     Composes a text out of the email and name and returns it.

     Example: John Doe <john@doe.com> or just john@doe.com
    */
    function composeEmailName( $item, $key = false, $convert = true )
    {
        if ( $key !== false and
             isset( $item[$key] ) )
            return $item[$key];
        if ( $item['name'] )
        {
            if ( $convert )
                $item['name'] = $this->convertHeaderText( $item['name'] );
            $text = $item['name'] . ' <' . $item['email'] . '>';
        }
        else
            $text = $item['email'];
        return $text;
    }

    /*!
     \static
     Composes an email text out of all items in \a $items and returns it.
     All items are comma separated.
    */
    function composeEmailItems( $items, $join = true, $key = false, $convert = true )
    {
        $textElements = array();
        foreach ( $items as $item )
        {
            $textElements[] = eZMail::composeEmailName( $item, $key, $convert );
        }

        if ( $join )
            $text = implode( ', ', $textElements );
        else
            $text = $textElements;

        return $text;
    }

    /*!
     \return an array with headers, each header item is an associative array with the keys \c name and \c content.
             \c content will either be a string or an array with strings.

     The parameter \a $parameters contains optional parameters, they can be:
     - exclude-headers - \c Array of header names which will not be included in the result array.
     \sa contentString, blankNewLines
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
        $headerNames = array();
        if ( !in_array( 'to', $excludeHeaders ) )
        {
            $toHeaderContent = count( $this->ReceiverElements ) > 0 ? $this->composeEmailItems( $this->ReceiverElements ) : 'undisclosed-recipients:;';
            $headers[] = array( 'name' => 'To',
                                'content' => $toHeaderContent );
            $headerNames[] = 'to';
        }
        if ( !in_array( 'date', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'Date',
                                'content' => date( 'r' ) );
            $headerNames[] = 'date';
        }
        if ( $this->Subject !== false and
             !in_array( 'subject', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'Subject',
                                'content' => $this->subject() );
            $headerNames[] = 'subject';
        }
        if ( $this->From !== false and
             !in_array( 'from', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'From',
                                'content' => $this->composeEmailName( $this->From ) );
            $headerNames[] = 'from';
        }
        if ( count( $this->CcElements ) > 0 and
             !in_array( 'cc', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'Cc',
                                'content' => $this->composeEmailItems( $this->CcElements ) );
            $headerNames[] = 'cc';
        }
        if ( count( $this->BccElements ) > 0 and
             !in_array( 'bcc', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'Bcc',
                                'content' => $this->composeEmailItems( $this->BccElements ) );
            $headerNames[] = 'bcc';
        }
        if ( $this->ReplyTo !== false and
             !in_array( 'reply-to', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'Reply-To',
                                'content' => $this->composeEmailName( $this->ReplyTo ) );
            $headerNames[] = 'reply-to';
        }
        if ( !in_array( 'mime-version', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'MIME-Version',
                                'content' => $this->MIMEVersion );
            $headerNames[] = 'mime-version';
        }
        if ( !in_array( 'content-type', $excludeHeaders ) )
        {
            $charset = $this->usedCharset();
            if ( $this->ContentType['boundary'] )
            {
            $headers[] = array( 'name' => 'Content-Type',
                                'content' => array( $this->ContentType['type'], 'charset='. $charset, 'boundary="'. $this->ContentType['boundary'] . '"' ) );
            }
            else
            {
                $headers[] = array( 'name' => 'Content-Type',
                                    'content' => array( $this->ContentType['type'], 'charset='. $charset ) );
            }
            $headerNames[] = 'content-type';
        }
        if ( !in_array( 'content-transfer-encoding', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'Content-Transfer-Encoding',
                                'content' => $this->ContentType['transfer-encoding'] );
            $headerNames[] = 'content-transfer-encoding';
        }
        if ( !in_array( 'content-disposition', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'Content-Disposition',
                                'content' => $this->ContentType['disposition'] );
            $headerNames[] = 'content-disposition';
        }
        if ( !in_array( 'user-agent', $excludeHeaders ) )
        {
            $headers[] = array( 'name' => 'User-Agent',
                                'content' => $this->UserAgent );
            $headerNames[] = 'user-agent';
        }
        if ( !in_array( 'message-id', $excludeHeaders ) )
        {
            if ( $this->MessageID )
            {
                $headers[] = array( 'name' => 'Message-Id',
                                    'content' => $this->MessageID );
                $headerNames[] = 'message-id';
            }
        }

        $extraHeaders = $this->ExtraHeaders;
        foreach ( $extraHeaders as $extraHeader )
        {
            if ( !in_array( strtolower( $extraHeader['name'] ), $excludeHeaders ) and
                 !in_array( strtolower( $extraHeader['name'] ), $headerNames ) )
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
        $convert = true;
        if ( isset( $parameters['convert'] ) )
            $convert = $parameters['convert'];
        $textElements = array();
        $headers = $this->headers( $parameters );
        foreach ( $headers as $header )
        {
            $headerText = $this->blankNewlines( $header['name'] ) . ': ';
            $contentText = $this->blankNewlines( $this->contentString( $header['content'] ) );
            $headerText .= $contentText;
            $textElements[] = $headerText;
        }
        return $textElements;
    }

    /*!
     Composes a text field out of all the headers and returns it.
     The parameter \a $parameters will be passed to the headers() function.
    */
    function headerText( $parameters = array() )
    {
        $convert = true;
        if ( isset( $parameters['convert'] ) )
            $convert = $parameters['convert'];
        $text = '';
        $headers = $this->headers( $parameters );
        $headerCount = 0;
        foreach ( $headers as $header )
        {
            if ( $headerCount++ > 0 )
                $text .= EZ_MAIL_LINE_SEPARATOR;
            $text .= $this->blankNewlines( $header['name'] ) . ': ';
            $contentText = $this->blankNewlines( $this->contentString( $header['content'] ) );
            $text .= $contentText;
        }
        return $text;
    }

    /*!
     Calls convertText with \a $isHeader set to \c true.
    */
    function convertHeaderText( $text )
    {
        $charset = $this->contentCharset();
        if ( $charset != 'us-ascii' )
        {
            $newText = $this->encodeMimeHeader( $text );
            return $newText;
        }
        return $text;
    }

    /*!
      Encodes $str using mb_encode_mimeheader() if it is aviable, or does base64 encodin of a header if not.
     */
    function encodeMimeHeader( $str )
    {
        if ( !$this->TextCodec )
        {
             $this->TextCodec =& eZTextCodec::instance( $this->contentCharset(), $this->outputCharset() );
        }

        if ( function_exists( "mb_encode_mimeheader" ) )
        {
            $encoded = mb_encode_mimeheader( $str, $this->TextCodec->InputCharsetCode, "B", EZ_MAIL_LINE_SEPARATOR );
        }
        else
        {
            if (  0 == preg_match_all( '/[\000-\010\013\014\016-\037\177-\377]/', $str, $matches ) )
                return $str;

            $maxlen = 75 - 7 - strlen( $this->TextCodec->InputCharsetCode );

            $encoding = 'B';
            $encoded = base64_encode( $str );
            $maxlen -= $maxlen % 4;
            $encoded = trim( chunk_split( $encoded, $maxlen, "\n" ) );

            $encoded = preg_replace( '/^(.*)$/m', " =?".$this->TextCodec->InputCharsetCode."?$encoding?\\1?=", $encoded );

            $encoded = trim( str_replace( "\n", EZ_MAIL_LINE_SEPARATOR, $encoded ) );
        }

        return $encoded;
    }


    /*!
     Converts the text \a $text to a suitable output format.
     \note Header conversion is not supported yet, for now it will only return original text when \a $isHeader is set to \c true.
    */
    function convertText( $text, $isHeader = false )
    {

        $charset = $this->contentCharset();
        if ( $this->isAllowedCharset( $charset ) )
            return $text;
        $outputCharset = $this->outputCharset();
        if ( !$this->TextCodec )
        {
            $this->TextCodec =& eZTextCodec::instance( $charset, $outputCharset );
        }
        $newText = $this->TextCodec->convertString( $text );
        return $newText;
    }

    /*!
     \return \c true if the charset \a $charset is allowed as output charset.
     \sa allowedCharsets.
    */
    function isAllowedCharset( $charset )
    {
        include_once( 'lib/ezi18n/classes/ezcharsetinfo.php' );
        $realCharset = eZCharsetInfo::realCharsetCode( $charset );
        $charsets = $this->allowedCharsets();
        foreach ( $charsets as $charsetName )
        {
            $realName = eZCharsetInfo::realCharsetCode( $charsetName );
            if ( $realName == $realCharset )
                return true;
        }
        return false;
    }

    /*!
     \return an array with charsets that can be used directly as output charsets.
    */
    function allowedCharsets()
    {
        $ini =& eZINI::instance();
        $charsets = $ini->variable( 'MailSettings', 'AllowedCharsets' );
        return $charsets;
    }

    /*!
     \return the charset which will be used for output.
    */
    function usedCharset()
    {
        $charset = $this->contentCharset();
        if ( $this->isAllowedCharset( $charset ) )
            return $charset;
        return $this->outputCharset();
    }

    /*!
     \return the default output charset.
    */
    function outputCharset()
    {
        $ini =& eZINI::instance();
        $outputCharset = $ini->variable( 'MailSettings', 'OutputCharset' );
        return $outputCharset;
    }

/*
//        $subj = "=?$iso?B?" . trim( chunk_split( base64_encode( $subj ))) . "?=";
*/

    /// \privatesection
    var $ReceiverElements;
    var $From;
    var $CcElements;
    var $BccElements;
    var $ContentType;
    var $UserAgent;
    var $ReplyTo;
    var $Subject;
    var $BodyText;
    var $ExtraHeaders;
    var $TextCodec;
    var $MessageID;
}

?>
