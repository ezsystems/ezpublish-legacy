<!-- Treemenu icon click popup menu -->
<script type="text/javascript">
menuArray['ContextMenu'] = {ldelim} 'depth': 0, 'headerID': 'menu-header' {rdelim};
menuArray['ContextMenu']['elements'] = {ldelim}{rdelim};
//menuArray['ContextMenu']['elements']['menu-view'] = {ldelim} 'url': {"/content/view/full/%nodeID%"|ezurl} {rdelim};
menuArray['ContextMenu']['elements']['menu-edit'] = {ldelim} 'url': {"/content/edit/%objectID%"|ezurl} {rdelim};
menuArray['ContextMenu']['elements']['menu-copy'] = {ldelim} 'url': {"/content/copy/%objectID%"|ezurl} {rdelim};
menuArray['ContextMenu']['elements']['menu-copy-subtree']= {ldelim} 'url': {"/content/copysubtree/%nodeID%"|ezurl} {rdelim};
menuArray['ContextMenu']['elements']['menu-create-here']= {ldelim} 'disabled_class': 'menu-item-disabled' {rdelim};
{*menuArray['ContextMenu']['elements']['child-menu-create-here'] = {ldelim} 'disabled_class': 'menu-item-disabled' {rdelim};*}
</script>

<div class="popupmenu" id="ContextMenu">
    <div class="popupmenuheader"><h3 id="menu-header">XXX</h3>
        <div class="break"></div>
    </div>
    <!-- a id="menu-view" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )">{"View"|i18n("design/admin/popupmenu")}</a -->
{if $multilingual_site}
    <a id="menu-edit-in" class="more" href="#" onmouseover="ezpopmenu_showSubLevel( event, 'EditSubmenu', 'menu-edit-in' ); return false;">{'Edit in'|i18n( 'design/admin/popupmenu' )}</a>
{else}
    <a id="menu-edit" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )">{"Edit"|i18n("design/admin/popupmenu")}</a>
{/if}
    <hr />
    <a id="menu-copy" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )">{"Copy"|i18n("design/admin/popupmenu")}</a>
    <a id="menu-copy-subtree" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )">{"Copy subtree"|i18n("design/admin/popupmenu")}</a>
    <a id="menu-move" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )" onclick="ezpopmenu_submitForm( 'menu-form-move' ); return false;">{"Move"|i18n("design/admin/popupmenu")}</a>
    <a id="menu-remove" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )" onclick="ezpopmenu_submitForm( 'menu-form-remove' ); return false;">{"Remove"|i18n("design/admin/popupmenu")}</a>
    <a id="menu-advanced" class="more" href="#" onmouseover="ezpopmenu_showSubLevel( event, 'Advanced', 'menu-advanced' ); return false;">{'Advanced'|i18n( 'design/admin/popupmenu' )}</a>
    <hr />
    <a id="menu-bookmark" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-addbookmark' ); return false;">{"Add to my bookmarks"|i18n("design/admin/popupmenu")}</a>
    <a id="menu-notify" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-notify' ); return false;">{"Add to my notifications"|i18n("design/admin/popupmenu")}</a>

    <a id="menu-create-here" class="more" href="#" onmouseover="ezpopmenu_showSubLevel( event, 'CreateHereMenu', 'menu-create-here' ); return false;">{'Create here'|i18n( 'design/admin/popupmenu' )}</a>

    {* Include additional context menu items  based on .ini settings *}
    {section var=template loop=ezini( 'AdditionalMenuSettings', 'ContextMenuTemplateArray', 'admininterface.ini' )}
        {include uri=concat('design:', $template )}
    {/section}
</div>