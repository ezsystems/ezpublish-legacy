{* Placement code for content/edit *}

    <table class="list" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <th width="60%">{"Location"|i18n("design/standard/content/edit")}:</th>
        <th colspan="1">{"Sort by"|i18n("design/standard/content/edit")}:</th>
        <th colspan="2">{"Ordering"|i18n("design/standard/content/edit")}:</th>
        <th colspan="1">{"Main"|i18n("design/standard/content/edit")}:</th>
        <th colspan="1">{"Move"|i18n("design/standard/content/edit")}:</th>
    </tr>
    {let name=Node sort_fields=hash(1,"Path"|i18n("design/standard/content/edit"),9,"Name"|i18n("design/standard/content/edit"),2,"Published"|i18n("design/standard/content/edit"),3,"Modified"|i18n("design/standard/content/edit"),4,"Section"|i18n("design/standard/content/edit"),5,"Depth"|i18n("design/standard/content/edit"),6,"Class Identifier"|i18n("design/standard/content/edit"),7,"Class Name"|i18n("design/standard/content/edit"),8,"Priority"|i18n("design/standard/content/edit"))}
   {let existingParentNodes=$object.parent_nodes}
    {section loop=$assigned_node_array sequence=array(bglight,bgdark)}
    {let parent_node=$Node:item.parent_node_obj}
    <tr>
        <td class="{$Node:sequence}">
	{switch match=$Node:parent_node.node_id}
	{case match=1}
	Top node
	{/case}
	{case}
        {section name=Path loop=$Node:parent_node.path}
	{$Node:Path:item.name} /
	{/section}
        {$Node:parent_node.name}
	{/case}
	{/switch} / {$object.name}
        </td>
        <td class="{$Node:sequence}">
          <select name="SortFieldMap[{$Node:item.id}]">
          {section name=Sort loop=$Node:sort_fields}
            <option value="{$Node:Sort:key}" {section show=eq($Node:Sort:key,$Node:item.sort_field)}selected="selected"{/section}>{$Node:Sort:item}</option>
          {/section}
          </select>
        </td>
        <td class="{$Node:sequence}" width="25">
	<nobr><img src={"asc.gif"|ezimage} alt="Ascending" /><input type="radio" name="SortOrderMap[{$Node:item.id}]" value="1" {section show=eq($Node:item.sort_order,1)}checked="checked"{/section} /></nobr>
	</td>
        <td class="{$Node:sequence}" width="25">
	<nobr><img src={"desc.gif"|ezimage} alt="Descending" /><input type="radio" name="SortOrderMap[{$Node:item.id}]" value="0" {section show=eq($Node:item.sort_order,0)}checked="checked"{/section} /></nobr>
        </td>
        <td class="{$Node:sequence}" align="right">
        <input type="radio" name="MainNodeID" {section show=eq($main_node_id,$Node:item.parent_node)}checked="checked"{/section} value="{$Node:item.parent_node}" />
        </td>
        <td class="{$Node:sequence}" align="right">
        {switch match=$Node:item.parent_node}
        {case in=$Node:existingParentNodes}
         <input type="image" name="{concat('MoveNodeID_',$Node:item.parent_node)}" src={"move.gif"|ezimage} value="{$Node:item.parent_node}"  />
        {/case}
        {case}
          {section show=$Node:item.from_node_id|gt(0)}
            <input type="image" name="{concat('MoveNodeID_',$Node:item.parent_node)}" src={"move.gif"|ezimage} value="{$Node:item.parent_node}"  />
          {section-else}      
          {/section}   
         {/case}
        {/switch}
        </td>
        <td class="{$Node:sequence}" align="right">
     {section show=eq($Node:item.parent_node,$main_node_id)|not}
        <input type="image" name="{concat('RemoveNodeID_',$Node:item.parent_node)}" src={"remove.png"|ezimage} value="{$Node:item.parent_node}"  />
     {/section}
        </td>
    </tr>
    {/let}
    {/section}
    {/let}
    {/let}
 </table>
 <div align="right" class="buttonblock">
  <input class="button" type="submit" name="BrowseNodeButton" value="{'Add location(s)'|i18n('design/standard/content/edit')}" />
 </div>
