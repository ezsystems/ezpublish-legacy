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
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

function eZSetupRemoteClassID( $parameters, $remoteID )
{
    $remoteMap = $parameters['class_remote_map'];
    if ( isset( $remoteMap[$remoteID] ) )
        return $remoteMap[$remoteID]['id'];
    return false;
}

function eZSetupRemoteClassIdentifier( $parameters, $remoteID )
{
    $remoteMap = $parameters['class_remote_map'];
    if ( isset( $remoteMap[$remoteID] ) )
        return $remoteMap[$remoteID]['identifier'];
    return false;
}

function eZSetupRemoteNodeID( $parameters, $remoteID )
{
    $remoteMap = $parameters['node_remote_map'];
    if ( isset( $remoteMap[$remoteID] ) )
        return $remoteMap[$remoteID];
    return false;
}

function eZSetupRemoteObjectID( $parameters, $remoteID )
{
    $remoteMap = $parameters['object_remote_map'];
    if ( isset( $remoteMap[$remoteID] ) )
        return $remoteMap[$remoteID];
    return false;
}

function eZSetupTypes()
{
    return array( 'news' => array( 'name' => 'News',
                                   'identifier' => 'news',
                                   'summary' => 'With the eZ publish open source News site solution you can publish articles and news and communicate with your readers in a online newspaper containing the features you would normally need.',
                                   'thumbnail' => 'news.png' ),
                  'blog' => array( 'name' => 'Blog',
                                   'identifier' => 'blog',
                                   'summary' => 'The eZ publish Web log can be used as a personal web page. This open source Web log solution is ideal for all kinds of users but is mostly used for sharing information between companies, clubs, groups, friends etc. Features like Latest blog list, Login, Calendar, Poll, Link list is standard.',
                                   'thumbnail' => 'blog.png' ),
                  'corporate' => array( 'name' => 'Corporate',
                                        'identifier' => 'corporate',
                                        'summary' => 'The eZ publish Corporate website is a good starting point if you want to present your company in an easy and professional way. Let your customers, partners and employees find information about your company and products with this open source Corporate solution.',
                                        'thumbnail' => 'corporate.png' ),
                  'forum' => array( 'name' => 'Forum',
                                    'identifier' => 'forum',
                                    'summary' => 'The eZ publish Forum is a solution ideal for users that want to have a forum for discussions, views and ideas within the community. This open source Forum solution is also ideal for companies, clubs, groups, friend etc with features like Latest discussions, Latest posts, Number of posts, Number of replies, Sticky and User accounts with signature.',
                                    'thumbnail' => 'forum.png' ),
                  'gallery' => array( 'name' => 'Gallery',
                                      'identifier' => 'gallery',
                                      'summary' => 'With the eZ publish Gallery you can have an online image gallery and share all your images, pictures and memories with your friend and family. You will get the functionality you need to publish news and pictures like Latest news, Picture list, File and picture upload and a Slideshow.',
                                      'thumbnail' => 'gallery.png' ),
                  'intranet' => array( 'name' => 'Intranet',
                                       'identifier' => 'intranet',
                                       'summary' => 'Using the eZ publish Intranet you will get an Intranet up and running in no time. You will get the needed functionality publishing news, files, pictures, links and for adding contacts in your intranet or extranet with this open source Intranet solution.',
                                       'thumbnail' => 'intranet.png' ),
                  'shop' => array( 'name' => 'Shop',
                                   'identifier' => 'shop',
                                   'summary' => 'With the eZ publish Webshop you will get the functionality you need to publish and sell your products and communicate with your customers. You will get customer lists, order view, product list per customer and monthly/early sales statistics and a VAT system.',
                                   'thumbnail' => 'shop.png' ),
                  'plain' => array( 'name' => 'Plain',
                                    'identifier' => 'plain',
                                    'summary' => "Stripped install.\nContains no special toolbar or menu choices",
                                    'thumbnail' => 'plain.png' )
                  );
}

