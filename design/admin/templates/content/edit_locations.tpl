{default exclude_remote_assignments=false()}
{let name=Node exclude_remote_assignments=$:exclude_remote_assignments
               sort_fields=hash( 6, 'Class identifier'|i18n( 'design/admin/content/edit' ),
                                 7, 'Class name'|i18n( 'design/admin/content/edit' ),
                                 5, 'Depth'|i18n( 'design/admin/content/edit' ),
                                 3, 'Modified'|i18n( 'design/admin/content/edit' ),
                                 9, 'Name'|i18n( 'design/admin/content/edit' ),
                                 1, 'Path String'|i18n( 'design/admin/content/edit' ),
                                 8, 'Priority'|i18n( 'design/admin/content/edit' ),
                                 2, 'Published'|i18n( 'design/admin/content/edit' ),
                                 4, 'Section'|i18n( 'design/admin/content/edit' ) )
               has_top_levels=false()
               existingParentNodes=$object.parent_nodes}

{* Check if top node. *}
{section loop=$assigned_node_array}
    {if $Node:item.parent_node|le( 1 )}
        {set has_top_levels=true()}
    {/if}
{/section}




<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h2 class="context-title">{'Locations (%locations)'|i18n( 'design/admin/content/edit',, hash( '%locations', $assigned_node_array|count ) )}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<table class="list" cellspacing="0" >
<tr>
{* JB TODO: The alt/title fields should get different text descriptions when they are disabled *}
    <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} width="16" height="16" alt="{'Invert selection.'|i18n( 'design/admin/content/edit' )}" title="{'Invert selection.'|i18n( 'design/admin/content/edit' )}" onclick="ezjs_toggleCheckboxes( document.editform, 'AssignmentIDSelection[]' ); return false;" /></th>
    <th>{'Location'|i18n( 'design/admin/content/edit' )}</th>
    <th>{'Sub items'|i18n( 'design/admin/content/edit' )}</th>
    <th>{'Sorting of sub items'|i18n( 'design/admin/content/edit' )}</th>
    {if $:has_top_levels|not}
    <th>{'Current visibility'|i18n( 'design/admin/content/edit' )}</th>
    <th>{'Visibility after publishing'|i18n( 'design/admin/content/edit' )}</th>
    <th>{'Main'|i18n( 'design/admin/content/edit' )}</th>
    <th>&nbsp;</th>
    {/if}
