<?php
//
// Created on: <18-Apr-2002 10:04:48 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
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
*/
function changelogList( $version )
{
    $changes =& $GLOBALS["eZSDK-Changes"];
    if ( !isset( $changes ) and !is_array( $changes ) )
    {
        $changes = array();
        $root = "doc/changelogs";
        $dir = "$root/$version/";
        if ( !file_exists( $dir ) )
            return $changes;
        $fd = opendir( $dir );
        if ( !$fd )
            return $changes;
        while( ( $file = readdir( $fd ) ) !== false )
        {
            if ( $file == "." or
                 $file == ".." or
                 $file[0] == "." or
                 is_dir( $file ) )
                continue;
            if ( !preg_match( "/^CHANGELOG-([0-9]+)\.([0-9]+)-([0-9]+)$/", $file, $regs ) )
                continue;
            $maj = $regs[1];
            $min = $regs[2];
            $rel = $regs[3];
            $changes[$rel] =  array( "maj" => $maj,
                                     "min" => $min,
                                     "rel" => $rel );
        }
        ksort( $changes );
    }
    return $changes;
}

function loadChangelogDescription( $version, $rel )
{
    $root = "doc/changelogs";
    $desc = null;
    $desc_file = "$root/$version/CHANGELOG-desc-$version-$rel";
    if ( file_exists( $desc_file ) )
    {
        $fd_desc = fopen( $desc_file, "r" );
        $desc = fread( $fd_desc, 500 );
        $pos = strpos( "\n", $desc );
        if ( $pos !== false )
            $desc = substr( $desc, 0, $pos );
    }
    return $desc;
}

/*!
*/
function loadChangelogList( $version, $baseURI )
{
    include_once( "lib/ezutils/classes/ezsys.php" );
    include_once( "kernel/sdk/parser.php" );

    // Settings for NVH setup
    $pathPrepend = eZSys::wwwDir();
    $indexPathPrepend = eZSys::indexDir();

    // $version
    // $change_items
    $templateVars = array();
    $templateData = "<h1>eZ publish changes</h1>
<p>Here's the changes in the %version% series.</p>
%change_items%";

    // $maj
    // $min
    // $rel
    // $desc
    // $baseURI
    // $index_path_prepend
    $templateChangeVars = array();
    $templateChangeData = "<h3><a href=\"%index_path_prepend%%baseURI%/changes/view/changelog/%maj%.%min%/%rel%\">%maj%.%min%-%rel%</a></h3>
<p>%desc%</p>";

    $templateVars["version"] = $version;
    $change_items =& $templateVars["change_items"];
    $change_items = "";

    $changes =& changelogList( $version );

    $templateChangeVars["index_path_prepend"] = $indexPathPrepend;
    $templateChangeVars["baseURI"] = $baseURI;
    foreach( $changes as $change )
    {
        $maj = $change["maj"];
        $min = $change["min"];
        $rel = $change["rel"];
        $desc = "No description";
        $desc_ret = loadChangelogDescription( $version, $rel );
        if ( $desc_ret !== null )
            $desc = $desc_ret;
        $templateChangeVars["maj"] = $maj;
        $templateChangeVars["min"] = $min;
        $templateChangeVars["rel"] = $rel;
        $templateChangeVars["desc"] = $desc;
        $change_items .= simpleParse( $templateChangeData, $templateChangeVars );
    }
    $data = simpleParse( $templateData, $templateVars );
    return $data;
}

function loadChangelog( $version, $release )
{
    // $version
    // $release
    // $desc
    // $items
    $templateVars = array();
    $templateData = "<h1>eZ publish changes in %version%-%release%</h1>
<p>%desc%</p>
%items%\n";

    // $name
    // $entries
    // $header_size
    $templateSectionVars = array();
    $templateSectionData = "<%header_size%>%name%</%header_size%>
<ul>%entries%</ul>\n";

    // $text
    $templateEntryVars = array();
    $templateEntryData = "<li>%text%</li>\n";

    $desc = "";
    $desc_ret = loadChangelogDescription( $version, $release );
    if ( $desc_ret !== null )
        $desc = $desc_ret;

    $templateVars["version"] = $version;
    $templateVars["release"] = $release;
    $templateVars["desc"] = $desc;

    $file = "doc/changelogs/$version/CHANGELOG-$version-$release";
    return loadItemlog( $file,
                        $templateVars, $templateSectionVars, $templateEntryVars,
                        $templateData, $templateSectionData, $templateEntryData );
}