function eZSetupFunctionality( $siteType )
{
    if ( $siteType == 'blog' )
    {
        return array( 'required' => array( 'weblog' ),
                      'recommended' => array(),
                      'theme' => 't03' );
    }
    else if ( $siteType == 'news' )
    {
        return array( 'required' => array( 'news' ),
                      'recommended' => array( 'media', 'galler' ),
                      'theme' => 't01' );
    }
    else if ( $siteType == 'corporate' )
    {
        return array( 'required' => array( 'news' ),
                      'recommended' => array( 'contact_us' ),
                      'theme' => 't01' );
    }
    else if ( $siteType == 'forum' )
    {
        return array( 'required' => array( 'forum' ),
                      'recommended' => array( 'poll' ),
                      'theme' => 't08' );
    }
    else if ( $siteType == 'gallery' )
    {
        return array( 'required' => array( 'gallery' ),
                      'recommended' => array(),
                      'theme' => 't20' );
    }
    else if ( $siteType == 'intranet' )
    {
        return array( 'required' => array(),
                      'recommended' => array( 'news', 'contacts', 'files', 'media' ),
                      'theme' => 't19' );
    }
    else if ( $siteType == 'shop' )
    {
        return array( 'required' => array( 'products' ),
                      'recommended' => array( 'contact_us' ),
                      'theme' => 't04' );
    }
    else
    {
        return array( 'required' => array(),
                      'recommended' => array(),
                      'theme' => 't02' );
    }
}

function eZSetupForumINISettings( $siteType, $parameters, $isAdmin )
{
    return array( 'name' => 'forum.ini',
                  'settings' => array( 'ForumSettings' => array( 'StickyUserGroupArray' => array( 12 ) ) ) );
}

function eZSetupSiteINISettings( $siteType, $parameters, $isAdmin )
{
    $settings = array();
    if ( $siteType == 'intranet' )
    {
        $settings['SiteAccessSettings'] = array_merge( $settings['SiteAccessSettings'], array( 'RequireUserLogin' => 'true' ) );
        $settings['SiteSettings'] = array( 'LoginPage' => 'custom' );
    }
    else
    {
        $settings['SiteAccessSettings'] = array_merge( $settings['SiteAccessSettings'], array( 'RequireUserLogin' => 'false' ) );
        $settings['SiteSettings'] = array( 'LoginPage' => 'embedded' );
    }
    $settings['ContentSettings'] = array( 'CachedViewPreferences' => array( 'full' => 'admin_navigation_content=0;admin_navigation_information=0;admin_navigation_languages=0;admin_navigation_locations=0;admin_navigation_relations=0;admin_navigation_roles=0;admin_navigation_policies=0;' ) );
    // ViewPreferences[full]=admin_navigation_content=0;admin_navigation_information=0;admin_navigation_languages=0;admin_navigation_locations=0;admin_navigation_relations=0;admin_navigation_roles=0;admin_navigation_policies=0;
    return array( 'name' => 'site.ini',
                  'settings' => $settings );
}

function eZSetupMenuINISettings( $siteType, $parameters, $isAdmin )
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
                  'reset_arrays' => true,
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

