<div id="clearcache-tool">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr">{section show=$first}<div class="box-tl"><div class="box-tr">{/section}

{section show=ezpreference( 'admin_clearcache_menu' )}
    {section show=and( ne( $ui_context, 'edit' ), ne( $ui_context, 'browse' ) )}
     <h4><a class="showhide" href={'/user/preferences/set/admin_clearcache_menu/0'|ezurl} title="{'Hide clear cache menu.'|i18n( 'design/admin/pagelayout' )}"><span class="bracket">[</span>-<span class="bracket">]</span></a> <a href={'/setup/cache/'|ezurl} title="{'Cache management page'|i18n( 'design/admin/pagelayout' )}">{'Clear cache'|i18n( 'design/admin/pagelayout' )}</a></h4>
    {section-else}
     {section show=eq( $ui_context, 'edit' )}
       <h4><span class="disabled openclose"><span class="bracket">[</span>-<span class="bracket">]</span></span> <span class="disabled">{'Clear cache'|i18n( 'design/admin/pagelayout' )}</span></h4>
     {section-else}
       <h4><a class="showhide" href={'/user/preferences/set/admin_clearcache_menu/0'|ezurl} title="{'Hide clear cache menu.'|i18n( 'design/admin/pagelayout' )}"><span class="bracket">[</span>-<span class="bracket">]</span></a> {'Clear cache'|i18n( 'design/admin/pagelayout' )}</h4>
     {/section}
    {/section}
    
</div></div></div></div>{section show=$first}</div></div>{/section}

{section show=$last}
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">
{section-else}
<div class="box-ml"><div class="box-mr"><div class="box-content">
{/section}

{include uri='design:setup/clear_cache.tpl'}

</div></div></div>{section show=$last}</div></div></div>{/section}

{section-else}
    {section show=and( ne( $ui_context,'edit' ), ne( $ui_context, 'browse' ) )}
     <h4><a class="showhide" href={'/user/preferences/set/admin_clearcache_menu/1'|ezurl} title="{'Show clear cache menu.'|i18n( 'design/admin/pagelayout' )}"><span class="bracket">[</span>+<span class="bracket">]</span></a> <a href={'/setup/cache/'|ezurl} title="{'Cache management page'|i18n( 'design/admin/pagelayout' )}">{'Clear cache'|i18n( 'design/admin/pagelayout' )}</a></h4>
    {section-else}
     {section show=eq( $ui_context, 'edit' )}
      <h4><span class="disabled openclose"><span class="bracket">[</span>+<span class="bracket">]</span></span> <span class="disabled">{'Clear cache'|i18n( 'design/admin/pagelayout' )}</span></h4>
     {section-else}
      <h4><a class="showhide" href={'/user/preferences/set/admin_clearcache_menu/1'|ezurl} title="{'Show clear cache menu.'|i18n( 'design/admin/pagelayout' )}"><span class="bracket">[</span>+<span class="bracket">]</span></a> {'Clear cache'|i18n( 'design/admin/pagelayout' )}</h4>
     {/section}
    {/section}
    
</div></div></div></div>{section show=$first}</div></div>{/section}

{section show=$last}
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">
</div></div></div></div></div></div>
{/section}

{/section}
</div>