function loadTodo()
{
    $priorities = "";
    for ( $i = 1; $i <= 9; ++$i )
    {
        $priorities .= "<td class=\"pri_$i\">$i</td>";
    }
    // $items
    $templateVars = array();
    $templateData = "<h1>Todo list for eZ publish</h1>
<p><b>Priorities</b><table><tr>$priorities</tr></table></p>
%items%\n";

    // $name
    // $entries
    // $header_size
    $templateSectionVars = array();
    $templateSectionData = "<%header_size%>%name%</%header_size%>
<ul>%entries%</ul>\n";

    // $text
    $templateEntryVars = array();
    $templateEntryData = "<li class=\"%priority_text%\">%text%</li>\n";

    $file = "doc/todo";
    return loadItemlog( $file,
                        $templateVars, $templateSectionVars, $templateEntryVars,
                        $templateData, $templateSectionData, $templateEntryData );
}

/*!
*/
function loadItemlog( $file,
                      $templateVars, $templateSectionVars, $templateEntryVars,
                      $templateData, $templateSectionData, $templateEntryData )
{
    include_once( "lib/ezutils/classes/ezsys.php" );

    // Settings for NVH setup
    $pathPrepend = eZSys::wwwDir();
    $indexPathPrepend = eZSys::indexDir();

    if ( !file_exists( $file ) )
        return "";
    $fd = fopen( $file, "r" );
    $changes = fread( $fd, filesize( $file ) );
    fclose( $fd );
    $len = strlen( $changes );
    $pos = 0;
    $last_section_pos = false;
    $sections = array();
    while( $pos < $len )
    {
        $sectionDone = false;
        while ( !$sectionDone )
        {
            $sectionDone = true;
            $section_pos = strpos( $changes, ":", $pos );
            $data = substr( $changes, $last_section_pos, $section_pos - $last_section_pos );
            $dataItems = explode( "\n", $data );
            if ( count( $dataItems ) > 0 and
                 strlen( $dataItems[count( $dataItems ) - 1] ) > 0 and
                 !preg_match( "/[a-zA-Z0-9*]/", $dataItems[count( $dataItems ) - 1][0] ) )
            {
                $pos = $section_pos + 1;
                $sectionDone = false;
            }
        }
        $section_start_pos = $section_pos;
//         if ( $section_pos === false )
//             break;
        if ( $section_pos === false )
            $section_end = $len - 1;
        else
            $section_end = $section_pos;
        $tmp_pos = $pos;
        while( $tmp_pos < $section_end )
        {
            $end_pos = strpos( $changes, "\n", $tmp_pos );
            if ( $end_pos === false )
            {
                $tmp_pos = $len - 1;
                break;
            }
            if ( $end_pos > $section_end )
                break;
            $tmp_pos = $end_pos + 1;
        }
        $section_pos = $tmp_pos;
        if ( $last_section_pos !== false )
        {
            $section_data = trim( substr( $changes, $last_section_pos, $section_pos - $last_section_pos ) );
            $section = array( "name" => $section_name,
                              "data" => $section_data );
            $sections[] = $section;
        }
        $section_name = trim( substr( $changes, $section_pos, $section_end - $section_pos ) );
        $last_section_pos = $section_end + 1;
        $pos = $section_end + 1;
        if ( $section_start_pos === false )
            break;
    }
    $items_text =& $templateVars["items"];
    $items_text = "";
    foreach( $sections as $section )
    {
        $name = $section["name"];
        $header_size = "h3";
        if ( $name[0] == "*" )
        {
            $name = substr( $name, 1 );
            $header_size = "h2";
        }
        $entries = explode( "\n-", $section["data"] );
        $templateSectionVars["name"] = $name;
        $templateSectionVars["header_size"] = $header_size;
        $entry_text =& $templateSectionVars["entries"];
        $entry_text = "";
        foreach( $entries as $entry )
        {
            if ( strlen( $entry ) > 0 and
                 $entry[0] == "-" )
                $entry = substr( $entry, 1 );
            $entry = trim( $entry );
            $pri = 1;
            if ( preg_match( "/^([1-9])/", $entry, $regs ) )
            {
                $pri = $regs[1];
                $entry = substr( $entry, 1 );
            }
            if ( $entry == "" )
                continue;
            $entry = htmlspecialchars( $entry );
            $entry = preg_replace( array( "/(^|\s)\*([^\s]+)\*(\s?)/",
                                          "#(^|\s)/([^\s]+)/(\s?)#",
                                          "/(^|\s)_([^\s]+)_(\s?)/" ),
                                   array( "$1<b>$2</b>$3",
                                          "$1<i>$2</i>$3",
                                          "$1<u>$2</u>$3" ),
                                   $entry );
            $templateEntryVars["text"] = $entry;
            $templateEntryVars["priority"] = $pri;
            $templateEntryVars["priority_text"] = "pri_$pri";
            $entry_text .= simpleParse( $templateEntryData, $templateEntryVars );
        }
        $items_text .= simpleParse( $templateSectionData, $templateSectionVars );
    }

    $data = simpleParse( $templateData, $templateVars );

    return $data;
}

