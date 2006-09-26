{section show=eq(ezini( 'TreeMenu', 'DynamicTreeEnabled', 'contentstructuremenu.ini' ), "true")}
    {include uri="design:contentstructuremenu/dynamic_content_structure_menu.tpl"}
{section-else}
    {* This is the normal content_structure_menu.tpl as it comes with eZ publish 3.6.2 *}
    {include uri="design:contentstructuremenu/static_content_structure_menu.tpl"}
{/section}
