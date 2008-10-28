<?php
//
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

require_once('lib/ezutils/classes/ezdebug.php');
function stateEditPostFetch( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage, &$validation )
{
}

function stateEditPreCommit( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage )
{
}

function stateEditActionCheck( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage )
{
    if ( $module->isCurrentAction( 'StateEdit' ) )
    {
        $http = eZHTTPTool::instance();
        if ( $http->hasPostVariable( 'SelectedStateIDList' ) )
        {
            $selectedStateIDList = $http->postVariable( 'SelectedStateIDList' );

            // we don't need to re-assign states the object currently already has assigned
            $currentStateIDArray = $object->attribute( 'state_id_array' );
            $selectedStateIDList = array_diff( $selectedStateIDList, $currentStateIDArray );

            // filter out any states the current user is not allowed to assign
            $canAssignStateIDList = $object->attribute( 'allowed_assign_state_id_list' );
            $selectedStateIDList = array_intersect( $selectedStateIDList, $canAssignStateIDList );

            foreach ( $selectedStateIDList as $selectedStateID )
            {
                $state = eZContentObjectState::fetchById( $selectedStateID );
                $object->assignState( $state );
            }
        }
    }
}

function stateEditPreTemplate( $module, $class, $object, $version, $contentObjectAttributes, $editVersion, $editLanguage, $tpl )
{
}

function initializeStateEdit( $module )
{
    $module->addHook( 'post_fetch', 'stateEditPostFetch' );
    $module->addHook( 'pre_commit', 'stateEditPreCommit' );
    $module->addHook( 'action_check', 'stateEditActionCheck' );
    $module->addHook( 'pre_template', 'stateEditPreTemplate' );
}

?>
