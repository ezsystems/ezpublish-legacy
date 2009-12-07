<div id="quicksettings-tool">
{if and( $hide_right_menu|not, ezpreference( 'admin_quicksettings_menu' ) )}

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
   {if eq( $ui_context, 'edit' )}
     <h4><span class="disabled openclose"><span class="bracket">[</span>-<span class="bracket">]</span></span> <span class="disabled">{'Quick settings'|i18n( 'design/admin/pagelayout' )}</span></h4>
   {else}
     <h4><a class="showhide" href={'/user/preferences/set/admin_quicksettings_menu/0'|ezurl} title="{'Hide quick settings'|i18n( 'design/admin/pagelayout' )}"><span class="bracket">[</span>-<span class="bracket">]</span></a> {'Quick settings'|i18n( 'design/admin/pagelayout' )}</h4>
   {/if}
</div></div></div></div></div></div>
<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">
    {let siteaccess=ezpreference( 'admin_quicksettings_siteaccess' )
         select_siteaccess=true}
    
    {include uri='design:setup/quick_settings.tpl'}
    
    {/let}
</div></div></div></div></div></div>

{else}

<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
     {if eq( $ui_context, 'edit' )}
      <h4><span class="disabled openclose"><span class="bracket">[</span>+<span class="bracket">]</span></span> <span class="disabled">{'Quick settings'|i18n( 'design/admin/pagelayout' )}</span></h4>
     {else}
      <h4><a class="showhide" href={'/user/preferences/set/admin_quicksettings_menu/1'|ezurl} title="{'Quick settings'|i18n( 'design/admin/pagelayout' )}"><span class="bracket">[</span>+<span class="bracket">]</span></a>{'Quick settings'|i18n( 'design/admin/pagelayout' )}</h4>
     {/if}
</div></div></div></div></div></div>

{/if}
</div>