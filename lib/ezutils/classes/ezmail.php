<?php
//
// $Id: ezmail.php,v 1.44.2.7 2002/06/10 16:41:45 fh Exp $
//
// Definition of eZMail class
//
// Created on: <15-Mar-2001 20:40:06 fh>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

  This class will be deprecated in the next eZ Publish release, and replaced with ezcMail from eZ Components.
 
  The eZMail class was used like this (with old smtp class which will be removed):
    $mail = new eZMail();
    $mail->setSender( $fromEmail, $yourName );
    $mail->setReceiver( $receiversEmail, $receiversName );
    $mail->setSubject( $subject );
 
    $smtp = new smtp( $parameters );
    $smtpConnected = $smtp->connect();
    if ( $smtpConnected )
    {
        $result = $smtp->send( $sendData );
    }
    
  Since the smtp class will be removed, ezcMailSmtpTransport from eZ
  Components can be used temporarily instead (the class eZSMTPTransport
  is using ezcMailSmtpTransport instead of smtp as well):
  
    $smtp = new ezcMailSmtpTransport( $host, $username, $password, $port );
    $smtp->send( $mail->Mail );

  Instead of the code above, ezcMail will be used together with the SMTP
  transport from eZ Components (MTA transport will work as well):

    $mail = new ezcMail();
    $mail->from = new ezcMailAddress( $fromEmail, $yourName );
    $mail->addTo( new ezcMailAddress( $receiversEmail, $receiversName ) );
    $mail->subject = $subject;
    
    $smtp = new ezcMailSmtpTransport( $host, $username, $password, $port );
    $smtp->send( $mail );
*/

class eZMail
{
    const REGEXP = '(((\"[^\"\f\n\r\t\v\b]+\")|([A-Za-z0-9_\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[A-Za-z0-9_\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]{2,})))';

    /*!
      Constructs a new eZMail object.
    */
    function eZMail()
    {
        $this->Mail = new ezcMail();

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
        $version = eZPublishSDK::version();

        $this->MIMEVersion = '1.0';
        $this->ContentType = array( 'type' => 'text/plain',
                                    'charset' => eZTextCodec::internalCharset(),
                                    'transfer-encoding' => '8bit',
                                    'disposition' => 'inline',
                                    'boundary' => false );
        $this->UserAgent = "eZ Publish, Version $version";

        $ini = eZINI::instance();

        if ( $ini->hasVariable( 'MailSettings', 'ContentType' ) )
            $this->setContentType( $ini->variable( 'MailSettings', 'ContentType' ) );
    }

    /*!
      Returns the receiver addresses as text with only the email address.

      \deprecated
    */
    function receiverEmailText( $convert = true )
    {
/*
        $addresses = array();
        foreach ( $this->Mail->to as $address )
        {
            $addresses[] = $address->email;
        }
        return implode( ', ', $addresses );
*/
        return $this->composeEmailItems( $this->ReceiverElements, true, 'email', $convert );
    }

    /*!
      Returns the receiver addresses as text.

      \deprecated
    */
    function receiverText( $convert = true )
    {
//        return ezcMailTools::composeEmailAddresses( $this->Mail->to );
        return $this->composeEmailItems( $this->ReceiverElements, true, false, $convert );
    }

    /*!
      Returns the receiver cc addresses as an array with texts.

      \deprecated
    */
    function ccReceiverTextList( $convert = true )
    {
/*
        $addresses = array();
        foreach ( $this->Mail->cc as $address )
        {
            $addresses[] = $address->email;
        }
        return implode( ', ', $addresses );
*/
        return $this->composeEmailItems( $this->CcElements, false, 'email', $convert );
    }

    /*!
      \deprecated
    */
    function bccReceiverTextList( $convert = true )
    {
/*
        $addresses = array();
        foreach ( $this->Mail->bcc as $address )
        {
            $addresses[] = $address->email;
        }
        return implode( ', ', $addresses );
*/
        return $this->composeEmailItems( $this->BccElements, false, 'email', $convert );
    }

