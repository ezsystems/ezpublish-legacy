{default page_uri_suffix=false()
         left_max=7
         right_max=6}
{default name=ViewParameter
         page_uri_suffix=false()
         left_max=$left_max
         right_max=$right_max}
{let item_previous=sub( $view_parameters.offset,
                         $item_limit )
      item_next=sum( $view_parameters.offset,
                     $item_limit )
      page_count=int( ceil( div( $item_count,$item_limit ) ) )
      current_page=int( ceil( div( $view_parameters.offset,
                                   $item_limit ) ) )

      left_length=min($ViewParameter:current_page,$:left_max)
      right_length=min(sub($ViewParameter:page_count,$ViewParameter:current_page,1),$:right_max)
      view_parameter_text=""
      offset_text=eq( ezini( 'ControlSettings', 'AllowUserVariables', 'template.ini' ), 'true' )|choose( '/offset/', '/(offset)/' )}

{* Create view parameter text with the exception of offset *}
{section loop=$view_parameters}
 {section-exclude match=eq($:key,offset)}
 {section-exclude match=$:item|not}
 {set view_parameter_text=concat('(',$:view_parameter_text,')/',$:key,'/',$:item)}
{/section}

{section show=$:page_count|gt(1)}

<div class="selectbar">
<table class="selectbar" width="100%" cellpadding="0" cellspacing="2" border="0">
<tr>
     {switch match=$:item_previous|lt(0) }
       {case match=0}
     <td class="selectbar" width="1%">
      <a class="selectbar" href={concat($page_uri,$:item_previous|gt(0)|choose('',concat($:offset_text,$:item_previous)),$:view_parameter_text,$page_uri_suffix)|ezurl}>&laquo;&nbsp;{"Previous"|i18n("design/standard/navigator")}</a>
     </td>
       {/case}
       {case match=1}
       {/case}
     {/switch}

    <td>
<div align="center">
<table width="1%" cellpadding="0" cellspacing="2" border="0" align="center">
<tr align="center">

{section show=$:current_page|gt($:left_max)}
<td>
 <a href={concat($page_uri,$:view_parameter_text,$page_uri_suffix)|ezurl}>1</a>
</td>
{section show=sub($:current_page,$:left_length)|gt(1)}
<td>...
</td>
{/section}
{/section}

    {section name=Quick loop=$:left_length}
<td>
        {let page_offset=sum(sub($ViewParameter:current_page,$ViewParameter:left_length),$:index)}
          <a href={concat($page_uri,$:page_offset|gt(0)|choose('',concat($:offset_text,mul($:page_offset,$item_limit))),$ViewParameter:view_parameter_text,$page_uri_suffix)|ezurl}>{$:page_offset|inc}</a>
        {/let}
</td>
    {/section}

<td>
        <b>{$:current_page|inc}</b>
</td>

    {section name=Quick loop=$:right_length}
<td>
        {let page_offset=sum($ViewParameter:current_page,1,$:index)}
          <a href={concat($page_uri,$:page_offset|gt(0)|choose('',concat($:offset_text,mul($:page_offset,$item_limit))),$ViewParameter:view_parameter_text,$page_uri_suffix)|ezurl}>{$:page_offset|inc}</a>
        {/let}
</td>
    {/section}

{section show=$:page_count|gt(sum($:current_page,$:right_max,1))}
{section show=sum($:current_page,$:right_max,2)|lt($:page_count)}
<td>
...
</td>
{/section}
<td>
 <a href={concat($page_uri,$:page_count|dec|gt(0)|choose('',concat($:offset_text,mul($:page_count|dec,$item_limit))),$:view_parameter_text,$page_uri_suffix)|ezurl}>{$:page_count}</a>
</td>
{/section}

</tr>
</table>
</div>
    </td>

    {switch match=$:item_next|lt($item_count)}
      {case match=1}
        <td class="selectbar" width="1%">
        <a class="selectbar" href={concat($page_uri,$:offset_text,$:item_next,$:view_parameter_text,$page_uri_suffix)|ezurl}>{"Next"|i18n("design/standard/navigator")}&nbsp;&raquo;</a>
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
{/default}
{/default}