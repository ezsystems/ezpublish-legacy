<div class="selectbar">
<table class="selectbar" width="100%" cellpadding="0" cellspacing="2" border="0">
<tr>
	{switch match=$page.previous|lt(0) }
	  {case match=0}
	<td class="selectbar" width="1%">
          <a class="selectbar" href="{$module.functions.sitemap.uri}/{$top_object_id}/offset/{$page.previous}"><<&nbsp;Previous</a>
    </td>
	  {/case}
          {case match=1}
	  {/case}
        {/switch}
    <td width="80%">
    &nbsp;
    </td>
	{switch match=$page.next|lt($tree_count) }
	  {case match=1}
	<td class="selectbar" width="1%">
          <a class="selectbar" href="{$module.functions.sitemap.uri}/{$top_object_id}/offset/{$page.next}">Next&nbsp;>></a>
    </td>
          {/case}
	  {case}
          {/case}
        {/switch}
</tr>
</table>
</div>
