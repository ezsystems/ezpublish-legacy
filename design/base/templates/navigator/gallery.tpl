{let item_previous=sub( $view_parameters.offset,
                         $item_limit )
     item_next=sum( $view_parameters.offset,
                     $item_limit )}
{section show=$:page_count|gt(1)}
     {switch match=$:item_previous|lt(0) }
       {case match=0}
     <td class="selectbar" width="1%">
      <a class="selectbar" href={concat($page_uri,$:item_previous|gt(0)|choose('',concat('/offset/',$:item_previous)),$:view_parameter_text,$page_uri_suffix)|ezurl}>&laquo;&nbsp;{"Previous"|i18n("design/standard/navigator")}</a>
     </td>
       {/case}
       {case match=1}
       {/case}
     {/switch}
{/section}

{switch match=$:item_next|lt($item_count)}
      {case match=1}
        <td class="selectbar" width="1%">
        <a class="selectbar" href={concat($page_uri,'/offset/',$:item_next,$:view_parameter_text,$page_uri_suffix)|ezurl}>{"Next"|i18n("design/standard/navigator")}&nbsp;&raquo;</a>
        </td>
      {/case}
      {case}
      {/case}
    {/switch}
{/section}

{/let}
