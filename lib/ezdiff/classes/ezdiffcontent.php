<?php
//
// Definition of eZDiffContent class
//
// <creation-tag>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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
      Set the member values with relevant meta data.
    */
    function setMetaData( $statArray, $oldWordArray, $newWordArray )
    {
        $this->StatisticalMatrix = $statArray;
        $this->NewTextIsLonger = $statArray['newTextLonger'];
        $this->OldWordArray = $oldWordArray;
        $this->NewWordArray = $newWordArray;
    }

    /*!
      \public
      Retrieve the statistical matrix
    */
    function getStatMatrix()
    {
        return $this->StatisticalMatrix;
    }

    /*!
      \public
      Retrive the original words
    */
    function getOriginalWords()
    {
        return $this->OldWordArray;
    }

    /*!
      \public
      Retrive the new words
    */
    function getNewWords()
    {
        return $this->NewWordArray;
    }

    /*!
      \public
      \return Whether new text is longer or not
    */
    function newTextLonger()
    {
        return $this->NewTextIsLonger;
    }

    /*!
      \public
      Set the difference array
    */
    function setDiff( $array )
    {
        $this->Differences = $array;
    }

    /*!
      \public
      Return the Difference matrix
    */
    function getDiff()
    {
        return $this->Differences;
    }

    /*!
      \public
      Return the set of consequtive changes.
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
        return array( 'diff',
                      'changes',
                      'old_content',
                      'new_content' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function &attribute( $attrName )
    {
        switch ( $attrName )
        {
            case 'diff':
            {
                $retVal = $this->getDiff();
            }break;

            case 'changes':
            {
                $retVal = $this->getChanges();
            }break;

            case 'old_content':
            {
                $retVal = $this->getOldContent();
            }break;

            case 'new_content':
            {
                $retVal = $this->getNewContent();
            }break;

            default:
            {
                eZDebug::writeError( "Attribute '$attrName' does not exist", 'eZDiffContent' );
                $retVal = null;
            }break;
        }
        return $retVal;
    }

    /// \privatesection
    /// Statistical info on the raw text data.
    var $StatisticalMatrix;

    /// If \c true the newer text contains more words than the previous version
    var $NewTextIsLonger;

    /// The set of detected differences by word
    var $Differences;

    /// The set of detected consequtive changes
    var $Changeset;

    /// The array of old, original words
    var $OldWordArray;

    /// The array of new words
    var $NewWordArray;

    /// Old Object
    var $OldContent;

    /// New Object
    var $NewContent;
}
?>
