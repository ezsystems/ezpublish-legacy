{let
children=fetch('content', 'list', hash(
					parent_node_id, $#DesignKeys:used.node, 
					sort_by, array(array(priority,true())), 
					class_filter_type,include,
					class_filter_array,array(1,6)
					)
)
}

<h1>{$node.name|wash}</h1>
<p>{attribute_view_gui attribute=$node.data_map.description}</p>

<table width="100%" cellpadding="0" cellspacing="0" border="0">

{switch match=$#DesignKeys:used.depth}
{case match=1}
{/case}
{case}

{section name=Child loop=$children}
<tr>
    <td>
    {node_view_gui view=line content_node=$Child:item}
    </td>
</tr>
{/section}
{/case}    
{/switch}
</table>

{/let}