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

{* Advanced menu *}
menuArray['Advanced'] = new Array();
menuArray['Advanced']['depth'] = 1; // this is a first level submenu of ContextMenu
menuArray['Advanced']['elements'] = new Array();
menuArray['Advanced']['elements']['menu-hide'] = new Array();
menuArray['Advanced']['elements']['menu-hide']['url'] = {"/content/hide/%nodeID%"|ezurl};
menuArray['Advanced']['elements']['menu-list'] = new Array();
menuArray['Advanced']['elements']['menu-list']['url'] = {"content/view/sitemap/%nodeID%"|ezurl};


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
menuArray['ClassMenu']['elements']['view-cache-delete'] = new Array();
menuArray['ClassMenu']['elements']['view-cache-delete']['url'] = {"%currentURL%"|ezurl};
menuArray['ClassMenu']['elements']['recursive-view-cache-delete'] = new Array();
menuArray['ClassMenu']['elements']['recursive-view-cache-delete']['url'] = {"%currentURL%"|ezurl};

menuArray['BookmarkMenu'] = new Array();
menuArray['BookmarkMenu']['depth'] = 0;
menuArray['BookmarkMenu']['headerID'] = 'bookmark-header';
menuArray['BookmarkMenu']['elements'] = new Array();
menuArray['BookmarkMenu']['elements']['bookmark-view'] = new Array();
menuArray['BookmarkMenu']['elements']['bookmark-view']['url'] = {"/content/view/full/%nodeID%"|ezurl};
menuArray['BookmarkMenu']['elements']['bookmark-edit'] = new Array();
menuArray['BookmarkMenu']['elements']['bookmark-edit']['url'] = {"/content/edit/%objectID%"|ezurl};

</script>
<script language="JavaScript" type="text/javascript" src={'javascript/lib/ezjslibdomsupport.js'|ezdesign}></script>
<script language="JavaScript" type="text/javascript" src={'javascript/lib/ezjslibmousetracker.js'|ezdesign}></script>
<script language="JavaScript" type="text/javascript" src={'javascript/popupmenu/ezpopupmenu.js'|ezdesign}></script>

<!-- Treemenu icon click popup menu -->
<div class="popupmenu" id="ContextMenu">
    <div class="popupmenuheader"><h3 id="menu-header">XXX</h3>
        {*<div class="window-close" onclick="ezpopmenu_hide( 'ContextMenu' )"><p>X</p></div>*}
        <div class="break"></div>
    </div>
    <a id="menu-view" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )">{"View"|i18n("design/admin/popupmenu")}</a>
    <a id="menu-edit" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )">{"Edit"|i18n("design/admin/popupmenu")}</a>
    <hr />
    <a id="menu-copy" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )">{"Copy"|i18n("design/admin/popupmenu")}</a>
    <a id="menu-move" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )" onclick="ezpopmenu_submitForm( 'menu-form-move' ); return false;">{"Move"|i18n("design/admin/popupmenu")}</a>
    <a id="menu-remove" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )" onclick="ezpopmenu_submitForm( 'menu-form-remove' ); return false;">{"Remove"|i18n("design/admin/popupmenu")}</a>
    <a id="menu-advanced" class="more" href="#" onmouseover="ezpopmenu_showSubLevel( event, 'Advanced', 'menu-advanced' ); return false;">{'Advanced'|i18n( 'design/admin/popupmenu' )}</a>
    <hr />
    <a id="menu-expand" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )"
       onclick="ezcst_expandSubtree( CurrentSubstituteValues['%nodeID%'] ); ezpopmenu_hideAll(); return false;">{"Expand"|i18n("design/admin/popupmenu")}</a>
    <a id="menu-collapse" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )"
       onclick="ezcst_collapseSubtree( CurrentSubstituteValues['%nodeID%'] ); ezpopmenu_hideAll(); return false;">{"Collapse"|i18n("design/admin/popupmenu")}</a>
    <hr />
    <a id="menu-bookmark" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-addbookmark' ); return false;">{"Add to my bookmarks"|i18n("design/admin/popupmenu")}</a>
    <a id="menu-notify" href="#" onmouseover="ezpopmenu_mouseOver( 'ContextMenu' )"
       onclick="ezpopmenu_submitForm( 'menu-form-notify' ); return false;">{"Add to my notifications"|i18n("design/admin/popupmenu")}</a>
</div>

<!-- Subitems icon click popup menu -->
<div class="popupmenu" id="SubitemsContextMenu">
    <div class="popupmenuheader"><h3 id="child-menu-header">XXX</h3>
{*        <div class="window-close" onclick="ezpopmenu_hide( 'SubitemsContextMenu' )"><p>X</p></div> *}
        <div class="break"></div>
    </div>
    <a id="child-menu-view" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )">{"View"|i18n("design/admin/popupmenu")}</a>
    <a id="child-menu-edit" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )">{"Edit"|i18n("design/admin/popupmenu")}</a>
    <hr />
    <a id="child-menu-copy" href="#" onmouseover="ezpopmenu_mouseOver( 'SubitemsContextMenu' )">{"Copy"|i18n("design/admin/popupmenu")}</a>
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
</div>

