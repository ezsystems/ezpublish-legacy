<script language="JavaScript1.2" type="text/javascript">
var menuArray = new Array();
menuArray['ContextMenu'] = new Array();
menuArray['ContextMenu']['depth'] = 0;
menuArray['ContextMenu']['headerID'] = 'menu-header';
menuArray['ContextMenu']['elements'] = new Array();
menuArray['ContextMenu']['elements']['menu-view'] = new Array();
menuArray['ContextMenu']['elements']['menu-view']['url'] = {"/content/view/full/%nodeID%"|ezurl};

menuArray['ContextMenu']['elements']['menu-edit'] = new Array();
menuArray['ContextMenu']['elements']['menu-edit']['url'] = {"/content/edit/%objectID%"|ezurl};

menuArray['ContextMenu']['elements']['menu-copy'] = new Array();
menuArray['ContextMenu']['elements']['menu-copy']['url'] = {"/content/copy/%objectID%"|ezurl};

</script>
<script language="JavaScript" src={"javascript/lib/ezjslibmousetracker.js"|ezdesign}></script>
<script language="JavaScript" src={"javascript/popupmenu/ezpopupmenu.js"|ezdesign}></script>

<div class="popupmenu" id="ContextMenu">
    <div class="popupmenuheader"><h3 id="menu-header">XXX</h3>
        <div class="window-close" onclick="ezpopmnu_hide( 'ContextMenu' )"><p>X</p></div>
        <div class="break"></div>
    </div>
    <a id="menu-view" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )">{"View"|i18n("design/standard/popupmenu")}</a>
    <a id="menu-edit" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )">{"Edit"|i18n("design/standard/popupmenu")}</a>
    <hr />
    <a id="menu-remove" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )"
       onclick="ezpopmnu_submitForm( 'menu-form-remove' ); return false;">{"Remove"|i18n("design/standard/popupmenu")}</a>
    <a id="menu-copy" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )">{"Copy"|i18n("design/standard/popupmenu")}</a>
    <hr />
    <a id="menu-bookmark" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )"
       onclick="ezpopmnu_submitForm( 'menu-form-bookmark' ); return false;">{"Add to my bookmarks"|i18n("design/standard/popupmenu")}</a>
    <a id="menu-notify" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )"
       onclick="ezpopmnu_submitForm( 'menu-form-notify' ); return false;">{"Add to my notifications"|i18n("design/standard/popupmenu")}</a>
</div>

{* Forms used by the various elements *}
<form id="menu-form-bookmark" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ActionAddToBookmarks" value="x" />
</form>

<form id="menu-form-remove" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="TopLevelNode" value="%nodeID%" />
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ContentObjectID" value="%objectID" />
  <input type="hidden" name="ActionRemove" value="x" />
</form>

<form id="menu-form-notify" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ActionAddToNotification" value="x" />
</form>