{default embed_mode         = true()
         class_filter_array = array()
         classes            = fetch( 'class', 'list' )}
	<div class="panel" id="search_box" style="display: none; position: relative;">
	    {if $embed_mode}
	        <a id="embed_search_go_back_link" title="Go back" href="JavaScript:void(0);" style="position: absolute; top: 0px; right: -5px;"><img width="16" height="16" border="0" src={"tango/emblem-unreadable.png"|ezimage} /></a>
	    {/if}
	    <table class="properties">
	    <tr>
	        <td>
	            <input id="SearchText" name="SearchStr" type="text" value="" onkeypress="return eZOEPopupUtils.searchEnter(event)" />	
	            <select name="SearchContentClassID[]" multiple="multiple" size="4" style="vertical-align:middle">
	            <option value="">{"All"|i18n("design/standard/ezoe")}</option>
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
	</div>
{/default}