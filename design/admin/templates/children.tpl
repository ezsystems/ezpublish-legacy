{* Generic children list for admin interface. *}


<p>Items per page: <a href={'/user/preferences/set/items/1'|ezurl}>10</a> <a href={'/user/preferences/set/items/2'|ezurl}>25</a> <a href={'/user/preferences/set/items/3'|ezurl}>50</a></p>

{let number_of_items=10}

{switch match=ezpreference( 'items' )}
{case match=2}
    {set number_of_items=25}
{/case}
{case match=3}
    {set number_of_items=50}
{/case}
{case}
    {set number_of_items=10}
{/case}
{/switch}

</p>


<form method="post" action={"content/action"|ezurl}>
{let can_remove=false()
     can_edit=false()
     can_create=false()
     can_copy=false()
     children_count=fetch( content, list_count, hash( parent_node_id, $node.node_id ) )
     children=fetch( content, list, hash( parent_node_id, $node.node_id,
                                          sort_by, $node.sort_array,
                                          limit, $number_of_items,
                                          offset, $view_parameters.offset ) ) }

{* If there are children: show list and buttons that belong to the list. *}
{section var=AvailableChildren show=$children}

    {* Copying operation is allowed if the user can create stuff under the current node. *}
    {set can_copy=$node.object.can_create}

    {* Check if the current user is allowed to *}
    {* edit or delete any of the children.     *}

    {section var=Children loop=$children}
        {section show=$Children.item.object.can_remove}
            {set can_remove=true()}
        {/section}
        {section show=$Children.item.object.can_edit}
            {set can_edit=true()}
        {/section}
        {section show=$Children.item.object.can_create}
            {set can_create=true()}
        {/section}
    {/section}

<div class="content-navigation-childlist">
    <table class="list" cellspacing="0">
    <tr>
        {* Remove column *}
        <th class="remove"> &nbsp; </th>

        {* Name column *}
        <th class="name">{'Name'|i18n( 'design/admin/layout ')}:</th>

        {* Class type column *}
        <th class="class">{'Type'|i18n( 'design/admin/layout ')}:</th>

        {* Section column *}
        <th class="section">{'Section'|i18n( 'design/admin/layout ')}:</th>

        {* Priority column *}
        {section show=eq( $node.sort_array[0][0], 'priority' )}
            <th class="priority">{'Priority'|i18n( 'design/standard/node/view' )}:</th>
        {/section}

        {* Copy column *}
        {* section show=$can_copy *}
            <th class="copy">{'Copy'|i18n( 'design/standard/node/view' )}:</th>
        {* /section *}

        {* Edit column *}
        <th class="edit">{'Edit'|i18n( 'design/standard/node/view' )}:</th>
    </tr>

    {section var=Nodes loop=$children sequence=array( bglight, bgdark )}
    {let quoted_child=concat( '&quot;', $Nodes.item.name|wash(), '&quot;' )
         quoted_node=concat( '&quot;', $node.name|wash(), '&quot;'  )}

        <tr class="{$Children.sequence}">

        {* Remove checkbox *}
        <td>
        {section show=$Nodes.item.object.can_remove}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'Use these checkboxes to mark items for removal. Click the "Remove selected" button to actually remove the selected items.'|i18n( 'design/admin/layout' )|wash()} "/>
            {section-else}
            <input type="checkbox" name="DeleteIDArray[]" value="{$Nodes.item.node_id}" title="{'You do not have permissions to mark this item for removal.'|i18n( 'design/admin/layout' )}" disabled="disabled" />
        {/section}
        </td>

        <td>{node_view_gui view=line content_node=$Nodes.item}</td>

        {* Class type *}
        <td>{$Nodes.item.object.class_name|wash()}</td>

        {* Section *}
        <td>{$Nodes.item.object.section_id}</td>

        {* Priority *}
        {section show=eq( $node.sort_array[0][0], 'priority' )}
            <td>
            {section show=$node.object.can_edit}
                <input type="text" name="Priority[]" size="3" value="{$Nodes.item.priority}" title="{'Use the priority fields to control the order in which the items appear. Use positive and negative integers. Click the "Update priorities" button to apply the changes.'|i18n( 'design/admin/layout')|wash()}" />
                <input type="hidden" name="PriorityID[]" value="{$Nodes.item.node_id}" />
                {section-else}
                <input type="text" name="Priority[]" size="3" value="{$Nodes.item.priority}" title="{'You are not allowed to update the priorities because you do not have permissions to edit %quoted_node'|i18n( 'admin/design/layout',, hash( '%quoted_node', $quoted_node ) )}" disabled="disabled" />
            {/section}
            </td>
        {/section}

    {* Copy button *}
    <td>
    {section show=$can_copy}
    <a href={concat( 'content/copy/', $Nodes.item.contentobject_id )|ezurl}><img src={'copy.gif'|ezimage} alt="{'Copy'|i18n( 'design/admin/layout' )}" title="{'Click here to create a copy of %quoted_child. The copy will be created within the current location.'|i18n( 'design/admin/layout',,hash( '%quoted_child', $quoted_child ) )}" /></a>
    {section-else}
    <img src={'copy_disabled.png'|ezimage} alt="{'Copy'|i18n( 'design/admin/layout' )}" title="{'You can not make a copy of %quoted_child because you do not have create permissions for %quoted_node.'|i18n( 'design/admin/layout',,hash( '%quoted_child', $quoted_child, '%quoted_node', $quoted_node ) )}" />
    {/section}
    </td>

        {* Edit button *}
        {* section show=$can_edit *}
        <td>
        {section show=$Nodes.item.object.can_edit}
            <a href={concat( 'content/edit/', $Nodes.item.contentobject_id )|ezurl}><img src={'edit.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/layout')}" title="{'Click here to edit %quoted_child.'|i18n( 'design/admin/layout',, hash( '%quoted_child', $quoted_child ) )}" /></a>
        {section-else}
            <img src={'edit_disabled.png'|ezimage} alt="{'Edit'|i18n( 'design/admin/layout' )}" title="{'You do not have permissions to edit %quoted_child.'|i18n( 'design/admin/layout',,hash( '%quoted_child', $quoted_child ) )}" /></a>
        {/section}
        </td>
        {* /section *}
  </tr>