function eZSetupToolbarINISettings( $siteType, $parameters )
{
    $nodeRemoteMap = $parameters['node_remote_map'];
    $classRemoteMap = $parameters['class_remote_map'];
    if ( $siteType == 'blog' )
    {
        $nodeSubtree = 'weblog';
        $nodeID = 2;
        $createTitle = 'New weblog';
        if ( isset( $nodeRemoteMap['712e4b066aebe1431f8612bf67436d58'] ) )
            $nodeID = $nodeRemoteMap['712e4b066aebe1431f8612bf67436d58'];
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $node =& eZContentObjectTreeNode::fetch( $nodeID );
        if ( is_object( $node ) )
        {
            $nodeSubtree = $node->attribute( 'path_identification_string' );
        }
        $classIdentifier = 'weblog';
        if ( isset( $classRemoteMap['b3492bd3cb20be996408f5c16aa68c12'] ) )
            $classIdentifier = $classRemoteMap['b3492bd3cb20be996408f5c16aa68c12']['identifier'];
        $toolbar = array( 'name' => 'toolbar.ini',
                          'reset_arrays' => true,
                          'settings' => array( 'Toolbar_right' => array( 'Tool' => array( 'calendar', 'create_object', 'searchbox', 'users' ) ),
                                               'Toolbar_top' => array( 'Tool' => array( 'login' ) ),
                                               'Toolbar_bottom' => array( 'Tool' => array() ),
                                               'Tool_right_calendar_1' => array( 'show_subtree' => $nodeSubtree,
                                                                                 'show_classidentifiers' => $classIdentifier ),
                                               'Tool_right_create_object_2' => array( 'title' => $createTitle,
                                                                                      'show_subtree' => $nodeSubtree,
                                                                                      'type_classidentifier' => $classIdentifier,
                                                                                      'placement_node' => $nodeID ) ) );
    }
    else if ( $siteType == 'forum' )
    {
        $nodeID = 2;
        $nodeListTitle = 'Latest topic';
        if ( isset( $nodeRemoteMap['e0845f2d5210cb3a9732249926cca224'] ) )
            $nodeID = $nodeRemoteMap['e0845f2d5210cb3a9732249926cca224'];

        $nodeSubtree = 'weblog';
        $toolbar = array( 'name' => 'toolbar.ini',
                          'reset_arrays' => true,
                          'settings' => array( 'Toolbar_right' => array( 'Tool' => array( 'login', 'node_list', 'searchbox', 'users' ) ),
                                               'Toolbar_top' => array( 'Tool' => array( 'notification' ) ),
                                               'Toolbar_bottom' => array( 'Tool' => array() ),
                                               'Tool_right_node_list_2' => array( 'show_subtree' => false,
                                                                                  'title' => $nodeListTitle,
                                                                                  'parent_node' => $nodeID,
                                                                                  'sort_by' => 'modified_subnode' ),
                                               ) );
    }
    else if ( $siteType == 'gallery' )
    {
        $nodeID = 2;
        $nodeListTitle = 'New images';
        if ( isset( $nodeRemoteMap['ee24204c9b90600531bca6bea97b558c'] ) )
            $nodeID = $nodeRemoteMap['ee24204c9b90600531bca6bea97b558c'];

        $nodeSubtree = 'weblog';
        $toolbar = array( 'name' => 'toolbar.ini',
                          'reset_arrays' => true,
                          'settings' => array( 'Toolbar_right' => array( 'Tool' => array( 'node_list', 'searchbox', 'users' ) ),
                                               'Toolbar_top' => array( 'Tool' => array( 'login', 'searchbox' ) ),
                                               'Toolbar_bottom' => array( 'Tool' => array() ),
                                               'Tool_right_node_list_1' => array( 'show_subtree' => false,
                                                                                  'title' => $nodeListTitle,
                                                                                  'parent_node' => $nodeID ),
                                               ) );
    }
    else if ( $siteType == 'news' )
    {
        $nodeID = 2;
        $nodeListTitle = 'Latest news';
        if ( isset( $nodeRemoteMap['fade9b4882ffea151a9b10043ff7cb25'] ) )
            $nodeID = $nodeRemoteMap['fade9b4882ffea151a9b10043ff7cb25'];

        $nodeSubtree = 'weblog';
        $toolbar = array( 'name' => 'toolbar.ini',
                          'reset_arrays' => true,
                          'settings' => array( 'Toolbar_right' => array( 'Tool' => array( 'node_list', 'users' ) ),
                                               'Toolbar_top' => array( 'Tool' => array( 'searchbox' ) ),
                                               'Toolbar_bottom' => array( 'Tool' => array() ),
                                               'Tool_right_node_list_1' => array( 'show_subtree' => false,
                                                                                  'title' => $nodeListTitle,
                                                                                  'parent_node' => $nodeID ),
                                               ) );
    }
    else if ( $siteType == 'shop' )
    {
        $nodeSubtree = 'products';
        $nodeID = 2;
        $title = 'Latest products';
        if ( isset( $nodeRemoteMap['1bd02326e11c6b7fb2d14324c47b5b9a'] ) )
            $nodeID = $nodeRemoteMap['1bd02326e11c6b7fb2d14324c47b5b9a'];
        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $node =& eZContentObjectTreeNode::fetch( $nodeID );
        if ( is_object( $node ) )
        {
            $nodeSubtree = $node->attribute( 'path_identification_string' );
        }
        $toolbar = array( 'name' => 'toolbar.ini',
                          'reset_arrays' => true,
                          'settings' => array( 'Toolbar_right' => array( 'Tool' => array( 'node_list', 'basket', 'searchbox', 'best_seller' ) ),
                                               'Toolbar_top' => array( 'Tool' => array( 'login' ) ),
                                               'Toolbar_bottom' => array( 'Tool' => array() ),
                                               'Tool_right_node_list_1' => array( 'parent_node' => $nodeID,
                                                                                  'title' => $title,
                                                                                  'show_subtree' => $nodeSubtree,
                                                                                  'limit' => 6 ) ) );
    }
    else
    {
        $toolbar = array( 'name' => 'toolbar.ini',
                          'reset_arrays' => true,
                          'settings' => array( 'Toolbar_right' => array( 'Tool' => array( 'node_list' ) ),
                                               'Toolbar_top' => array( 'Tool' => array( 'login', 'searchbox' ) ),
                                               'Toolbar_bottom' => array( 'Tool' => array() ),
                                               'Tool_right_node_list_1' => array( 'parent_node' => '2',
                                                                                  'title' => 'Latest',
                                                                                  'show_subtree' => '',
                                                                                  'limit' => 5 ) ) );
    }
    return $toolbar;
}

