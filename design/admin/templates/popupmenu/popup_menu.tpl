<script language="JavaScript" type="text/javascript">
var menuArray = new Array();
menuArray['ContextMenu'] = new Array();
menuArray['ContextMenu']['depth'] = 0;
menuArray['ContextMenu']['elements'] = new Array();
menuArray['ContextMenu']['elements']['menu-view'] = new Array();
menuArray['ContextMenu']['elements']['menu-view']['url'] = '/content/view/full/%nodeID%';

menuArray['ContextMenu']['elements']['menu-edit'] = new Array();
menuArray['ContextMenu']['elements']['menu-edit']['url'] = '/content/edit/%objectID%';
</script>
<script language="JavaScript" src={"javascript/lib/ezjslibmousetracker.js"|ezdesign}></script>
<script language="JavaScript" src={"javascript/popupmenu/ezpopupmenu.js"|ezdesign}></script>

<div class="popupmenu" id="ContextMenu">
    <div class="popupmenuheader"><h3>Edit</h3>
        <div class="window-close" onclick="ezpopmnu_hide( 'ContextMenu' )"><p>X</p></div>
        <div class="break"></div>
    </div>
    <a id="menu-view" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )">{"View"|i18n("design/standard/popupmenu")}</a>
    <a id="menu-edit" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )">{"Edit"|i18n("design/standard/popupmenu")}</a>
    <hr />
    <a id="menu-remove" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )">{"Remove"|i18n("design/standard/popupmenu")}</a>
    <a id="menu-copy" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )">{"Copy"|i18n("design/standard/popupmenu")}</a>
    <a id="menu-view" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )">{"Move"|i18n("design/standard/popupmenu")}</a>
    <hr />
    <a id="menu-bookmark" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )">{"Add to my bookmarks"|i18n("design/standard/popupmenu")}</a>
    <a id="menu-notify" href="#" onmouseover="ezpopmnu_mouseOver( 'ContextMenu' )">{"Notify me about changes"|i18n("design/standard/popupmenu")}</a>
</div>
