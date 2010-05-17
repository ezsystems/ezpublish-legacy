<div class="content-view-children">

{* Generic children list for admin interface. *}
{* TODO: admin_children_viewmode should be replaced w/the table column preferences *} 
{def $item_type    = ezpreference( 'admin_list_limit' )
     $number_of_items = min( $item_type, 3)|choose( 10, 10, 25, 50 )
     $can_remove   = false()
     $can_move     = false()
     $can_edit     = false()
     $can_create   = false()
     $can_copy     = false()
     $current_path = first_set( $node.path_array[1], 1 )
     $admin_children_viewmode = ezpreference( 'admin_children_viewmode' )
     $children_count = fetch( content, list_count, hash( 'parent_node_id', $node.node_id,
                                                         'objectname_filter', $view_parameters.namefilter ) )
     $children    = array()
     $priority    = and( eq( $node.sort_array[0][0], 'priority' ), $node.can_edit, $children_count )
     $priority_dd = and( $priority, $admin_children_viewmode|ne( 'thumbnail' ), $view_parameters.offset|eq( 0 ) )}
     

<!-- Children START -->

<div class="context-block">
<form name="children" method="post" action={'content/action'|ezurl}>
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />

{if $children_count}
    {set $children = fetch( 'content', 'list', hash( 'parent_node_id', $node.node_id,
                                            'sort_by', $node.sort_array,
                                            'limit', $number_of_items,
                                            'offset', $view_parameters.offset,
                                            'objectname_filter', $view_parameters.namefilter ) )}
{/if}


{* DESIGN: Header START *}<div class="box-header">

    <div class="button-left">
    <h2 class="context-title"><a href={$node.depth|gt(1)|choose('/'|ezurl,$node.parent.url_alias|ezurl )} title="{'Up one level.'|i18n(  'design/admin/node/view/full'  )}"><img src={'up-16x16-grey.png'|ezimage} alt="{'Up one level.'|i18n( 'design/admin/node/view/full' )}" title="{'Up one level.'|i18n( 'design/admin/node/view/full' )}" /></a>&nbsp;{'Sub items (%children_count)'|i18n( 'design/admin/node/view/full',, hash( '%children_count', $children_count ) )}</h2>
    </div>

    <div class="button-right button-header">
    </div>

<div class="float-break"></div>

{* DESIGN: Header END *}</div>

{* DESIGN: Content START *}<div class="box-content">


{* Items per page and view mode selector. *}
<div class="context-toolbar">
<div class="float-break"></div>
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

    <input type="hidden" name="NodeID" value="{$node.node_id}" />

    {* Display the actual list of nodes. *}
    {include uri='design:children_detailed.tpl'}



{* DESIGN: Content END *}</div>

</form>

</div>

{* Load yui code for subitems diplay even if current node has no children (since cache blocks  does not vary by this) *}
{ezscript_require('ezjsc::yui2', 'ezjsc::yui3')}


{* Load drag and drop code if access rights are ok (but not depending on node sort as pagelayout cache-block does not include that in key) *}
{if $node.can_edit}
{ezscript_require( array( 'ezjsc::yui3', 'ezjsc::yui3io', 'ezajaxsubitems_sortdd.js' ) )}
{/if}

{* Execute drag and drop code if sortField=priority and access rights are ok *}
{if $priority_dd}
<script type="text/javascript">
eZAjaxSubitemsSortDD.init();
</script>
{/if}


<!-- Children END -->

{undef $item_type $number_of_items $can_remove $can_move $can_edit $can_create $can_copy $current_path $admin_children_viewmode $children_count $children}
</div>