function eZSetupAdminToolbarINISettings( $siteType, $parameters )
{
    $toolbar = array (
        'name' => 'toolbar.ini',
        'reset_arrays' => true,
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
                'title' => 'Settings',
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

function eZSetupAdminOverrideINISettings( $siteType, $parameters )
{
    return array (
        'name' => 'override.ini',
        'settings' =>
        array (
            'article' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/article.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'article',
                    ),
                ),
            'comment' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/comment.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'comment',
                    ),
                ),
            'company' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/company.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'company',
                    ),
                ),
            'feedback_form' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/feedback_form.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'feedback_form',
                    ),
                ),
            'file' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/file.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'file',
                    ),
                ),
            'flash' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/flash.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'flash',
                    ),
                ),
            'folder' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/folder.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'folder',
                    ),
                ),
            'forum' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/forum.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum',
                    ),
                ),
            'forum_topic' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/forum_topic.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_topic',
                    ),
                ),
            'forum_reply' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/forum_reply.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_reply',
                    ),
                ),
            'gallery' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/gallery.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'gallery',
                    ),
                ),
            'image' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image',
                    ),
                ),
            'link' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/link.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'link',
                    ),
                ),
            'person' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/person.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'person',
                    ),
                ),
            'poll' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/poll.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'poll',
                    ),
                ),
            'product' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/product.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'product',
                    ),
                ),
            'review' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/review.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'review',
                    ),
                ),
            'quicktime' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/quicktime.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'quicktime',
                    ),
                ),
            'real_video' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/real_video.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'real_video',
                    ),
                ),
            'weblog' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/weblog.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'weblog',
                    ),
                ),
            'windows_media' =>
            array (
                'Source' => 'node/view/admin_preview.tpl',
                'MatchFile' => 'admin_preview/windows_media.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'windows_media',
                    ),
                ),
            'thumbnail_image' =>
            array (
                'Source' => 'node/view/thumbnail.tpl',
                'MatchFile' => 'thumbnail/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image',
                    ),
                ),
            'window_controls' =>
            array (
                'Source' => 'window_controls.tpl',
                'MatchFile' => 'window_controls_user.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'navigation_part_identifier' => 'ezusernavigationpart',
                    ),
                ),
            'windows' =>
            array (
                'Source' => 'windows.tpl',
                'MatchFile' => 'windows_user.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'navigation_part_identifier' => 'ezusernavigationpart',
                    ),
                ),
            'embed_image' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed_image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image',
                    ),
                ),
            ),
        );
}
function eZSetupOverrideINISettings( $siteType, $parameters )
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
                    'class_identifier' => 'folder'
                    ),
                ),
            'line_folder' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/folder.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'folder'
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
                    'classification' => 'list'
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
                    'classification' => 'subtreelist'
                    ),
                ),
            'embed_folder_contentlist' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/folder_contentlist.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'folder'
                    ),
                ),
            'edit_user' =>
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/user.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'user'
                    ),
                ),
            'article_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/article.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'article'
                    ),
                ),
            'article_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/article.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'article'
                    ),
                ),
            'article_listitem' =>
            array (
                'Source' => 'node/view/listitem.tpl',
                'MatchFile' => 'listitem/article.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'article'
                    ),
                ),
            'article_embed' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/article.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'article'
                    ),
                ),
            'class_image' =>
            array (
                'Source' => 'content/datatype/view/ezobjectrelation.tpl',
                'MatchFile' => 'datatype/ezobjectrelation/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'article'
                    ),
                ),
            'full_comment' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/comment.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class' => '13'
                    ),
                ),
            'line_comment' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/comment.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'comment'
                    ),
                ),
            'edit_comment' =>
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/comment.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'comment'
                    ),
                ),
            'file_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/file.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'file'
                    ),
                ),
            'file_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/file.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'file'
                    ),
                ),
            'edit_file' =>
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/file.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'file'
                    ),
                ),
            'embed_file' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/file.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'file'
                    ),
                ),
            'file_listitem' =>
            array (
                'Source' => 'node/view/listitem.tpl',
                'MatchFile' => 'listitem/file.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'file'
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
                    'class_identifier' => 'link'
                    ),
                ),
            'listitem_link' =>
            array (
                'Source' => 'node/view/listitem.tpl',
                'MatchFile' => 'listitem/link.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'link'
                    ),
                ),
            'line_link' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/link.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'link'
                    ),
                ),
            'image_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image'
                    ),
                ),
            'image_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image'
                    ),
                ),
            'image_galleryline' =>
            array (
                'Source' => 'node/view/galleryline.tpl',
                'MatchFile' => 'galleryline/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image'
                    ),
                ),
            'image_galleryslide' =>
            array (
                'Source' => 'node/view/galleryslide.tpl',
                'MatchFile' => 'galleryslide/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image'
                    ),
                ),
            'image_listitem' =>
            array (
                'Source' => 'node/view/listitem.tpl',
                'MatchFile' => 'listitem/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image'
                    ),
                ),
            'image_embed' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'image'
                    ),
                ),
            'text_linked_image' =>
            array (
                'Source' => 'content/view/text_linked.tpl',
                'MatchFile' => 'textlinked/image.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class' => '5'
                    ),
                ),
            'flash_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/flash.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'flash'
                    ),
                ),
            'flash_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/flash.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'flash'
                    ),
                ),
            'embed_flash' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/flash.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'flash'
                    ),
                ),
            'quicktime_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/quicktime.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'quicktime'
                    ),
                ),
            'quicktime_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/quicktime.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'quicktime'
                    ),
                ),
            'embed_quicktime' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/quicktime.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'quicktime'
                    ),
                ),
            'windows_media_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/windows_media.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'windows_media'
                    ),
                ),
            'windows_media_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/windows_media.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'windows_media'
                    ),
                ),
            'embed_windows_media' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/windows_media.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'windows_media'
                    ),
                ),
            'real_video_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/real_video.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'real_video'
                    ),
                ),
            'real_video_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/real_video.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'real_video'
                    ),
                ),
            'embed_real_video' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/real_video.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'real_video'
                    ),
                ),
            'forum_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/forum.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum'
                    ),
                ),
            'forum_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/forum.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum'
                    ),
                ),
            'forum_embed' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/forum.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum'
                    ),
                ),
            'forum_topic_edit' =>
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/forum_topic.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_topic'
                    ),
                ),
            'forum_topic_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/forum_topic.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_topic'
                    ),
                ),
            'forum_topic_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/forum_topic.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_topic'
                    ),
                ),
            'forum_topic_listitem' =>
            array (
                'Source' => 'node/view/listitem.tpl',
                'MatchFile' => 'listitem/forum_topic.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_topic'
                    ),
                ),
            'forum_reply_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/forum_reply.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_reply'
                    ),
                ),
            'forum_reply_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/forum_reply.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_reply'
                    ),
                ),
            'forum_reply_edit' =>
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/forum_reply.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'forum_reply'
                    ),
                ),
            'weblog_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/weblog.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'weblog'
                    ),
                ),
            'weblog_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/weblog.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'weblog'
                    ),
                ),
            'weblog_edit' =>
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/weblog.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'weblog'
                    ),
                ),
            'product_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/product.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'product'
                    ),
                ),
            'product_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/product.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'product'
                    ),
                ),
            'product_embed' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/product.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'product'
                    ),
                ),
            'product_listitem' =>
            array (
                'Source' => 'node/view/listitem.tpl',
                'MatchFile' => 'listitem/product.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'product'
                    ),
                ),
            'review_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/review.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'review'
                    ),
                ),
            'review_edit' =>
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/review.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'review'
                    ),
                ),
            'gallery_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/gallery.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'gallery'
                    ),
                ),
            'gallery_slideshow' =>
            array (
                'Source' => 'node/view/slideshow.tpl',
                'MatchFile' => 'slideshow/gallery.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'gallery'
                    ),
                ),
            'gallery_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/gallery.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'gallery'
                    ),
                ),
            'gallery_embed' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/gallery.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'gallery'
                    ),
                ),
            'poll_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/poll.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'poll'
                    ),
                ),
            'poll_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/poll.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'poll'
                    ),
                ),
            'poll_embed' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/poll.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'poll'
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
                    'class_identifier' => 'person'
                    ),
                ),
            'person_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/person.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'person'
                    ),
                ),
            'person_embed' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/person.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'person'
                    ),
                ),
            'edit_person' =>
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/person.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'person'
                    ),
                ),
            'company_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/company.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'company'
                    ),
                ),
            'company_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/company.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'company'
                    ),
                ),
            'company_embed' =>
            array (
                'Source' => 'content/view/embed.tpl',
                'MatchFile' => 'embed/company.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'company'
                    ),
                ),
            'edit_company' =>
            array (
                'Source' => 'content/edit.tpl',
                'MatchFile' => 'edit/company.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'company'
                    ),
                ),
            'feedback_form_full' =>
            array (
                'Source' => 'node/view/full.tpl',
                'MatchFile' => 'full/feedback_form.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'feedback_form'
                    ),
                ),
            'feedback_form_line' =>
            array (
                'Source' => 'node/view/line.tpl',
                'MatchFile' => 'line/feedback_form.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'feedback_form'
                    ),
                ),
            'feedback_form_mail' =>
            array (
                'Source' => 'content/collectedinfomail/form.tpl',
                'MatchFile' => 'collectedinfomail/feedback_form.tpl',
                'Subdir' => 'templates',
                'Match' =>
                array (
                    'class_identifier' => 'feedback_form'
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
            'pdf_article_main' =>
            array (
                'Source' => 'node/view/pdf.tpl',
                'MatchFile' => 'pdf/article/main.tpl',
                'Match' =>
                array (
                    'class_identifier' => 'article'
                    ),
                'Subdir' => 'templates'
                ),
            'pdf_article_title' =>
            array (
                'Source' => 'content/datatype/pdf/ezstring.tpl',
                'MatchFile' => 'pdf/article/title.tpl',
                'Match' =>
                array (
                    'class_identifier' => 'article',
                    'attribute_identifier' => 'title'
                    ),
                'Subdir' => 'templates'
                ),
            'pdf_article_author' =>
            array (
                'Source' => 'content/datatype/pdf/ezauthor.tpl',
                'MatchFile' => 'pdf/article/author.tpl',
                'Match' =>
                array (
                    'class_identifier' => 'article',
                    'attribute_identifier' => 'author'
                    ),
                'Subdir' => 'templates'
                ),
            'pdf_article_xml_headers' =>
            array (
                'Source' => 'content/datatype/pdf/ezxmltags/header.tpl',
                'MatchFile' => 'pdf/article/xml_header.tpl',
                'Match' =>
                array (
                    'class_identifier' => 'article'
                    ),
                'Subdir' => 'templates'
                ),
            'pdf_article_xml_paragraph' =>
            array (
                'Source' => 'content/datatype/pdf/ezxmltags/paragraph.tpl',
                'MatchFile' => 'pdf/article/xml_paragraph.tpl',
                'Match' =>
                array (
                    'class_identifier' => 'article'
                    ),
                'Subdir' => 'templates'
                ),
            'pdf_article_footer' =>
            array (
                'Source' => 'content/pdf/footer.tpl',
                'MatchFile' => 'pdf/article/footer.tpl',
                'Match' =>
                array (
                    'class_identifier' => 'article'
                    ),
                'Subdir' => 'templates'
                ),
            'pdf_article_embed_image' =>
            array (
                'Source' => 'content/pdf/embed.tpl',
                'MatchFile' => 'pdf/article/image.tpl',
                'Match' =>
                array (
                    'class_identifier' => 'article'
                    ),
                'Subdir' => 'templates'
                )
            )
        );
}

