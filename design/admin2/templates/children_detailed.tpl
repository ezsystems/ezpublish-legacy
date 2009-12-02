<div class="content-navigation-childlist">
    <table class="list" cellspacing="0">
    <tr>
        {* Remove column *}
        <th class="remove"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" title="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" onclick="ezjs_toggleCheckboxes( document.children, 'DeleteIDArray[]' ); return false;" /></th>

        {* Name column *}
        <th class="name">{'Name'|i18n( 'design/admin/node/view/full' )}</th>

        {* Hidden/Invisible column *}
        <th class="hidden_invisible">{'Visibility'|i18n( 'design/admin/node/view/full' )}</th>

        {* Class type column *}
        <th class="class">{'Type'|i18n( 'design/admin/node/view/full' )}</th>

        {* Modifier column *}
        <th class="modifier">{'Modifier'|i18n( 'design/admin/node/view/full' )}</th>

        {* Modified column *}
        <th class="modified">{'Modified'|i18n( 'design/admin/node/view/full' )}</th>

        {* Section column *}
        <th class="section">{'Section'|i18n( 'design/admin/node/view/full' )}</th>

        {* Priority column *}
        {if eq( $node.sort_array[0][0], 'priority' )}
            <th class="priority">{'Priority'|i18n( 'design/admin/node/view/full' )}</th>
        {/if}

        {* Copy column *}
        <th class="copy">&nbsp;</th>

        {* Move column *}
        <th class="move">&nbsp;</th>

        {* Edit column *}
        <th class="edit">&nbsp;</th>
    </tr>

    {section var=Nodes loop=$children sequence=array( bglight, bgdark )}
    {let child_name=$Nodes.item.name|wash
         node_name=$node.name
         section_object=fetch( section, object, hash( section_id, $Nodes.object.section_id ) )}

        <tr class="{$Nodes.sequence}">

        {* Remove checkbox *}
        <td>
        {if $Nodes.item.can_remove}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'Use these checkboxes to select items for removal. Click the "Remove selected" button to remove the selected items.'|i18n( 'design/admin/node/view/full' )|wash}" />
            {else}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'You do not have permission to remove this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
        {/if}
        </td>

        {* Name *}
        <td>{node_view_gui view=line content_node=$Nodes.item}</td>

        {* Visibility. *}
        <td class="nowrap">{$Nodes.item.hidden_status_string}</td>

        {* Class type *}
        <td class="class">{$Nodes.item.class_name|wash}</td>

        {* Modifier *}
        <td class="modifier"><a href={$Nodes.item.object.current.creator.main_node.url_alias|ezurl}>{$Nodes.item.object.current.creator.name|wash}</a></td>

        {* Modified *}
        <td class="modified">{$Nodes.item.object.modified|l10n( shortdatetime )}</td>

        {* Section *}
        <td>{section show=$section_object}<a href={concat( '/section/view/', $Nodes.object.section_id )|ezurl}>{$section_object.name|wash}</a>{section-else}<i>{'Unknown'|i18n( 'design/admin/node/view/full' )}</i>{/section}</td>

        {* Priority *}
        {if eq( $node.sort_array[0][0], 'priority' )}
            <td>
            {if $node.can_edit}
                <input class="priority" type="text" name="Priority[]" size="3" value="{$Nodes.item.priority}" title="{'Use the priority fields to control the order in which the items appear. You can use both positive and negative integers. Click the "Update priorities" button to apply the changes.'|i18n( 'design/admin/node/view/full' )|wash}" />
                <input type="hidden" name="PriorityID[]" value="{$Nodes.item.node_id}" />
                {else}
                <input class="priority" type="text" name="Priority[]" size="3" value="{$Nodes.item.priority}" title="{'You are not allowed to update the priorities because you do not have permission to edit <%node_name>.'|i18n( 'design/admin/node/view/full',, hash( '%node_name', $node_name ) )|wash}" disabled="disabled" />
            {/if}
            </td>
        {/if}

    {* Copy button *}
    <td>
    {if $can_copy}
    <a href={concat( 'content/copy/', $Nodes.item.contentobject_id )|ezurl}><img src={'copy.gif'|ezimage} alt="{'Copy'|i18n( 'design/admin/node/view/full' )}" title="{'Create a copy of <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" /></a>
    {else}
    <img src={'copy-disabled.gif'|ezimage} alt="{'Copy'|i18n( 'design/admin/node/view/full' )}" title="{'You cannot make a copy of <%child_name> because you do not have create permission for <%node_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name, '%node_name', $node_name ) )|wash}" />
    {/if}
    </td>

    {* Move button. *}
    <td>
    <a href={concat( 'content/move/', $Nodes.item.node_id )|ezurl}><img src={'move.gif'|ezimage} alt="{'Move'|i18n( 'design/admin/node/view/full' )}" title="{'Move <%child_name> to another location.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" /></a>
    </td>

        {* Edit button *}
        {* section show=$can_edit *}
        <td>
        {if $Nodes.item.can_edit}
            <a href={concat( 'content/edit/', $Nodes.item.contentobject_id )|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" /></a>
        {else}
            <img src={'edit-disabled.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to edit <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" />
        {/if}
        </td>
        {* /section *}
  </tr>

{/let}
{/section}

</table>
</div>

