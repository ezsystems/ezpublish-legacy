{default embed_mode         = true()
         class_filter_array = array()
         root_nodes         = fetch('content', 'list', hash( 'parent_node_id', 1 ))}
	<div class="slide" style="display: none;">
	    <div style="background-color: #eee; text-align: center">
	    {if $embed_mode}
	        <a id="embed_browse_go_back_link" title="Go back" href="JavaScript:void(0);" style="float: left;"><img width="13" height="11" border="0" src={"ezoe/arrow-left.gif"|ezimage} /></a>
	    {/if}
	    {foreach $root_nodes as $n}
	        <a href="JavaScript:eZOEPopupUtils.browse( {$n.node_id} )" style="font-weight: bold">{$n.name}</a> &nbsp;
	    {/foreach}
	    </div>
	    <table id="browse_box_prev">
	        <thead>
	        </thead>
	        <tbody>
	        </tbody>
	        <tfoot>
	        </tfoot>
	    </table>
	</div>
{/default}