function eZSetupImageINISettings( $siteType, $parameters, $isAdmin )
{
    $image = array( 'name' => 'image.ini',
                    'reset_arrays' => true,
                    'settings' => array( 'AliasSettings' => array( 'AliasList' => array( 'small', 'medium', 'listitem', 'articleimage', 'articlethumbnail', 'gallerythumbnail', 'imagelarge', 'large', 'rss', 'logo' ) ),
                                         'logo' => array( 'Reference' => false,
                                                          'Filters' => array( 'geometry/scaledownonly=250;58' ) ),
                                         'small' => array( 'Reference' => false,
                                                           'Filters' => array( 'geometry/scaledownonly=100;160' ) ),
                                         'medium' => array( 'Reference' => false,
                                                            'Filters' => array( 'geometry/scaledownonly=200;290' ) ),
                                         'large' => array( 'Reference' => false,
                                                            'Filters' => array( 'geometry/scaledownonly=360;440' ) ),
                                         'listitem' => array( 'Reference' => false,
                                                            'Filters' => array( 'geometry/scaledownonly=110;170' ) ),
                                         'articleimage' => array( 'Reference' => false,
                                                            'Filters' => array( 'geometry/scaledownonly=170;350' ) ),
                                         'articlethumbnail' => array( 'Reference' => false,
                                                            'Filters' => array( 'geometry/scaledownonly=70;150' ) ),
                                         'gallerythumbnail' => array( 'Reference' => false,
                                                            'Filters' => array( 'geometry/scaledownonly=70;150' ) ),
                                         'imagelarge' => array( 'Reference' => false,
                                                            'Filters' => array( 'geometry/scaledownonly=360;440' ) ),
                                         'rss' => array( 'Reference' => false,
                                                            'Filters' => array( 'geometry/scale=88;31' ) )
                                         ) );

    return $image;
}

