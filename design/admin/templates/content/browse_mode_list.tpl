<table class="list" cellspacing="0">
<tr>
    <th class="tight">
    {section show=eq( $select_type, 'checkbox' )}
        <img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/content/browse' )}" title="{'Invert selection.'|i18n( 'design/admin/content/browse' )}" onclick="ezjs_toggleCheckboxes( document.browse, '{$select_name}[]' ); return false;" />
    {section-else}
        &nbsp;
    {/section}
    </th>
    <th class="wide">
    {'Name'|i18n( 'design/admin/content/browse' )}
    </th>
    <th class="tight">
    {'Type'|i18n( 'design/admin/content/browse' )}
    </th>
</tr>

{section var=Nodes loop=$node_array sequence=array( bglight, bgdark )}
  <tr class="{$Nodes.sequence}">
    <td>
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
    </td>
    <td>
    {* Replaces node_view_gui... *}
    {section show=$browse.ignore_nodes_click|contains( $Nodes.item.node_id )|not}
        {section show=and( or( ne( $browse.action_name, 'MoveNode' ), ne( $browse.action_name, 'CopyNode' ) ), $Nodes.item.object.content_class.is_container )}
            {$Nodes.item.object.class_identifier|class_icon( small, $Nodes.item.object.class_name )}&nbsp;<a href={concat( '/content/browse/', $Nodes.item.node_id )|ezurl}>{$Nodes.item.name|wash}</a>
        {section-else}
            {$Nodes.item.object.class_identifier|class_icon( small, $Nodes.item.object.class_name )}&nbsp;{$Nodes.item.name|wash}
        {/section}
    {section-else}
        {$Nodes.item.object.class_identifier|class_icon( small, $Nodes.item.object.class_name )}&nbsp;{$Nodes.item.name|wash}
    {/section}
    </td>
    <td class="class">
    {$Nodes.item.object.content_class.name|wash}
    </td>
 </tr>
{/section}
</table>