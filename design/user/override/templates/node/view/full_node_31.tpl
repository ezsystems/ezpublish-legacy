
<table width="100%">
<tr>
    {section name=Forum loop=fetch(content,list,hash(parent_node_id,32))}
    <td valign="top">
    <table width="150" border="0" cellspacing="0" cellpadding="3">
    <tr>
       <td bgcolor="#cccccc" style="border-style: solid; border-width: 1px; border-color: black;">
       <a href={concat('content/view/full/',$Forum:item.node_id,'/')|ezurl}<b>{$Forum:item.name}</b></td>
    <tr>
    <tr>
       <td align="center">
       <br />
       {attribute_view_gui attribute=$Forum:item.object.data_map.icon}
       </td>
    </tr>
    <tr>
       <td align="left">
       <br />
       {attribute_view_gui attribute=$Forum:item.object.data_map.description}
       </td>
    </tr>
    </table>

    </td>
    {/section}

</tr>
</table>