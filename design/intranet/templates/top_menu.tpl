

<table class="layout" width="700" cellpadding="0" cellspacing="0" border="0">
<tr>

        {* Menubox start *}

	    {let  top_menu_level1=fetch( content, list, hash( parent_node_id, 2, 
                                           limit, 6, 
                                           sort_by, array( priority, true() ),
                                           class_filter_type, include,
                                           class_filter_array, array( 'folder' ) ) ) }

            {section name=Mitem1 loop=$top_menu_level1}
                    <td class="topmenu" background="/design/intranet/images/intranet-top-background-repeat.gif" colspan="2">
                        <a href={$Mitem1:item.url_alias|ezurl}>{$Mitem1:item.name|wash}</a>
	            </td>
            {/section}
	    {/let}
        {* Menubox stop *}    
<tr/>
<tr>
<td>
	    {let  top_menu_level2=fetch( content, list, hash( parent_node_id, $node.node_id, 
                                           limit, 6, 
                                           sort_by, array( priority, true() ),
                                           class_filter_type, include,
                                           class_filter_array, array( 'info_page' ) ) ) }

            {section name=Mitem1 loop=$top_menu_level2}
                    <td class="topmenu" background="/design/intranet/images/intranet-top-background-repeat.gif" colspan="2">
                        <a href={$Mitem1:item.node_id|ezurl}>{$Mitem1:item.name|wash}</a>
	            </td>
            {/section}
	    {/let}

<td/>
</tr>

</table>
