<div id="package" class="create">
    <div id="sid-{$current_step.id|wash}" class="pc-{$installer.id|wash}">

    <form method="post" action={'package/install'|ezurl}>

    {include uri="design:package/install/error.tpl"}

    {include uri="design:package/install_header.tpl"}

    <p>{'Please select site access mapping.'|i18n('design/standard/package')}</p>


    <label>{'Selected your siteaccess'|i18n('design/standard/package')}</label>
    
    {section loop=$site_access_map}
      <div>{$:key|wash} : <select name="SiteAccessMap_{$:key|wash}">
        {section loop=$available_site_access_array}
          <option>{$:item|wash}</option>
        {/section}
        </select>
      </div>
      {delimiter}
        <div class="break">
      {/delimiter}
    {/section}

    {include uri="design:package/navigator.tpl"}

    </form>

    </div>
</div>