<?php
//
// Created on: <16-Apr-2004 10:04:58 amos>
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

function eZSetupTypes()
{
    return array( 'news' => array( 'name' => 'News',
                                   'identifier' => 'news',
                                   'summary' => 'With the eZ publish open source News site solution you can publish articles and news and communicate with your readers in a online newspaper containing the features you would normally need.',
                                   'thumbnail' => 'news_thumbnail.png' ),
                  'blog' => array( 'name' => 'Blog',
                                   'identifier' => 'blog',
                                   'summary' => '',
                                   'thumbnail' => 'blog_thumbnail.png' ),
                  'corporate' => array( 'name' => 'Corporate',
                                        'identifier' => 'corporate',
                                        'summary' => '',
                                        'thumbnail' => 'corporate_thumbnail.png' ),
                  'forum' => array( 'name' => 'Forum',
                                    'identifier' => 'forum',
                                    'summary' => '',
                                    'thumbnail' => 'forum_thumbnail.png' ),
                  'gallery' => array( 'name' => 'Gallery',
                                      'identifier' => 'gallery',
                                      'summary' => '',
                                      'thumbnail' => 'gallery_thumbnail.png' ),
                  'intranet' => array( 'name' => 'Intranet',
                                       'identifier' => 'intranet',
                                       'summary' => '',
                                       'thumbnail' => 'intranet_thumbnail.png' ),
                  'shop' => array( 'name' => 'Shop',
                                   'identifier' => 'shop',
                                   'summary' => '',
                                   'thumbnail' => 'shop_thumbnail.png' ),
                  'plain' => array( 'name' => 'Plain',
                                    'identifier' => 'plain',
                                    'summary' => "Stripped install.\nContains no special toolbar or menu choices",
                                    'thumbnail' => 'plain_thumbnail.png' )
                  );
}

function eZSetupFunctionality( $siteType )
{
    if ( $siteType == 'blog' )
    {
        return array( 'required' => array( 'blog' ) );
    }
    else if ( $siteType == 'news' )
    {
        return array( 'required' => array( 'news' ) );
    }
    else if ( $siteType == 'corporate' )
    {
        return array( 'required' => array( 'news' ) );
    }
    else if ( $siteType == 'forum' )
    {
        return array( 'required' => array( 'forum' ) );
    }
    else if ( $siteType == 'gallery' )
    {
        return array( 'required' => array( 'gallery' ) );
    }
    else if ( $siteType == 'intranet' )
    {
        return array( 'required' => array() );
    }
    else if ( $siteType == 'shop' )
    {
        return array( 'required' => array( 'shop' ) );
    }
    else
    {
        return array( 'required' => array() );
    }
}

function eZSetupForumINISettings( $siteType )
{
    return array( 'name' => 'forum.ini',
                  'settings' => array( 'ForumSettings' => array( 'StickyUserGroupArray' => array( 12 ) ) ) );
}

function eZSetupSiteINISettings( $siteType )
{
    $settings = array();
    if ( $siteType == 'intranet' )
    {
        $settings = array_merge( $settings,
                                 array( 'SiteAccessSettings' => array( 'RequireUserLogin' => 'true' ) ) );
    }
    return array( 'name' => 'menu.ini',
                  'settings' => $settings );
}

function eZSetupMenuINISettings( $siteType )
{
    $default = array( 'CurrentMenu' => 'TopOnly',
                      'TopMenu' => 'flat_top',
                      'LeftMenu' => '' );
    $typeMap = array( 'news' => array( 'CurrentMenu' => 'LeftTop',
                                       'TopMenu' => 'flat_top',
                                       'LeftMenu' => 'flat_left' ),
                      'corporate' => array( 'CurrentMenu' => 'LeftTop',
                                            'TopMenu' => 'flat_top',
                                            'LeftMenu' => 'flat_left' ),
                      'intranet' => array( 'CurrentMenu' => 'LeftTop',
                                           'TopMenu' => 'flat_top',
                                           'LeftMenu' => 'flat_left' ),
                      'shop' => array( 'CurrentMenu' => 'LeftTop',
                                       'TopMenu' => 'flat_top',
                                       'LeftMenu' => 'flat_left' ) );
    if ( isset( $typeMap[$siteType] ) )
        $default = $typeMap[$siteType];
    return array( 'name' => 'menu.ini',
                  'settings' => array( 'MenuSettings' => array( 'AvailableMenuArray' => array( 'TopOnly',
                                                                                               'LeftOnly',
                                                                                               'DoubleTop',
                                                                                               'LeftTop' ) ),
                                       'SelectedMenu' => $default,
                                       'TopOnly' => array( 'TitleText' => 'Only top menu',
                                                           'MenuThumbnail' => 'menu/top_only.jpg',
                                                           'TopMenu' => 'flat_top',
                                                           'LeftMenu' => '' ),
                                       'LeftOnly' => array( 'TitleText' => 'Left menu',
                                                           'MenuThumbnail' => 'menu/left_only.jpg',
                                                           'TopMenu' => '',
                                                           'LeftMenu' => 'flat_left' ),
                                       'DoubleTop' => array( 'TitleText' => 'Double top menu',
                                                             'MenuThumbnail' => 'menu/double_top.jpg',
                                                             'TopMenu' => 'double_top',
                                                             'LeftMenu' => '' ),
                                       'LeftTop' => array( 'TitleText' => 'Left and top',
                                                             'MenuThumbnail' => 'menu/left_top.jpg',
                                                             'TopMenu' => 'flat_top',
                                                             'LeftMenu' => 'flat_left' )
                                       ) );
}

