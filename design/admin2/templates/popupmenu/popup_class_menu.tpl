<!-- Class popup menu -->
<script type="text/javascript">
menuArray['ClassMenu'] = {ldelim} 'depth': 0, 'headerID': 'class-header' {rdelim};
menuArray['ClassMenu']['elements'] = {ldelim}{rdelim};
menuArray['ClassMenu']['elements']['class-view'] = {ldelim} 'url': {"/class/view/%classID%"|ezurl} {rdelim};
menuArray['ClassMenu']['elements']['class-edit'] = {ldelim} 'url': {"/class/edit/%classID%"|ezurl} {rdelim};
menuArray['ClassMenu']['elements']['view-cache-delete'] = {ldelim} 'url': {"%currentURL%"|ezurl} {rdelim};
menuArray['ClassMenu']['elements']['recursive-view-cache-delete'] = {ldelim} 'url': {"%currentURL%"|ezurl} {rdelim};
menuArray['ClassMenu']['elements']['class-createnodefeed'] = {ldelim} 'url': {"/content/view/full/%nodeID%"|ezurl} {rdelim};
menuArray['ClassMenu']['elements']['class-createnodefeed']['disabled_class'] = 'menu-item-disabled';
menuArray['ClassMenu']['elements']['class-createnodefeed']['disabled_for'] = {ldelim} 'class-createnodefeed': 'yes' {rdelim};
menuArray['ClassMenu']['elements']['class-removenodefeed'] = {ldelim} 'url': {"/content/view/full/%nodeID%"|ezurl} {rdelim};
menuArray['ClassMenu']['elements']['class-removenodefeed']['disabled_class'] = 'menu-item-disabled';
menuArray['ClassMenu']['elements']['class-removenodefeed']['disabled_for'] = {ldelim} 'class-removenodefeed': 'yes' {rdelim};
menuArray['ClassMenu']['elements']['class-history'] = {ldelim} 'url': {"content/history/%objectID%"|ezurl} {rdelim};
menuArray['ClassMenu']['elements']['url-alias'] = {ldelim} 'url': {"content/urlalias/%nodeID%"|ezurl} {rdelim};
</script>

<div class="popupmenu" id="ClassMenu">
    <div class="popupmenuheader"><h3 id="class-header">XXX</h3>
{*        <div class="window-close" onclick="ezpopmenu_hide( 'ClassMenu' )"><p>X</p></div> *}
        <div class="break"></div>
    </div>
    <a id="class-view" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"View class"|i18n("design/admin/popupmenu")}</a>
{if $multilingual_site}
    <a id="class-edit-in" class="more" href="#" onmouseover="ezpopmenu_showSubLevel( event, 'EditClassSubmenu', 'class-edit-in' ); return false;">{'Edit class in'|i18n( 'design/admin/popupmenu' )}</a>
{else}
    <a id="class-edit" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"Edit class"|i18n("design/admin/popupmenu")}</a>
{/if}

    <hr />
    <a id="view-cache-delete" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )" onclick="ezpopmenu_submitForm( 'menu-form-view-cache-delete' ); return false;">{"Delete view cache"|i18n("design/admin/popupmenu")}</a>
<!--
    <a id="template-cache-delete" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"Delete template cache"|i18n("design/admin/popupmenu")}</a>
-->
    <a id="recursive-view-cache-delete" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )" onclick="ezpopmenu_submitForm( 'menu-form-recursive-view-cache-delete' ); return false;">{"Delete view cache from here"|i18n("design/admin/popupmenu")}</a>
    <hr />
    <a id="class-createnodefeed" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-createnodefeed' ); return false;">{"Create RSS/ATOM feed"|i18n("design/admin/popupmenu")}</a>
    <a id="class-removenodefeed" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-removenodefeed' ); return false;">{"Remove RSS/ATOM feed"|i18n("design/admin/popupmenu")}</a>
    <hr />
    <a id="override-view" class="more" href="#" onmouseover="ezpopmenu_hide('OverrideByClassSiteAccess'); ezpopmenu_hide('OverrideByNodeSiteAccess'); ezpopmenu_showSubLevel( event, 'OverrideSiteAccess', 'override-view' ); return false;">{"Template overrides"|i18n("design/admin/popupmenu")}</a>
    <a id="override-by-class-view" class="more" href="#" onmouseover="ezpopmenu_hide('OverrideSiteAccess'); ezpopmenu_hide('OverrideByNodeSiteAccess'); ezpopmenu_showSubLevel( event, 'OverrideByClassSiteAccess', 'override-by-class-view' ); return false;">{"New class override"|i18n("design/admin/popupmenu")}</a>
    <a id="override-by-node-view" class="more" href="#" onmouseover="ezpopmenu_hide('OverrideSiteAccess'); ezpopmenu_hide('OverrideByClassSiteAccess'); ezpopmenu_showSubLevel( event, 'OverrideByNodeSiteAccess', 'override-by-node-view' ); return false;">{"New node override"|i18n("design/admin/popupmenu")}</a>
    <hr />
    <a id="class-history" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{'Manage versions'|i18n( 'design/admin/popupmenu' )}</a>
    <a id="url-alias" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{'Manage URL aliases'|i18n( 'design/admin/popupmenu' )}</a>
    
    {* Include additional class menu items  based on .ini settings *}
    {foreach ezini( 'AdditionalMenuSettings', 'ClassMenuTemplateArray', 'admininterface.ini' ) as $template}
        {include uri=concat('design:', $template )}
    {/foreach}
</div>

{* Add node feed. *}
<form id="menu-form-createnodefeed" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="ContentObjectID" value="%objectID%" />
  <input type="hidden" name="NodeID" value="%nodeID%" />
  <input type="hidden" name="CreateNodeFeed" value="x" />
</form>

{* Remove node feed *}
<form id="menu-form-removenodefeed" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="ContentObjectID" value="%objectID%" />
  <input type="hidden" name="NodeID" value="%nodeID%" />
  <input type="hidden" name="RemoveNodeFeed" value="x" />
</form>

{* Delete view cache for node. *}
<form id="menu-form-view-cache-delete" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="NodeID" value="%nodeID%" />
  <input type="hidden" name="ObjectID" value="%objectID%" />
  <input type="hidden" name="CurrentURL" value="%currentURL%" />
  <input type="hidden" name="ClearViewCacheButton" value="x" />
</form>

{* Delete view cache for subtree. *}
<form id="menu-form-recursive-view-cache-delete" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="NodeID" value="%nodeID%" />
  <input type="hidden" name="ObjectID" value="%objectID%" />
  <input type="hidden" name="CurrentURL" value="%currentURL%" />
  <input type="hidden" name="ClearViewCacheSubtreeButton" value="x" />
</form>