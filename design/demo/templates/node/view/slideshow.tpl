{default with_children=true()
	 is_standalone=true()}
{let page_limit=1
     item_list=and( $with_children, fetch( 'content' , 'list', hash( 
     							       parent_node_id, $node.node_id, 
							       limit, $page_limit, 
							       offset, $view_parameters.offset
							       )
					 )
                  )
     list_count=and( $with_children, fetch( 'content', 'list_count', hash(
     								     parent_node_id, $node.node_id
								     )
					  )
                   )
     thumbnail_page_limit=8
}

{section show=$is_standalone}
    <form method="post" action={"/content/action/"|ezurl}>
{/section}

<table border="0" cellspacing="10" cellpadding="0">
<tr>
    <td>
        {section show=$with_children}
            <table border="0" cellpadding="10" cellspacing="0"  bgcolor="#ffffff">
            {section name=Child loop=$item_list}
                <tr>
	            <td align="left" colspan="4">
	                {$Child:item.name|texttoimage( 'gallery' )}
	            </td>
                    <td align="right">
                    {concat( $view_parameters.offset|inc, " of ", $list_count)|texttoimage( 'gallery' )}
                    </td>
	        </tr>
                <tr> 
	            <td colspan="5" valign="top" bgcolor="#000000">
                        {node_view_gui view=large content_node=$Child:item}
	            </td>
                </tr>
            {/section}

            {let item_previous=sub( $view_parameters.offset, $page_limit ) item_next=sum( $view_parameters.offset, $page_limit )}

            <tr bgcolor="#ffffff">
                <td width="41%">
	            &nbsp;
                </td>
    	        {switch match=$item_previous|lt( 0 )}
	        {case match=0}
                    <td valign="top" width="6%">
	                <a href={concat( 'content/view', '/slideshow/', $node.node_id, $item_previous|gt( 0 )|choose('', concat( '/offset/' , $item_previous ) ) )|ezurl}><img src={"arrow_left.gif"|ezimage} width="18" height="18" border="0"></a>
	            </td>
	        {/case}
	        {case match=1}
	            <td valign="top" width="6%">
	                <img src={"arrow_left_gray.gif"|ezimage} width="18" height="18" border="0">
	            </td>
	        {/case}
	        {/switch}

	        <td width="6%"> 
	            <div align="center">
                        <a href="javascript:OpenWindow({concat('layout/set/fullscreen/','content/view/slideshow/',$node.node_id,'/offset/',$view_parameters.offset)|ezurl(single)},'popup','scrollbars=no,resizable=yes,width=500,height=400')"><img src={"fullsize_icon.gif"|ezimage} width="18" height="18" alt="Fullscreen" border="0" /></a>
                    </div>
	        </td>

	        {switch match=$item_next|lt( $list_count )}
	        {case match=1}
                <td width="6%"> 
	            <div align="right">
                        <a href={concat( 'content/view', '/slideshow/', $node.node_id, '/offset/', $item_next )|ezurl}><img src={"arrow_right.gif"|ezimage} width="18" height="18" border="0"></a>
                    </div>
	        </td>
	        {/case}
	        {case}
                    <td width="6%">
	                 <div align="right">
                             <img src={"arrow_right_gray.gif"|ezimage} width="18" height="18" border="0">
                        </div>
	            </td>
	        {/case}
	        {/switch}

	        {let offset_of_page=mul( int( div( $view_parameters.offset, $thumbnail_page_limit ) ), $thumbnail_page_limit )}
	        <td width="41%">
	            <div align="right">
                        <a href={concat( 'content/view', '/thumbnail/', $node.node_id, $offset_of_page|gt( 0 )|choose( '', concat( '/offset/', $offset_of_page ) ) )|ezurl}><img src={"x_close.gif"|ezimage} width="18" height="18" border="0"></a>
                    </div>
	         </td>
	        {/let}
            </tr>
            {/let}
            </table>
        {/section}
    </td>
</tr>
</table>



{section show=$is_standalone}
    </form>
{/section}

{/let}
{/default}