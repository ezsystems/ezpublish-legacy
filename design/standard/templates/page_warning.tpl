{section show=$warning_list}
<div class="error">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  {section name=Warning loop=$warning_list}
<tr>
    <td>
      <h3 class="error">{$Warning:item.error.type} ({$Warning:item.error.number})</h3>
      <ul class="error">
        <li>{$Warning:item.text}</li>
      </ul>
    </td>
</tr>
  {/section}
</table>
</div>
{/section}
