{default with_children=true()}
{section show=$with_children}

{let page_limit=8
     list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))}

<table width="450" height="433" border="0" align="top" cellpadding="0" cellspacing="0">
{section name=Child loop=fetch('content','list',hash(parent_node_id,$node.node_id,limit,$page_limit,offset,$view_parameters.offset))}
<tr>
<td valign="top">
	{node_view_gui view=medium content_node=$Child:item}
</td>
</tr>
{/section}
</table>
{/let}

{/section}
{/default}