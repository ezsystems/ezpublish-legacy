<table width="100%">
<tr>
    {section name=Forum loop=fetch(content,list,hash(parent_node_id,32))}
    <td valign="top">
    {node_view_gui view=line content_node=$Forum:item}
    </td>
    {/section}

</tr>
</table>