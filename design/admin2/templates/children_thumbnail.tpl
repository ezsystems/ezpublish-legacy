{* This template displays a collection of child nodes as thumbnails. *}
{* It is included/used from within the children.tpl if the user's viewmode is set to list. *}
<div class="content-navigation-childlist">
<table id="ezasi-subitems-list" class="list-thumbnails" cellspacing="0" summary="{'List of sub items of current node, with controlls to edit, remove and move them directly.'|i18n( 'design/admin/node/view/full' )}">
    <tr>
    {section var=Nodes loop=$children}
    {let child_name=$Nodes.item.name|wash}
        <td width="25%">
        {node_view_gui view=thumbnail content_node=$Nodes.item}

        <div class="controls">
        {* Remove checkbox *}
        {if $Nodes.item.can_remove}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'Use these checkboxes to select items for removal. Click the "Remove selected" button to remove the selected items.'|i18n( 'design/admin/node/view/full' )|wash()}" />
            {else}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'You do not have permission to remove this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
        {/if}

        {* Edit button *}
        {if $Nodes.item.can_edit}
            <a href={concat( 'content/edit/', $Nodes.item.contentobject_id )|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" /></a>
        {else}
            <img src={'edit-disabled.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to edit <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" />
        {/if}

        <p><a href={$Nodes.url_alias|ezurl}>{$child_name}</a></p>
        </div>
    {/let}
</td>
{delimiter modulo=4}
</tr><tr>
{/delimiter}
{/section}
</tr>
</table>
</div>
