<th valign="top">
{switch name=Sw match=$content}
  {case match="<p></p>"}
  &nbsp;
  {/case}
  {case}
  {$content}
  {/case}
{/switch}
</th>