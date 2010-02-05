{def $multilingual_site = fetch( 'content', 'translation_list' )|count|gt( 1 )}
<script type="text/javascript" src={'javascript/popupmenu/ezpopupmenu.js'|ezdesign}></script>

{include uri='design:popupmenu/popup_context_menu.tpl'}

{include uri='design:popupmenu/popup_subitems_menu.tpl'}

{include uri='design:popupmenu/popup_class_menu.tpl'}

{include uri='design:popupmenu/popup_bookmark_menu.tpl'}

{* Include additional submenus based on .ini files *}
{section var=template loop=ezini( 'AdditionalMenuSettings', 'SubMenuTemplateArray', 'admininterface.ini' )}
   {include uri=concat('design:', $template )}
{/section}

{include uri='design:popupmenu/popup_sub_createhere_menu.tpl'}

{include uri='design:popupmenu/popup_sub_edit_menu.tpl'}

{include uri='design:popupmenu/popup_sub_advance_menu.tpl'}

{include uri='design:popupmenu/popup_sub_editclass_menu.tpl'}

{include uri='design:popupmenu/popup_sub_siteaccess_menus.tpl'}



{* Forms used by several default menues *}

{* Add bookmark. *}
<form id="menu-form-addbookmark" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ActionAddToBookmarks" value="x" />
</form>

{* Remove node. *}
<form id="menu-form-remove" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="TopLevelNode" value="%nodeID%" />
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ContentObjectID" value="%objectID%" />
  <input type="hidden" name="ActionRemove" value="x" />
</form>

{* Move node. *}
<form id="menu-form-move" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="TopLevelNode" value="%nodeID%" />
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ContentObjectID" value="%objectID%" />
  <input type="hidden" name="MoveNodeButton" value="x" />
</form>

{* Add to notifications. *}
<form id="menu-form-notify" method="post" action={"/content/action"|ezurl}>
  <input type="hidden" name="ContentNodeID" value="%nodeID%" />
  <input type="hidden" name="ActionAddToNotification" value="x" />
</form>
