<tr>
  <td class="{$sequence}">
  <p>{"Posted: %1"|i18n('design/standard/collaboration',,array($item.created|l10n(shortdatetime)))}</p>
  </td>

  <td class="{$sequence}" rowspan="2" valign="top">
    {collaboration_participation_view view=text_linked collaboration_participant=$item_link.participant}
  </td>
</tr>
<tr>
  <td class="{$sequence}">
  {$item.data_text1|wash(xhtml)}
  </td>
</tr>