function eZSetupToolbarINISettings( $siteType )
{
    if ( $siteType == 'blog' )
    {
        $toolbar = array( 'name' => 'toolbar.ini',
                          'settings' => array( 'Toolbar_right' => array( 'Tool' => array( 'calendar', 'create_object', 'search', 'users' ) ),
                                               'Toolbar_top' => array( 'Tool' => array( 'login' ) ),
                                               'Toolbar_bottom' => array( 'Tool' => array() ),
                                               'Tool_right_calendar_1' => array( 'show_subtree' => 'blogs',
                                                                                 'class_identifier' => 'weblog' ),
                                               'Tool_right_create_object_2' => array( 'subtree' => 'blogs',
                                                                                      'class_identifier' => 'weblog',
                                                                                      'node_placement' => 98 ) ) );
    }
    else if ( $siteType == 'shop' )
    {
        $toolbar = array( 'name' => 'toolbar.ini',
                          'settings' => array( 'Toolbar_right' => array( 'Tool' => array( 'node_list', 'basket', 'search' ) ),
                                               'Toolbar_top' => array( 'Tool' => array( 'login' ) ),
                                               'Toolbar_bottom' => array( 'Tool' => array() ),
                                               'Tool_right_node_list_1' => array( 'parent_node' => '2',
                                                                                  'title' => 'Latest news',
                                                                                  'subtree' => '' ) ) );
    }
    else
    {
        $toolbar = array( 'name' => 'toolbar.ini',
                          'settings' => array( 'Toolbar_right' => array( 'Tool' => array( 'node_list' ) ),
                                               'Toolbar_top' => array( 'Tool' => array( 'login', 'searchbox' ) ),
                                               'Toolbar_bottom' => array( 'Tool' => array() ),
                                               'Tool_right_node_list_1' => array( 'parent_node' => '2',
                                                                                  'title' => 'Latest',
                                                                                  'subtree' => '' ) ) );
    }
    return $toolbar;
}

function eZSetupAdminToolbarINISettings( $siteType )
{
    $toolbar = array (
        'name' => 'toolbar.ini',
        'settings' =>
        array (
            'Toolbar' =>
            array (
                'AvailableToolBarArray' =>
                array (
                    0 => 'setup',
                    ),
                ),
            'Tool' =>
            array (
                'AvailableToolArray' =>
                array (
                    0 => 'setup_link',
                    ),
                ),
            'Toolbar_setup' =>
            array (
                'Tool' =>
                array (
                    0 => 'setup_link',
                    1 => 'setup_link',
                    2 => 'setup_link',
                    3 => 'setup_link',
                    4 => 'setup_link',
                    ),
                ),
            'Tool_setup_link' =>
            array (
                'title' => '',
                'link_icon' => '',
                'url' => '',
                ),
            'Tool_setup_link_description' =>
            array (
                'title' => 'Title',
                'link_icon' => 'Icon',
                'url' => 'URL',
                ),
            'Tool_setup_setup_link_1' =>
            array (
                'title' => 'Classes',
                'link_icon' => 'classes.png',
                'url' => '/class/grouplist',
                ),
            'Tool_setup_setup_link_2' =>
            array (
                'title' => 'Cache',
                'link_icon' => 'cache.png',
                'url' => '/setup/cache',
                ),
            'Tool_setup_setup_link_3' =>
            array (
                'title' => 'URL translator',
                'link_icon' => 'url_translator.png',
                'url' => '/content/urltranslator',
                ),
            'Tool_setup_setup_link_4' =>
            array (
                'title' => 'Common ini settings',
                'link_icon' => 'common_ini_settings.png',
                'url' => '/content/edit/52',
                ),
            'Tool_setup_setup_link_5' =>
            array (
                'title' => 'Look and feel',
                'link_icon' => 'look_and_feel.png',
                'url' => '/content/edit/54',
                ),
            ),
             );
    return $toolbar;
}

