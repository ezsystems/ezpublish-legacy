{* Placement code for content/edit which handles placement with dropdowns *}

{let parent_node=2}

{section show=$object.contentclass_id|eq(16)}
    {set parent_node=55}
{/section}

{section show=$object.contentclass_id|eq(17)}
    {set parent_node=55}
{/section}

{section show=$object.contentclass_id|eq(12)}
    {set parent_node=62}
{/section}

{section show=$object.contentclass_id|eq(2)}
    {set parent_node=50}
{/section}

{let folder_list=fetch('content','list',hash( parent_node_id, $parent_node,
                                          sort_by ,$node.sort_array,
                                          class_filter_type, 'include',
                                          class_filter_array, array( 'folder' ) ))}

{default placement=hash( list, array( hash( node_list, $folder_list ) ),
                         main_assignment_element_number, 1 ) }

<div class="placement">

    <input type="hidden" name="MainAssignmentElementNumber" value="{$placement.main_assignment_element_number}" />

    {section var=element loop=$placement.list}

    <div class="element">
    <select class="element_{$element.number}" name="SetPlacementNodeIDArray[{$element.number}]">
        {section var=node loop=$element.item.node_list}
            <option value="{$node.item.node_id}" {section show=eq($assigned_remote_map[$element.number].parent_node,$node.item.node_id)}selected="selected"{/section}>{$node.item.name}</option>
        {/section}
    </select>
    </div>

    {delimiter}
    <div class="delimiter">
    </div>
    {/delimiter}

    {/section}
{/default}

{/let}

{/let}
</div>
