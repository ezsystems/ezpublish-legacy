<?php
//
// Created on: <03-May-2002 15:17:01 bf>
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

include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezcontentclass.php" );
include_once( "kernel/classes/ezcontentobjectversion.php" );
include_once( "kernel/classes/ezcontentobjectattribute.php" );

include_once( "lib/ezutils/classes/ezhttptool.php" );

include_once( "kernel/common/template.php" );

$ObjectID = $Params["ObjectID"];
$EditVersion = $Params["EditVersion"];

$http =& eZHTTPTool::instance();

if ( $http->hasPostVariable( "BackButton" )  )
{
    $Module->redirectTo( $Module->functionURI( "edit" ) . "/" . $ObjectID . "/" . $EditVersion . "/" );
    return;
}

$translateToLanguage = "no_NO";
if ( $http->hasPostVariable( "SelectLanguageButton" )  )
{
    $translateToLanguage = $http->postVariable( "TranslateToLanguage" );

    print( $translateToLanguage );
}

$tpl =& templateInit();

$object =& eZContentObject::fetch( $ObjectID );

if ( ! $object->attribute( 'can_edit' ) )
{
        $Module->redirectTo( '/error/403' );
        return;
}

$version =& $object->version( $EditVersion );

$classID = $object->attribute( "class_id" );
$class =& eZContentClass::fetch( $classID );

$originalContentAttributes =& $version->attributes();
$translateContentAttributes =& $version->attributes( $translateToLanguage );

if ( $http->hasPostVariable( "StoreButton" )  )
{
    $inputValidated = true;
    $unvalidatedAttributes = array();
    reset( $translateContentAttributes );
    while( ( $key = key( $translateContentAttributes ) ) !== null )
    {
        $data =& $translateContentAttributes[$key];
        if ( $data->validateInput( $http, "ContentObjectAttribute" ) == false )
        {
            eZDebug::writeNotice( "Validating " . $data->attribute( "id" ) . " failed" );
            $inputValidated = false;
            $unvalidatedAttributes[] = $data->attribute( "id" );
        }
        else
        {
            eZDebug::writeNotice( "Validating " . $data->attribute( "id" ) . " success" );
        }
        $data->fetchInput( $http, "ContentObjectAttribute" );
        next( $translateContentAttributes );
    }

    if ( $inputValidated == true  )
    {
        // increment the current version for the object
        // !! this function should be moved to a publish function to
        // !! work with workflows
        $currentVersion = $object->attribute( "current_version" );
        //    $object->setAttribute( "current_version", $editVersion );
        $object->setAttribute( "parent_id", $parentObjectID );

        $object->store();

        $version->setAttribute( "modified", mktime() );
        $version->store();

        // fetch the current version object

        $i=0;
        reset( $translateContentAttributes );
        while( ( $key = key( $translateContentAttributes ) ) !== null )
        {
            $data =& $translateContentAttributes[$key];
            //        $data->setAttribute( "id", null );
            //        $data->setAttribute( "version", $editVersion );
            $data->store();
            $i++;
            next( $translateContentAttributes );
        }

        $object->store();
    }
}

// create a new language
if ( count( $translateContentAttributes ) == 0 )
{
    $translateContentAttributes = $originalContentAttributes;

    foreach ( $translateContentAttributes as $contentAttribute )
    {
        $contentAttribute->setAttribute( "id", null );
        $contentAttribute->setAttribute( "language_code", $translateToLanguage );
        $contentAttribute->store();
    }

}

$tpl->setVariable( "object", $object );
$tpl->setVariable( "edit_version", $EditVersion );

$tpl->setVariable( "content_attributes", $originalContentAttributes );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:content/translate.tpl" );

?>
