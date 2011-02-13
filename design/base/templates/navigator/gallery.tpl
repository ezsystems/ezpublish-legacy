{let item_previous=sub( $view_parameters.offset,
                         $item_limit )
     item_next=sum( $view_parameters.offset,
                     $item_limit )}

<div class="pagenavigator">

    <p>
    {if $:page_count|gt(1)}
         {switch match=$:item_previous|lt(0) }
           {case match=0}
               <span class="previous"><a href={concat($page_uri,$:item_previous|gt(0)|choose('',concat('/offset/',$:item_previous)),$:view_parameter_text,$page_uri_suffix)|ezurl}><span class="text">&laquo;&nbsp;{"Previous"|i18n("design/base")}</span></a></span>
           {/case}
           {case match=1}
           {/case}
         {/switch}
    {/if}

    {switch match=$:item_next|lt($item_count)}
          {case match=1}
              <span class="next"><a href={concat($page_uri,'/offset/',$:item_next,$:view_parameter_text,$page_uri_suffix)|ezurl}><span class="text">{"Next"|i18n("design/base")}&nbsp;&raquo;</span></a></span>
          {/case}
          {case}
          {/case}
        {/switch}

    </p>

    <div class="break"></div>

</div>

{/let}
