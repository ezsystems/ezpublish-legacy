<?php
//
// Definition of eZSys class
//
// Created on: <01-Mar-2002 13:48:53 amos>
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
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZSys ezsys.php
  \ingroup eZUtils
  \brief Easy access to various system settings

  The system is checked to see whether a virtualhost-less setup is used
  and sets the appropriate variables which can be fetched with
  siteDir(), wwwDir() and indexFile().
  It also detects file and enviroment separators, fetch them with
  fileSeparator() and envSeparator().
  In addition userIndexFile(), adminIndexFile() and xmlrpcIndexFile()
  can be used to fetch the index file of the user page, admin page and
  xmlrpc interface.

  Example:
\code
// Run the init in the index file
eZSys::init( $SCRIPT_FILENAME, $PHP_SELF, $DOCUMENT_ROOT,
             $SCRIPT_NAME, $REQUEST_URI );
print( eZSys::indexFile() );
print( eZSys::wwwDir() );
$ini =& eZINI::instance();
eZSys::initIni( $ini );
\endcode
*/

class eZSys
{
    /*!
     Initializes the object with settings taken from the current script run.
    */
    function eZSys()
    {
        $this->Attributes = array( "wwwdir" => true,
                                   "sitedir" => true,
                                   "indexfile" => true,
                                   "mysqlSupport" => true,
                                   "postgresqlSupport" => true,
                                   "oracleSupport" => true,
                                   "magickQuotes" => true,
                                   "hostname" => true );
        // Determine OS specific settings
        if ( substr( php_uname(), 0, 7) == "Windows" )
        {
            $this->FileSeparator = "\\";
            $this->LineSeparator= "\r\n";
            $this->EnvSeparator = ";";
        }
        else
        {
            $this->FileSeparator = "/";
            $this->LineSeparator= "\n";
            $this->EnvSeparator = ":";
        }
    }

    /*!
     Returns the string which is used for file separators on the current OS (server).
     \static
    */
    function fileSeparator()
    {
        if ( !isset( $this ) or get_class( $this ) != "ezsys" )
            $this =& eZSys::instance();
        return $this->FileSeparator;
    }

    /*!
     Returns the string which is used for line separators on the current OS (server).
     \static
    */
    function lineSeparator()
    {
        if ( !isset( $this ) or get_class( $this ) != "ezsys" )
            $this =& eZSys::instance();
        return $this->LineSeparator;
    }

    /*!
     Returns the string which is used for enviroment separators on the current OS (server).
     \static
    */
    function envSeparator()
    {
        if ( !isset( $this ) or get_class( $this ) != "ezsys" )
            $this =& eZSys::instance();
        return $this->EnvSeparator;
    }

    /*!
     The path to where all the code resides.
     \static
    */
    function siteDir()
    {
        if ( !isset( $this ) or get_class( $this ) != "ezsys" )
            $this =& eZSys::instance();
        return $this->SiteDir;
    }

    /*!
     The relative directory path of the vhless setup.
     \static
    */
    function wwwDir()
    {
        if ( !isset( $this ) or get_class( $this ) != "ezsys" )
            $this =& eZSys::instance();
        return $this->WWWDir;
    }

    /*!
     The filepath for the index file.
     \static
    */
    function indexDir()
    {
        if ( !isset( $this ) or get_class( $this ) != "ezsys" )
            $this =& eZSys::instance();
        return $this->wwwDir() . $this->indexFile();
    }

    /*!
     The filepath for the index file with the access path appended.
     \static
     \sa indexFileName
    */
    function indexFile()
    {
        if ( !isset( $this ) or get_class( $this ) != "ezsys" )
            $this =& eZSys::instance();
        $text = $this->IndexFile;
        if ( $this->AccessPath != "" )
        {
            if ( $text != "" )
                $text .= "/";
            $text .= $this->AccessPath;
        }
        return $text;
    }

    /*!
     The filepath for the index file.
     \static
    */
    function indexFileName()
    {
        if ( !isset( $this ) or get_class( $this ) != "ezsys" )
            $this =& eZSys::instance();
        return $this->IndexFile;
    }

    /*!
     Returns the current hostname.
     \static
    */
    function hostname()
    {
        return $GLOBALS["HTTP_HOST"];
    }

    /*!
     Returns true if PostgreSQL support was found in PHP
     \static
    */
    function postgresqlSupport()
    {
        if ( function_exists( "pg_pconnect" ) )
            return true;
        else
            return false;
    }

    /*!
     Returns true if MySQL support was found in PHP.
     \static
    */
    function mysqlSupport()
    {
        if ( function_exists( "mysql_pconnect" ) )
            return true;
        else
            return false;
    }

