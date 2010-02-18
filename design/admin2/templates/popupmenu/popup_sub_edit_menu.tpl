<!-- Edit menu -->
<script type="text/javascript">
<!--
menuArray['EditSubmenu'] = {ldelim} 'depth': 1 {rdelim};
menuArray['EditSubmenu']['elements'] = {ldelim}{rdelim};
menuArray['EditSubmenu']['elements']['edit-languages'] = {ldelim} 'variable': '%languages%' {rdelim};
menuArray['EditSubmenu']['elements']['edit-languages']['content'] = '<a href={"/content/edit/%objectID%/f/%locale%"|ezurl} onmouseover="ezpopmenu_mouseOver( \'EditSubmenu\' )">%name%<\/a>';
menuArray['EditSubmenu']['elements']['edit-languages-another'] = {ldelim} 'url': {"/content/edit/%objectID%/a"|ezurl} {rdelim};
// -->
</script>

<div class="popupmenu" id="EditSubmenu">
    <div id="edit-languages"></div>
    <hr />
    <a id="edit-languages-another" href="#" onmouseover="ezpopmenu_mouseOver( 'EditSubmenu' )">{'New translation'|i18n( 'design/admin/popupmenu' )}</a>
</div>