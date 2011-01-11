{if is_set( $custom_root_node )}
    {def $root_node    = $custom_root_node
         $root_node_id = $root_node.node_id}
{elseif is_set( $custom_root_node_id )}
    {def $root_node_id = $custom_root_node_id
         $root_node    = fetch( 'content', 'node', hash( 'node_id', $root_node_id ) )}
{else}
    {def $root_node_id = ezini('TreeMenu','RootNodeID','contentstructuremenu.ini')
         $root_node    = fetch( 'content', 'node', hash( 'node_id', $root_node_id ) )}
{/if}
{if is_unset( $menu_persistence )}
    {def $menu_persistence = ezini('TreeMenu','MenuPersistence','contentstructuremenu.ini')|eq('enabled')}
{/if}
{if is_unset( $hide_node_list )}
    {def $hide_node_list = array()}
{/if}
{if and( is_set( $search_subtree_array[0] ), $search_subtree_array[0]|ne( '1' ) )}
    {def $search_node = fetch( 'content', 'node', hash( 'node_id', $search_subtree_array[0] ))}
    {if is_set( $search_node.path_array[1] )}
        {set $root_node_id = $search_node.path_array[1]}
    {/if}
    {undef $search_node}
{/if}
{def $user_class_group_id  = ezini('ClassGroupIDs', 'Users', 'content.ini')
     $setup_class_group_id = ezini('ClassGroupIDs', 'Setup', 'content.ini')
     $user_root_node_id    = ezini('NodeSettings', 'UserRootNode', 'content.ini')
}
{if $root_node_id|gt( 1 )}
    {def $filter_type          = cond( $root_node.path_array|contains( $user_root_node_id ), 'include', 'exclude')
         $filter_groups        = cond( $root_node.path_array|contains( $user_root_node_id ), array( $user_class_group_id ), array( $user_class_group_id, $setup_class_group_id ))}
{else}
    {def $filter_type          = 'exclude'
         $filter_groups        = array()}
{/if}

{def $click_action = ezini('TreeMenu','ItemClickAction','contentstructuremenu.ini')}
{if and( is_set( $csm_menu_item_click_action ), $click_action|not )}
    {set $click_action = $csm_menu_item_click_action}
{/if}

{if $click_action}
    {set $click_action = $click_action|ezurl(no)}
{/if}

