<?php
//
// Definition of eZDiffContent class
//
// Created on: <24-Jul-2007 13:45:21 hovik>
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

/*! \file ezdiffcontent.php
  eZDiffContent class
*/

/*!
  \class eZDiffContent ezdiffcontent.php
  \ingroup eZDiff
  \brief eZDiff provides an interface for accessing changes in an eZContentObject

  eZDiffContent holds container structures for viewing and accessing detected differences
  in an eZContentObject. This is an abstract class.
*/

class eZDiffContent
{
    /*!
      \public
      Return the set of changes.
    */
    function getChanges()
    {
        return $this->Changeset;
    }

    /*!
      \public
      Returns the old stored content
    */
    function getOldContent()
    {
        return $this->OldContent;
    }

    /*!
      \public
      Returns the new stored content
    */
    function getNewContent()
    {
        return $this->NewContent;
    }

    /*!
      \public
      Sets the old stored content
    */
    function setOldContent( $data )
    {
        $this->OldContent = $data;
    }

    /*!
      \public
      Sets the new stored content
    */
    function setNewContent( $data )
    {
        $this->NewContent = $data;
    }

    /*!
      \public
      Set the changeset array
    */
    function setChanges( $data )
    {
        $this->Changeset = $data;
    }


    function attributes()
    {
        return array( 'changes',
                      'old_content',
                      'new_content' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function attribute( $attrName )
    {
        switch ( $attrName )
        {
            case 'changes':
            {
                return $this->getChanges();
            }break;

            case 'old_content':
            {
                return $this->getOldContent();
            }break;

            case 'new_content':
            {
                return $this->getNewContent();
            }break;

            default:
            {
                eZDebug::writeError( "Attribute '$attrName' does not exist", 'eZDiffContent' );
                return null;
            }break;
        }
    }

    /// \privatesection
    /// The set of detected changes
    public $Changeset;

    /// Old Object
    public $OldContent;

    /// New Object
    public $NewContent;
}
?>
