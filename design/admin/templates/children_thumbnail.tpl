{* This template displays a collection of child nodes as thumbnails. *}
{* It is included/used from within the child.tpl if the user's viewmode is set to list. *}
<div class="content-navigation-childlist">
    <table class="list-thumbnails" cellspacing="0">

<tr>
    {section var=Nodes loop=$children sequence=array( bglight, bgdark )}
    {let quoted_child=concat( '&quot;', $Nodes.item.name|wash(), '&quot;' )
         quoted_node=concat( '&quot;', $node.name|wash(), '&quot;'  )}
<td width="25%">
        {node_view_gui view=thumbnail content_node=$Nodes.item}

        <div class="controls">
        {* Remove checkbox *}
        {section show=$Nodes.item.object.can_remove}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'Use these checkboxes to mark items for removal. Click the "Remove selected" button to actually remove the selected items.'|i18n( 'design/admin/layout' )|wash()} "/>
            {section-else}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'You do not have permissions to mark this item for removal.'|i18n( 'design/admin/layout' )}" disabled="disabled" />
        {/section}
        <a href={$Nodes.url_alias|ezurl}>{$Nodes.name|wash}</a>
        {* Priority *}
{*        {section show=eq( $node.sort_array[0][0], 'priority' )}

            {section show=$node.object.can_edit}
                <input type="text" name="Priority[]" size="2" value="{$Nodes.item.priority}" title="{'Use the priority fields to control the order in which the items appear. Use positive and negative integers. Click the "Update priorities" button to apply the changes.'|i18n( 'design/admin/layout')|wash()}" />
                <input type="hidden" name="PriorityID[]" value="{$Nodes.item.node_id}" />
                {section-else}
                <input type="text" name="Priority[]" size="2" value="{$Nodes.item.priority}" title="{'You are not allowed to update the priorities because you do not have permissions to edit %quoted_node'|i18n( 'admin/design/layout',, hash( '%quoted_node', $quoted_node ) )}" disabled="disabled" />
            {/section}

        {/section}
*}
        {* Edit button *}
        {section show=$Nodes.item.object.can_edit}
            <a href={concat( 'content/edit/', $Nodes.item.contentobject_id )|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/layout')}" title="{'Click here to edit %quoted_child.'|i18n( 'design/admin/layout',, hash( '%quoted_child', $quoted_child ) )}" /></a>
        {section-else}
            <img src={'edit_disabled.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/layout' )}" title="{'You do not have permissions to edit %quoted_child.'|i18n( 'design/admin/layout',,hash( '%quoted_child', $quoted_child ) )}" /></a>
        {/section}
        </div>
{/let}
</td>
{delimiter modulo=4}
    </tr><tr>
{/delimiter}

{/section}
</table>
</div>
