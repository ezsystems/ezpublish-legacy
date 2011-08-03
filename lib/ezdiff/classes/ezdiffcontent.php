<?php
/**
 * File containing the eZDiffContent class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
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
