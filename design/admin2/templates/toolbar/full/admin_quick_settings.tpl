<div id="quicksettings-tool">

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr">{if $first}<div class="box-tl"><div class="box-tr">{/if}

{if ezpreference( 'admin_quicksettings_menu' )}
   {if eq( $ui_context, 'edit' )}
     <h4><span class="disabled openclose"><span class="bracket">[</span>-<span class="bracket">]</span></span> <span class="disabled">{'Quick settings'|i18n( 'design/admin/pagelayout' )}</span></h4>
   {else}
     <h4><a class="showhide" href={'/user/preferences/set/admin_quicksettings_menu/0'|ezurl} title="{'Hide quick settings'|i18n( 'design/admin/pagelayout' )}"><span class="bracket">[</span>-<span class="bracket">]</span></a> {'Quick settings'|i18n( 'design/admin/pagelayout' )}</h4>
   {/if}
    
</div></div></div></div>{if $first}</div></div>{/if}

{if $last}
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">
{else}
<div class="box-ml"><div class="box-mr"><div class="box-content">
{/if}

{let siteaccess=ezpreference( 'admin_quicksettings_siteaccess' )
     select_siteaccess=true}

{include uri='design:setup/quick_settings.tpl'}

{/let}

</div></div></div>{if $last}</div></div></div>{/if}

{else}
     {if eq( $ui_context, 'edit' )}
      <h4><span class="disabled openclose"><span class="bracket">[</span>+<span class="bracket">]</span></span> <span class="disabled">{'Quick settings'|i18n( 'design/admin/pagelayout' )}</span></h4>
     {else}
      <h4><a class="showhide" href={'/user/preferences/set/admin_quicksettings_menu/1'|ezurl} title="{'Quick settings'|i18n( 'design/admin/pagelayout' )}"><span class="bracket">[</span>+<span class="bracket">]</span></a>{'Quick settings'|i18n( 'design/admin/pagelayout' )}</h4>
     {/if}
    
</div></div></div></div>{if $first}</div></div>{/if}

{if $last}
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">
</div></div></div></div></div></div>
{/if}

{/if}
</div>