<!-- Advanced menu -->
<div class="popupmenu" id="Advanced">
{*    <div class="popupmenuheader"><h3 id="menu-header">{'Advanced'|i18n( 'design/admin/popupmenu' )}</h3>
        <div class="window-close" onclick="ezpopmenu_hide( 'Advanced' )"><p>X</p></div>
        <div class="break"></div>
    </div> *}
    <a id="menu-swap" href="#" onmouseover="ezpopmenu_mouseOver( 'Advanced' )" onclick="ezpopmenu_submitForm( 'menu-form-swap' ); return false;">{'Swap with another node'|i18n( 'design/admin/popupmenu' )}</a>
    <a id="menu-hide" href="#" onmouseover="ezpopmenu_mouseOver( 'Advanced' )">{'Hide / unhide'|i18n( 'design/admin/popupmenu' )}</a>
    <a id="menu-list" href="#" onmouseover="ezpopmenu_mouseOver( 'Advanced' )">{'View index'|i18n( 'design/admin/popupmenu' )}</a>
</div>


<!-- Class popup menu -->
<div class="popupmenu" id="ClassMenu">
    <div class="popupmenuheader"><h3 id="class-header">XXX</h3>
{*        <div class="window-close" onclick="ezpopmenu_hide( 'ClassMenu' )"><p>X</p></div> *}
        <div class="break"></div>
    </div>
    <a id="class-view" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"View class"|i18n("design/admin/popupmenu")}</a>
    <a id="class-edit" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"Edit class"|i18n("design/admin/popupmenu")}</a>
    <hr />
    <a id="view-cache-delete" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )" onclick="ezpopmenu_submitForm( 'menu-form-view-cache-delete' ); return false;">{"Delete view cache"|i18n("design/admin/popupmenu")}</a>
<!--
    <a id="template-cache-delete" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"Delete template cache"|i18n("design/admin/popupmenu")}</a>
-->
    <a id="recursive-view-cache-delete" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )" onclick="ezpopmenu_submitForm( 'menu-form-recursive-view-cache-delete' ); return false;">{"Delete view cache from here"|i18n("design/admin/popupmenu")}</a>
<!--
    <hr />
    <a id="override-view" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"Template overrides"|i18n("design/admin/popupmenu")}</a>
    <a id="override-view" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"New class override"|i18n("design/admin/popupmenu")}</a>
    <a id="override-view" href="#" onmouseover="ezpopmenu_mouseOver( 'ClassMenu' )">{"New node override"|i18n("design/admin/popupmenu")}</a>
-->
</div>



<!-- Bookmark popup menu -->
<div class="popupmenu" id="BookmarkMenu">
    <div class="popupmenuheader"><h3 id="bookmark-header">XXX</h3>
{*        <div class="window-close" onclick="ezpopmenu_hide( 'BookmarkMenu' )"><p>X</p></div> *}
        <div class="break"></div>
    </div>
    <a id="bookmark-view" href="#" onmouseover="ezpopmenu_mouseOver( 'BookmarkMenu' )">{"View"|i18n("design/admin/popupmenu")}</a>
    <a id="bookmark-edit" href="#" onmouseover="ezpopmenu_mouseOver( 'BookmarkMenu' )">{"Edit"|i18n("design/admin/popupmenu")}</a>
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

{* Move node. *}
<form id="menu-form-move" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="TopLevelNode" value="%nodeID%" />
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ContentObjectID" value="%objectID" />
  <input type="hidden" name="MoveNodeButton" value="x" />
</form>

{* Swap node *}
<form id="menu-form-swap" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="TopLevelNode" value="%nodeID%" />
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ContentObjectID" value="%objectID" />
  <input type="hidden" name="SwapNodeButton" value="x" />
</form>


{* Add to notifications. *}
<form id="menu-form-notify" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ActionAddToNotification" value="x" />
</form>

{* Delete view cache for node. *}
<form id="menu-form-view-cache-delete" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="NodeID" value="%nodeID%" />
  <input type="hidden" name="ObjectID" value="%objectID%" />
  <input type="hidden" name="CurrentURL" value={"%currentURL%"|ezurl} />
  <input type="hidden" name="ClearViewCacheButton" value="x" />
</form>

{* Delete view cache for subtree. *}
<form id="menu-form-recursive-view-cache-delete" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="NodeID" value="%nodeID%" />
  <input type="hidden" name="ObjectID" value="%objectID%" />
  <input type="hidden" name="CurrentURL" value={"%currentURL%"|ezurl} />
  <input type="hidden" name="ClearViewCacheSubtreeButton" value="x" />
</form>
