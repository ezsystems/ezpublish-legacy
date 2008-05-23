{default embed_mode         = true()
         class_filter_array = array()
         classes            = fetch( 'class', 'list' )
         has_access         = fetch( 'user', 'has_access_to', hash( 'module', 'ezoe',
                                                                    'function', 'search' ) )}
<script type="text/javascript">
<!--

eZOeMCE['empty_result_string'] = "{"No results were found when searching for &quot;%1&quot;"|i18n("design/standard/content/search",,hash( '%1', '<search_string>' ))}"


//-->
</script>
	<div class="panel" id="search_box" style="display: none; position: relative;">
    {if $embed_mode}
        <a id="embed_search_go_back_link" title="Go back" href="JavaScript:void(0);" style="position: absolute; top: 0px; right: -5px;"><img width="16" height="16" border="0" src={"tango/emblem-unreadable.png"|ezimage} /></a>
    {/if}
	{if $has_access}
	    <div id="search_progress" class="progress-indicator" style="display: none;"></div>
	    <table class="properties">
	    <tr>
	        <td>
	            <input id="SearchText" name="SearchStr" type="text" value="" onkeypress="return eZOEPopupUtils.searchEnter(event)" />	
	            <select name="SearchContentClassID[]" multiple="multiple" size="4" style="vertical-align:middle">
	            <option value=""{if $:class_filter_array|not} selected="selected"{/if}>{"All"|i18n("design/standard/ezoe")}</option>
	            {foreach $classes as $class}
	                <option value="{$class.id|wash}"{if $:class_filter_array|contains( $class.identifier )} selected="selected"{/if}>{$class.name|wash}</option>
	            {/foreach}
	            </select>
	        </td>
	        <td><input type="submit" name="SearchButton" id="SearchButton" value="{'Search'|i18n('design/admin/content/search')}" onclick="return eZOEPopupUtils.searchEnter(event, true)" /></td>
	    </tr>
	    <tr>
	        <td colspan="2">
	        <table id="search_box_prev">
	        <thead>
	        </thead>
	        <tbody>
	        </tbody>
	        <tfoot>
	        </tfoot>
	    </table>
	        </td>
	    </tr>
	    </table>
	{else}
	    <p>{"Your current user does not have the proper privileges to access this page."|i18n('design/standard/error/kernel')}</p>
	{/if}
	</div>
{/default}