<?php
//
// Definition of eZDiffContainerObjectEngine class
//
// <creation-tag>
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

/*! \file ezdiffcontainerobjectengine.php
  eZDiffContainerObjectEngine class
*/

/*!
  \class eZDiffContainerObjectEngine ezdiffcontainerobjectengine.php
  \ingroup eZDiff
  \brief Creates an object containing two versions of a content object.

  The eZDiffEngine class is an abstract class, providing interface and shared code
  for the different available DiffEngine.
*/

//include_once( 'lib/ezdiff/classes/ezdiffengine.php' );

class eZDiffContainerObjectEngine extends eZDiffEngine
{
    function eZDiffContainerObjectEngine()
    {
    }

    /*!
      Create containerobject containig content from two versions
    */
    function createDifferenceObject( $old, $new )
    {
        //include_once( 'lib/ezdiff/classes/ezdiffcontainerobject.php' );
        $container = new eZDiffContainerObject();
        $container->setOldContent( $old );
        $container->setNewContent( $new );

        return $container;
    }
}

?>
