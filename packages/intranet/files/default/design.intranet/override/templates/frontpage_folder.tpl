<div id="frontpage">
<h1>{"Latest news"|i18n("design/intranet/layout")}</h1>

{let news_list=fetch( content, tree, hash( parent_node_id, 2,
                                           limit, 9,
                                           sort_by, array( published, false() ),
                                           class_filter_type, 'include',
                                           class_filter_array, array( 'article' )
                                            ) )}

<div id="largenews">
{section var=news max=1 loop=$news_list sequence=array(bglight,bgdark)}
   <div class="child">
   {node_view_gui view=line content_node=$news.item}
   </div>
{/section}
</div>


<div id="mediumnews">
<table>
<tr>
    {section var=news offset=1 loop=$news_list sequence=array(bglight,bgdark)}
       <td>
          <div class="child">
          {node_view_gui view=line content_node=$news.item}
          </div>
       </td>
    {delimiter modulo=2}
</tr>
<tr>      
    {/delimiter}
    {/section}
</tr>
</table>
</div>

{/let}

</div>
