<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$site.http_equiv.Content-language|wash}" lang="{$site.http_equiv.Content-language|wash}">
<head>

{* Do some left + right menu stuff before cache-block's *}
{def $hide_right_menu  = ezpreference( 'admin_right_menu_hide' )
     $admin_left_size  = ezpreference( 'admin_left_menu_size' )
     $left_size_hash   = 0
     $user_hash        = concat( $current_user.role_id_list|implode( ',' ), ',', $current_user.limited_assignment_value_list|implode( ',' ) )}

{if or( $hide_right_menu, $admin_left_size )}
	<style type="text/css">
	{if $hide_right_menu}
	    div#page div#rightmenu  {ldelim} width: 0.4em; {rdelim}
	    div#page div#maincontent {ldelim} margin-right: 0.4em; {rdelim}
	{/if}
	{if $admin_left_size}
	    {def $left_menu_widths = ezini( 'LeftMenuSettings', 'MenuWidth', 'menu.ini')}
	    {if is_set( $left_menu_widths[$admin_left_size] )}
	        {set $left_size_hash = $left_menu_widths[$admin_left_size]}
		    div#leftmenu {ldelim} width: {$left_size_hash|int}em; {rdelim}
		    div#maincontent {ldelim} margin-left: {$left_size_hash|int}em; {rdelim}
	    {else}
		    div#page div#leftmenu {ldelim} width: {$admin_left_size|wash}; {rdelim}
		    div#page div#maincontent {ldelim} margin-left: {$admin_left_size|wash}; {rdelim}
		{/if}
	    {undef $left_menu_widths}
	{/if}
	</style>
{/if}

{cache-block keys=array( $module_result.uri, $user_hash, $left_size_hash )}{* Pr uri cache *}

{include uri='design:page_head.tpl'}


{cache-block keys=array( $navigation_part.identifier, $module_result.navigation_part, $ui_context, $ui_component, $user_hash )}{* Pr tab cache *}

{include uri='design:page_head_style.tpl'}
{include uri='design:page_head_script.tpl'}

</head>
<body>

<div id="page">
<div id="header">
<div id="header-design" class="float-break">

	<div id="header-search">
	<form action={'/content/search/'|ezurl} method="get">
	    {def $current_node_id         = first_set( $module_result.node_id, 0 )
	         $selected_search_node_id = first_set( $search_subtree_array[0], 0 )
	         $searching_disabled      = $ui_context|eq( 'edit' )}
		{if $searching_disabled}
		    <select name="SubTreeArray" title="{'Search location, to be able to narrow down the search results!'|i18n('design/admin/pagelayout')}" disabled="disabled">
		        <option value="1" title="{'Search everthing!'|i18n( 'design/admin/pagelayout' )}">{'Everything'|i18n( 'design/admin/pagelayout' )}</option>
		    </select>
		    <input id="searchtext" name="SearchText" type="text" size="20" value="{if is_set( $search_text )}{$search_text|wash}{/if}" disabled="disabled" defaultValue="{'Search text'|i18n( 'design/admin/pagelayout' )}" />
		    <input id="searchbutton" class="button-disabled" name="SearchButton" type="submit" value="{'Search'|i18n( 'design/admin/pagelayout' )}" disabled="disabled" />
		    <p class="advanced hide"><span class="disabled">{'Advanced'|i18n( 'design/admin/pagelayout' )}</span></p>
		{else}
		    <select name="SubTreeArray" title="{'Search location, to be able to narrow down the search results!'|i18n('design/admin/pagelayout')}">
		        <option value="1" title="{'Search everthing!'|i18n( 'design/admin/pagelayout' )}">{'Everything'|i18n( 'design/admin/pagelayout' )}</option>
		        <option value="{ezini( 'NodeSettings', 'RootNode', 'content.ini' )}" title="{'Search content!'|i18n( 'design/admin/pagelayout' )}">{'Content'|i18n( 'design/admin/pagelayout' )}</option>
		        <option value="{ezini( 'NodeSettings', 'MediaRootNode', 'content.ini' )}" title="{'Search media!'|i18n( 'design/admin/pagelayout' )}">{'Media'|i18n( 'design/admin/pagelayout' )}</option>
		        <option value="{ezini( 'NodeSettings', 'UserRootNode', 'content.ini' )}" title="{'Search users!'|i18n( 'design/admin/pagelayout' )}">{'Users'|i18n( 'design/admin/pagelayout' )}</option>
		        {if $selected_search_node_id|gt( 1 )}
		            <option value="{$selected_search_node_id}" selected="selected">{'The same location'|i18n( 'design/admin/pagelayout' )}</option>
		        {elseif $current_node_id}
		            <option value="{$current_node_id}">{'Current location'|i18n( 'design/admin/pagelayout' )}</option>
		        {/if}
		    </select>
		    <input id="searchtext" name="SearchText" type="text" size="20" value="{if is_set( $search_text )}{$search_text|wash}{/if}" defaultValue="{'Search text'|i18n( 'design/admin/pagelayout' )}" />
		    <input id="searchbutton" class="button" name="SearchButton" type="submit" value="{'Search'|i18n( 'design/admin/pagelayout' )}" />
		    {if eq( $ui_context, 'browse' ) }
		        <input name="Mode" type="hidden" value="browse" />
		        <input name="BrowsePageLimit" type="hidden" value="{min( ezpreference( 'admin_list_limit' ), 3)|choose( 10, 10, 25, 50 )}" />
		        <p class="advanced hide"><span class="disabled">{'Advanced'|i18n( 'design/admin/pagelayout' )}</span></p>
		    {else}
		        <p class="advanced hide"><a href={'/content/advancedsearch'|ezurl} title="{'Advanced search.'|i18n( 'design/admin/pagelayout' )}">{'Advanced'|i18n( 'design/admin/pagelayout' )}</a></p>
		    {/if}
		{/if}
	    {undef $current_node_id $selected_search_node_id $searching_disabled}
	</form>
	</div>

    <div id="header-logo">
        <a href="http://ez.no" title="eZ Publish {fetch( 'setup', 'version' )}" target="_blank">eZ logo</a>
    </div>

    <div id="header-usermenu">
        <a href={'/user/logout'|ezurl} title="{'Logout from the system.'|i18n( 'design/admin/pagelayout' )}" id="header-usermenu-logout">{'Logout'|i18n( 'design/admin/pagelayout' )}</a>
    </div>

	<div id="header-topmenu">
	<ul>
	{foreach topmenu($ui_context) as $menu}
	    {include uri='design:page_topmenuitem.tpl' menu_item=$menu navigationpart_identifier=$navigation_part.identifier}
	{/foreach}
	</ul>
	</div>

</div>
</div>
{/cache-block}{* /Pr tab cache *}

<hr class="hide" />

<div id="path">
<div id="path-design">

{include uri='design:page_toppath.tpl'}

</div>
</div>


<hr class="hide" />

<div id="columns">

{if and( eq( $ui_context, 'edit' ), eq( $ui_component, 'content' ) )}
{else}
<div id="leftmenu">
<div id="leftmenu-design">

{if is_set( $module_result.left_menu )}
    {include uri=$module_result.left_menu}
{else}
	{* 
	    Get navigationpart identifier variable depends if the call is an contenobject
	    or a custom module 
	*}
	{def $navigation_part_name = $navigation_part.identifier}
	{if $navigation_part_name|eq('')}
	    {set $navigation_part_name = $module_result.navigation_part}
	{/if}
	{* 
	    Include automatically the menu template for the $navigation_part_name
	    ez $part_name navigationpart =>  parts/$part_name/menu.tpl
	*}
	{def $extract_length = sub( count_chars( $navigation_part_name ), '14' )
	     $part_name = $navigation_part_name|extract( '2', $extract_length )}

	{include uri=concat( 'design:parts/', $part_name, '/menu.tpl' )}

	{undef $extract_length $part_name $navigation_part_name}
{/if}

</div>
</div>

<hr class="hide" />

{/if}

<div id="rightmenu">
<div id="rightmenu-design">

{tool_bar name='admin_right' view=full}
{tool_bar name='admin_developer' view=full}

</div>
</div>

<hr class="hide" />

{/cache-block}{* /Pr uri cache *}


{if and( eq( $ui_context, 'edit' ), eq( $ui_component, 'content' ) )}
{* Main area START *}

{include uri='design:page_mainarea.tpl'}

{* Main area END *}
{else}

<div id="maincontent">
<div id="maincontent-design" class="float-break"><div id="fix">
<!-- Maincontent START -->
{* Main area START *}

{include uri='design:page_mainarea.tpl'}

{* Main area END *}
<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>

{/if}
<div class="break"></div>
</div>

<hr class="hide" />

<div id="footer" class="float-break">
<div id="footer-design">

{include uri='design:page_copyright.tpl'}

</div>
</div>

<div class="break"></div>
</div>

<script type="text/javascript">
<!--
document.getElementById('header-usermenu-logout').innerHTML += ' ({$current_user.login|wash})';{* contentobject.name *}
-->
</script>


{* This comment will be replaced with actual debug report (if debug is on). *}
<!--DEBUG_REPORT-->

</body>
</html>