function eZSetupContentINISettings( $siteType, $parameters, $isAdmin )
{
    $designList = $parameters['design_list'];
    $allowVersions = false;
    if ( $parameters['is_admin'] )
        $allowVersions = true;
    $image = array( 'name' => 'content.ini',
                    'reset_arrays' => true,
                    'settings' => array( 'VersionView' => array( 'AvailableSiteDesignList' => $designList ),
                                         'DefaultPreviewDesign' => $parameters['preview_design'],
                                         'AllowChangeButtons' => 'disabled',
                                         'AllowVersionsButton' => ( $allowVersions ? 'enbled' : 'disabled' ) ) );

    return $image;
}

function eZSetupForumRoles( &$roles, $siteType, $parameters )
{
    if ( !in_array( 'forum', $parameters['extra_functionality'] ) )
        return false;
    $forumClassID = eZSetupRemoteClassID( $parameters, 'b241f924b96b267153f5f55904e0675a' );
    $forumReplyClassID = eZSetupRemoteClassID( $parameters, '80ee42a66b2b8b6ee15f5c5f4b361562' );
    $forumTopicClassID = eZSetupRemoteClassID( $parameters, '71f99c516743a33562c3893ef98c9b60' );
    $folderClassID = eZSetupRemoteClassID( $parameters, 'a3d405b81be900468eb153d774f4f0d2' );

    $guestAccountsID = eZSetupRemoteObjectID( $parameters, '5f7f0bdb3381d6a461d8c29ff53d908f' );

    $sectionID = 1;
    $selfID = 1;
    // Status values taken from ezcontentobjectversion.php
    $statusDraft = 0; // EZ_VERSION_STATUS_DRAFT
    $statusPending = 2; // EZ_VERSION_STATUS_PENDING

    $roles[] = array( 'name' => 'Forum user',
                      'policies' => array( array( 'module' => 'content',
                                                  'function' => 'create',
                                                  'limitation' => array( 'Class' => array( $forumTopicClassID ),
                                                                         'Section' => array( $sectionID ),
                                                                         'ParentClass' => array( $forumClassID ) ) ),
                                           array( 'module'=> 'content',
                                                  'function' => 'edit',
                                                  'limitation' => array( 'Class' => array( $forumTopicClassID ),
                                                                         'Section' => array( $sectionID ) ) ),
                                           array( 'module' => 'content',
                                                  'function' => 'create',
                                                  'limitation' => array( 'Class' => array( $forumReplyClassID ),
                                                                         'Section' => array( $sectionID ),
                                                                         'ParentClass' => array( $forumTopicClassID ) ) ),
                                           array( 'module'=> 'content',
                                                  'function' => 'edit',
                                                  'limitation' => array( 'Class' => array( $forumReplyClassID ),
                                                                         'Section' => array( $sectionID ) ) ),
                                           array( 'module'=> 'content',
                                                  'function' => 'versionread',
                                                  'limitation' => array( 'Class' => array( $forumTopicClassID, $forumReplyClassID ),
                                                                         'Section' => array( $sectionID ),
                                                                         'Owner' => array( $selfID ),
                                                                         'Status' => array( $statusDraft, $statusPending ) ) ),
                                           array( 'module'=> 'user',
                                                  'function' => 'selfedit' ),
                                           array( 'module'=> 'user',
                                                  'function' => 'password' ),
                                           array( 'module'=> 'notification',
                                                  'function' => 'use' ) ),
                      'assignments' => array( array( 'user_id' => $guestAccountsID ) ) );
    $roles[] = array( 'name' => 'Forum administrator',
                      'policies' => array( array( 'module' => 'content',
                                                  'function' => 'create',
                                                  'limitation' => array( 'Class' => array( $forumClassID ),
                                                                         'Section' => array( $sectionID ),
                                                                         'ParentClass' => array( $folderClassID ) ) ),
                                           array( 'module'=> 'content',
                                                  'function' => 'edit',
                                                  'limitation' => array( 'Class' => array( $forumClassID ),
                                                                         'Section' => array( $sectionID ) ) ),
                                           array( 'module'=> 'content',
                                                  'function' => 'remove',
                                                  'limitation' => array( 'Class' => array( $forumClassID, $forumTopicClassID, $forumReplyClassID ),
                                                                         'Section' => array( $sectionID ) ) ),
                                           array( 'module'=> 'content',
                                                  'function' => 'versionread',
                                                  'limitation' => array( 'Class' => array( $forumClassID ),
                                                                         'Section' => array( $sectionID ) ) ) ) );
}

