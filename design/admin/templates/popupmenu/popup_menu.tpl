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

menuArray['SubitemsContextMenu'] = new Array();
menuArray['SubitemsContextMenu']['depth'] = 0;
menuArray['SubitemsContextMenu']['headerID'] = 'child-menu-header';
menuArray['SubitemsContextMenu']['elements'] = new Array();
menuArray['SubitemsContextMenu']['elements']['child-menu-view'] = new Array();
menuArray['SubitemsContextMenu']['elements']['child-menu-view']['url'] = {"/content/view/full/%nodeID%"|ezurl};
menuArray['SubitemsContextMenu']['elements']['child-menu-edit'] = new Array();
menuArray['SubitemsContextMenu']['elements']['child-menu-edit']['url'] = {"/content/edit/%objectID%"|ezurl};
menuArray['SubitemsContextMenu']['elements']['child-menu-copy'] = new Array();
menuArray['SubitemsContextMenu']['elements']['child-menu-copy']['url'] = {"/content/copy/%objectID%"|ezurl};

menuArray['ClassMenu'] = new Array();
menuArray['ClassMenu']['depth'] = 0;
menuArray['ClassMenu']['headerID'] = 'class-header';
menuArray['ClassMenu']['elements'] = new Array();
menuArray['ClassMenu']['elements']['class-view'] = new Array();
menuArray['ClassMenu']['elements']['class-view']['url'] = {"/class/view/%classID%"|ezurl};
menuArray['ClassMenu']['elements']['class-edit'] = new Array();
menuArray['ClassMenu']['elements']['class-edit']['url'] = {"/class/edit/%classID%"|ezurl};

menuArray['BookmarkMenu'] = new Array();
menuArray['BookmarkMenu']['depth'] = 0;
menuArray['BookmarkMenu']['headerID'] = 'bookmark-header';
menuArray['BookmarkMenu']['elements'] = new Array();
menuArray['BookmarkMenu']['elements']['bookmark-view'] = new Array();
menuArray['BookmarkMenu']['elements']['bookmark-view']['url'] = {"/content/view/full/%nodeID%"|ezurl};
menuArray['BookmarkMenu']['elements']['bookmark-edit'] = new Array();
menuArray['BookmarkMenu']['elements']['bookmark-edit']['url'] = {"/content/edit/%objectID%"|ezurl};

</script>
<script language="JavaScript" src={"javascript/lib/ezjslibdomsupport.js"|ezdesign}></script>
<script language="JavaScript" src={"javascript/lib/ezjslibmousetracker.js"|ezdesign}></script>
<script language="JavaScript" src={"javascript/popupmenu/ezpopupmenu.js"|ezdesign}></script>

<!-- Treemenu icon click popup menu -->
<div class="popupmenu" id="ContextMenu">
    <div class="popupmenuheader"><h3 id="menu-header">XXX</h3>
        <div class="window-close" onclick="ezpopmenu_hide( 'ContextMenu' )"><p>X</p></div>
        <div class="break"></div>
    </div>
    <a id="menu-view" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )">{"View"|i18n("design/standard/popupmenu")}</a>
    <a id="menu-edit" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )">{"Edit"|i18n("design/standard/popupmenu")}</a>
    <hr />
    <a id="menu-remove" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-remove' ); return false;">{"Remove"|i18n("design/standard/popupmenu")}</a>
    <a id="menu-copy" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )">{"Copy"|i18n("design/standard/popupmenu")}</a>
    <hr />
    <a id="menu-expand" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )"
       onclick="ezcst_expandSubtree( CurrentSubstituteValues['%nodeID%'] ); ezpopmenu_hideAll(); return false;">{"Expand subtree"|i18n("design/standard/popupmenu")}</a>
    <a id="menu-collapse" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )"
       onclick="ezcst_collapseSubtree( CurrentSubstituteValues['%nodeID%'] ); ezpopmenu_hideAll(); return false;">{"Collapse subtree"|i18n("design/standard/popupmenu")}</a>
    <hr />
    <a id="menu-bookmark" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-addbookmark' ); return false;">{"Add to my bookmarks"|i18n("design/standard/popupmenu")}</a>
    <a id="menu-notify" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-notify' ); return false;">{"Add to my notifications"|i18n("design/standard/popupmenu")}</a>
