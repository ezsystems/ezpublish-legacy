{*?template charset=latin1?*}

<table border="0" cellspacing="5" cellpadding="0">

{section show=count($database)}
  <tr>
    <td>{"Database"|i18n("design/standard/setup")}</td>
    <td>{$database}</td>
  </tr>
{/section}

{section show=count($languages)}
  <tr>
      <td>{"Languages"|i18n("design/standard/setup")}</td>
    <td>
      {section name=Languages loop=$languages}
        {$:item}<br/>
      {/section}
    </td>
  </tr>
{/section}

{section show=count($summary_email_info)}
  <tr>
    <td>{"Mail server"|i18n("design/standard/setup")}</td>
    <td>{$summary_email_info}</td>
  </tr>
{/section}

{section show=$sites}
  <tr>
      <td>{"Sitedesign"|i18n("design/standard/setup")}</td>
    <td>
      {section name=Sites loop=$sites}
        {$:item.identifier}<br/>
      {/section}
    </td>
  </tr>
{/section}

</table>
