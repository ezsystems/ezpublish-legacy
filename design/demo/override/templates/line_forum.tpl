{* Forum template *}

{default content_version=$node.contentobject_version_object
         node_name=$node.name}

<table class="forumfront" width="150" border="0" cellspacing="0" cellpadding="3">
<tr>
   <th class="forumheader">
   <a href={concat( 'content/view/full/', $node.node_id, '/' )|ezurl}>{$node_name|wash}</a>
   </th>
</tr>
<tr>
    <td>
    &nbsp;
    </td>
</tr>
<tr>
   <td class="forumimage">
   <a href={concat( 'content/view/full/', $node.node_id, '/' )|ezurl}>{attribute_view_gui attribute=$content_version.data_map.icon}</a>
   </td>
</tr>
<tr>
    <td>
    &nbsp;
    </td>
</tr>
<tr>
    <td>
        <h3>Latest</h3>
        <ul>
            {section name=Message loop=fetch( content, tree, hash( 
                                                            parent_node_id, $node.node_id, 
                                                            limit, 5,
                                                            sort_by, array( published, false() ),
                                                            class_filter_type, include,
                                                            class_filter_array, array( 7 ) ) ) }
               <li>{node_view_gui view=line content_node=$Message:item}</li>
           {/section}
       </ul>
    </td>
</tr>
</table>

{/default}