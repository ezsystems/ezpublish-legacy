<tr class="{$sequence}">
  <td>
  {section show=$is_read|not}<b>{/section}{"Posted: %1"|i18n('design/admin/collaboration/view/element/ezapprove_comment',,array($item.created|l10n(shortdatetime)))}{section show=$is_read|not}</b>{/section}
  </td>
  <td>
  {section show=$is_read|not}<b>[new]</b>{/section}
  </td>

  <td rowspan="2" valign="top">
    {collaboration_participation_view view=text_linked collaboration_participant=$item_link.participant}
  </td>
</tr>
<tr class="{$sequence}">
  <td colspan="2">
  {$item.data_text1|wash}
  </td>
</tr>
