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
    {section show=$:item.user_status.is_active}
      {section show=$:item.user_status.is_read}
      <img src={"collaboration/status_read.png"|ezimage} alt="{'Read'|i18n('design/admin/collaboration/item_list')}" />
      {section-else}
      {set item_class="status_unread"}
      <img src={"collaboration/status_unread.png"|ezimage} alt="{'Unread'|i18n('design/admin/collaboration/item_list')}" />
      {/section}
    {section-else}
    {set item_class="status_inactive"}
    <img src={"collaboration/status_inactive.png"|ezimage} alt="{'Inactive'|i18n('design/admin/collaboration/item_list')}" />
    {/section}
  </td>
  <td class="tight">
    <a href={concat("collaboration/item/full/",$:item.id)|ezurl}>{collaboration_icon view=small collaboration_item=$:item}</a>
  </td>
  <td>
    {collaboration_view_gui view=line item_class=$:item_class collaboration_item=$:item}
    {section show=and($:item.use_messages,$:item.unread_message_count)}
    <b><a href={concat("collaboration/item/full/",$:item.id)|ezurl}>({$:item.unread_message_count})</a></b>
    {section-else}
    &nbsp;
    {/section}
  </td>
  <td>
    {$:item.created|l10n(shortdatetime)}
  </td>
</tr>
{/let}
{/section}
</table>