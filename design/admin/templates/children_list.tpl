<div class="content-navigation-childlist">
    <table class="list" cellspacing="0">
    <tr>
        {* Remove column *}
        <th class="remove"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" title="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" onclick="ezjs_toggleCheckboxes( document.children, 'DeleteIDArray[]' ); return false;" /></th>

        {* Name column *}
        <th class="name">{'Name'|i18n( 'design/admin/node/view/full' )}</th>

        {* Class type column *}
        <th class="class">{'Type'|i18n( 'design/admin/node/view/full' )}</th>

        {* Priority column *}
        {if eq( $node.sort_array[0][0], 'priority' )}
            <th class="priority">{'Priority'|i18n( 'design/admin/node/view/full' )}</th>
        {/if}

        {* Edit column *}
        <th class="edit">&nbsp;</th>
    </tr>

    {section var=Nodes loop=$children sequence=array( bglight, bgdark )}
    {let child_name=$Nodes.item.name|wash
         node_name=$node.name
         nodeContent=$Nodes.item.object}

        <tr class="{$Nodes.sequence}">

        {* Remove checkbox *}
        <td>
        {if $Nodes.item.can_remove}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'Use these checkboxes to select items for removal. Click the "Remove selected" button to  remove the selected items.'|i18n( 'design/admin/node/view/full' )|wash()}" />
            {else}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'You do not have permission to remove this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
        {/if}
        </td>

        {* Name *}
        <td>{node_view_gui view=line content_node=$Nodes.item}
        {if $nodeContent.class_identifier|eq('user')}
            {if not($nodeContent.data_map['user_account'].content.is_enabled)}
            <span class="userstatus-disabled">{'(disabled)'|i18n("design/admin/node/view/full")}</span>
            {/if}
            {if $nodeContent.data_map['user_account'].content.is_locked}
            <span class="userstatus-disabled">{'(locked)'|i18n("design/admin/node/view/full")}</span>
            {/if}
        {/if}
         </td>

        {* Class type *}
        <td class="class">{$Nodes.item.class_name|wash()}</td>

        {* Priority *}
        {if eq( $node.sort_array[0][0], 'priority' )}
            <td>
            {if $node.can_edit}
                <input type="text" name="Priority[]" size="3" value="{$Nodes.item.priority}" title="{'Use the priority fields to control the order in which the items appear. You can use both positive and negative integers. Click the "Update priorities" button to apply the changes.'|i18n( 'design/admin/node/view/full' )|wash()}" />
                <input type="hidden" name="PriorityID[]" value="{$Nodes.item.node_id}" />
                {else}
                <input type="text" name="Priority[]" size="3" value="{$Nodes.item.priority}" title="{'You are not allowed to update the priorities because you do not have permission to edit <%node_name>.'|i18n( 'design/admin/node/view/full',, hash( '%node_name', $node_name ) )|wash}" disabled="disabled" />
            {/if}
            </td>
        {/if}

        {* Edit button *}
        <td>
        {if $Nodes.item.can_edit}
            <a href={concat( 'content/edit/', $Nodes.item.contentobject_id )|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" /></a>
        {else}
            <img src={'edit-disabled.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to edit %child_name.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" />
        {/if}
        </td>
  </tr>

{/let}
{/section}

</table>
</div>