function eZSetupWeblogRoles( &$roles, $siteType, $parameters )
{
    if ( !in_array( 'weblog', $parameters['extra_functionality'] ) )
        return false;
    $weblogClassID = eZSetupRemoteClassID( $parameters, 'b3492bd3cb20be996408f5c16aa68c12' );
    $commentClassID = eZSetupRemoteClassID( $parameters, '000c14f4f475e9f2955dedab72799941' );

    $guestAccountsID = eZSetupRemoteObjectID( $parameters, '5f7f0bdb3381d6a461d8c29ff53d908f' );

    $sectionID = 1;
    $selfID = 1;
    // Status values taken from ezcontentobjectversion.php
    $statusDraft = 0; // EZ_VERSION_STATUS_DRAFT
    $statusPending = 2; // EZ_VERSION_STATUS_PENDING

    $roles[] = array( 'name' => 'Weblog user',
                      'policies' => array( array( 'module' => 'content',
                                                  'function' => 'create',
                                                  'limitation' => array( 'Class' => array( $commentClassID ),
                                                                         'Section' => array( $sectionID ),
                                                                         'ParentClass' => array( $weblogClassID ) ) ),
                                           array( 'module'=> 'content',
                                                  'function' => 'edit',
                                                  'limitation' => array( 'Class' => array( $commentClassID ),
                                                                         'Section' => array( $sectionID ) ) ),
                                           array( 'module'=> 'content',
                                                  'function' => 'versionread',
                                                  'limitation' => array( 'Class' => array( $commentClassID ),
                                                                         'Section' => array( $sectionID ),
                                                                         'Owner' => array( $selfID ),
                                                                         'Status' => array( $statusDraft, $statusPending ) ) ) ),
                      'assignments' => array( array( 'user_id' => $guestAccountsID ) ) );
}


