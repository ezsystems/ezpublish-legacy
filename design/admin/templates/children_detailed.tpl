<div class="content-navigation-childlist">
    <table class="list" cellspacing="0">
    <tr>
        {* Remove column *}
        <th class="remove"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" title="{'Invert selection.'|i18n( 'design/admin/node/view/full' )}" onclick="ezjs_toggleCheckboxes( document.children, 'DeleteIDArray[]' ); return false;" /></th>

        {* Name column *}
        <th class="name">{'Name'|i18n( 'design/admin/node/view/full' )}</th>

        {* Hidden/Invisible column *}
        <th class="hidden_invisible">{'Status'|i18n( 'design/admin/node/view/full' )}</th>

        {* Class type column *}
        <th class="class">{'Type'|i18n( 'design/admin/node/view/full' )}</th>

        {* Modifier column *}
        <th class="modifier">{'Modifier'|i18n( 'design/admin/node/view/full' )}</th>

        {* Modified column *}
        <th class="modified">{'Modified'|i18n( 'design/admin/node/view/full' )}</th>

        {* Section column *}
        <th class="section">{'Section'|i18n( 'design/admin/node/view/full' )}</th>

        {* Priority column *}
        {section show=eq( $node.sort_array[0][0], 'priority' )}
            <th class="priority">{'Priority'|i18n( 'design/admin/node/view/full' )}</th>
        {/section}

        {* Copy column *}
        <th class="copy">&nbsp;</th>

        {* Edit column *}
        <th class="edit">&nbsp;</th>
    </tr>

    {section var=Nodes loop=$children sequence=array( bglight, bgdark )}
    {let child_name=$Nodes.item.name
         node_name=$node.name}

        <tr class="{$Nodes.sequence}">

        {* Remove checkbox *}
        <td>
        {section show=$Nodes.item.can_remove}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'Use these checkboxes to mark items for removal. Click the "Remove selected" button to actually remove the selected items.'|i18n( 'design/admin/node/view/full' )|wash} "/>
            {section-else}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'You do not have permissions to mark this item for removal.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
        {/section}
        </td>

        {* Name *}
        <td>{node_view_gui view=line content_node=$Nodes.item}</td>

        {* Hidden/Invisible *}
        <td>
        {section show=$Nodes.item.can_edit}
            <a href={concat( 'content/hide/', $Nodes.item.node_id )|ezurl}>{$Nodes.item.hidden_invisible_string}</a>
        {section-else}
            {$Nodes.item.hidden_invisible_string}
        {/section}
        </td>

        {* Class type *}
        <td>{$Nodes.item.class_name|wash}</td>

        {* Modifier *}
        <td class="modifier"><a href={$node.object.current.creator.main_node.url_alias|ezurl}>{$Nodes.item.object.current.creator.name|wash}</a></td>

        {* Modified *}
        <td class="modified">{$Nodes.item.object.modified|l10n( shortdatetime )}</td>

        {* Section *}
        <td>{fetch( section, object, hash( section_id, $Nodes.item.object.section_id ) ).name|wash}</td>

        {* Priority *}
        {section show=eq( $node.sort_array[0][0], 'priority' )}
            <td>
            {section show=$node.can_edit}
                <input class="priority" type="text" name="Priority[]" size="3" value="{$Nodes.item.priority}" title="{'Use the priority fields to control the order in which the items appear. Use positive and negative integers. Click the "Update priorities" button to apply the changes.'|i18n( 'design/admin/node/view/full' )|wash}" />
                <input type="hidden" name="PriorityID[]" value="{$Nodes.item.node_id}" />
                {section-else}
                <input class="priority" type="text" name="Priority[]" size="3" value="{$Nodes.item.priority}" title="{'You are not allowed to update the priorities because you do not have permissions to edit <%node_name>.'|i18n( 'design/admin/node/view/full',, hash( '%node_name', $node_name ) )|wash}" disabled="disabled" />
            {/section}
            </td>
        {/section}

    {* Copy button *}
    <td>
    {section show=$can_copy}
    <a href={concat( 'content/copy/', $Nodes.item.contentobject_id )|ezurl}><img src={'copy.gif'|ezimage} alt="{'Copy'|i18n( 'design/admin/node/view/full' )}" title="{'Create a copy of <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" /></a>
    {section-else}
    <img src={'copy_disabled.png'|ezimage} alt="{'Copy'|i18n( 'design/admin/node/view/full' )}" title="{'You can not make a copy of <%child_name> because you do not have create permissions for <%node_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name, '%node_name', $node_name ) )|wash}" />
    {/section}
    </td>

        {* Edit button *}
        {* section show=$can_edit *}
        <td>
        {section show=$Nodes.item.can_edit}
            <a href={concat( 'content/edit/', $Nodes.item.contentobject_id )|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" /></a>
        {section-else}
            <img src={'edit_disabled.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permissions to edit <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" /></a>
        {/section}
        </td>
        {* /section *}
  </tr>

{/let}
{/section}

</table>
</div>

