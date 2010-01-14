{if fetch( 'user', 'has_access_to', hash( 'module', 'setup', 'function', 'managecache' ) )}

<div id="clearcache-tool">
{if ezpreference( 'admin_clearcache_menu' )}

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

    {if and( ne( $ui_context, 'edit' ), ne( $ui_context, 'browse' ) )}
        <h4><a class="show-hide-control" href={'/user/preferences/set/admin_clearcache_menu/0'|ezurl} title="{'Hide clear cache menu.'|i18n( 'design/admin/pagelayout' )}">-</a> {'Clear cache'|i18n( 'design/admin/pagelayout' )}</h4>
    {else}
	    {if eq( $ui_context, 'edit' )}
	       <h4><span class="disabled show-hide-control">-</span> <span class="disabled">{'Clear cache'|i18n( 'design/admin/pagelayout' )}</span></h4>
	    {else}
	       <h4><a class="show-hide-control" href={'/user/preferences/set/admin_clearcache_menu/0'|ezurl} title="{'Hide clear cache menu.'|i18n( 'design/admin/pagelayout' )}">-</a> {'Clear cache'|i18n( 'design/admin/pagelayout' )}</h4>
	    {/if}
    {/if}
    
{* DESIGN: Header END *}</div></div>


{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

    {include uri='design:setup/clear_cache.tpl'}

{* DESIGN: Content END *}</div></div></div>

{else}

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

    {if and( ne( $ui_context,'edit' ), ne( $ui_context, 'browse' ) )}
        <h4><a class="show-hide-control" href={'/user/preferences/set/admin_clearcache_menu/1'|ezurl} title="{'Show clear cache menu.'|i18n( 'design/admin/pagelayout' )}">+</a> {'Clear cache'|i18n( 'design/admin/pagelayout' )}</h4>
    {else}
	    {if eq( $ui_context, 'edit' )}
	        <h4><span class="disabled show-hide-control">+</span> <span class="disabled">{'Clear cache'|i18n( 'design/admin/pagelayout' )}</span></h4>
	    {else}
	        <h4><a class="show-hide-control" href={'/user/preferences/set/admin_clearcache_menu/1'|ezurl} title="{'Show clear cache menu.'|i18n( 'design/admin/pagelayout' )}">+</a> {'Clear cache'|i18n( 'design/admin/pagelayout' )}</h4>
	    {/if}
    {/if}
    
{* DESIGN: Header END *}</div></div>

{/if}
</div>

{/if}