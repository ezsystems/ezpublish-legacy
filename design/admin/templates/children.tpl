<div class="content-view-children">

<!-- Children START -->

<div class="context-block">
<form name="children" method="post" action={'content/action'|ezurl}>

{* Generic children list for admin interface. *}
{let item_type=ezpreference( 'items' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     can_remove=false()
     can_edit=false()
     can_create=false()
     can_copy=false()
     children_count=$node.children_count
     children=fetch( content, list, hash( parent_node_id, $node.node_id,
                                          sort_by, $node.sort_array,
                                          limit, $number_of_items,
                                          offset, $view_parameters.offset ) ) }

<div class="box-header">
<div class="box-tc"><div class="box-ml"><div class="box-mr">
<div class="box-tl"><div class="box-tr">

<h2 class="context-title"><a href={$node.depth|gt(1)|choose('/'|ezurl,$node.parent.url_alias|ezurl )} title="{'Up one level'|i18n(  'design/admin/node/view/full'  )}" /><img src={'back-button-16x16.gif'|ezimage} alt="{'Up one level'|i18n( 'design/admin/node/view/full' )}" title="{'Up one level'|i18n( 'design/admin/node/view/full' )}" /></a>&nbsp;{'Sub items [%children_count]'|i18n( 'design/admin/node/view/full',, hash( '%children_count', $children_count ) )}</h2>

<div class="header-subline"></div>

</div></div>
</div></div></div>
</div>

{* If there are children: show list and buttons that belong to the list. *}
{section show=$children}

<div class="box-ml"><div class="box-mr">
<div class="box-content">

{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
    <p>
    {switch match=$number_of_items}
    {case match=25}
        <a href={'/user/preferences/set/items/1'|ezurl}>10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/items/3'|ezurl}>50</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/items/1'|ezurl}>10</a>
        <a href={'/user/preferences/set/items/2'|ezurl}>25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/items/2'|ezurl}>25</a>
        <a href={'/user/preferences/set/items/3'|ezurl}>50</a>
        {/case}

        {/switch}
    </p>
</div>
<div class="right">
        <p>
        {switch match=ezpreference( 'admin_children_viewmode' )}
        {case match='thumbnail'}
        <a href={'/user/preferences/set/admin_children_viewmode/list'|ezurl}>List</a>
        <span class="current">Thumbnail</span>
        <a href={'/user/preferences/set/admin_children_viewmode/detailed'|ezurl}>Detailed</a>
        {/case}

        {case match='detailed'}
        <a href={'/user/preferences/set/admin_children_viewmode/list'|ezurl}>List</a>
        <a href={'/user/preferences/set/admin_children_viewmode/thumbnail'|ezurl}>Thumbnail</a>
        <span class="current">Detailed</span>
        {/case}

        {case}
        <span class="current">List</span>
        <a href={'/user/preferences/set/admin_children_viewmode/thumbnail'|ezurl}>Thumbnail</a>
        <a href={'/user/preferences/set/admin_children_viewmode/detailed'|ezurl}>Detailed</a>
        {/case}
        {/switch}
        </p>
</div>

<div class="break"></div>

</div>
</div>

    {* Copying operation is allowed if the user can create stuff under the current node. *}
    {set can_copy=$node.can_create}

    {* Check if the current user is allowed to *}
    {* edit or delete any of the children.     *}
    {section var=Children loop=$children}
        {section show=$Children.item.can_remove}
            {set can_remove=true()}
        {/section}
        {section show=$Children.item.can_edit}
            {set can_edit=true()}
        {/section}
        {section show=$Children.item.can_create}
            {set can_create=true()}
        {/section}
    {/section}


{* Display the actual list of nodes. *}
{switch match=ezpreference( 'admin_children_viewmode' )}

{case match='thumbnail'}
    {include uri='design:children_thumbnail.tpl'}
{/case}

{case match='detailed'}
    {include uri='design:children_detailed.tpl'}
{/case}

{case}
    {include uri='design:children_list.tpl'}
{/case}
{/switch}

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=$node.url_alias
         item_count=$children_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>

</div>
</div></div>

{* Else: there are no children. *}
{section-else}
    <p>{'The current item does not contain any sub items.'|i18n( 'design/admin/node/view/full' )}
{/section}

{* Button bar for remove and update priorities buttons. *}
<div class="controlbar">

<div class="box-bc"><div class="box-ml"><div class="box-mr">
<div class="box-tc">
<div class="box-bl"><div class="box-br">

<div class="block">
    {* Remove button *}
    <div class="left">
    {section show=$can_remove}
        <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/node/view/full' )}" title="{'Remove the selected items from the list above.'|i18n( 'design/admin/node/view/full' )}" />
    {section-else}
        <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permissions to remove any of the items from the list above.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
    {/section}
    </div>

    <div class="right">
    {* Update priorities button *}
    {section show=and( eq( $node.sort_array[0][0], 'priority' ), $node.can_edit, $children_count )}
        <input class="button" type="submit" name="UpdatePriorityButton" value="{'Update priorities'|i18n( 'design/admin/node/view/full' )}" title="{'Apply changes to the priorities of the items in the list above.'|i18n( 'design/admin/node/view/full' )}" />
    {section-else}
        <input class="button" type="submit" name="UpdatePriorityButton" value="{'Update priorities'|i18n( 'design/admin/node/view/full' )}" title="{'You can not update the priorities because you do not have permissions to edit the current item or because a non-priority sorting method is used.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
    {/section}
    </div>

    <div class="break"></div>
</div>


{* The "Create new here" thing: *}
<div class="block">
    {section show=$node.can_create}
    <div class="left">
    <input type="hidden" name="NodeID" value="{$node.node_id}" />

   {let can_create_classes=fetch( content, can_instantiate_class_list, hash( group_id, array( ezini( 'ClassGroupIDs', 'Users', 'content.ini' ), ezini( 'ClassGroupIDs', 'Setup', 'content.ini' ) ), parent_node, $node, filter_type, exclude ) )}

   {section show=eq( $node.path_array[1], ezini( 'NodeSettings', 'UserRootNode', 'content.ini' ) )}
          {set can_create_classes=fetch( content, can_instantiate_class_list, hash( group_id, ezini( 'ClassGroupIDs', 'Users', 'content.ini' ), parent_node, $node ) )}
   {/section}

    <select name="ClassID" title="{'Use this menu to select the type of item you wish to create. Click the "Create here" button. The item will be created within the current location.'|i18n( 'design/admin/node/view/full' )|wash()}">
        {section var=CanCreateClasses loop=$can_create_classes}
        <option value="{$CanCreateClasses.item.id}">{$CanCreateClasses.item.name|wash()}</option>
        {/section}
    </select>

    {/let}

    <input class="button" type="submit" name="NewButton" value="{'Create here'|i18n( 'design/admin/node/view/full' )}" title="{'Create a new item within the current location. Use the menu on the left to select the type of the item.'|i18n( 'design/admin/node/view/full' )}" />
    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
    <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id}" />
    <input type="hidden" name="ViewMode" value="full" />
    </div>
    {section-else}
    <div class="left">
    <select name="ClassID" disabled="disabled">
    <option value="">Not available</option>
    </select>
    <input class="button" type="submit" name="NewButton" value="{'Create here'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permissions to create new items within the current location.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
    </div>
    {/section}

