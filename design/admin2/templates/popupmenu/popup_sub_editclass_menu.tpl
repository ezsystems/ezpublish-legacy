<!-- Edit class submenu -->
<script type="text/javascript">
<!--
menuArray['EditClassSubmenu'] = {ldelim} 'depth': 1 {rdelim};
menuArray['EditClassSubmenu']['elements'] = {ldelim}{rdelim};
menuArray['EditClassSubmenu']['elements']['edit-class-languages'] = {ldelim} 'variable': '%languages%' {rdelim};
menuArray['EditClassSubmenu']['elements']['edit-class-languages']['content'] = '<a href={"/class/edit/%classID%/(language)/%locale%"|ezurl} onmouseover="ezpopmenu_mouseOver( \'EditClassSubmenu\' )">%name%<\/a>';
menuArray['EditClassSubmenu']['elements']['edit-class-another-language'] = {ldelim} 'url': {"/class/edit/%classID%"|ezurl} {rdelim};
menuArray['EditClassSubmenu']['elements']['edit-class-another-language']['disabled_class'] = 'menu-item-disabled';
// -->
</script>

<div class="popupmenu" id="EditClassSubmenu">
    <div id="edit-class-languages"></div>
    <hr />
    <!-- <a id="edit-class-another-language" href="#" onmouseover="ezpopmenu_mouseOver( 'EditClassSubmenu' )">{'Another language'|i18n( 'design/admin/popupmenu' )}</a> -->
    <!-- <div id="edit-class-another-language"></div> -->
    <a id="edit-class-another-language" href="#" onmouseover="ezpopmenu_mouseOver( 'EditClassSubmenu' )">{'Another language'|i18n( 'design/admin/popupmenu' )}</a>
</div>