<!-- Advanced menu -->
<script type="text/javascript">
menuArray['Advanced'] = {ldelim} 'depth': 1 {rdelim};
menuArray['Advanced']['elements'] = {ldelim}{rdelim};
menuArray['Advanced']['elements']['menu-hide'] = {ldelim} 'url': {"/content/hide/%nodeID%"|ezurl} {rdelim};
menuArray['Advanced']['elements']['menu-list'] = {ldelim} 'url': {"content/view/sitemap/%nodeID%"|ezurl} {rdelim};
menuArray['Advanced']['elements']['reverse-related'] = {ldelim} 'url': {"content/reverserelatedlist/%nodeID%"|ezurl} {rdelim};
menuArray['Advanced']['elements']['menu-history'] = {ldelim} 'url': {"content/history/%objectID%"|ezurl} {rdelim};
menuArray['Advanced']['elements']['menu-url-alias'] = {ldelim} 'url': {"content/urlalias/%nodeID%"|ezurl} {rdelim};
</script>

<div class="popupmenu" id="Advanced">
    <a id="menu-swap" href="#" onmouseover="ezpopmenu_mouseOver( 'Advanced' )" onclick="ezpopmenu_submitForm( 'menu-form-swap' ); return false;">{'Swap with another node'|i18n( 'design/admin/popupmenu' )}</a>
    <a id="menu-hide" href="#" onmouseover="ezpopmenu_mouseOver( 'Advanced' )">{'Hide / unhide'|i18n( 'design/admin/popupmenu' )}</a>
    <hr />
    <a id="menu-list" href="#" onmouseover="ezpopmenu_mouseOver( 'Advanced' )">{'Sitemap for subtree'|i18n( 'design/admin/popupmenu' )}</a>
    <a id="reverse-related" href="#" onmouseover="ezpopmenu_mouseOver( 'Advanced' )">{'Reverse related for subtree'|i18n( 'design/admin/popupmenu' )}</a>
    <hr />
    <a id="menu-history" href="#" onmouseover="ezpopmenu_mouseOver( 'Advanced' )">{'Manage versions'|i18n( 'design/admin/popupmenu' )}</a>
    <a id="menu-url-alias" href="#" onmouseover="ezpopmenu_mouseOver( 'Advanced' )">{'Manage URL aliases'|i18n( 'design/admin/popupmenu' )}</a>

    {* Include additional advanced menu items  based on .ini settings *}
    {foreach ezini( 'AdditionalMenuSettings', 'AdvancedMenuTemplateArray', 'admininterface.ini' ) as $template}
        {include uri=concat('design:', $template )}
    {/foreach}
</div>

{* Swap node *}
<form id="menu-form-swap" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="TopLevelNode" value="%nodeID%" />
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ContentObjectID" value="%objectID%" />
  <input type="hidden" name="SwapNodeButton" value="x" />
</form>