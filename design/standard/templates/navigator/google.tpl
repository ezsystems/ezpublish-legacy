{let item_previous=sub($view_parameters.offset,$item_limit) item_next=sum($view_parameters.offset,$item_limit)
     page_count=int(ceil(div($item_count,$item_limit))) current_page=int(ceil(div($view_parameters.offset,$item_limit)))}
<div class="selectbar">
<table class="selectbar" width="100%" cellpadding="0" cellspacing="2" border="0">
<tr>
     {switch match=$item_previous|lt(0) }
       {case match=0}
         <td class="selectbar" width="1%">
         <a class="selectbar" href={concat($page_uri,$item_previous|gt(0)|choose('',concat('/offset/',$item_previous)))|ezurl}><<&nbsp;Previous</a>
         </td>
       {/case}
       {case match=1}
       {/case}
     {/switch}

    <td width="35%">
    &nbsp;
    </td>

    <td width="10%">
    {section name=Quick loop=$page_count max=10 show=$page_count|gt(1)}
    {switch match=$Quick:index}
      {case match=$current_page}
        <b>{$Quick:number}</b>
      {/case}
      {case}
        {let page_offset=mul($Quick:index,$item_limit)}
          <a href={concat($page_uri,$Quick:page_offset|gt(0)|choose('',concat('/offset/',$Quick:page_offset)))|ezurl}>{$Quick:number}</a>
        {/let}
      {/case}
    {/switch}
    {/section}
    </td>
    <td width="35%">
    &nbsp;
    </td>

    {switch match=$item_next|lt($item_count) }
      {case match=1}
        <td class="selectbar" width="1%">
        <a class="selectbar" href={concat($page_uri,'/offset/',$item_next)|ezurl}>Next&nbsp;>></a>
        </td>
      {/case}
      {case}
      {/case}
    {/switch}
</tr>
</table>
</div>
{/let}
