{let item_previous=sub( $view_parameters.offset,
                        $item_limit )
     item_next=sum( $view_parameters.offset,
                    $item_limit )
     left_max=10
     right_max=9
     page_count=int( ceil( div( $item_count,$item_limit ) ) )
     current_page=int( ceil( div( $view_parameters.offset,
                                  $item_limit ) ) )

     left_length=min($current_page,$left_max)
     right_length=min(sub($page_count,$current_page,1),$right_max)
}

{section show=$page_count|gt(1)}

<div class="selectbar">
<table class="selectbar" width="100%" cellpadding="0" cellspacing="2" border="0">
<tr>
     {switch match=$item_previous|lt(0) }
       {case match=0}
     <td class="selectbar" width="1%">
      <a class="selectbar" href={concat($page_uri,$item_previous|gt(0)|choose('',concat('/offset/',$item_previous)))|ezurl}><<&nbsp;{"Previous"|i18n("design/standard/navigator")}</a>
     </td>
       {/case}
       {case match=1}
       {/case}
     {/switch}

    <td>
<div align="center">
<table width="1%" cellpadding="0" cellspacing="2" border="0" align="center">
<tr align="center">

{section show=$current_page|gt($left_max)}
<td>
 <a href={$page_uri|ezurl}>1</a>
</td>
{section show=sub($current_page,$left_length)|gt(1)}
<td>...
</td>
{/section}
{/section}

    {section name=Quick loop=$left_length}
<td>
        {let page_offset=sum(sub($current_page,$left_length),$:index)}
          <a href={concat($page_uri,$Quick:page_offset|gt(0)|choose('',concat('/offset/',$Quick:page_offset)))|ezurl}>{$:page_offset|inc}</a>
        {/let}
</td>
    {/section}

<td>
        <b>{$current_page|inc}</b>
</td>

    {section name=Quick loop=$right_length}
<td>
        {let page_offset=sum($current_page,1,$:index)}
          <a href={concat($page_uri,$Quick:page_offset|gt(0)|choose('',concat('/offset/',$Quick:page_offset)))|ezurl}>{$:page_offset|inc}</a>
        {/let}
</td>
    {/section}

{section show=$page_count|gt(sum($current_page,$right_max,1))}
{section show=sum($current_page,$right_max,2)|lt($page_count)}
<td>
...
</td>
{/section}
<td>
 <a href={concat($page_uri,$page_count|dec|gt(0)|choose('',concat('/offset/',$page_count|dec)))|ezurl}>{$page_count}</a>
</td>
{/section}

</tr>
</table>
</div>
    </td>

    {switch match=$item_next|lt($item_count) }
      {case match=1}
        <td class="selectbar" width="1%">
        <a class="selectbar" href={concat($page_uri,'/offset/',$item_next)|ezurl}>{"Next"|i18n("design/standard/navigator")}&nbsp;>></a>
        </td>
      {/case}
      {case}
      {/case}
    {/switch}
</tr>
</table>
</div>

{/section}
{/let}