</tr>

    {section loop=$assigned_node_array sequence=array( bglight, bgdark )}
    {section-exclude match=$:item.parent_node|le( 0 )}
    {section-exclude match=and($:exclude_remote_assignments,$:item.remote_id|gt(0))}
    {let parent_node=$Node:item.parent_node_obj
         nameStart=concat( '<span title="', 'This location will remain unchanged when the object is published.'|i18n( 'design/admin/content/edit' ), '">' )
         nameEnd='</span>'}
    {if $Node:item.is_create_operation}
        {set nameStart=concat( '<ins title="', 'This location will be created when the object is published.'|i18n( 'design/admin/content/edit' ), '">' )}
        {set nameEnd='</ins>'}
    {/if}
    {if $Node:item.is_move_operation}
        {set nameStart=concat( '<span title="', 'This location will be moved when the object is published.'|i18n( 'design/admin/content/edit' ), '">' )}
        {set nameEnd='</span>'}
    {/if}
    {if $Node:item.is_remove_operation}
        {set nameStart=concat( '<del title="', 'This location will be removed when the object is published.'|i18n( 'design/admin/content/edit' ), '">' )}
        {set nameEnd='</del>'}
    {/if}
    <tr class="{$Node:sequence}">

    {* Remove. *}
    <td>
    {if or( $location_ui_enabled|not, and( $Node:item.node, $Node:item.node.can_remove|not ) )}
    <input type="checkbox" name="AssignmentIDSelection[]" value="{$Node:item.parent_node}" title="{'You do not have permission to remove this location.'|i18n( 'design/admin/content/edit' )}" disabled="disabled" />
    {else}
    <input type="checkbox" name="AssignmentIDSelection[]" value="{$Node:item.parent_node}" title="{'Select location for removal.'|i18n( 'design/admin/content/edit' )}" />
    {/if}
    </td>

    {* Location. *}
    <td>
    {$:nameStart}
    {switch match=$Node:parent_node.node_id}
    {case match=1}
    {'Top node'|i18n( 'design/admin/content/edit' )}
    {/case}
    {case}
    {section name=Path loop=$Node:parent_node.path}
    {$Node:Path:item.name|wash} /
    {/section}
    {$Node:parent_node.name|wash}
    {/case}
    {/switch} / {$object.name|wash}
    {$:nameEnd}
    </td>

    {* Sub items. *}
    <td>
    {if $Node:item.node|not}
    0
    {else}
    {$Node:item.node.children_count}
    {/if}
    </td>

    {* Sorting. *}
    <td>
    <select {if $location_ui_enabled|not}disabled="disabled" {/if}name="SortFieldMap[{$Node:item.id}]" title="{'Use this menu to set the sorting method for the sub items in this location.'|i18n( 'design/admin/content/edit' )}">
    {section name=Sort loop=$Node:sort_fields}
    <option value="{$Node:Sort:key}" {if eq($Node:Sort:key,$Node:item.sort_field)}selected="selected"{/if}>{$Node:Sort:item}</option>
    {/section}
    </select>
    <select {if $location_ui_enabled|not}disabled="disabled" {/if}name="SortOrderMap[{$Node:item.id}]" title="{'Use this menu to set the sorting direction for the sub items in this location.'|i18n( 'design/admin/content/edit' )}">
    <option value="1" {if eq( $Node:item.sort_order, 1)}selected="selected"{/if}>{'Asc.'|i18n( 'design/admin/content/edit' )}</option>
    <option value="0" {if eq( $Node:item.sort_order, 0)}selected="selected"{/if}>{'Desc.'|i18n( 'design/admin/content/edit' )}</option>
    </select>
    </td>

    {if $:has_top_levels|not}

    {* Current/previous visibility status. *}
    <td>
    {if $Node:item.node}
        {$Node:item.node.hidden_status_string}
    {else}
        {if $Node:item.parent_node_obj.is_invisible}
            {'Hidden by parent'|i18n( 'design/admin/content/edit' )}
        {else}
            {'Visible'|i18n( 'design/admin/content/edit' )}
        {/if}
    {/if}
    </td>

    {* Visibility status after publishing. *}
    <td>
    <select {if $location_ui_enabled|not}disabled="disabled" {/if}name="FutureNodeHiddenState_{$Node:parent_node.node_id}">
    <option value="unchanged" selected="selected">{'Unchanged'|i18n( 'design/admin/content/edit' )}</option>
    {if or($Node:item.node|not, $Node:item.node.is_hidden)}
    <option value="visible">{'Visible'|i18n( 'design/admin/content/edit' )}</option>
    {/if}
    {if or($Node:item.node|not, $Node:item.node.is_hidden|not)}
    <option value="hidden">{'Hidden'|i18n( 'design/admin/content/edit' )}</option>
    {/if}
    </select>
    </td>

    {* Main node. *}
    <td>
    <input {if $location_ui_enabled|not}disabled="disabled" {/if}type="radio" name="MainNodeID" {if eq($main_node_id,$Node:item.parent_node)}checked="checked"{/if} value="{$Node:item.parent_node}" title="{'Use these radio buttons to specify the main location (main node) for the object being edited.'|i18n( 'design/admin/content/edit' )}" />
    </td>

    {* Move. *}
    <td>
    {switch match=$Node:item.parent_node}
    {case in=$Node:existingParentNodes}
    <input {if $location_ui_enabled|not}disabled="disabled" {/if}type="image" name="{concat( 'MoveNodeID_', $Node:item.parent_node )}" src={'move.gif'|ezimage} value="{$Node:item.parent_node}" title="{'Move to another location.'|i18n( 'design/admin/content/edit' )}" />
    {/case}
    {case}
    {if $Node:item.from_node_id|gt( 0 )}
    <input {if $location_ui_enabled|not}disabled="disabled" {/if}type="image" name="{concat( 'MoveNodeID_', $Node:item.parent_node )}" src={'move.gif'|ezimage} value="{$Node:item.parent_node}" title="{'Move to another location.'|i18n( 'design/admin/content/edit' )}" />
    {else}
    &nbsp;
    {/if}
    {/case}
    {/switch}
    </td>

    {/if}

    </tr>
    {/let}
    {/section}
 </table>


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">

{if $:has_top_levels|not}

    {if and( $assigned_node_array|count|ge( 1 ), $location_ui_enabled )}
    <input class="button" type="submit" name="RemoveAssignmentButton" value="{'Remove selected'|i18n( 'design/admin/content/edit' )}" title="{'Remove selected locations.'|i18n( 'design/admin/content/edit' )}" />
    {else}
    <input class="button-disabled" type="submit" name="RemoveAssignmentButton" value="{'Remove selected'|i18n( 'design/admin/content/edit' )}" disabled="disabled" />
    {/if}

    <input {if $location_ui_enabled|not}disabled="disabled" class="button-disabled"{else}class="button"{/if} type="submit" name="BrowseNodeButton" value="{'Add locations'|i18n( 'design/admin/content/edit' )}" title="{'Add one or more locations for the object being edited.'|i18n( 'design/admin/content/edit' )}" />

{else}
    <input class="button-disabled" type="submit" name="RemoveAssignmentButton" value="{'Remove selected'|i18n( 'design/admin/content/edit' )}" disabled="disabled"  title="{'You cannot add or remove locations because the object being edited belongs to a top node.'|i18n( 'design/admin/content/edit' )}" />
    <input class="button-disabled" type="submit" name="BrowseNodeButton" value="{'Add locations'|i18n( 'design/admin/content/edit' )}" disabled="disabled" title="{'You cannot add or remove locations because the object being edited belongs to a top node.'|i18n( 'design/admin/content/edit' )}" />
    <input type="hidden" name="MainNodeID" value="{$main_node_id}" />
{/if}


</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>

{/let}
{/default}