    /*!
      Returns the receiver addresses as an array with texts.

      \deprecated
    */
    function receiverTextList( $convert = true )
    {
/*
        $addresses = array();
        foreach ( $this->Mail->to as $address )
        {
            $addresses[] = $address->email;
        }
        return implode( ', ', $addresses );
*/
        return $this->composeEmailItems( $this->ReceiverElements, false, 'email', $convert );
    }

    /*!
      Returns the receiver addresses.

      \deprecated
    */
    function receiverElements()
    {
/*
        $addresses = array();
        foreach ( $this->Mail->to as $address )
        {
            $addresses[] = array( 'name' => $address->name, 'email' => $address->email );
        }
        return $addresses;
*/
        return $this->ReceiverElements;
    }

    /*!
      Returns the addresses which should get a carbon copy.

      \deprecated
     */
    function ccElements()
    {
/*
        $addresses = array();
        foreach ( $this->Mail->cc as $address )
        {
            $addresses[] = array( 'name' => $address->name, 'email' => $address->email );
        }
        return $addresses;
*/
        return $this->CcElements;
    }

    /*!
      Returns the addresses which should get a blind carbon copy.

      \deprecated
     */
    function bccElements()
    {
/*
        $addresses = array();
        foreach ( $this->Mail->bcc as $address )
        {
            $addresses[] = array( 'name' => $address->name, 'email' => $address->email );
        }
        return $addresses;
*/
        return $this->BccElements;
    }


    /*!
      Returns the receiver address.

      \deprecated
    */
    function replyTo( $convert = true )
    {
//        return $this->Mail->getHeader( 'Reply-To' );
        if ( !$convert )
            return $this->ReplyTo;
        return $this->convertHeaderText( $this->ReplyTo );
    }

