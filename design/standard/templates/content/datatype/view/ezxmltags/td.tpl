<td valign="top">
{switch name=Sw match=$content}
  {case match=""}
  &nbsp;
  {/case}
  {case}
  {$content}
  {/case}
{/switch}
</td>