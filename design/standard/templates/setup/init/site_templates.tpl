{*?template charset=latin1?*}

<form method="post" action="{$script}">

  <div align="center">
    <h1>{"Site templates"|i18n("design/standard/setup/init")}</h1>
  </div>

  <p>
    {"Please choose one or more of the demo sites you would like to test or base your site(s) on. Use Plain if you wish to start from scratch."i18n("design/standard/setup/init")}
  </p>

  <table border="0" cellspacing="2" cellpadding="0">
    
    <tr>

    {section name=SiteTemplate loop=$site_templates}
    
      <td class="setup_site_templates">
            {section show=$:item.image_file_name}
              <img src={$:item.image_file_name|ezroot} alt="{$:item.name|wash}" />
              <input type="hidden" name="eZSetup_site_templates[{$:index}][image]" value="{$:item.image_file_name}" />
            {section-else}
              <img src={"design/standard/images/setup/eZ_setup_template_default.png"|ezroot} alt="{$:item.name|wash}" />
              <input type="hidden" name="eZSetup_site_templates[{$:index}][image]" value="" />
            {/section}
      </td>

      {section show=eq( mod( $:index, 4 ), 3 )}

	</tr>
	<tr>

	{section name=SiteTemplateInner loop=$site_templates offset=sub($SiteTemplate:index,3) max=4}
	  <td align="bottom" class="normal">
	    <input type="checkbox" name="eZSetup_site_templates[{sum(sub($SiteTemplate:index, 3), $:index)}][checked]" value="{$:item.identifier}">{$:item.name}</input>
            <input type="hidden" name="eZSetup_site_templates[{sum(sub($SiteTemplate:index, 3), $:index)}][identifier]" value="{$:item.identifier}" />
            <input type="hidden" name="eZSetup_site_templates[{sum(sub($SiteTemplate:index, 3), $:index)}][name]" value="{$:item.name}" />
	  </td>
        {/section}

      {/section}

      {section show=eq( mod( $:index, 4 ), 3 )}
        </tr>
	  <td colspan="4">
	    &nbsp;
	  </td>
        <tr>
      {/section}

    {/section}
    </tr>

  </table>      

  {include uri="design:setup/persistence.tpl"}

  {include uri='design:setup/init/navigation.tpl'}

</form>