{/let}
{/section}

</table>
</div>




<div class="controlbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat( '/content/view', '/full/', $node.node_id)
         item_count=$children_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}

    {* Remove button *}
    {section show=$can_remove}
        <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n('design/standard/node/view')}" title="{'Click here to remove checked/marked items from the list above.'|i18n( 'design/admin/layout' )}" />
    {section-else}
        <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n('design/standard/node/view')}" title="{'You do not have permissions to remove any of the items from the list above.'|i18n( 'design/admin/layout' )}" disabled="disabled" />
    {/section}

    {* Update priorities button *}
    {section show=eq( $node.sort_array[0][0], 'priority' )}
    {section show=$node.object.can_edit}
        <input class="button" type="submit" name="UpdatePriorityButton" value="{'Update priorities'|i18n('design/standard/node/view')}" title="{'Click here to apply changes to the priorities of the items in the list above.'|i18n( 'design/admin/layout' )}" />
    {section-else}
        <input class="button" type="submit" name="UpdatePriorityButton" value="{'Update priorities'|i18n('design/standard/node/view')}" title="{'You do not have permissions to change the priorities of the items in the list above.'|i18n( 'design/admin/layout' )}" disabled="disabled" />
    {/section}
    {/section}

{section-else}
<div class="controlbar">
{/section}

{* The "Create new here" thing: *}
<div class="createblock">
{section show=$node.object.can_create}
<input type="hidden" name="NodeID" value="{$node.node_id}" />
<select name="ClassID" title="{'Use this menu to select the type of item you wish to create. Click the "Create here" button. The item will be created within the current location.'|i18n( 'design/admin/layout' )|wash()}">
{section var=CanCreateClasses loop=$node.object.can_create_class_list}
<option value="{$CanCreateClasses.item.id}">{$CanCreateClasses.item.name|wash()}</option>
{/section}
</select>
<input class="button" type="submit" name="NewButton" value="{'Create here'|i18n( 'design/standard/node/view' )}" title="{'Click here to create a new item within the current location. Use the menu on the left to select the type of the item.'|i18n( 'design/admin/layout' )}" />
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />
</div>
{section-else}
<select name="ClassID" disabled="disabled">
<option value="">Not available</option>
</select>
<input class="button" type="submit" name="NewButton" value="{'Create here'|i18n( 'design/standard/node/view' )}" title="{'You do not have permissions to create new items within the current location.'|i18n( 'design/admin/layout' )}" disabled="disabled" />
</div>
{/section}
</form>
</div>

{/let}
{/let}