$content = "";

$baseURI = $Params["base_uri"];
$Type = $Params["part"];

$error = false;

$Result = array();

switch ( $Type )
{
    case "todo":
    {
        $content .= loadTodo();
        $Result["title"] = "Todo";
    } break;
    case "features":
    {
        ob_start();
        $docFile = "sdk/changes/features.php";
        if ( file_exists( $docFile ) )
            include( $docFile );
        $content = ob_get_contents();
        ob_end_clean();

        if ( isset( $DocResult ) and
             is_array( $DocResult ) )
        {
            if ( isset( $DocResult["title"] ) )
                $docName = $DocResult["title"];
        }
        $Result["title"] = $docName;
    } break;
    case "changelog":
    {
        include_once( "lib/version.php" );
        $ChangeVersion = eZPublishSDK::version( false );
        $ChangeRelease = null;

        if ( isset( $Params["rest"][0] ) and $Params["rest"][0] != "" )
            $ChangeVersion = $Params["rest"][0];
        else
            $ChangeRelease = eZPublishSDK::release();
        if ( isset( $Params["rest"][1] ) )
            $ChangeRelease = $Params["rest"][1];

        if ( !is_numeric( $ChangeRelease ) )
        {
            $content .= loadChangelogList( $ChangeVersion, $baseURI );
            $Result["title"] = "Changes in $ChangeVersion";
        }
        else
        {
            $content .= loadChangelog( $ChangeVersion, $ChangeRelease );

            $Result["title"] = "Changelog $ChangeVersion-$ChangeRelease";
        }

        $changes =& changelogList( $ChangeVersion );

        $nav =& $Result["navigation"];
        $nav = array();

        $forward = null;
        $back = null;
        $features = array();
//         $features[] = array( "name" => "Changes in $ChangeVersion" );
        $last_rel = null;
        foreach( $changes as $change )
        {
            $maj = $change["maj"];
            $min = $change["min"];
            $rel = $change["rel"];
            $features[] = array( "uri" => "changelog/" . "$maj.$min/$rel",
                                 "name" => "$maj.$min-$rel" );

            if ( isset( $ChangeRelease ) )
            {
                if ( $last_rel !== null and $ChangeRelease == $last_rel )
                {
                    $forward = array( "uri" => "changelog/$ChangeVersion/$rel",
                                      "name" => "$ChangeVersion-$rel" );
                }
                if ( $last_rel !== null and $ChangeRelease == $rel )
                {
                    $back = array( "uri" => "changelog/$ChangeVersion/$last_rel",
                                   "name" => "$ChangeVersion-$last_rel" );
                }
                else if ( $last_rel === null )
                {
                    $back = array( "uri" => "changelog/$ChangeVersion",
                                   "name" => "$ChangeVersion" );
                }
                $last_rel = $rel;
            }
        }
        if ( !isset( $ChangeRelease ) )
        {
            if ( count( $changes ) > 0 )
            {
                $change = array_shift( $changes );
                $forward = array( "uri" => "changelog/$ChangeVersion/" . $change["rel"],
                                  "name" => "$ChangeVersion-" . $change["rel"] );
            }
        }
        $Result["features"] = $features;
        $Result["feature_placement"] = "changelog/$ChangeVersion";

        if ( $forward !== null )
            $nav["forward"] = $forward;
        if ( $back !== null )
            $nav["back"] = $back;
    } break;
    default:
    {
        $error = true;
    } break;
}

if ( $error )
{
    $content .= "<p>Undefined feature $Type</p>";
}

$Result["content"] = $content;
$Result["pagelayout"] = false;
$Result["external_css"] = true;

?>
