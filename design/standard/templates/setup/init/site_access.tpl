{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

  <div align="center">
    <h1>{"Site access configuration"|i18n("design/standard/setup/init")}</h1>
  </div>

  <p>
    {"Choose which access method you would like to use for your site(s).The access method determines how people will access your site.
 If unsure choose URL."|i18n("design/standard/setup/init")|break}
  </p>

  <p>
  <div class="input_highlight">
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="normal">
          <input type="radio" name="eZSetup_site_access" value="url" checked>{"URL (recommended)"|i18n("design/standard/setup/init")}</input>
        </td>
      </tr>
      <tr>
        <td class="normal">
          <input type="radio" name="eZSetup_site_access" value="port">{"Port. Note: Requires web server configuration "|i18n("design/standard/setup/init")}</input>
        </td>
      </tr>
      <tr>
        <td class="normal">
          <input type="radio" name="eZSetup_site_access" value="hostname">{"Hostname. Note: Requires DNS setup."|i18n("design/standard/setup/init")}</input>
        </td>
      </tr>
    </table>
  </div>
  </p>

  {include uri='design:setup/init/navigation.tpl'}
  {include uri="design:setup/persistence.tpl"}

</form>
