{*?template charset=latin1?*}

<table border="0" cellspacing="3" cellpadding="0">

  <tr>
    <td>
    {section show=count( $system_check )}
      <div class="setup_summary_ok">
    {section-else}
      <div class="setup_summary_empty">
    {/section}
    
    {"System check:"|i18n("design/standard/setup")}</div></td>

    <td>
    {section show=count( $system_check )}
      <div class="setup_summary_ok">{"Ok"|i18n("design/standard/setup")}</div>
    {/section}
    </td>
  </tr>

  <tr>
    <td>
    {section show=count($image_processor)}
      <div class="setup_summary_ok">
    {section-else}
      <div class="setup_summary_empty">
    {/section}

    {"Image settings:"|i18n("design/standard/setup")}</div></td>

    <td>
    {section show=count($image_processor)}
      <div class="setup_summary_ok">{$image_processor}</div>
    {/section}
    </td>
    </div>
  </tr>

  <tr>  
    <td >
    {section show=count($summary_email_info)}
      <div class="setup_summary_ok">
    {section-else}
      <div class="setup_summary_empty">
    {/section}

    {"Outgoing mail:"|i18n("design/standard/setup")}</td>

    <td>
    {section show=count($summary_email_info)}
      <div class="setup_summary_ok">{$summary_email_info}</div>
    {/section}
    </td>
  </tr>

  <tr>
    <td>
    {section show=count($database)}
      <div class="setup_summary_ok">
    {section-else}
      <div class="setup_summary_empty">
    {/section}

    {"Database settings:"|i18n("design/standard/setup")}</div></td>

    <td>
    {section show=count($database)}
      <div class="setup_summary_ok">{$database}</div></td>
    {/section}
    </td>
  </tr>

  <tr>  
    <td valign="top">
    {section show=count($languages)}
      <div class="setup_summary_ok">
    {section-else}
      <div class="setup_summary_empty">
    {/section}
  
    {"Language settings:"|i18n("design/standard/setup")}</div></td>

    <td>
    {section show=count($languages)}
      <div class="setup_summary_ok">
      {section name=Languages loop=$languages}
        {$:item}<br/>
      {/section}
      </div>
    {/section}
    </td>
  </tr>

  <tr>
    <td valign="top">
    {section show=$sites}
      <div class="setup_summary_ok">
    {section-else}
      <div class="setup_summary_empty">
    {/section}

    {"Sitedesign:"|i18n("design/standard/setup")}</td>

    <td>
    {section show=$sites}
      <div class="setup_summary_ok">
      {section name=Sites loop=$sites}
        {$:item.name}<br/>
      {/section}
      </div>
    {/section}
    </td>
  </tr>

</table>