</div>

<!-- Subitems icon click popup menu -->
<div class="popupmenu" id="SubitemsContextMenu">
    <div class="popupmenuheader"><h3 id="child-menu-header">XXX</h3>
        <div class="window-close" onclick="ezpopmenu_hide( 'SubitemsContextMenu' )"><p>X</p></div>
        <div class="break"></div>
    </div>
    <a id="child-menu-view" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )">{"View"|i18n("design/standard/popupmenu")}</a>
    <a id="child-menu-edit" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )">{"Edit"|i18n("design/standard/popupmenu")}</a>
    <hr />
    <a id="child-menu-remove" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-remove' ); return false;">{"Remove"|i18n("design/standard/popupmenu")}</a>
    <a id="child-menu-copy" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )">{"Copy"|i18n("design/standard/popupmenu")}</a>
    <hr />
    <a id="child-menu-bookmark" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-addbookmark' ); return false;">{"Add to my bookmarks"|i18n("design/standard/popupmenu")}</a>
    <a id="child-menu-notify" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-notify' ); return false;">{"Add to my notifications"|i18n("design/standard/popupmenu")}</a>
</div>


<!-- Class popup menu -->
<div class="popupmenu" id="ClassMenu">
    <div class="popupmenuheader"><h3 id="class-header">XXX</h3>
        <div class="window-close" onclick="ezpopmenu_hide( 'ClassMenu' )"><p>X</p></div>
        <div class="break"></div>
    </div>
    <a id="class-view" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"View class"|i18n("design/standard/popupmenu")}</a>
    <a id="class-edit" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"Edit class"|i18n("design/standard/popupmenu")}</a>
<!--
    <hr />
    <a id="view-cache-delete" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"Delete view cache"|i18n("design/standard/popupmenu")}</a>
    <a id="template-cache-delete" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"Delete template cache"|i18n("design/standard/popupmenu")}</a>
    <a id="recursive-view-cache-delete" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"Delete view cache from here"|i18n("design/standard/popupmenu")}</a>
    <hr />
    <a id="override-view" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"Template overrides"|i18n("design/standard/popupmenu")}</a>
    <a id="override-view" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"New class override"|i18n("design/standard/popupmenu")}</a>
    <a id="override-view" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"New node override"|i18n("design/standard/popupmenu")}</a>
-->
</div>



<!-- Bookmark popup menu -->
<div class="popupmenu" id="BookmarkMenu">
    <div class="popupmenuheader"><h3 id="bookmark-header">XXX</h3>
        <div class="window-close" onclick="ezpopmenu_hide( 'BookmarkMenu' )"><p>X</p></div>
        <div class="break"></div>
    </div>
    <a id="bookmark-view" href="#" onmouseover="ezpopmenu_mouseOver( 'BookmarkMenu' )">{"View"|i18n("design/standard/popupmenu")}</a>
    <a id="bookmark-edit" href="#" onmouseover="ezpopmenu_mouseOver( 'BookmarkMenu' )">{"Edit"|i18n("design/standard/popupmenu")}</a>
</div>


{* Forms used by the various elements *}

{* Add bookmark. *}
<form id="menu-form-addbookmark" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ActionAddToBookmarks" value="x" />
</form>

{* Remove node. *}
<form id="menu-form-remove" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="TopLevelNode" value="%nodeID%" />
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ContentObjectID" value="%objectID" />
  <input type="hidden" name="ActionRemove" value="x" />
</form>

{* Add to notifications. *}
<form id="menu-form-notify" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ActionAddToNotification" value="x" />
</form>