<script type="text/javascript">
<!--
var treeMenu;
(function(){ldelim}

{cache-block keys=array( $root_node_id, $access_type ) expiry=0}
    {def $root_node_url = $root_node.url}
    {if $root_node_id|eq( 1 )}
        {set $root_node_url = 'content/dashboard'}
    {elseif $root_node_url|eq('')}
        {set $root_node_url = concat( 'content/view/full/', $root_node_id )}
    {/if}

    var rootNode = {ldelim}{*
        *}"node_id":{$root_node_id},{*
        *}"object_id":{if $root_node.object.id}{$root_node.object.id}{else}0{/if},{*
        *}"class_id":{$root_node.object.contentclass_id},{*
        *}"has_children":{if $root_node.children_count}true{else}false{/if},{*
        *}"name":"{$root_node.name|wash(javascript)}",{*
        *}"url":{$root_node_url|ezurl},{*
        *}"modified_subnode":{$root_node.modified_subnode},{*
        *}"languages":["{$root_node.object.language_codes|implode('", "')}"],{*
        *}"class_list":[{foreach fetch('content','can_instantiate_class_list',hash('parent_node',$root_node, 'filter_type', $filter_type, 'group_id', $filter_groups)) as $class}{$class.id}{delimiter},{/delimiter}{/foreach}]{rdelim};

    {cache-block keys=array( $root_node_id|gt( 1 ), $access_type ) expiry=86400 ignore_content_expiry}
        {def $iconInfo      = icon_info('class')
            $classIconsSize = ezini('TreeMenu','ClassIconsSize','contentstructuremenu.ini')}
        var params = {ldelim}{*
            *}"iconsList":[],{*
            *}"contentTreeUrl":"{"content/treemenu"|ezurl(no)}/",{*
            *}"wwwDirPrefix":"{ezsys('wwwdir')}/{$iconInfo.theme_path}/{$iconInfo.size_path_list[$classIconsSize]}/"{rdelim};

        params.languages = {ldelim}{*
            *}{foreach fetch('content','translation_list') as $language}{*
                *}"{$language.locale_code|wash(javascript)}":"{$language.intl_language_name|wash(javascript)}"{*
                *}{delimiter},{/delimiter}{*
            *}{/foreach}{*
        *}{rdelim};

        params.classes = {ldelim}{*
            *}{foreach fetch('class','list') as $class}{*
                *}"{$class.id}":{ldelim}name:"{$class.name|wash(javascript)}",identifier:"{$class.identifier|wash(javascript)}"{rdelim}{*
                *}{delimiter},{/delimiter}{*
            *}{/foreach}{*
        *}{rdelim};

        {foreach $iconInfo.icons as $class => $icon}{*
            *}params.iconsList['{$class}'] = params.wwwDirPrefix + "{$icon}";
        {/foreach}
        params.iconsList['__default__'] = params.wwwDirPrefix + "{$iconInfo.default}";

        {if ezini('TreeMenu','PreloadClassIcons','contentstructuremenu.ini')|eq('enabled')}
        ezjslib_preloadImageList( params.iconsList );
        {/if}

        params.showTips       = {if ezini('TreeMenu','ToolTips','contentstructuremenu.ini')|eq('enabled')}true{else}false{/if};
        params.createHereMenu = "{ezini('TreeMenu','CreateHereMenu','contentstructuremenu.ini')}";
        params.autoOpen       = {if ezini('TreeMenu','AutoopenCurrentNode','contentstructuremenu.ini')|eq('enabled')}true{else}false{/if};

        var i18n = {ldelim}{*
            *}"expand":"{'Click on the icon to display a context-sensitive menu.'|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)}",{*
            *}"node_id":"{'Node ID'|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)}",{*
            *}"object_id":"{'Object ID'|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)}",{*
            *}"visibility":"{'Visibility'|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)}",{*
            *}"hidden":"{'Hidden'|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)}",{*
            *}"visible":"{'Visible'|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)}",{*
            *}"hiddenbyparent":"{'Hidden by superior'|i18n('design/admin/contentstructuremenu')|wash(xhtml)|wash(javascript)}",{*
            *}"disabled":"{'Dynamic tree menu is disabled for this siteaccess!'|i18n("design/admin/contentstructuremenu")|wash("javascript")}",{*
            *}"not_exist":"{'Node does not exist'|i18n("design/admin/contentstructuremenu")|wash("javascript")}",{*
            *}"internal_error":"{'Internal error̈́'|i18n("design/admin/contentstructuremenu")|wash("javascript")}"{rdelim};
        {undef $iconInfo $classIconsSize}
    {/cache-block}
{/cache-block}

   {* Uncached (pr request / user) *}
    params.action    = "{$click_action}";
    params.context   = "{$ui_context}";
    params.hideNodes = [{$hide_node_list|implode(',')}];
    params.expiry    = "{fetch('content','content_tree_menu_expiry')}";
    params.useCookie = {if $menu_persistence}true{else}false{/if};
    params.path      = [{if is_set($module_result.path[0].node_id)}{foreach $module_result.path as $element}'{$element.node_id}'{delimiter},{/delimiter}{/foreach}{/if}];
{default current_user=fetch('user','current_user')}
    params.perm      = "{concat($current_user.role_id_list|implode(','),'|',$current_user.limited_assignment_value_list|implode(','))|md5}";
{/default}

    treeMenu = new ContentStructureMenu( params, i18n );

    document.writeln( '<ul id="content_tree_menu">' );
    document.writeln( treeMenu.generateEntry( rootNode, false, true ) );
    document.writeln( '<\/ul>' );

    treeMenu.load( false, rootNode.node_id, rootNode.modified_subnode );
{rdelim})();
// -->
</script>
