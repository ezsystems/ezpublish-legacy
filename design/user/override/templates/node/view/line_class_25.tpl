<table width="100%" cellspacing="1" cellpadding="4" bgcolor="#000000">
<tr>
    <th bgcolor="#FF9900" width="100%">
    <span class="small">{$node.object.name}</span>
    </th>
</tr>
<tr>
    <td bgcolor="#FDF4D9" width="100%">
    {attribute_view_gui attribute=$node.object.data_map.description}
    <span class="small">
    URL:
    {attribute_view_gui attribute=$node.object.data_map.link}
    </span>
    </td>
</tr>

</table>
<br />
