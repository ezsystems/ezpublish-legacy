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
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

//!! eZCommon
//! eZMail
/*!

  Functions that are used when sending mail have ideas from:
    Sascha Schumann <sascha@schumann.cx>
    Tobias Ratschiller <tobias@dnet.it>
  extended and modified to fit eZ publish needs by
     Frederik Holljen <fh@ez.no>
  Example code:
  \code

  \endcode
*/
/* DEFINES */
define( "UNREAD", 0 );
define( "READ", 1 );
define( "REPLIED", 2 );
define( "FORWARDED", 3 );
define( "MAIL_SENT", 4 );

class eZMail
{
    /*!
      Constructs a new eZMail object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZMail( $id = "", $params = array() )
    {
        // See if we should use SMTP
        if ( isset( $params["hostname"] ) )
        {
            $this->Hostname = $params["hostname"];
            if ( isset( $params["port"] ) )
                $this->Port = $params["port"];
            else
                $this->Port = 25;
            if ( isset( $params["user"] ) )
                $this->User = $params["user"];
            if ( isset( $params["password"] ) )
                $this->Password = $params["password"];
        }

        
        $this->FilesAttached = false;

        // array used when sending mail.. do not alter!!!
        $this->parts = array();
        if ( $id != "" )
        {

            $this->ID = $id;
            $this->get( $this->ID );
        }
        else
        {
            // default values
            $this->IsPublished = 0;
            $this->UDate = time();
        }
    }

    /*!
      Deletes a eZMail object from the database.
    */
    function delete( $id = -1 )
    {
        return true;
    }

    /*!
      Stores a mail to the database.
    */
    function store()
    {
        return true;
    }

    function removeContacts( $mailID )
    {

    }