{* Hiding *}
&nbsp;&nbsp;&nbsp;<label>Hidden state:</label>
{section show=$node.can_edit}
    <a href={concat( 'content/hide/', $node.node_id )|ezurl}>{$node.hidden_invisible_string}</a>
{section-else}
    {$node.hidden_invisible_string}
{/section}

{* Sorting *}
<div class="right">
<label>{'Sorting:'|i18n( 'design/admin/node/view/full' )}</label>

{let sort_fields=hash( 6, 'Class Identifier'|i18n( 'design/admin/node/view/full' ),
                       7, 'Class Name'|i18n( 'design/admin/node/view/full' ),
                       5, 'Depth'|i18n( 'design/admin/node/view/full' ),
                       3, 'Modified'|i18n( 'design/admin/node/view/full' ),
                       9, 'Name'|i18n( 'design/admin/node/view/full' ),
                       8, 'Priority'|i18n( 'design/admin/node/view/full' ),
                       2, 'Published'|i18n( 'design/admin/node/view/full' ),
                       4, 'Section'|i18n( 'design/admin/node/view/full' ) )
    title='You can not set the sorting method for the current location because you do not have permissions to edit the current item.'|i18n( 'design/admin/node/view/full' )
    disabled=' disabled="disabled"' }

{section show=and( $node.can_edit, $children_count )}
    {set title='Use these controls to set the sorting method for the items within the current location.'|i18n( 'design/admin/node/view/full' )}
    {set disabled=''}
{/section}

<select name="SortingField" title="{$title}"{$disabled}>
{section var=Sort loop=$sort_fields}
    <option value="{$Sort.key}" {section show=eq( $Sort.key, $node.sort_field )}selected="selected"{/section}>{$Sort.item}</option>
{/section}
</select>

<select name="SortingOrder" title="{$title}"{$disabled}>
    <option value="0"{section show=eq($node.sort_order, 0)} selected="selected"{/section}>{'Up'|i18n( 'design/admin/node/view/full' )}</option>
    <option value="1"{section show=eq($node.sort_order, 1)} selected="selected"{/section}>{'Down'|i18n( 'design/admin/node/view/full' )}</option>
</select>

<input class="button" type="submit" name="SetSorting" value="{'Set'|i18n( 'design/admin/node/view/full' )}" title="{$title}"{$disabled} />

{/let}


</div>

<div class="break"></div>

</div>

</div></div>
</div>
</div></div></div>

</div>

</form>

</div>

<!-- Children END -->

{/let}
</div>
