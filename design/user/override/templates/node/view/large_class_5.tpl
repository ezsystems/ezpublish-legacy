{default content_object=$node.object}

<table cellspacing="0" cellpadding="0" border="0">
<tr>
   <td>{attribute_view_gui attribute=$content_object.data_map.image}</td>
</tr>
<tr>
   <td>
   <font color="white">
   {attribute_view_gui attribute=$content_object.data_map.caption}
   </font>
   </td>
</tr>
</table>
{/default}