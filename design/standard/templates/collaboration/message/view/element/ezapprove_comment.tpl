<tr>
  <td class="{$sequence}" rowspan="2" valign="top" width="3%">
  {$item.creator_id}
  </td>

  <td class="{$sequence}">
  <p>{"Posted: %1"|i18n('design/standard/collaboration',,array($item.created|l10n(shortdatetime)))}</p>
  </td>
</tr>
<tr>
  <td class="{$sequence}">
  {$item.data_text1|wash(xhtml)}
  </td>
</tr>
