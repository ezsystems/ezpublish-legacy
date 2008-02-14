<?php
//
// Definition of eZDiff class
//
// <creation-tag>
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

/*! \file ezdiff.php
  Diff functionality
*/

include_once( 'lib/ezutils/classes/ezdebug.php' );

/*!
  \class eZDiff ezdiff.php
  \ingroup eZDiff
  \brief eZDiff provides an access point the diff system

  The eZDiff class is responsible for accessing and loading the correct
  diff engine when a datatype makes a call to eZDiff library.
*/
class eZDiff
{
    /*!
      Instantiates the eZDiff object
      \param $diffEngineType The type of diff engine to initialize at start
    */
    function eZDiff( $diffEngineType = false )
    {
        if ( $diffEngineType )
        {
            $this->DiffEngine = $diffEngineType;
            eZDebug::writeNotice( "Diff engine type: " . $diffEngineType, 'eZDiff' );
        }
    }

    /*!
      \public
      Set the diff engine to be used for diffing.
      \param $diffEngineType The type of diff engine to intitialize
    */
    function setDiffEngineType( $diffEngineType )
    {
        if ( isset( $diffEngineType ) )
        {
            $this->DiffEngine = $diffEngineType;
            eZDebug::writeNotice( "Changing diff engine to type: " . $diffEngineType, 'eZDiff' );
        }
    }

    /*!
      \public
      \return The diff engine type used in this instance.
    */
    function getDiffEngineType()
    {
        return $this->DiffEngine;
    }

    /*!
      \public
      \return Internal id of engine type given in \a $typeString
    */
    function engineType( $typeString )
    {
        switch ( $typeString )
        {
            case 'text':
            {
                return $this->DIFF_TYPE['text'];
            }break;

            case 'xml':
            {
                return $this->DIFF_TYPE['xml'];
            }break;

            case 'container':
            {
                return $this->DIFF_TYPE['container'];
            }break;
        }
    }

    /*!
      \public
      Returns diff engine of \a $type
    */
    function initDiffEngine()
    {
        include_once( 'lib/ezdiff/classes/ezdiffengine.php' );

        if ( !$diffEngine = $this->DiffEngineInstance )
        {
            switch( $this->DiffEngine )
            {
                case '0': //Text
                {
                    include_once( 'lib/ezdiff/classes/ezdifftextengine.php' );
                    $this->DiffEngineInstance = new eZDiffTextEngine();
                }break;

                case '1': //XML
                {
                    include_once( 'lib/ezdiff/classes/ezdiffxmltextengine.php' );
                    $this->DiffEngineInstance = new eZDiffXMLTextEngine();
                }break;

                case '2': //ObjectContainer
                {
                    include_once( 'lib/ezdiff/classes/ezdiffcontainerobjectengine.php' );
                    $this->DiffEngineInstance = new eZDiffContainerObjectEngine();
                }
            }
        }
    }

    /*!
      \public
      Perform a diff operation on the provided set of data. A valid
      diff engine have to be specified, before this can be run.

      \return An eZDiffContent object with the detected changes
    */
    function &diff( $fromData, $toData)
    {
        if ( !$this->DiffEngineInstance )
            return null;

        $differ =& $this->DiffEngineInstance;
        $diffObject = $differ->createDifferenceObject( $fromData, $toData );
        return $diffObject;
    }

    ///\privatesection
    /// Variable holding the diff engine type
    var $DiffEngine;

    /// The instance of the diff engine class
    var $DiffEngineInstance;

    /// The allowed input on which to diff
    var $DIFF_TYPE = array( 'text' => 0,
                            'xml' => 1,
                            'container' => 2 );
}

?>
