<div class="content-view-children">

<!-- Children START -->

<div class="context-block">
<form name="children" method="post" action={'content/action'|ezurl}>
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
{* Generic children list for admin interface. *}
{def $item_type = ezpreference( 'admin_list_limit' )
     $number_of_items = min( $item_type, 3)|choose( 10, 10, 25, 50 )
     $can_remove = false()
     $can_move = false()
     $can_edit = false()
     $can_create = false()
     $can_copy = false()
     $current_path = first_set( $node.path_array[1], 1 )
     $admin_children_viewmode = ezpreference( 'admin_children_viewmode' )
     $children_count = fetch( content, list_count, hash( 'parent_node_id', $node.node_id,
                                                      'objectname_filter', $view_parameters.namefilter ) )
     $children = array()}

{if $children_count}
    {set $children = fetch( 'content', 'list', hash( 'parent_node_id', $node.node_id,
                                            'sort_by', $node.sort_array,
                                            'limit', $number_of_items,
                                            'offset', $view_parameters.offset,
                                            'objectname_filter', $view_parameters.namefilter ) )}
{/if}


{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr block thight-block">

    <div class="left">
    <h2 class="context-title"><a href={$node.depth|gt(1)|choose('/'|ezurl,$node.parent.url_alias|ezurl )} title="{'Up one level.'|i18n(  'design/admin/node/view/full'  )}"><img src={'back-button-16x16.gif'|ezimage} alt="{'Up one level.'|i18n( 'design/admin/node/view/full' )}" title="{'Up one level.'|i18n( 'design/admin/node/view/full' )}" /></a>&nbsp;{'Sub items [%children_count]'|i18n( 'design/admin/node/view/full',, hash( '%children_count', $children_count ) )}</h2>
    </div>

    <div class="right">
    <label class="inline">{'Sorting'|i18n( 'design/admin/node/view/full' )}:</label>

    {let sort_fields=hash( 6, 'Class identifier'|i18n( 'design/admin/node/view/full' ),
                           7, 'Class name'|i18n( 'design/admin/node/view/full' ),
                           5, 'Depth'|i18n( 'design/admin/node/view/full' ),
                           3, 'Modified'|i18n( 'design/admin/node/view/full' ),
                           9, 'Name'|i18n( 'design/admin/node/view/full' ),
                           8, 'Priority'|i18n( 'design/admin/node/view/full' ),
                           2, 'Published'|i18n( 'design/admin/node/view/full' ),
                           4, 'Section'|i18n( 'design/admin/node/view/full' ) )
        title='You cannot set the sorting method for the current location because you do not have permission to edit the current item.'|i18n( 'design/admin/node/view/full' )
        disabled=' disabled="disabled"' }

    {if $node.can_edit}
        {set title='Use these controls to set the sorting method for the sub items of the current location.'|i18n( 'design/admin/node/view/full' )}
        {set disabled=''}
        <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id}" />
    {/if}

    <select name="SortingField" title="{$title}"{$disabled}>
    {section var=Sort loop=$sort_fields}
        <option value="{$Sort.key}" {if eq( $Sort.key, $node.sort_field )}selected="selected"{/if}>{$Sort.item}</option>
    {/section}
    </select>

    <select name="SortingOrder" title="{$title}"{$disabled}>
        <option value="0"{if eq($node.sort_order, 0)} selected="selected"{/if}>{'Descending'|i18n( 'design/admin/node/view/full' )}</option>
        <option value="1"{if eq($node.sort_order, 1)} selected="selected"{/if}>{'Ascending'|i18n( 'design/admin/node/view/full' )}</option>
    </select>

    <input {if $disabled}class="button-disabled"{else}class="button"{/if} type="submit" name="SetSorting" value="{'Set'|i18n( 'design/admin/node/view/full' )}" title="{$title}" {$disabled} />

    {/let}
    </div>


{* DESIGN: Subline *}<div class="break"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* If there are children: show list and buttons that belong to the list. *}
{if $children}

