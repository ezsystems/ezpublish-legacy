<div id="quicksettings-tool">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr">{section show=$first}<div class="box-tl"><div class="box-tr">{/section}

{section show=ezpreference( 'admin_quicksettings_menu' )}
   {section show=eq( $ui_context, 'edit' )}
     <h4><span class="disabled openclose"><span class="bracket">[</span>-<span class="bracket">]</span></span> <span class="disabled">{'Quick settings'|i18n( 'design/admin/pagelayout' )}</span></h4>
   {section-else}
     <h4><a class="showhide" href={'/user/preferences/set/admin_quicksettings_menu/0'|ezurl} title="{'Hide quick settings'|i18n( 'design/admin/pagelayout' )}"><span class="bracket">[</span>-<span class="bracket">]</span></a> {'Quick settings'|i18n( 'design/admin/pagelayout' )}</h4>
   {/section}
    
</div></div></div></div>{section show=$first}</div></div>{/section}

{section show=$last}
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">
{section-else}
<div class="box-ml"><div class="box-mr"><div class="box-content">
{/section}

{let siteaccess=ezpreference( 'admin_quicksettings_siteaccess' )
     select_siteaccess=true}

{include uri='design:setup/quick_settings.tpl'}

{/let}

</div></div></div>{section show=$last}</div></div></div>{/section}

{section-else}
     {section show=eq( $ui_context, 'edit' )}
      <h4><span class="disabled openclose"><span class="bracket">[</span>+<span class="bracket">]</span></span> <span class="disabled">{'Quick settings'|i18n( 'design/admin/pagelayout' )}</span></h4>
     {section-else}
      <h4><a class="showhide" href={'/user/preferences/set/admin_quicksettings_menu/1'|ezurl} title="{'Quick settings'|i18n( 'design/admin/pagelayout' )}"><span class="bracket">[</span>+<span class="bracket">]</span></a>{'Quick settings'|i18n( 'design/admin/pagelayout' )}</h4>
     {/section}
    
</div></div></div></div>{section show=$first}</div></div>{/section}

{section show=$last}
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">
</div></div></div></div></div></div>
{/section}

{/section}
</div>
