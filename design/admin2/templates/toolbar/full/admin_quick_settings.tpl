{if $hide_right_menu|not}{* Only fetch policy if right menu is visible *}
{if fetch( 'user', 'has_access_to', hash( 'module', 'setup', 'function', 'setup' ) )}

<div id="quicksettings-tool">
{if ezpreference( 'admin_quicksettings_menu' )}

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

   {if eq( $ui_context, 'edit' )}
     <h4><span class="disabled show-hide-control">-</span> <span class="disabled">{'Quick settings'|i18n( 'design/admin/pagelayout' )}</span></h4>
   {else}
     <h4><a class="show-hide-control" href={'/user/preferences/set/admin_quicksettings_menu/0'|ezurl} title="{'Hide quick settings'|i18n( 'design/admin/pagelayout' )}">-</a> {'Quick settings'|i18n( 'design/admin/pagelayout' )}</h4>
   {/if}

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">
    {let siteaccess        = ezpreference( 'admin_quicksettings_siteaccess' )
         select_siteaccess = true}
        {include uri='design:setup/quick_settings.tpl'}
    {/let}
{* DESIGN: Content END *}</div></div></div>

{else}

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

     {if eq( $ui_context, 'edit' )}
         <h4><span class="disabled show-hide-control">+</span> <span class="disabled">{'Quick settings'|i18n( 'design/admin/pagelayout' )}</span></h4>
     {else}
         <h4><a class="show-hide-control" href={'/user/preferences/set/admin_quicksettings_menu/1'|ezurl} title="{'Quick settings'|i18n( 'design/admin/pagelayout' )}">+</a>{'Quick settings'|i18n( 'design/admin/pagelayout' )}</h4>
     {/if}

{* DESIGN: Header END *}</div></div>

{/if}
</div>

{/if}
{/if}