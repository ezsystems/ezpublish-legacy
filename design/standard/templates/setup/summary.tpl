{*?template charset=latin1?*}

<table border="0" cellspacing="5" cellpadding="0">

{section show=count($database)}
  <tr>
    <td valign="top">{"Database"|i18n("design/standard/setup")}</td>
    <td>{$database}</td>
  </tr>
{/section}

{section show=count($languages)}
  <tr>
    <td valign="top">{"Languages"|i18n("design/standard/setup")}</td>
    <td>
      {section name=Languages loop=$languages}
        {$:item}<br/>
      {/section}
    </td>
  </tr>
{/section}

{section show=count($summary_email_info)}
  <tr>
    <td valign="top">{"Mail server"|i18n("design/standard/setup")}</td>
    <td>{$summary_email_info}</td>
  </tr>
{/section}

{section show=$sites}
  <tr>
    <td valign="top">{"Sitedesign"|i18n("design/standard/setup")}</td>
    <td>
      {section name=Sites loop=$sites}
        {$:item.name}<br/>
      {/section}
    </td>
  </tr>
{/section}

</table>