function eZSetupOverrideINISettings( $siteType )
{
    return array (
        'name' => 'override.ini',
        'settings' =>
        array (
            'full_folder' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/folder.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'folder',
                    ),
                ),
            'line_folder' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/folder.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'folder',
                    ),
                ),
            'embed_folder_list' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/folder_list.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'folder',
                    'classification' => 'list',
                    ),
                ),
            'embed_folder_subtree' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/folder_subtree.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'folder',
                    'classification' => 'subtreelist',
                    ),
                ),
            'embed_folder_contentlist' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/folder_contentlist.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'folder',
                    ),
                ),
            'edit_user' =>
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/user.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'user',
                    ),
                ),
            'article_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/article.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'article',
                    ),
                ),
            'article_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/article.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'article',
                    ),
                ),
            'article_listitem' =>
            array (
                'Source' => 'node/view/listitem.tpl',
                'MatchFile' => 'listitem/article.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'article',
                    ),
                ),
            'article_embed' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/article.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'article',
                    ),
                ),
            'class_image' =>
            array (
                'Source' => 'content/datatype/view/ezobjectrelation.tpl',
                'MatchFile' => 'datatype/ezobjectrelation/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'article',
                    ),
                ),
            'full_comment' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/comment.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class' => '13',
                    ),
                ),
            'line_comment' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/comment.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'comment',
                    ),
                ),
            'edit_comment' =>
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/comment.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'comment',
                    ),
                ),
            'file_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/file.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'file',
                    ),
                ),
            'file_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/file.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'file',
                    ),
                ),
            'edit_file' =>
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/file.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'file',
                    ),
                ),
            'embed_file' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/file.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'file',
                    ),
                ),
            'file_listitem' =>
            array (
                'Source' => 'node/view/listitem.tpl',
                'MatchFile' => 'listitem/file.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'file',
                    ),
                ),
            'file_binaryfile' =>
            array (
                'Source' => 'content/datatype/view/ezbinaryfile.tpl',
                'MatchFile' => 'datatype/ezbinaryfile.tpl',
                'Subdir' => 'templates',
                ),
            'full_link' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/link.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'link',
                    ),
                ),
            'line_link' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/link.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'link',
                    ),
                ),
            'image_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image',
                    ),
                ),
            'image_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image',
                    ),
                ),
            'image_galleryline' =>
            array (
                'Source' => 'node/view/galleryline.tpl',
                'MatchFile' => 'galleryline/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image',
                    ),
                ),
            'image_galleryslide' =>
            array (
                'Source' => 'node/view/galleryslide.tpl',
                'MatchFile' => 'galleryslide/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image',
                    ),
                ),
            'image_listitem' =>
            array (
                'Source' => 'node/view/listitem.tpl',
                'MatchFile' => 'listitem/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image',
                    ),
                ),
            'image_embed' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image',
                    ),
                ),
            'text_linked_image' =>
            array (
                'Source' => 'content/view/text_linked.tpl',
                'MatchFile' => 'textlinked/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class' => '5',
                    ),
                ),
            'flash_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/flash.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'flash',
                    ),
                ),
            'flash_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/flash.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'flash',
                    ),
                ),
            'embed_flash' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/flash.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'flash',
                    ),
                ),
            'quicktime_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/flash.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'quicktime',
                    ),
                ),
            'quicktime_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/quicktime.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'quicktime',
                    ),
                ),
            'embed_quicktime' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/quicktime.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'quicktime',
                    ),
                ),
            'windows_media_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/windows_media.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'windows_media',
                    ),
                ),
            'windows_media_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/windows_media.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'windows_media',
                    ),
                ),
            'embed_windows_media' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/windows_media.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'windows_media',
                    ),
                ),
            'real_video_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/real_video.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'real_video',
                    ),
                ),
            'real_video_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/real_video.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'real_video',
                    ),
                ),
            'embed_real_video' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/real_video.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'real_video',
                    ),
                ),
            'forum_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/forum.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum',
                    ),
                ),
            'forum_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/forum.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum',
                    ),
                ),
            'forum_embed' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/forum.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum',
                    ),
                ),
            'forum_topic_edit' =>
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/forum_topic.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_topic',
                    ),
                ),
            'forum_topic_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/forum_topic.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_topic',
                    ),
                ),
            'forum_topic_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/forum_topic.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_topic',
                    ),
                ),
            'forum_reply_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/forum_reply.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_reply',
                    ),
                ),
            'forum_reply_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/forum_reply.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_reply',
                    ),
                ),
            'forum_reply_edit' => 
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/forum_reply.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'forum_reply',
                    ),
                ),
            'weblog_full' => 
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/weblog.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'weblog',
                    ),
                ),
            'weblog_line' => 
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/weblog.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'weblog',
                    ),
                ),
            'weblog_edit' => 
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/weblog.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'weblog',
                    ),
                ),
            'product_full' => 
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/product.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'product',
                    ),
                ),
            'product_line' => 
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/product.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'product',
                    ),
                ),
            'product_embed' => 
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/product.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'product',
                    ),
                ),
            'product_listitem' => 
            array (
                'Source' => 'node/view/listitem.tpl',
                'MatchFile' => 'listitem/product.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'product',
                    ),
                ),
            'review_line' => 
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/review.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'review',
                    ),
                ),
            'review_edit' => 
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/review.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'review',
                    ),
                ),
            'gallery_full' => 
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/gallery.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'gallery',
                    ),
                ),
            'gallery_slideshow' => 
            array (
                'Source' => 'node/view/slideshow.tpl',
                'MatchFile' => 'slideshow/gallery.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'gallery',
                    ),
                ),
            'gallery_line' => 
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/gallery.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'gallery',
                    ),
                ),
            'gallery_embed' => 
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/gallery.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'gallery',
                    ),
                ),
            'poll_full' => 
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/poll.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'poll',
                    ),
                ),
            'poll_line' => 
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/poll.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'poll',
                    ),
                ),
            'poll_embed' => 
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/poll.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'poll',
                    ),
                ),
            'poll_result' => 
            array (
                'Source' => 'content/collectedinfo/poll.tpl',
                'MatchFile' => 'collectedinfo/poll_result.tpl',
                'Subdir' => 'templates',
                ),
            'person_full' => 
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/person.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'person',
                    ),
                ),
            'person_line' => 
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/person.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'person',
                    ),
                ),
            'person_embed' => 
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/person.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'person',
                    ),
                ),
            'edit_person' => 
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/person.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'person',
                    ),
                ),
            'company_full' => 
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/company.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'company',
                    ),
                ),
            'company_line' => 
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/company.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'company',
                    ),
                ),
            'company_embed' => 
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/company.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'company',
                    ),
                ),
            'edit_company' => 
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/company.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'company',
                    ),
                ),
            'feedback_form_full' => 
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/feedback_form.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'feedback_form',
                    ),
                ),
            'feedback_form_line' => 
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/feedback_form.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'feedback_form',
                    ),
                ),
            'feedback_form_mail' => 
            array (
                'Source' => 'content/collectedinfomail/form.tpl',
                'MatchFile' => 'collectedinfomail/feedback_form.tpl',
                'Subdir' => 'templates',
                'Match' => 
                array (
                    'class_identifier' => 'feedback_form',
                    ),
                ),
            'factbox' => 
            array (
                'Source' => 'content/datatype/view/ezxmltags/factbox.tpl',
                'MatchFile' => 'datatype/ezxmltext/factbox.tpl',
                'Subdir' => 'templates',
                ),
            'quote' => 
            array (
                'Source' => 'content/datatype/view/ezxmltags/quote.tpl',
                'MatchFile' => 'datatype/ezxmltext/quote.tpl',
                'Subdir' => 'templates',
                ),
            'price' => 
            array (
                'Source' => 'content/datatype/view/ezprice.tpl',
                'MatchFile' => 'datatype/price.tpl',
                'Subdir' => 'templates',
                ),
            'matrix' => 
            array (
                'Source' => 'content/datatype/view/ezmatrix.tpl',
                'MatchFile' => 'datatype/ezmatrix/view.tpl',
                'Subdir' => 'templates',
                ),
            'edit_matrix' => 
            array (
                'Source' => 'content/datatype/edit/ezmatrix.tpl',
                'MatchFile' => 'datatype/ezmatrix/edit.tpl',
                'Subdir' => 'templates',
                ),
            ),
        );
}

function eZSetupINISettings( $siteType )
{
    $settings = array();
    $settings[] = eZSetupForumINISettings( $siteType );
    $settings[] = eZSetupMenuINISettings( $siteType );
    $settings[] = eZSetupOverrideINISettings( $siteType );
    $settings[] = eZSetupToolbarINISettings( $siteType );
    $settings[] = eZSetupSiteINISettings( $siteType );

    return $settings;
}

function eZSetupAdminINISettings( $siteType )
{
    $settings = array();
    $settings[] = eZSetupAdminToolbarINISettings( $siteType );

    return $settings;
}

?>