function eZSetupINISettings( $siteType, $parameters )
{
    $parameters = array_merge( $parameters,
                               array( 'is_admin' => false ) );
    $settings = array();
    $settings[] = eZSetupForumINISettings( $siteType, $parameters, false );
    $settings[] = eZSetupMenuINISettings( $siteType, $parameters, false );
    $settings[] = eZSetupOverrideINISettings( $siteType, $parameters );
    $settings[] = eZSetupToolbarINISettings( $siteType, $parameters );
    $settings[] = eZSetupSiteINISettings( $siteType, $parameters, false );
    $settings[] = eZSetupImageINISettings( $siteType, $parameters, false );
    $settings[] = eZSetupContentINISettings( $siteType, $parameters, false );

    return $settings;
}

function eZSetupAdminINISettings( $siteType, $parameters )
{
    $parameters = array_merge( $parameters,
                               array( 'is_admin' => true ) );
    $settings = array();
    $settings[] = eZSetupAdminToolbarINISettings( $siteType, $parameters );
    $settings[] = eZSetupAdminOverrideINISettings( $siteType, $parameters );
    $settings[] = eZSetupSiteINISettings( $siteType, $parameters, true );
    $settings[] = eZSetupContentINISettings( $siteType, $parameters, true );

    return $settings;
}

function eZSetupRoles( $siteType, $parameters )
{
    $roles = array();
    eZSetupForumRoles( $roles, $siteType, $parameters );
    eZSetupWeblogRoles( $roles, $siteType, $parameters );
    return $roles;
}

?>
