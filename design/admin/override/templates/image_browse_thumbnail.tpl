<b>{$node.name|wash}</b>
<table width="150" height="150" class="list">
<tr>
    <td align="center" class="bglight">
    <a href={concat('/content/browse/',$node.node_id)|ezurl}>{attribute_view_gui attribute=$node.object.data_map.image image_class=small}</a>
    {attribute_view_gui attribute=$node.object.data_map.caption}
    </td>
</tr>
</table>
