{default parent_group_id=0
         current_depth=0
         offset=$view_parameters.offset item_limit=10
         summary_indentation=10}
<h1>Summary</h1>

{let group_tree=fetch("collaboration","group_tree",hash("parent_group_id",$parent_group_id))
     latest_item_count=fetch("collaboration","item_count")
     latest_item_list=fetch("collaboration","item_list",hash("limit",$item_limit,"offset",$offset))}

<table width="100%" cellspacing="0" cellpadding="0" border="1">
<tr>
  <td valign="top" width="70%">

  {section show=$latest_item_count}

<table cellspacing="0" cellpadding="0" border="0">
{section name=Item loop=$latest_item_list sequence=array(bglight,bgdark)}
<tr>
  <td class="{$:sequence}">
    <a href={concat("collaboration/item/full/",$:item.id)|ezurl}>{collaboration_icon view=small collaboration_item=$:item}</a>
  </td>
  <td class="{$:sequence}">
    {collaboration_view_gui view=line collaboration_item=$:item}
  </td>
  <td class="{$:sequence}">
    <a href={concat("collaboration/item/full/",$:item.id)|ezurl}>[more]</a>
  </td>
</tr>
{/section}
</table>

{include name=Navigator
         uri='design:navigator/google.tpl'
         page_uri="/collaboration/view/summary"
         item_count=$latest_item_count
         view_parameters=$view_parameters
         item_limit=$item_limit}

{section-else}
<p>No new items to be handled.</p>
{/section}

  </td>

  <td valign="top" align="right" width="20%">


<table cellspacing="0" cellpadding="0" border="0">
{section name=Group loop=$group_tree sequence=array(bglight,bgdark)}
<tr>
{let group_item_count=$:item.item_count}
  <td class="{$:sequence}">
    <img src={"1x1-transparent.gif"|ezimage} width="{mul(sub($:item.depth,$current_depth),$summary_indentation)}" height="1" alt="" border="0" />
    <a href={concat("collaboration/group/list/",$:item.id)|ezurl}>{$:item.title}</a>{section show=$:group_item_count|gt(0)} <b>({$:group_item_count})</b>{/section}
  </td>
{/let}
</tr>
{/section}
</table>

  </td>
</tr>

</table>

{/let}

{/default}


