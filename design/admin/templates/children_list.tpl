<div class="content-navigation-childlist">
    <table class="list" cellspacing="0">
    <tr>
        {* Remove column *}
        <th class="remove"> &nbsp; </th>

        {* Name column *}
        <th class="name">{'Name'|i18n( 'design/admin/layout ')}</th>

        {* Class type column *}
        <th class="class">{'Type'|i18n( 'design/admin/layout ')}</th>

        {* Priority column *}
        {section show=eq( $node.sort_array[0][0], 'priority' )}
            <th class="priority">{'Priority'|i18n( 'design/standard/node/view' )}</th>
        {/section}

        {* Edit column *}
        <th class="edit">{'Edit'|i18n( 'design/standard/node/view' )}</th>
    </tr>

    {section var=Nodes loop=$children sequence=array( bglight, bgdark )}
    {let quoted_child=concat( '"', $Nodes.item.name, '"' )|wash()
         quoted_node=concat( '"', $node.name, '"' )|wash()}

        <tr class="{$Nodes.sequence}">

        {* Remove checkbox *}
        <td>
        {section show=$Nodes.item.can_remove}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'Use these checkboxes to mark items for removal. Click the "Remove selected" button to actually remove the selected items.'|i18n( 'design/admin/layout' )|wash()} "/>
            {section-else}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'You do not have permissions to mark this item for removal.'|i18n( 'design/admin/layout' )}" disabled="disabled" />
        {/section}
        </td>

        {* Name *}
        <td>{node_view_gui view=line content_node=$Nodes.item}</td>

        {* Class type *}
        <td>{$Nodes.item.object.class_name|wash()}</td>

        {* Priority *}
        {section show=eq( $node.sort_array[0][0], 'priority' )}
            <td>
            {section show=$node.can_edit}
                <input type="text" name="Priority[]" size="3" value="{$Nodes.item.priority}" title="{'Use the priority fields to control the order in which the items appear. Use positive and negative integers. Click the "Update priorities" button to apply the changes.'|i18n( 'design/admin/layout')|wash()}" />
                <input type="hidden" name="PriorityID[]" value="{$Nodes.item.node_id}" />
                {section-else}
                <input type="text" name="Priority[]" size="3" value="{$Nodes.item.priority}" title="{'You are not allowed to update the priorities because you do not have permissions to edit %quoted_node'|i18n( 'admin/design/layout',, hash( '%quoted_node', $quoted_node ) )}" disabled="disabled" />
            {/section}
            </td>
        {/section}

        {* Edit button *}
        <td>
        {section show=$Nodes.item.can_edit}
            <a href={concat( 'content/edit/', $Nodes.item.contentobject_id )|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/layout')}" title="{'Click here to edit %quoted_child.'|i18n( 'design/admin/layout',, hash( '%quoted_child', $quoted_child ) )}" /></a>
        {section-else}
            <img src={'edit_disabled.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/layout' )}" title="{'You do not have permissions to edit %quoted_child.'|i18n( 'design/admin/layout',,hash( '%quoted_child', $quoted_child ) )}" /></a>
        {/section}
        </td>
  </tr>

{/let}
{/section}

</table>
</div>

