{default with_children=true()
	 is_standalone=true()
}
{let page_limit=8
     list_count=and( $with_children, fetch( 'content', 'list_count', hash(
								     parent_node_id, $node.node_id
								     )
					  )
		   )
}
{default node_name=$node.name}

{section show=$is_standalone}
    <form method="post" action={"content/action/"|ezurl}>
{/section}

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td>
        {$node_name|texttoimage( 'gallery' )}
    </td>
</tr>
</table>

{section show=$with_children}

    <table width="100%">
    <tr>
        {section name=Child loop=fetch( 'content', 'list', hash(
    						       parent_node_id, $node.node_id, 
						       limit, $page_limit,
						       offset, $view_parameters.offset ) )}
            <td align="left">
                <table border="0" bgcolor="#000000" cellpadding="10" cellspacing="0">
                <tr> 
                    <td valign="top">
 	                <a href={concat( "/content/view/slideshow/", $node.node_id, "/offset/", sum( $view_parameters.offset, $Child:index ) )|ezurl}>
	                {node_view_gui view=medium content_node=$Child:item}</a>
	            </td>
                </tr>
                </table>
            </td>
            {delimiter modulo=ceil( div( $list_count, 2 ) )}
                </tr>
                <tr>
                <td>
                    &nbsp;
                </td>
                </tr>
                <tr>
            {/delimiter}
        {/section}
    </tr>
    </table>

    {include name=navigator
             uri='design:navigator/google.tpl'
             page_uri=concat( 'content/view', '/thumbnail/', $node.node_id )
             item_count=$list_count
             view_parameters=$view_parameters
             item_limit=$page_limit}

{/section}

{section show=$is_standalone}
    </form>
{/section}

{/default}
{/let}
{/default}