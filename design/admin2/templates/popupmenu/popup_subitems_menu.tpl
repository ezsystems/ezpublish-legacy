<!-- Subitems icon click popup menu -->
<script type="text/javascript">
<!--
menuArray['SubitemsContextMenu'] = {ldelim} 'depth': 0, 'headerID': 'child-menu-header' {rdelim};
menuArray['SubitemsContextMenu']['elements'] = {ldelim}{rdelim};
//menuArray['SubitemsContextMenu']['elements']['child-menu-view'] = {ldelim} 'url': {"/content/view/full/%nodeID%"|ezurl} {rdelim};
menuArray['SubitemsContextMenu']['elements']['child-menu-preview'] = {ldelim} 'url': {"/content/versionview/%objectID%/%version%"|ezurl} {rdelim};
menuArray['SubitemsContextMenu']['elements']['child-menu-edit'] = {ldelim} 'url': {"/content/edit/%objectID%"|ezurl} {rdelim};
menuArray['SubitemsContextMenu']['elements']['child-menu-copy'] = {ldelim} 'url': {"/content/copy/%objectID%"|ezurl} {rdelim};
menuArray['SubitemsContextMenu']['elements']['child-menu-copy-subtree'] = {ldelim} 'url': {"/content/copysubtree/%nodeID%"|ezurl} {rdelim};
menuArray['SubitemsContextMenu']['elements']['child-menu-create-here'] = {ldelim} 'disabled_class': 'menu-item-disabled' {rdelim};
// -->
</script>

<div class="popupmenu" id="SubitemsContextMenu">
    <div class="popupmenuheader"><h3 id="child-menu-header">XXX</h3>
{*        <div class="window-close" onclick="ezpopmenu_hide( 'SubitemsContextMenu' )"><p>X</p></div> *}
        <div class="break"></div>
    </div>
{*    <a id="child-menu-view" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )">{"View"|i18n("design/admin/popupmenu")}</a> *}
    <a id="child-menu-preview" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )">{"Preview"|i18n("design/admin/popupmenu")}</a>
{if $multilingual_site}
    <a id="child-menu-edit-in" class="more" href="#" onmouseover="ezpopmenu_showSubLevel( event, 'EditSubmenu', 'child-menu-edit-in' ); return false;">{'Edit in'|i18n( 'design/admin/popupmenu' )}</a>
{else}
    <a id="child-menu-edit" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )">{"Edit"|i18n("design/admin/popupmenu")}</a>
{/if}
    <hr />
    <a id="child-menu-copy" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )">{"Copy"|i18n("design/admin/popupmenu")}</a>
    <a id="child-menu-copy-subtree" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )">{"Copy subtree"|i18n("design/admin/popupmenu")}</a>
    <a id="child-menu-move" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-move' ); return false;">{"Move"|i18n("design/admin/popupmenu")}</a>
    <a id="child-menu-remove" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-remove' ); return false;">{"Remove"|i18n("design/admin/popupmenu")}</a>
    <a id="child-menu-advanced" class="more" href="#" onmouseover="ezpopmenu_showSubLevel( event, 'Advanced', 'child-menu-advanced' ); return false;">{'Advanced'|i18n( 'design/admin/popupmenu' )}</a>
    <hr />
    <a id="child-menu-bookmark" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-addbookmark' ); return false;">{"Add to my bookmarks"|i18n("design/admin/popupmenu")}</a>
    <a id="child-menu-notify" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-notify' ); return false;">{"Add to my notifications"|i18n("design/admin/popupmenu")}</a>

    <a id="child-menu-create-here" class="more" href="#" onmouseover="ezpopmenu_showSubLevel( event, 'CreateHereMenu', 'child-menu-create-here' ); return false;">{'Create here'|i18n( 'design/admin/popupmenu' )}</a>

    {* Include additional subitems menu items  based on .ini settings *}
    {section var=template loop=ezini( 'AdditionalMenuSettings', 'SubitemsContextMenuTemplateArray', 'admininterface.ini' )}
        {include uri=concat('design:', $template )}
    {/section}
</div>