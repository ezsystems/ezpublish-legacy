{let
child_folders=fetch('content', 'list', hash(
					parent_node_id, $#DesignKeys:used.node, 
					sort_by, array(array(priority,true())), 
					class_filter_type,include,
					class_filter_array,array(1,6)
					)
)
childs=fetch('content', 'list', hash( parent_node_id, $#module_result.node_id ))

}
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
{*    {node_view_gui view=full content_node=$node} *}
</tr>
{switch match=$#DesignKeys:used.depth}
{case match=1}
{/case}
{case}

{section name=Child loop=$child_folders}
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