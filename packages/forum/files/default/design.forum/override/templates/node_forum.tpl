<table class="layout" width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
    {section name=Forum loop=fetch(content,list,hash(parent_node_id,$node.node_id))}
    <td valign="top">
    {node_view_gui view=line content_node=$Forum:item}
    </td>
    {/section}
</tr>
</table>
