{default embed_mode         = true()
         class_filter_array = array()
         root_nodes         = fetch('content', 'list', hash( 'parent_node_id', 1 ))}
	<div class="panel" style="display: none;">
	    <div style="background-color: #eee; text-align: center">
	    {if $embed_mode}
	        <a id="embed_browse_go_back_link" title="Go back" href="JavaScript:void(0);" style="float: right;"><img width="16" height="16" border="0" src={"tango/emblem-unreadable.png"|ezimage} /></a>
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
<script type="text/javascript">
<!--

eZOeMCE['root_node_name'] = "{'Top Level Nodes'|i18n('kernel/content')}";
eZOEPopupUtils.browse( {ezini( 'NodeSettings', 'RootNode', 'content.ini' )} );
// UserRootNode MediaRootNode

//-->
</script>