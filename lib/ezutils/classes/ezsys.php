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
        $this->Attributes = array( "mysqlSupport" => true,
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
        if ( count( $this->AccessPath ) > 0 )
        {
//             if ( $text != "" )
                $text .= "/";
            $text .= implode( '/', $this->AccessPath );
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
     \return the variable named \a $variableName in the global \c $_SERVER variable.
             If the variable is not present an error is shown and \c null is returned.
    */
    function &serverVariable( $variableName )
    {
        global $_SERVER;
        if ( !isset( $_SERVER[$variableName] ) )
        {
            eZDebug::writeError( "Server variable '$variableName' does not exist", 'eZSys::serverVariable' );
            return null;
        }
        return $_SERVER[$variableName];
    }

    /*!
     Sets the server variable named \a $variableName to \a $variableValue.
     \note Variables are only set for the current page view.
    */
    function setServerVariable( $variableName, $variableValue )
    {
        global $_SERVER;
        $_SERVER[$variableName] = $variableValue;
    }

    /*!
     \return the variable named \a $variableName in the global \c $_ENV variable.
             If the variable is not present an error is shown and \c null is returned.
    */
    function &environmentVariable( $variableName )
    {
        global $_ENV;
        if ( !isset( $_ENV[$variableName] ) )
        {
            eZDebug::writeError( "Environment variable '$variableName' does not exist", 'eZSys::environmentVariable' );
            return null;
        }
        return $_ENV[$variableName];
    }

    /*!
     Sets the environment variable named \a $variableName to \a $variableValue.
     \note Variables are only set for the current page view.
    */
    function setEnvironmentVariable( $variableName, $variableValue )
    {
        global $_ENV;
        $_ENV[$variableName] = $variableValue;
    }

    /*!
     Return true if the attribute $attr is set. Available attributes are
     wwwdir, sitedir or indexfile
    */
    function hasAttribute( $attr )
    {
        return ( isset( $this->Attributes[$attr] )
                 or $attr == "wwwdir"
                 or $attr == "sitedir"
                 or $attr == "indexfile"
                 or $attr == "indexdir" );
    }

    /*!
     Returns the attribute value for $attr or null if the attribute does not exist.
    */
    function &attribute( $attr )
    {
        if ( isset( $this->Attributes[$attr] ) )
        {
            $mname = $attr;
            return $this->$mname();
        }
        else if ( $attr == 'wwwdir' )
        {
            return $this->wwwDir();
        }
        else if ( $attr == 'sitedir' )
        {
            return $this->siteDir();
        }
        else if ( $attr == 'indexfile' )
        {
            return $this->indexFile();
        }
        else if ( $attr == 'indexdir' )
        {
            return $this->indexDir();
        }
        else
        {
            return null;
        }
    }

    /*!
     \static
     Sets the access path which is appended to the index file.
     \sa indexFile
    */
    function addAccessPath( $path )
    {
        if ( !isset( $this ) or get_class( $this ) != "ezsys" )
            $this =& eZSys::instance();
        if ( !is_array( $path ) )
            $path = array( $path );
        $this->AccessPath = array_merge( $this->AccessPath, $path );
    }

    /*!
     Initializes some variables according to some global PHP values.
     This function should be called once in the index file with the parameters
     stated in the parameter list.
     \static
    */
    function init( $def_index = "index.php" )
    {
        if ( !isset( $this ) or get_class( $this ) != "ezsys" )
            $this =& eZSys::instance();

        eZDebug::writeDebug( "PHP_SELF=" . eZSys::serverVariable( 'PHP_SELF' ) );
        eZDebug::writeDebug( "SCRIPT_FILENAME=" . eZSys::serverVariable( 'SCRIPT_FILENAME' ) );
        eZDebug::writeDebug( "DOCUMENT_ROOT=" . eZSys::serverVariable( 'DOCUMENT_ROOT' ) );
        eZDebug::writeDebug( "include_path=" . ini_get( 'include_path' ) );

        // Find out, where our files are.
        if ( ereg( "(.*/)([^\/]+\.php)$", eZSys::serverVariable( 'SCRIPT_FILENAME' ), $regs ) )
        {
            $siteDir = $regs[1];
            $index = "/" . $regs[2];
        }
        elseif ( ereg( "(.*/)([^\/]+\.php)/?", eZSys::serverVariable( 'PHP_SELF' ), $regs ) )
        {
            // Some people using CGI have their $_SERVER['SCRIPT_FILENAME'] not right... so we are trying this.
            $siteDir = eZSys::serverVariable( 'DOCUMENT_ROOT' ) . $regs[1];
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
        if ( ereg( "(.*)/([^\/]+\.php)$", eZSys::serverVariable( 'SCRIPT_NAME' ), $regs ) )
            $wwwDir = $regs[1];
		else if ( ereg( "(.*)/([^\/]+\.php)$", eZSys::serverVariable( 'PHP_SELF' ), $regs ) )
			$wwwDir = $regs[1];

        // Fallback... Finding the paths above failed, so $_SERVER['PHP_SELF'] is not set right.
        if ( $siteDir == "./" )
            eZSys::setServerVariable( 'PHP_SELF', eZSys::serverVariable( 'REQUEST_URI' ) );

        $def_index_reg = str_replace( ".", "\\.", $def_index );
        // Trick: Rewrite setup doesn't have index.php in $_SERVER['PHP_SELF'], so we don't want an $index
        if ( ! ereg( ".*$def_index_reg.*", eZSys::serverVariable( 'PHP_SELF' ) ) )
            $index = "";
        else
        {
            // Get the right $_SERVER['REQUEST_URI'], when using nVH setup.
            if ( ereg( "^$wwwDir$index(.+)", eZSys::serverVariable( 'PHP_SELF' ), $req ) )
                eZSys::setServerVariable( 'REQUEST_URI', $req[1] );
//            else
//                eZSys::setServerVariable( 'REQUEST_URI', "/" );
        }

        $this->AccessPath = array();
        $this->SiteDir =& $siteDir;
        $this->WWWDir =& $wwwDir;
        $this->IndexFile =& $index;

        eZDebug::writeDebug( "SiteDir=" . $this->SiteDir );
        eZDebug::writeDebug( "WWWDir=" . $this->WWWDir );
        eZDebug::writeDebug( "IndexFile=" . $this->IndexFile );
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
