{let
children=fetch('content', 'tree', hash(
					parent_node_id, 2, 
					sort_by, array(array('path',true()),array('priority',true())), 
					class_filter_type,include,
					class_filter_array,array(1,6)
					)
)
}

<h1>{$node.name}</h1>


<table width="100%" cellpadding="0" cellspacing="0" border="0">

{section name=Child loop=$children}
<tr>
    <td>
    <p class="menuitem">
    {section name=spacer loop=sub($Child:item.depth,2)}
    &nbsp;&nbsp;
    {/section}
    <a class="menuitem" href={$Child:item.url_alias|ezurl}>{$Child:item.name}</a>
    </p>
    </td>
</tr>
{/section}

</table>


{/let}