    /*!
     Returns true if Oracle support was found in PHP.
     \static
    */
    function oracleSupport()
    {
        if ( function_exists( "OCILogon" ) )
            return true;
        else
            return false;
    }

    /*!
     Returns true if magick quotes is enabled.
     \static
    */
    function magickQuotes()
    {

    }

    /*!
     Return true if the attribute $attr is set. Available attributes are
     wwwdir, sitedir or indexfile
    */
    function hasAttribute( $attr )
    {
        return isset( $this->Attributes[$attr] );
    }

    /*!
     Returns the attribute value for $attr or null if the attribute does not exist.
    */
    function &attribute( $attr )
    {
        if ( !isset( $this->Attributes[$attr] ) )
            return null;
        $mname = $attr;
        return $this->$mname();
    }

    /*!
     \static
     Sets the access path which is appended to the index file.
     \sa indexFile
    */
    function setAccessPath( $path )
    {
        if ( !isset( $this ) or get_class( $this ) != "ezsys" )
            $this =& eZSys::instance();
        $this->AccessPath = $path;
    }

    /*!
     Initializes some variables according to some global PHP values.
     This function should be called once in the index file with the parameters
     stated in the parameter list.
     \static
    */
    function init( &$SCRIPT_FILENAME, &$PHP_SELF, &$DOCUMENT_ROOT,
                   &$SCRIPT_NAME, &$REQUEST_URI,
                   $def_index = "index.php" )
    {
        if ( !isset( $this ) or get_class( $this ) != "ezsys" )
            $this =& eZSys::instance();

        // Find out, where our files are.
        if ( ereg( "(.*/)([^\/]+\.php)$", $SCRIPT_FILENAME, $regs ) )
        {
            $siteDir = $regs[1];
            $index = "/" . $regs[2];
        }
        elseif ( ereg( "(.*/)([^\/]+\.php)/?", $PHP_SELF, $regs ) )
        {
            // Some people using CGI have their $SCRIPT_FILENAME not right... so we are trying this.
            $siteDir = $DOCUMENT_ROOT . $regs[1];
            $index = "/" . $regs[2];
        }
        else
        {
            // Fallback... doesn't work with virtual-hosts, but better than nothing
            $siteDir = "./";
            $index = "/$def_index";
        }

        // Setting the right include_path
        $includePath = ini_get( "include_path" );
        if ( trim( $includePath ) != "" )
            $includePath .= $this->envSeparator() . $siteDir;
        else
            $includePath = $siteDir;
        ini_set( "include_path", $includePath );

        // Get the webdir.
        if ( ereg( "(.*)/([^\/]+\.php)$", $SCRIPT_NAME, $regs ) )
            $wwwDir = $regs[1];

        // Fallback... Finding the paths above failed, so $PHP_SELF is not set right.
        if ( $siteDir == "./" )
            $PHP_SELF = $REQUEST_URI;

        $def_index_reg = str_replace( ".", "\\.", $def_index );
        // Trick: Rewrite setup doesn't have index.php in $PHP_SELF, so we don't want an $index
        if ( ! ereg( ".*$def_index_reg.*", $PHP_SELF ) )
            $index = "";
        else
        {
            // Get the right $REQUEST_URI, when using nVH setup.
            if ( ereg( "^$wwwDir$index(.+)", $PHP_SELF, $req ) )
                $REQUEST_URI = $req[1];
            else
                $REQUEST_URI = "/";
        }

        $this->AccessPath = "";
        $this->SiteDir =& $siteDir;
        $this->WWWDir =& $wwwDir;
        $this->IndexFile =& $index;

    }

    /*!
     Initializes some variables which are read from site.ini
     \warning Do not call this before init()
    */
    function initIni( &$ini )
    {
        if ( !isset( $this ) or get_class( $this ) != "ezsys" )
            $this =& eZSys::instance();
    }

    /*!
     Returns the only legal instance of the eZSys class.
     \static
    */
    function &instance()
    {
        $instance =& $GLOBALS["eZSysInstance"];
        if ( get_class( $instance ) != "ezsys" )
        {
            $instance = new eZSys();
        }
        return $instance;
    }

    /// The line separator used in files
    var $LineSeparator;
    /// The directory separator used for files
    var $FileSeparator;
    /// The list separator used for env variables
    var $EnvSeparator;
    /// The path to where all the code resides
    var $SiteDir;
    /// The access path of the current site view
    var $AccessPath;
    /// The relative directory path of the vhless setup
    var $WWWDir;
    /// The filepath for the index
    var $IndexFile;
}

?>
