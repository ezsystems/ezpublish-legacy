{default with_children=true()}
{section show=$with_children}

{let page_limit=1
     item_list=fetch('content','list',hash(parent_node_id,$node.node_id,limit,$page_limit,offset,$view_parameters.offset))
     list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))
     thumbnail_page_limit=8}

<form method="post" action={"/content/action/"|ezurl}>

<table border="0" width="100%" cellspacing="10" cellpadding="0">
<tr>
  <td><center>
            <table border="0" align="center" cellpadding="10" cellspacing="6" bordercolor="#000000" bgcolor="#ffffff">

{section name=Child loop=$item_list}
              <tr bordercolor="#000000" bgcolor="#000000"> 
                <td colspan="3" valign="top" bgcolor="#000000">
                  {node_view_gui view=large content_node=$Child:item}
                </td>
              </tr>
{/section}
{let item_previous=sub($view_parameters.offset,$page_limit) item_next=sum($view_parameters.offset,$page_limit)}

<tr bgcolor="#ffffff">

    {switch match=$item_previous|lt(0)}
       {case match=0}
         <td valign="top" width="45%"><a href={concat('content/view','/slideshow/',$node.node_id,$item_previous|gt(0)|choose('',concat('/offset/',$item_previous)))|ezurl}><img src={"arrow_left.gif"|ezimage} width="18" height="18" border="0"></a></td>
       {/case}
       {case match=1}
         <td valign="top" width="45%"><img src={"arrow_left_gray.gif"|ezimage} width="18" height="18" border="0"></td>
       {/case}
    {/switch}

    <td width="10%"> <div align="center"><a href="javascript:window.close();"><img src={"x_close.gif"|ezimage} width="18" height="18" border="0"></a></div></td>

    {switch match=$item_next|lt($list_count)}
      {case match=1}
        <td width="45%"> <div align="right"><a href={concat('content/view','/slideshow/',$node.node_id,'/offset/',$item_next)|ezurl}><img src={"arrow_right.gif"|ezimage} width="18" height="18" border="0"></a></div></td>
      {/case}
      {case}
        <td width="45%"> <div align="right"><img src={"arrow_right_gray.gif"|ezimage} width="18" height="18" border="0"></div></td>
      {/case}
    {/switch}
</tr>

{/let}
            </table>
</center></td></tr>
</table>



</form>
{/let}

{/section}
{/default}