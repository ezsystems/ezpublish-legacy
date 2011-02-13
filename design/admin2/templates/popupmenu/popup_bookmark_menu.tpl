<!-- Bookmark popup menu -->
<script type="text/javascript">
<!--
menuArray['BookmarkMenu'] = {ldelim} 'depth': 0, 'headerID': 'bookmark-header' {rdelim};
menuArray['BookmarkMenu']['elements'] = {ldelim}{rdelim};
menuArray['BookmarkMenu']['elements']['bookmark-view'] = {ldelim} 'url': {"/content/view/full/%nodeID%"|ezurl} {rdelim};
menuArray['BookmarkMenu']['elements']['bookmark-edit'] = {ldelim} 'url': {"/content/edit/%objectID%"|ezurl} {rdelim};
// -->
</script>

<div class="popupmenu" id="BookmarkMenu">
    <div class="popupmenuheader"><h3 id="bookmark-header">XXX</h3>
{*        <div class="window-close" onclick="ezpopmenu_hide( 'BookmarkMenu' )"><p>X</p></div> *}
        <div class="break"></div>
    </div>
    <a id="bookmark-view" href="#" onmouseover="ezpopmenu_mouseOver( 'BookmarkMenu' )">{"View"|i18n("design/admin/popupmenu")}</a>
{if $multilingual_site}
    <a id="bookmark-edit-in" class="more" href="#" onmouseover="ezpopmenu_showSubLevel( event, 'EditSubmenu', 'bookmark-edit-in' ); return false;">{'Edit in'|i18n( 'design/admin/popupmenu' )}</a>
{else}
    <a id="bookmark-edit" href="#" onmouseover="ezpopmenu_mouseOver( 'BookmarkMenu' )">{"Edit"|i18n("design/admin/popupmenu")}</a>
{/if}
    <hr />
    <a id="bookmark-remove" href="#" onmouseover="ezpopmenu_mouseOver( 'BookmarkMenu' )"
        onclick="ezpopmenu_submitForm( 'menu-form-removebookmark' ); return false;">{"Remove bookmark"|i18n("design/admin/popupmenu")}</a>

    {* Include additional bookmark menu items  based on .ini settings *}
    {foreach ezini( 'AdditionalMenuSettings', 'BookmarkMenuTemplateArray', 'admininterface.ini' ) as $template}
        {include uri=concat('design:', $template )}
    {/foreach}
</div>

{* Remove bookmark *}
<form id="menu-form-removebookmark" method="post" action={"/content/bookmark"|ezurl}>
  <input type="hidden" name="DeleteIDArray[]" value="%bookmarkID%" />
  <input type="hidden" name="RemoveButton" value="x" />
  <input type="hidden" name="NeedRedirectBack" value="x" />
</form>