    function addContact( $mailID, $contactID, $companyEdit = true )
    {

    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id = "" )
    {
        $ret = false;

        return $ret;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the receiver address.
    */
    function to()
    {
        return $this->To;
    }

    /*!
      Sets the receiver address.
    */
    function setTo( $newTo )
    {
        $this->To = $newTo;
    }


    /*!
      Returns the receiver address.
    */
    function replyTo()
    {
        return $this->ReplyTo;
    }

    /*!
      Sets the receiver address.
    */
    function setReplyTo( $newReplyTo )
    {
        $this->ReplyTo = $newReplyTo;
    }

    /*!
      Returns the receiver address. Wrapper function
    */
    function receiver()
    {
        return $this->To;
    }

    /*!
      Sets the receiver address.  Wrapper function
    */
    function setReceiver( $newReceiver )
    {
        $this->To = $newReceiver;
    }

    /*!
      Returns the from address.
    */
    function from()
    {
        return $this->From;
    }


    /*!
      Sets the from address.
    */
    function setFrom( $newFrom )
    {
        $this->From = $newFrom;
    }

    /*!
      Returns a string containing all cc adresses.
     */
    function cc()
    {
        return $this->Cc;
    }

    /*!
      Sets the cc addresses. Use , separating (; and : and " " should also work )
     */
    function setCc( $newCc )
    {
        $this->Cc = $newCc;
    }

    /*!
      Returns a string containing all bcc adresses.
     */
    function bcc()
    {
        return $this->Bcc;
    }

    /*!
      Sets the bcc addresses. Use , separating (; and : and " " should also work )
     */
    function setBcc( $newBcc )
    {
        $this->Bcc = $newBcc;
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
      Returns the from name.
    */
    function fromName()
    {
        return $this->FromName;
    }

    /*!
      Sets the from name.
    */
    function setFromName( $newFrom )
    {
        $this->FromName = $newFrom;
    }

    /*!
      Returns the sender address.
    */
    function sender()
    {
        return $this->From;
    }

    /*!
      Sets the sender address.
    */
    function setSender( $newSender )
    {
        $this->From = $newSender;
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
        $this->BodyText = $newBody;
    }

    /*!
      Sets the body.
    */
    function setBodyText( $newBody )
    {
        $this->BodyText = $newBody;
    }

    /*!
      Returns the userID of the user that owns this object
    */
    function owner()
    {
        return $this->UserID;;
    }

    /*!
      Sets the owner of this mail
    */
    function setOwner( $newOwner )
    {

        if ( get_class( $newOwner ) == "ezuser" )
            $this->UserID = $newOwner->id();
        else
            $this->UserID = $newOwner;
    }

    /*!
      Returns the size of this mail in bytes.
     */
    function size()
    {
        return $this->Size;
    }

    /*!
      Returns the size of this object in a human readable fasion.
      An array is returned with entries:
      "size" - original size
      "size-string" short size
      "unit" GB, MB, KB or B
     */
    function siSize()
    {

        $units = array( "GB" => 10737741824,
                        "MB" => 1048576,
                        "KB" => 1024,
                        "B" => 0 );
        $decimals = 0;
        $size = $this->Size;
        $shortsize = $this->Size;

        while ( list( $unit_key, $val ) = each( $units ) )
        {
            if ( $size >= $val )
            {
                $unit = $unit_key;
                if ( $val > 0 )
                {
                    $decimals = 2;
                    $shortsize = $size / $val;
                }
                break;
            }
        }
        $shortsize = number_format( ( $shortsize ), $decimals);
        $size = array( "size" => $size,
                       "size-string" => $shortsize,
                       "unit" => $unit );
        return $size;
    }

     /*!
      Returns the size of this mail in bytes.
     */
    function setSize( $value )
    {
        $this->Size = $value;
    }

    /*!
      Returns the date of this mail in unix date format.
     */
    function uDate()
    {
        return $this->UDate;
    }

    /*!
      Sets the date of this mail in unix date time format.
     */
    function setUDate( $value )
    {
        $this->UDate = $value;
    }

    /*
      Returns the status of this mail.
      0 - UNREAD
      1 - READ
      2 - REPLIED
      3 - FORWARDED
      4 - MAIL_SENT
    */
    function status()
    {
        return $this->Status;
    }

    /*!
      Sets the status of this mail.
     0 - UNREAD
     1 - READ
     2 - REPLIED
     3 - FORWARDED
     4 - MAIL_SENT
     If direct write is set the data will be written directly to the database. No need for calling store() afterwords. In order to do this you must be sure that the object
     is allready in the database.
     */
    function setStatus( $status, $directWrite = false )
    {
        $this->Status = $status;
        $db =& eZDB::globalDatabase();
        if ( $directWrite == true )
            $db->query( "UPDATE eZMail_Mail SET Status='$status' where ID='$this->ID'" );
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
      Merges an array of email addresses into a list of email addresses.
    */
    function &mergeList( $emails )
    {
        if ( !is_array( $emails ) )
            return false;
        $emails =& implode( ",", $emails );
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
      \static
      Returns true if the mail with the given identification is allready downloaded for the given user.
     Note: this is the header ID we are talking about.
    */
    function isDownloaded( $mailident, $userID )
    {
        return true;
    }

    /*!
      Marks this mail in the downloaded database.
     */
    function markAsDownloaded()
    {
    }

     /*
      Returns the first folder that this mail is a member of.
     */
    function folder( $asObject = true )
    {
        return false;
    }

    /*!
      \static
      Returns all mail that belongs to this user as an array of eZMail objects.
     */
    function getByUser( $user = false, $onlyUnread = false )
    {
        if ( get_class( $user ) != "ezuser" )
            $user =& eZUser::currentUser();
    }

    /*!
      \static
      Returns all mail that is sendt to a contact
    */
    function getByContact( $ContactID, $CompanyEdit, $Offset, $Limit, $user = false )
    {

    }

    function getContacts( $mailID = false )
    {

    }

     /*
      Adds an attachment to this mail
      Recalculates the size of the mail.
     */
    function addFile( $file )
    {

    }

    /*!
      Deletes an attachment from the mail.
      Recalculates the size of the mail.
    */
    function deleteFile( $file )
    {
        if ( get_class( $file ) == "ezvirtualfile" )
        {
            $fileID = $file->id();
            $file->delete();
            $db =& eZDB::globalDatabase();
            $db->query( "DELETE FROM eZMail_MailAttachmentLink WHERE MailID='$this->ID' AND FileID='$fileID'" );
            $this->calculateSize();
        }
    }

    /*!
      Returns all attachments associatied with this mail.
    */
    function files()
    {

    }

    /*
      Adds an image attachment.
     */
    function addImage( $image )
    {

    }

    /*!
      Deletes an eZImage attachment from the mail.
     */
    function deleteImage( $image )
    {

    }

    /*!
      Returns all the images associated with this mail.
     */
    function images()
    {

    }

    /*!
      \static
      Returns true if the given account belongs to the given user.
     */
    function isOwner( $user, $mailID )
    {
        return false;
    }

    /*!
      Returns a new eZMail object with all fields set according to the parameter.
      Valid values are: "reply", "replyall", "forward". If no parameter is given
      it just returns a copy of the mail. If $attachments is set to true also the attachments are copied.
      NOTE: The returned mail is not member of any folders. Set a folder for this mail
      or it will be LOST.
     */
    function &copyMail( $copyType = "normal", $attachments = false )
    {
        $ini =& INIFile::globalINI();
        $copy = new eZMail();
        $copy->UserID = $this->UserID;

        if ( $copyType == "normal" || $copyType == "forward" )
        {
            if ( $copyType == "normal" )
            {
                $copy->To = $this->To;
                $copy->From = $this->From;
                $copy->FromName = $this->FromName;
                $copy->Cc = $this->Cc;
                $copy->Bcc = $this->Bcc;
                $copy->ReplyTo = $this->ReplyTo;
            }
            else
            {
                $copy->From = $this->To;
            }
            $copy->Subject = $this->Subject;
            $copy->BodyText = $this->BodyText;
            $copy->MessageID = $this->MessageID;
            $copy->References = $this->References;
            $attachments = $this->files();
            $copy->store();
            foreach ( $attachments as $attachment )
            {
                $copy->addFile( $attachment );
            }
        }
        else if ( $copyType == "reply" || $copyType == "replyall" )
        {
            $copy->To = $this->From;
            $copy->Subject = $ini->read_var( "eZMailMain", "ReplyPrefix" ) . $this->Subject;
            $copy->References = $this->MessageID;
            $copy->ReplyTo = $this->To;

            if ( $copyType == "replyall" )
                $copy->Cc = $this->Cc;

            $sentnsArray = explode( "\n", $this->BodyText );
            $resultArray = array();

            foreach ( $sentnsArray as $sentence )
                $resultArray[] = "> " . $sentence . "\n";

            $copy->BodyText = implode( "", $resultArray );
        }
        $copy->store();
        return $copy;
    }

    /*!
      Calculates the size of the mail and its attachments.
     */
    function calculateSize()
    {
        $size = strlen( $this->To );
        $size += strlen( $this->From );
        $size += strlen( $this->FromName );
        $size += strlen( $this->Cc );
        $size += strlen( $this->Bcc );
        $size += strlen( $this->References );
        $size += strlen( $this->ReplyTo );
        $size += strlen( $this->Subject );
        $size += strlen( $this->BodyText );

        $files = $this->files();
        foreach ( $files as $file )
            $size += $file->fileSize();

        $this->Size = $size;
    }

    /*!
      Sends the mail with the values specified.
     */
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
            if ( !empty( $this->FromName ) )
                $mime .= "From: " . $this->FromName . " <" . $this->From . ">\n";
            else
                $mime .= "From: "  . $this->From . "\n";
        }
        if ( !empty( $this->Cc ) )
            $mime .= "Cc: " . $this->Cc . "\n";
        if ( !empty( $this->Bcc ) )
            $mime .= "Bcc: " . $this->Bcc . "\n";
        if ( !empty( $this->ReplyTo ) )
            $mime .= "Reply-To: " . $this->ReplyTo . "\n";
        if ( !empty( $this->BodyText ) )
        {
            $body = preg_replace( "/(?<![\r])\n(?![\r])/", "\r\n", $this->BodyText );
            $this->add_attachment( $body, "", "text/plain");
        }

        $mime .= "MIME-Version: 1.0\n" . $this->build_multipart();
        if ( isset( $this->Hostname ) )
        {
            // Incomplete, quick hack... TODO: Do this properly
            require_once( "lib/ezsetup/classes/smtp.php" );
            $params['host'] = $this->Hostname;
            $params['port'] = 25;
            if ( isset( $this->User ) )
            {
                $params['auth'] = true;
                $params['user'] = $this->User;
                $params['pass'] = $this->Password;
            }

            $send["from"] = $this->From;
            $send["recipients"][] = $this->To;
            $send["headers"][] = "From: " . $this->FromName . " <" . $this->From . ">";
            $send["headers"][] = "To: " . $this->To;
            $send["headers"][] = "Subject: " . $this->Subject;
            $send["body"] = $this->BodyText;

            $email = smtp::connect( $params );
            $result = $email->send( $send );
            if ( isset( $email->errors[0] ) )
                eZDebug::writeError( "Error sending SMTP mail: " . $email->errors[0], "eZMail::send()" );
        }    
        else
            mail( $this->To, $this->Subject, "", $mime );
        $this->parts = array();
    }

      /*!
       \private

       void add_attachment(string message, [string name], [string ctype])
       Add an attachment to the mail object
     */
    function add_attachment( $message, $name = "", $ctype = "application/octet-stream" )
    {
        $this->parts[] = array (
            "ctype" => $ctype,
            "message" => $message,
            "encode" => $encode,
            "name" => $name
            );
    }

     /*!
      \private

      void build_message( array part )
      Build message parts of an multipart mail
    */
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

    /*!
      \private
      void build_multipart()
      Build a multipart mail
    */
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

    /*!
      \static

      returns every mail that is containing the search string
    */
    function search( $text, $user = -1 )
    {
        $db =& eZDB::globalDatabase();
        $return_array = array();
        if ( $user == -1 )
            $user =& eZUser::currentUser();
        $db->array_query( $id_array, "SELECT ID FROM eZMail_Mail WHERE
                                          (ToField LIKE '%$text%' OR
                                           FromField LIKE '%$text%' OR
                                           CC LIKE '%$text%' OR
                                           Subject LIKE '%$text%' OR
                                           BodyText LIKE '%$text%') AND
                                           UserID='" . $user->ID() . "'
                                          ORDER BY Subject" );
        foreach ( $id_array as $id )
        {
            $return_array[] =& new eZMail( $id[$db->fieldName( "ID" )] );
        }
        return $return_array;
    }




    /// this variable is only used during the buildup of a mail that is beeing sent. NEVER access directly!!!
    var $parts;

    /* Mail specific variables */
    var $To;
    /// email adress
    var $From;
    /// users name
    var $FromName;
    var $Cc;
    var $Bcc;
    /// used with the reference.
    var $MessageID;
    /// used to thread mail, originally from News
    var $References;
    var $ReplyTo;
    var $Subject;
    var $BodyText;

    var $Size;
    var $UDate;
    var $Status;

    /// SMTP settings
    var $Hostname;
    var $Port;
    var $User;
    var $Password;
    
    // variable to check if files are attached ( no need to use database if not)
    var $FilesAttached;
    /* database specific variables */
    var $ID;
    var $UserID;
}

?>
