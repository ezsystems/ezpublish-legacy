{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

  <div align="center">
    <h1>{"Choose site templates"|i18n("design/standard/setup/init")}</h1>
  </div>

  <p>
    {"Choose one or more site templates for your site"|i18n("design/standard/setup/init")}
  </p>

  <table border="0" cellspacing="0" cellpadding="0">
    
    <tr>
    {section name=SiteTemplate loop=$site_templates}

      <td class="setup_site_templates">
        <div align="top">
          <img src={concat( "/design/standard/images/setup/", $:item.image_file_name )|ezroot}>
        </div>
        <div align="bottom">
          <input type="checkbox" name="eZSetup_site_templates[]" value="{$:item.identifier}">{$:item.name}</input>
        </div>
      </td>

      {section show=eq( mod( $:index, 4 ), 3 )}
        </tr>
        <tr>
      {/section}

    {/section}
    </tr>

  </table>      

  {include uri="design:setup/init/steps.tpl"}
  {include uri="design:setup/persistence.tpl"}

  <div class="buttonblock">
      <input class="defaultbutton" type="submit" name="StepButton" value="&gt;&gt;" />
  </div>


</form>