{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="block thight-block">
<div class="left">
    <p>
    {switch match=$number_of_items}
    {case match=25}
        <a href={'/user/preferences/set/admin_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
        <span class="current">25</span>
        <a href={'/user/preferences/set/admin_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>

        {/case}

        {case match=50}
        <a href={'/user/preferences/set/admin_list_limit/1'|ezurl} title="{'Show 10 items per page.'|i18n( 'design/admin/node/view/full' )}">10</a>
        <a href={'/user/preferences/set/admin_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
        <span class="current">50</span>
        {/case}

        {case}
        <span class="current">10</span>
        <a href={'/user/preferences/set/admin_list_limit/2'|ezurl} title="{'Show 25 items per page.'|i18n( 'design/admin/node/view/full' )}">25</a>
        <a href={'/user/preferences/set/admin_list_limit/3'|ezurl} title="{'Show 50 items per page.'|i18n( 'design/admin/node/view/full' )}">50</a>
        {/case}

        {/switch}
    </p>
</div>
<div class="right">
        <p>
        {switch match=$admin_children_viewmode}
        {case match='thumbnail'}
        <a href={'/user/preferences/set/admin_children_viewmode/list'|ezurl} title="{'Display sub items using a simple list.'|i18n( 'design/admin/node/view/full' )}">{'List'|i18n( 'design/admin/node/view/full' )}</a>
        <span class="current">{'Thumbnail'|i18n( 'design/admin/node/view/full' )}</span>
        <a href={'/user/preferences/set/admin_children_viewmode/detailed'|ezurl} title="{'Display sub items using a detailed list.'|i18n( 'design/admin/node/view/full' )}">{'Detailed'|i18n( 'design/admin/node/view/full' )}</a>
        {/case}

        {case match='detailed'}
        <a href={'/user/preferences/set/admin_children_viewmode/list'|ezurl} title="{'Display sub items using a simple list.'|i18n( 'design/admin/node/view/full' )}">{'List'|i18n( 'design/admin/node/view/full' )}</a>
        <a href={'/user/preferences/set/admin_children_viewmode/thumbnail'|ezurl} title="{'Display sub items as thumbnails.'|i18n( 'design/admin/node/view/full' )}">{'Thumbnail'|i18n( 'design/admin/node/view/full' )}</a>
        <span class="current">{'Detailed'|i18n( 'design/admin/node/view/full' )}</span>
        {/case}

        {case}
        <span class="current">{'List'|i18n( 'design/admin/node/view/full' )}</span>
        <a href={'/user/preferences/set/admin_children_viewmode/thumbnail'|ezurl} title="{'Display sub items as thumbnails.'|i18n( 'design/admin/node/view/full' )}">{'Thumbnail'|i18n( 'design/admin/node/view/full' )}</a>
        <a href={'/user/preferences/set/admin_children_viewmode/detailed'|ezurl} title="{'Display sub items using a detailed list.'|i18n( 'design/admin/node/view/full' )}">{'Detailed'|i18n( 'design/admin/node/view/full' )}</a>
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
        {if $Children.item.can_remove}
            {set can_remove=true()}
        {/if}
        {if $Children.item.can_move}
            {set $can_move=true()}
        {/if}
        {if $Children.item.can_edit}
            {set can_edit=true()}
        {/if}
        {if $Children.item.can_create}
            {set can_create=true()}
        {/if}
    {/section}


    {* Display the actual list of nodes. *}
    {switch match=$admin_children_viewmode}
    
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

{* Else: there are no children. *}
{else}

<div class="block">
    <p>{'The current item does not contain any sub items.'|i18n( 'design/admin/node/view/full' )}</p>
</div>

{/if}

<div class="context-toolbar">
{* Alphabetical navigation can be enabled with content.ini [AlphabeticalFilterSettings] ContentFilterList[]  *}
{include name=navigator
         uri='design:navigator/alphabetical.tpl'
         page_uri=$node.url_alias
         item_count=$children_count
         view_parameters=$view_parameters
         node_id=$node.node_id
         item_limit=$number_of_items}
</div>

{* DESIGN: Content END *}</div></div></div>


{* Button bar for remove and update priorities buttons. *}
<div class="controlbar">

{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
    {* Remove and move button *}
    <div class="left">
        {if $can_remove}
            <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/node/view/full' )}" title="{'Remove the selected items from the list above.'|i18n( 'design/admin/node/view/full' )}" />
        {else}
            <input class="button-disabled" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to remove any of the items from the list above.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
        {/if}
        {if $can_move}
            <input class="button" type="submit" name="MoveButton" value="{'Move selected'|i18n( 'design/admin/node/view/full' )}" title="{'Move the selected items from the list above.'|i18n( 'design/admin/node/view/full' )}" />
        {else}
            <input class="button-disabled" type="submit" name="MoveButton" value="{'Move selected'|i18n( 'design/admin/node/view/full' )}" title="{'You do not have permission to move any of the items from the list above.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
        {/if}
    </div>

    <div class="right">
    
    {* Update priorities button *}
    {if and( eq( $node.sort_array[0][0], 'priority' ), $node.can_edit, $children_count )}
        <input class="button" type="submit" name="UpdatePriorityButton" value="{'Update priorities'|i18n( 'design/admin/node/view/full' )}" title="{'Apply changes to the priorities of the items in the list above.'|i18n( 'design/admin/node/view/full' )}" />
    {else}
        <input class="button-disabled" type="submit" name="UpdatePriorityButton" value="{'Update priorities'|i18n( 'design/admin/node/view/full' )}" title="{'You cannot update the priorities because you do not have permission to edit the current item or because a non-priority sorting method is used.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
    {/if}
    </div>

    <div class="break"></div>
</div>


{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</form>

</div>

<!-- Children END -->

{undef $item_type $number_of_items $can_remove $can_move $can_edit $can_create $can_copy $current_path $admin_children_viewmode $children_count $children}
</div>
