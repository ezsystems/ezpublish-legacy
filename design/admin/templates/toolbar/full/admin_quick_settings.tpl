{if fetch( 'user', 'has_access_to', hash( 'module', 'setup', 'function', 'setup' ) )}

<div id="quicksettings-tool">
{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

   {if eq( $ui_context, 'edit' )}
     <h4><span class="disabled">{'Quick settings'|i18n( 'design/admin/pagelayout' )}</span></h4>
   {else}
     <h4>{'Quick settings'|i18n( 'design/admin/pagelayout' )}</h4>
   {/if}

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">
    {let siteaccess        = ezpreference( 'admin_quicksettings_siteaccess' )
         select_siteaccess = true}
        {include uri='design:setup/quick_settings.tpl'}
    {/let}
{* DESIGN: Content END *}</div></div></div>
</div>

{/if}