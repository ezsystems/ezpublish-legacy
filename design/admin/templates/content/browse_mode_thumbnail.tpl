<table class="list-thumbnails" cellspacing="0">
  <tr>
    {section var=Nodes loop=$node_array sequence=array( bglight, bgdark )}
    {let child_name=$Nodes.item.name|wash
         ShowLink=and( $browse.ignore_nodes_click|contains( $Nodes.item.node_id )|not,
                       or( ne( $browse.action_name, 'MoveNode' ), ne( $browse.action_name, 'CopyNode' ) ),
                       $Nodes.item.object.content_class.is_container ) }
        <td width="25%">
        {* $ShowLink is passed in node_view_gui for corresponding representation of image. *}
        {node_view_gui view=browse_thumbnail content_node=$Nodes.item show_link=$ShowLink}
        <div class="controls">
        {* Checkboxes *}
        {section show=and( or( $browse.permission|not,
                           cond( is_set( $browse.permission.contentclass_id ),
                                 fetch( content, access, hash( access,          $browse.permission.access,
                                                               contentobject,   $Nodes.item,
                                                               contentclass_id, $browse.permission.contentclass_id ) ),
                                 fetch( content, access, hash( access,          $browse.permission.access,
                                                               contentobject,   $Nodes.item ) ) ) ),
                           $browse.ignore_nodes_select|contains( $Nodes.item.node_id )|not() )}
            {section show=is_array( $browse.class_array )}
                {section show=$browse.class_array|contains( $Nodes.item.object.content_class.identifier )}
                    <input type="{$select_type}" name="{$select_name}[]" value="{$Nodes.item[$select_attribute]}" />
                {section-else}
                    <input type="{$select_type}" name="" value="" disabled="disabled" />
                {/section}
            {section-else}
                {section show=and( or( eq( $browse.action_name, 'MoveNode' ), eq( $browse.action_name, 'CopyNode' ) ), $Nodes.item.object.content_class.is_container|not )}
                    <input type="{$select_type}" name="{$select_name}[]" value="{$Nodes.item[$select_attribute]}" disabled="disabled" />
                {section-else}
                    <input type="{$select_type}" name="{$select_name}[]" value="{$Nodes.item[$select_attribute]}" />
                {/section}
            {/section}
        {section-else}
            <input type="{$select_type}" name="" value="" disabled="disabled" />
        {/section}

        <p>{section show=$ShowLink}
            <a href={concat( '/content/browse/', $Nodes.item.node_id )|ezurl}>{$Nodes.item.name|wash}</a>
        {section-else}
            {$Nodes.item.name|wash}
        {/section}</p>
        </div>
        </td>
    {/let}
    {delimiter modulo=4}
    </tr><tr>
    {/delimiter}
    {/section}
  </tr>
</table>