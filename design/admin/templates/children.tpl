{* Generic children list for admin interface. *}
<form method="post" action={"content/action"|ezurl}>
{let item_type=ezpreference( 'items' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     can_remove=false()
     can_edit=false()
     can_create=false()
     can_copy=false()
     children_count=fetch( content, list_count, hash( parent_node_id, $node.node_id ) )
     children=fetch( content, list, hash( parent_node_id, $node.node_id,
                                          sort_by, $node.sort_array,
                                          limit, $number_of_items,
                                          offset, $view_parameters.offset ) ) }

{* If there are children: show list and buttons that belong to the list. *}
{section show=$children}

{* Items per page and view mode selector. *}
    <p>
        Items:
        <a href={'/user/preferences/set/items/1'|ezurl}>10</a>
        <a href={'/user/preferences/set/items/2'|ezurl}>25</a>
        <a href={'/user/preferences/set/items/3'|ezurl}>50</a></p>
    </p>

    <p>
        View:
        <a href={'/user/preferences/set/viewmode/list'|ezurl}>List</a>
        <a href={'/user/preferences/set/viewmode/thumbnail'|ezurl}>Thumbnail</a>
    </p>


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

{* Display the actual list of nodes. *}
{section show=eq( ezpreference( 'viewmode' ), 'thumbnail' )}

    {include uri='design:children_thumbnail.tpl'}

    {section-else}

    {include uri='design:children_list.tpl'}

{/section}

{* Button bar for remove and update priorities buttons. *}
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

{* Else: there are no children, but we still need to start the controlbar div. *}
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
