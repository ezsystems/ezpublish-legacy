{* Placement code for content/edit which handles placement with dropdowns *}
{default placement=hash( list, array() )}

<div class="placement">

    <input type="hidden" name="MainNodeID" value="" />

    {section var=element loop=$placement.list}

    <div class="element">
    <select class="element_{$element.number}" name="SetPlacementNodeIDArray[{$element.number}]">
        {section var=node loop=$element.item.node_list}
            <option value="{$node.key}" {section show=eq($assigned_remote_map[$element.number],$node.key)}selected="selected"{/section}>{$node.item}</option>
        {/section}
    </select>
    </div>

    {delimiter}
    <div class="delimiter">
    </div>
    {/delimiter}

    {/section}

{*    {let name=Choice
         list_node1=first_set($:assigned_remote_map[2].parent_node,0)
         list_node2=first_set($:assigned_remote_map[3].parent_node,31)}

    <input type="hidden" name="MainNodeID" value="26" />

    <input type="hidden" name="SetPlacementNodeIDArray[1]" value="26" />
    <input type="hidden" name="SetRemoteIDOrderMap[1]" value="1" />
    <input type="hidden" name="SetRemoteIDFieldMap[1]" value="9" />

    <select name="SetPlacementNodeIDArray[2]">
    {section loop=hash(0,"None",28,"Feature",29,"Some place",30,"Another place")}
      <option value="{$:key}" {section show=eq($:list_node1,$:key)}selected="selected"{/section}>{$:item}</option>
    {/section}
    </select>
    <input type="hidden" name="SetRemoteIDOrderMap[2]" value="0" />
    <input type="hidden" name="SetRemoteIDFieldMap[2]" value="1" />

    <select name="SetPlacementNodeIDArray[3]">
    {section loop=hash(0,"None",31,"1",32,"2")}
      <option value="{$:key}" {section show=eq($:list_node2,$:key)}selected="selected"{/section}>{$:item}</option>
    {/section}
    </select>
    <input type="hidden" name="SetRemoteIDOrderMap[3]" value="1" />
    <input type="hidden" name="SetRemoteIDFieldMap[3]" value="4" />

    {/let}*}

</div>

{/let}
