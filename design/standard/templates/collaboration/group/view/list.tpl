{default parent_group_id=0
         current_depth=0
         summary_indentation=10}

<table cellspacing="0" cellpadding="0" border="0">
<tr>
  <td valign="top" align="right">

  {include uri="design:collaboration/group_tree.tpl" current_depth=$current_depth
           summary_indentation=$summary_indentation parent_group_id=$parent_group_id}

  </td>

  <td valign="top" width="70%">

<table cellspacing="0" cellpadding="0" border="0">
{section name=Item loop=$collab_group.item_list sequence=array(bglight,bgdark)}
<tr>
  <td class="{$:sequence}">
    <a href={concat("collaboration/item/full/",$:item.id)|ezurl}>{collaboration_icon view=small collaboration_item=$:item}</a>
  </td>
  <td class="{$:sequence}">
    {collaboration_view_gui view=line collaboration_item=$:item}
  </td>
  <td class="{$:sequence}">&nbsp;</td>
  <td class="{$:sequence}">
    {section show=and($:item.use_messages,$:item.unread_message_count)}
    <p><b><a href={concat("collaboration/item/full/",$:item.id,"#messages")|ezurl}>({$:item.unread_message_count})</a></b></p>
    {section-else}
    &nbsp;
    {/section}
  </td>
  <td class="{$:sequence}">&nbsp;</td>
  <td class="{$:sequence}">
    <p><a href={concat("collaboration/item/full/",$:item.id)|ezurl}>[more]</a></p>
  </td>
</tr>
{/section}
</table>

  </td>
</tr>

</table>

{/default}
