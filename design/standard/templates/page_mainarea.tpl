{*?template charset=latin1?*}
{section show=$warning_list}
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  {section name=Warning loop=$warning_list}
<tr>
    <td>
      <div class="error">
      <h3 class="error">{$Warning:item.error.type} ({$Warning:item.error.number})</h3>
      <ul class="error">
        <li>{$Warning:item.text}</li>
      </ul>
      </div>
    </td>
</tr>
  {/section}
</table>
{/section}

{$module_result.content}