    /*!
      Returns the sender address.

      \deprecated
    */
    function sender( $convert = true )
    {
//        return $this->Mail->from;
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

      \deprecated
    */
    function senderText( $convert = true )
    {
//        return ezcMailTools::composeEmailAddress( $this->Mail->from );
        $text = eZMail::composeEmailName( $this->From );
        if ( !$convert )
            return $text;
        return $this->convertHeaderText( $text );
    }

    /*!
     \return the MIME version for this email, normally this is 1.0.
     \note The value is returned as a string.

      \deprecated
    */
    function mimeVersion()
    {
//        return $this->Mail->getHeader( 'MIME-Version' );
        return $this->MIMEVersion;
    }

    /*!
     \return the content type for this email, this is normally text/plain.

      \deprecated
    */
    function contentType()
    {
//        return $this->Mail->getHeader( 'Content-Type' );
        return $this->ContentType['type'];
    }

    /*!
     \return the charset for this email, this is normally taken from the internal charset.
     \sa usedCharset

      \deprecated
    */
    function contentCharset()
    {
        return $this->ContentType['charset'];
    }

    /*!
     \return the content transfer encoding, normally this is 8bit.

      \deprecated
    */
    function contentTransferEncoding()
    {
//        return $this->Mail->getHeader( 'Content-Transfer-Encoding' );
        return $this->ContentType['transfer-encoding'];
    }

    /*!
     \return the content disposition, normally this is inline.

      \deprecated
    */
    function contentDisposition()
    {
//        return $this->Mail->getHeader( 'Content-Disposition' );
//        return $this->Mail->contentDisposition->disposition;
        return $this->ContentType['disposition'];
    }

    /*!
     \return the user agent for this email, the user agent is automatically created if not specfied.

      \deprecated
    */
   function userAgent( $convert = true )
    {
//        return $this->Mail->getHeader( 'User-Agent' );
        if ( !$convert )
            return $this->UserAgent;
        return $this->convertHeaderText( $this->UserAgent );
    }

    /*!
     Sets the MIME version to \a $version.

      \deprecated
    */
    function setMIMEVersion( $version )
    {
        $this->Mail->setHeader( 'MIME-Version', $version );
        $this->MIMEVersion = $version;
    }

    /*!
     Sets the various content variables, any parameter which is set to something other than \c false
     will overwrite the old value.

      \deprecated
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

      \deprecated
    */
    function setUserAgent( $agent )
    {
        $this->Mail->setHeader( 'User-Agent', $agent );
        $this->UserAgent = $agent;
    }

    /*!
      Sets the receiver addresses.

      \deprecated
    */
    function setReceiverElements( $toElements )
    {
        $this->Mail->to = array();
        foreach ( $toElements as $address )
        {
            $name = isset( $address['name'] ) ? $address['name'] : false;
            $this->Mail->addTo( new ezcMailAddress( $address['email'], $name ) );
        }
        $this->ReceiverElements = $toElements;
    }

    /*!
      Sets the receiver address.
      \note This will remove all other receivers
      \sa addReceiver, setReceiverElements

      \deprecated
    */
    function setReceiver( $email, $name = false )
    {
        $this->Mail->to = array( new ezcMailAddress( $email, $name ) );
        $this->ReceiverElements = array( array( 'name' => $name,
                                                'email' => $email ) );
    }

    /*!
      Sets the receiver address, the email and name will be extracted from \a $text.
      \note This will remove all other receivers
      \sa addReceiver, setReceiverElements

      \deprecated
    */
    function setReceiverText( $text )
    {
        $this->extractEmail( $text, $email, $name );
        $this->Mail->to = array( new ezcMailAddress( $email, $name ) );
        $this->ReceiverElements = array( array( 'name' => $name,
                                                'email' => $email ) );
    }

    /*!
      Adds a new receiver address.

      \deprecated
    */
    function addReceiver( $email, $name = false )
    {
        $this->Mail->addTo( new ezcMailAddress( $email, $name ) );
        $this->ReceiverElements[] = array( 'name' => $name,
                                           'email' => $email );
    }

    /*!
      Sets the receiver address.

      \deprecated
    */
    function setReplyTo( $email, $name = false )
    {
        $this->Mail->setHeader( 'Reply-To', new ezcMailAddress( $email, $name ) );
        $this->ReplyTo = array( 'name' => $name,
                                'email' => $email );
    }

    /*!
      Sets the sender address.

      \deprecated
    */
    function setSender( $email, $name = false )
    {
        $this->Mail->from = new ezcMailAddress( $email, $name );
        $this->From = array( 'name' => $name,
                             'email' => $email );
    }

    /*!
      Sets the sender address, the email and name will be extracted from \a $text.

      \deprecated
    */
    function setSenderText( $text )
    {
        $this->extractEmail( $text, $email, $name );
        $this->Mail->from = new ezcMailAddress( $email, $name );
        $this->From = array( 'name' => $name,
                             'email' => $email );
    }

    /*!
      Sets the cc addresses.

      \deprecated
     */
    function setCcElements( $newCc )
    {
        $this->Mail->cc = array();
        foreach ( $newCc as $address )
        {
            $name = isset( $address['name'] ) ? $address['name'] : false;
            $this->Mail->addCc( new ezcMailAddress( $address['email'], $name ) );
        }
        $this->CcElements = $newCc;
    }

    /*!
      Adds a new Cc address.

      \deprecated
    */
    function addCc( $email, $name = false )
    {
        $this->Mail->addCc( new ezcMailAddress( $email, $name ) );
        $this->CcElements[] = array( 'name' => $name,
                                     'email' => $email );
    }

    /*!
      Sets the bcc addresses.

      \deprecated
     */
    function setBccElements( $newBcc )
    {
        $this->Mail->bcc = array();
        foreach ( $newBcc as $address )
        {
            $name = isset( $address['name'] ) ? $address['name'] : false;
            $this->Mail->addBcc( new ezcMailAddress( $address['email'], $name ) );
        }
        $this->BccElements = $newBcc;
    }

    /*!
      Adds a new Bcc address.

      \deprecated
    */
    function addBcc( $email, $name = false )
    {
        $this->Mail->addBcc( new ezcMailAddress( $email, $name ) );
        $this->BccElements[] = array( 'name' => $name,
                                      'email' => $email );
    }

    /*!
     Return the extra headers

      \deprecated
    */
    function extraHeaders()
    {
        return $this->ExtraHeaders;
    }

    /*!
     Adds the headers \a $headerName with header value \a $headerValue to the extra headers.

      \deprecated
    */
    function addExtraHeader( $headerName, $headerValue )
    {
        $this->Mail->setHeader( $headerName, $headerValue );
        return $this->ExtraHeaders[] = array( 'name' => $headerName,
                                              'content' => $headerValue );
    }

    /*!
     Similar to addExtraHeader() but will overwrite existing entries.

      \deprecated
    */
    function setExtraHeader( $headerName, $headerValue )
    {
        $this->Mail->setHeader( $headerName, $headerValue );
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

      \deprecated
    */
    function setExtraHeaders( $headers )
    {
        $this->Mail->setHeaders( $headers );
        return $this->ExtraHeaders = $headers;
    }

    /*!
      Returns the message ID format : <number\@serverID>
      Read in the RFC's if you want to know more about it..

      \deprecated
     */
    function messageID()
    {
//        return $this->Mail->messageId;
        return $this->MessageID;
    }

    /*!
      Sets the message ID. This is a server setting only so BE CAREFUL WITH THIS.

      \deprecated
    */
    function setMessageID( $newMessageID )
    {      
        $this->Mail->messageId = $newMessageID;
        $this->MessageID = $newMessageID;
    }

    /*!
      Returns the messageID that this message is a reply to.

      \deprecated
    */
    function references()
    {
        return $this->References;
    }

    /*!
      Sets the messageID that this message is a reply to.

      \deprecated
    */
    function setReferences( $newReference )
    {
        $this->References = $newReference;
    }

    /*!
      Returns the subject.

      \deprecated
    */
    function subject( $convert = true )
    {
//        return $this->Mail->subject;
        if ( !$convert )
            return $this->Subject;
        return $this->convertHeaderText( $this->Subject );
    }

    /*!
      Sets the subject of the mail.

      \deprecated
    */
    function setSubject( $newSubject )
    {
        $this->Mail->subject = trim( $newSubject );
        $this->Subject = trim( $newSubject );
    }

    /*!
      Returns the body.

      \deprecated
    */
    function body( $convert = true )
    {
//        return $this->Mail->body;
        if ( !$convert )
            return $this->BodyText;
        return $this->convertText( $this->BodyText );
    }

    /*!
      Sets the body.

      \deprecated
    */
    function setBody( $newBody )
    {
        // if it's an object (e.g. ezcMailText) then set it directly, otherwise
        // create a new ezcMailText object to contain the body text
        if ( $newBody instanceof ezcMailPart )
        {
            $this->Mail->body = $newBody;
        }
        else
        {
            $this->Mail->body = new ezcMailText( $newBody );
        }
        $newBody = preg_replace( "/\r\n|\r|\n/", eZMail::lineSeparator(), $newBody );
        $this->BodyText = $newBody;
    }

    /*!
      \static
      Splits a list of email addresses into an array where each entry is an email address.

      \deprecated
    */
    static function &splitList( $emails )
    {
        $emails = preg_split( "/[,;]/", $emails );
        return $emails;
    }

    /*!
      \static
      Static function for validating e-mail addresses.

      Returns true if successful, false if not.

      \deprecated
    */
    static function validate( $address )
    {
        return preg_match( '/^' . eZMail::REGEXP . '$/', $address );
    }

    /*!
      Extracts email addresses from $text.

      \deprecated
    */
    static function extractEmail( $text, &$email, &$name )
    {
        if ( preg_match( "/([^<]+)<" . eZMail::REGEXP . ">/", $text, $matches ) )
        {
            $email = $matches[2];
            $name = trim( $matches[1], '" ' );
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

      \deprecated
    */
    static function stripEmail( $address )
    {
        $res = preg_match( "/" . eZMail::REGEXP . "/", $address, $email );

        if ( $res )
            return $email[0];
        else
            return 0;
    }

    /*!
     \static
     \returns a text which does not contain newlines, newlines are converted to spaces.

      \deprecated
    */
    static function blankNewlines( $text )
    {
        return preg_replace( "/\r\n|\r|\n/", ' ', $text );
    }

    /*!
     \static
     \returns the header content as a simple string, will deflate arrays.
     \sa blankNewLines

      \deprecated
    */
    static function contentString( $content )
    {
        if ( is_array( $content ) )
            return implode( '; ', $content );
        else
            return (string)$content;
    }

    /*!
     Composes a text out of the email and name and returns it.

     Example: John Doe <john@doe.com> or just john@doe.com

      \deprecated
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
     Composes an email text out of all items in \a $items and returns it.
     All items are comma separated.

      \deprecated
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

      \deprecated
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

      \deprecated
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

      \deprecated
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
                $text .= eZMail::lineSeparator();
            $text .= $this->blankNewlines( $header['name'] ) . ': ';
            $contentText = $this->blankNewlines( $this->contentString( $header['content'] ) );
            $text .= $contentText;
        }
        return $text;
    }

    /*!
     Calls convertText with \a $isHeader set to \c true.

      \deprecated
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
      Encodes $str using mb_encode_mimeheader() if it is available, or does base64 encoding of a header if not.

      \deprecated
     */
    function encodeMimeHeader( $str )
    {
        if ( !$this->TextCodec )
        {
             $this->TextCodec = eZTextCodec::instance( $this->contentCharset(), $this->outputCharset() );
        }

        if ( function_exists( "mb_encode_mimeheader" ) )
        {
            $encoded = mb_encode_mimeheader( $str, $this->TextCodec->InputCharsetCode, "B", eZMail::lineSeparator() );
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

            $encoded = trim( str_replace( "\n", eZMail::lineSeparator(), $encoded ) );
        }

        return $encoded;
    }


    /*!
     Converts the text \a $text to a suitable output format.
     \note Header conversion is not supported yet, for now it will only return original text when \a $isHeader is set to \c true.

      \deprecated
    */
    function convertText( $text, $isHeader = false )
    {

        $charset = $this->contentCharset();
        if ( $this->isAllowedCharset( $charset ) )
            return $text;
        $outputCharset = $this->outputCharset();
        if ( !$this->TextCodec )
        {
            $this->TextCodec = eZTextCodec::instance( $charset, $outputCharset );
        }
        $newText = $this->TextCodec->convertString( $text );
        return $newText;
    }

    /*!
     \return \c true if the charset \a $charset is allowed as output charset.
     \sa allowedCharsets.

      \deprecated
    */
    function isAllowedCharset( $charset )
    {
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

      \deprecated
    */
    function allowedCharsets()
    {
        $ini = eZINI::instance();
        $charsets = $ini->variable( 'MailSettings', 'AllowedCharsets' );
        return $charsets;
    }

    /*!
     \return the charset which will be used for output.

      \deprecated
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

      \deprecated
    */
    function outputCharset()
    {
        $ini = eZINI::instance();
        $outputCharset = $ini->variable( 'MailSettings', 'OutputCharset' );
        return $outputCharset;
    }

    /*!
      Returns the line ending.
      
      \deprecated
    */
    static function lineSeparator()
    {
        $ini = eZINI::instance( 'site.ini' );
        $ending = $ini->variable( 'MailSettings', 'HeaderLineEnding' );
        if ( $ending == 'auto' )
        {
            $sys = eZSys::instance();
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

        return $separator;
    }

/*
//        $subj = "=?$iso?B?" . trim( chunk_split( base64_encode( $subj ))) . "?=";
*/

    /// \privatesection
    public $ReceiverElements;
    public $From;
    public $CcElements;
    public $BccElements;
    public $ContentType;
    public $UserAgent;
    public $ReplyTo;
    public $Subject;
    public $BodyText;
    public $ExtraHeaders;
    public $TextCodec;
    public $MessageID;
    public $MIMEVersion;
    
    /**
     * Contains an object of type ezcMail, which is used to store the
     * mail elements like subject, to, from, body etc, instead of using
     * the existing class variables ($Subject, $From, $receiversElements,
     * $BodyText etc).
     *
     * @var ezcMail
     */
    public $Mail;
}

?>