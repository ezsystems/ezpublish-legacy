{default content_version=$node.contentobject_version_object
         node_name=$node.name}

<table width="150" border="0" cellspacing="0" cellpadding="3">
<tr>
   <td bgcolor="#FF9900" style="border-style: solid; border-width: 1px; border-color: black;">
   &nbsp;&nbsp;<a href={concat('content/view/full/',$node.node_id,'/')|ezurl}><b class="small">{$node_name}</b></td>
<tr>
<tr>
    <td>
    &nbsp;
    </td>
<tr>
<tr>
   <td align="center" style="border-style: solid; border-width: 1px; border-color: black;">
   <table width="100%" border="0" cellspacing="4" cellpadding="0">
   <tr>
       <td align="center">
       <a href={concat('content/view/full/',$node.node_id,'/')|ezurl}>{attribute_view_gui attribute=$content_version.data_map.icon}</a>
       </td>
   </tr>
   </table>
   </td>
</tr>
<tr>
    <td>
    &nbsp;
    </td>
<tr>
<tr>
   <td align="left">
   <b class="small">Latest:</b><br />

   <table width="100%" border="0" cellspacing="4" cellpadding="0">
   {section name=Message loop=fetch(content,tree,hash(parent_node_id,$node.node_id,limit,5,sort_by,array(published,false()),class_filter_type,include,class_filter_array,array(8)))}
   <tr>
       <td valign="top">
       -
       </td>
       <td>
          {node_view_gui view=line content_node=$Message:item}
       <td>
   </tr>
   {/section}
   </table>
   </td>
</tr>
</table>

{/default}