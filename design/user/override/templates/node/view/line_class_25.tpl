{default content_object=$node.object
         node_name=$node.name}

<table width="100%" cellspacing="1" cellpadding="4" bgcolor="#000000">
<tr>
    <th bgcolor="#FF9900" width="100%">
    <span class="small">{$node_name}</span>
    </th>
</tr>
<tr>
    <td bgcolor="#FDF4D9" width="100%">
    {attribute_view_gui attribute=$content_object.data_map.description}
    <span class="small">
    URL:
    {attribute_view_gui attribute=$content_object.data_map.link}
    </span>
    </td>
</tr>

</table>
<br />

{/default}