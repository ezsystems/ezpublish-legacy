{default page_uri_suffix=false()}
{let item_previous=sub($view_parameters.offset,$item_limit) item_next=sum($view_parameters.offset,$item_limit)}
<div class="selectbar">
<table class="selectbar" width="100%" cellpadding="0" cellspacing="2" border="0">
<tr>
	{switch match=$item_previous|lt(0)}
	  {case match=0}
	<td class="selectbar" width="1%">
          <a class="selectbar" href={concat($page_uri,$item_previous|gt(0)|choose('',concat('/offset/',$item_previous)),$page_uri_suffix)|ezurl}><<&nbsp;{"Previous"|i18n("design/standard/navigator")}</a>
    </td>
	  {/case}
          {case match=1}
	  {/case}
        {/switch}
    <td width="80%">
    &nbsp;
    </td>
	{switch match=$item_next|lt($item_count)}
	  {case match=1}
	<td class="selectbar" width="1%">
          <a class="selectbar" href={concat($page_uri,'/offset/',$item_next,,$page_uri_suffix)|ezurl}>{"Next"|i18n("design/standard/navigator")}&nbsp;>></a>
    </td>
          {/case}
	  {case}
          {/case}
        {/switch}
</tr>
</table>
</div>
{/let}
{/default}