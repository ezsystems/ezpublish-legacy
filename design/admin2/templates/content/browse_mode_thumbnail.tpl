<table class="list-thumbnails" cellspacing="0">
  <tr>
    {section var=Nodes loop=$node_array}
    {* Note: The tpl code for $ignore_nodes_merge with the eq, unique and count
             is just a replacement for a missing template operator.
             If there are common elements the unique array will have less elements
             than the merged one
             In the future this should be replaced with a  new template operator that checks
             one array against another and returns true if elements in the first
             exists in the other *}
    {let child_name=$Nodes.item.name|wash
         ignore_nodes_merge_click=merge( $browse.ignore_nodes_click, $Nodes.item.path_array )
         ShowLink=and( eq( $ignore_nodes_merge_click|count,
                            $ignore_nodes_merge_click|unique|count ),
                       or( ne( $browse.action_name, 'MoveNode' ), ne( $browse.action_name, 'CopyNode' ) ),
                       $Nodes.item.object.content_class.is_container ) }
        <td width="25%">
        {* $ShowLink is passed in node_view_gui for corresponding representation of image. *}
        {node_view_gui view=browse_thumbnail content_node=$Nodes.item show_link=$ShowLink}
        <div class="controls">
        {* Checkboxes *}
        {* Note: The tpl code for $ignore_nodes_merge with the eq, unique and count
                 is just a replacement for a missing template operator.
                 If there are common elements the unique array will have less elements
                 than the merged one
                 In the future this should be replaced with a  new template operator that checks
                 one array against another and returns true if elements in the first
                 exists in the other *}
        {let ignore_nodes_merge=merge( $browse.ignore_nodes_select_subtree, $Nodes.item.path_array )
            browse_permission = true()}
        {if $browse.permission}
            {if $browse.permission.contentclass_id}
                {if is_array( $browse.permission.contentclass_id )}
                    {foreach $browse.permission.contentclass_id as $contentclass_id}
                        {set $browse_permission = fetch( 'content', 'access', hash( 'access', $browse.permission.access,
                                                                           'contentobject',   $Nodes.item,
                                                                           'contentclass_id', $contentclass_id ) )}
                        {if $browse_permission|not}{break}{/if}
                    {/foreach}
                {else}
                    {set $browse_permission = fetch( 'content', 'access', hash( 'access', $browse.permission.access,
                                                                       'contentobject',   $Nodes.item,
                                                                       'contentclass_id', $browse.permission.contentclass_id ) )}
                {/if}
            {else}
                {set $browse_permission = fetch( 'content', 'access', hash( 'access', $browse.permission.access,
                                                                   'contentobject',   $Nodes.item ) )}
            {/if}
        {/if}
        {if and( $browse_permission,
                           $browse.ignore_nodes_select|contains( $Nodes.item.node_id )|not,
                           eq( $ignore_nodes_merge|count,
                               $ignore_nodes_merge|unique|count ) )}
            {if is_array( $browse.class_array )}
                {if $browse.class_array|contains( $Nodes.item.object.content_class.identifier )}
                    <input type="{$select_type}" name="{$select_name}[]" value="{$Nodes.item[$select_attribute]}" />
                {else}
                    <input type="{$select_type}" name="_Disabled" value="" disabled="disabled" />
                {/if}
            {else}
                {if and( or( eq( $browse.action_name, 'MoveNode' ), eq( $browse.action_name, 'CopyNode' ), eq( $browse.action_name, 'AddNodeAssignment' ) ), $Nodes.item.object.content_class.is_container|not )}
                    <input type="{$select_type}" name="{$select_name}[]" value="{$Nodes.item[$select_attribute]}" disabled="disabled" />
                {else}
                    <input type="{$select_type}" name="{$select_name}[]" value="{$Nodes.item[$select_attribute]}" />
                {/if}
            {/if}
        {else}
            <input type="{$select_type}" name="_Disabled" value="" disabled="disabled" />
        {/if}
        {/let}

        <p>{if $ShowLink}
            <a href={concat( '/content/browse/', $Nodes.item.node_id )|ezurl}>{$Nodes.item.name|wash}</a>
        {else}
            {$Nodes.item.name|wash}
        {/if}</p>
        </div>
        </td>
    {/let}
    {delimiter modulo=4}
    </tr><tr>
    {/delimiter}
    {/section}
  </tr>
</table>