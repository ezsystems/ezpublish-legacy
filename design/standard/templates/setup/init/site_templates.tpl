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
        <div valign="top">
          {section show=$:item.image_file_name}
            <img src={$:item.image_file_name|ezroot} alt="{$:item.name|wash}" />
            <input type="hidden" name="eZSetup_site_templates[{$:index}][image]" value="{$:item.image_file_name}" />
          {section-else}
            <img src={"design/standard/images/setup/eZ_setup_template_default.png"|ezroot} alt="{$:item.name|wash}" />
            <input type="hidden" name="eZSetup_site_templates[{$:index}][image]" value="" />
          {/section}
        </div>
        <div align="bottom">
          <input type="checkbox" name="eZSetup_site_templates[{$:index}][checked]" value="{$:item.identifier}">{$:item.name}</input>
          <input type="hidden" name="eZSetup_site_templates[{$:index}][identifier]" value="{$:item.identifier}" />
          <input type="hidden" name="eZSetup_site_templates[{$:index}][name]" value="{$:item.name}" />
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
