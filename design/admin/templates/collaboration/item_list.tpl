<table class="list" cellspacing="0">
<tr>
  <th class="tight">&nbsp;</th>
  <th colspan="2">{"Subject"|i18n('design/admin/collaboration/item_list')}</th>
  <th>{"Date"|i18n('design/admin/collaboration/item_list')}</th>
</tr>
{section name=Item loop=$item_list sequence=array(bglight,bgdark)}
{let item_class="status_read"}
<tr class="{$:sequence}">
  <td class="tight">
    {if $:item.user_status.is_active}
      {if $:item.user_status.is_read}
      <img src={"collaboration/status_read.png"|ezimage} alt="{'Read'|i18n('design/admin/collaboration/item_list')}" />
      {else}
      {set item_class="status_unread"}
      <img src={"collaboration/status_unread.png"|ezimage} alt="{'Unread'|i18n('design/admin/collaboration/item_list')}" />
      {/if}
    {else}
    {set item_class="status_inactive"}
    <img src={"collaboration/status_inactive.png"|ezimage} alt="{'Inactive'|i18n('design/admin/collaboration/item_list')}" />
    {/if}
  </td>
  <td class="tight">
    <a href={concat("collaboration/item/full/",$:item.id)|ezurl}>{collaboration_icon view=small collaboration_item=$:item}</a>
  </td>
  <td>
    {collaboration_view_gui view=line item_class=$:item_class collaboration_item=$:item}
    {if and($:item.use_messages,$:item.unread_message_count)}
    <b><a href={concat("collaboration/item/full/",$:item.id)|ezurl}>({$:item.unread_message_count})</a></b>
    {else}
    &nbsp;
    {/if}
  </td>
  <td>
    {$:item.created|l10n(shortdatetime)}
  </td>
</tr>
{/let}
{/section}
</table>