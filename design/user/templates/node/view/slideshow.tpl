{let page_limit=1
     item_list=fetch('content','list',hash(parent_node_id,$node.node_id,limit,$page_limit,offset,$view_parameters.offset))
     list_count=fetch('content','list_count',hash(parent_node_id,$node.node_id))
     thumbnail_page_limit=8}

<form method="post" action={"/content/action/"|ezurl}>

<table width="100%" border="0" cellspacing="10" cellpadding="0">
<tr>
    <td>

<table border="0" cellpadding="10" cellspacing="0"  bgcolor="#ffffff">
{section name=Child loop=$item_list}
<tr>
	<td align="left">
	{$Child:item.name|texttoimage('gallery')}
	</td>
</tr>
<tr> 
    <td colspan="4" valign="top" bgcolor="#000000">
                  {node_view_gui view=large content_node=$Child:item}
    </td>
</tr>
{/section}
{let item_previous=sub($view_parameters.offset,$page_limit) item_next=sum($view_parameters.offset,$page_limit)}

<tr bgcolor="#ffffff">
     {switch match=$item_previous|lt(0)}
       {case match=0}
         <td valign="top" width="25%"><a href={concat('content/view','/slideshow/',$node.node_id,$item_previous|gt(0)|choose('',concat('/offset/',$item_previous)))|ezurl}><img src={"pil_left.gif"|ezimage} width="16" height="17" border="0"></a></td>
       {/case}
       {case match=1}
         <td valign="top" width="25%">&nbsp;</td>
       {/case}
     {/switch}

    {let offset_of_page=mul(int(div($view_parameters.offset,$thumbnail_page_limit)),$thumbnail_page_limit)}
    <td width="25%"> <div align="center"><a href={concat('content/view','/thumbnail/',$node.node_id,$offset_of_page|gt(0)|choose('',concat('/offset/',$offset_of_page)))|ezurl}><img src={"x_close.gif"|ezimage} width="20" height="18" border="0"></a></div></td>
    {/let}
    <td width="25%"> <div align="center"><a href="javascript:OpenWindow({concat('layout/set/fullscreen/','content/view/slideshow/',$node.node_id,'/offset/',$view_parameters.offset)|ezurl(single)},'popup','scrollbars=no,resizable=yes,width=500,height=400')"><img src={"fullsize_icon.gif"|ezimage} alt="Fullscreen" border="0" /></a></div></td>

    {switch match=$item_next|lt($list_count)}
      {case match=1}
                <td width="25%"> <div align="right"><a href={concat('content/view','/slideshow/',$node.node_id,'/offset/',$item_next)|ezurl}><img src={"pil_right.gif"|ezimage} width="17" height="17" border="0"></a></div></td>
      {/case}
      {case}
         <td valign="top" width="25%">&nbsp;</td>
      {/case}
    {/switch}
</tr>

{/let}
    </table>

    </td>
</tr>
</table>

</form>