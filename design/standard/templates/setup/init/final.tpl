{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">
<div align="center">
  <h1>{"Finished!"|i18n("design/standard/setup/init")}</h1>
</div>

<p>
  {"eZ publish has been installed with the following site(s)"|i18n("design/standard/setup/init")}
</p>

<p>
  <table border="0" cellspacing="3" cellpadding="0">
    
    <tr>
    {section name=SiteTemplate loop=$site_templates}

      <td class="setup_site_templates">
        <div align="top">
          <a href="{$:item.url|wash}">{section show=$:item.image_file_name}<img src={$:item.image_file_name|ezroot} alt="{$:item.name|wash}" />{section-else}<img src={"design/standard/images/setup/eZ_setup_template_default.png"|ezroot} alt="{$:item.name|wash}" />{/section}</a>
        </div>
        <div align="bottom">
	  <table border="0" cellspacing="0" cellpadding="0">
            <tr>
	      <td>{"Title"|i18n("design/standard/setup/init")}: </td>
	      <td>{$:item.name|wash}</td>
	    </tr>
	    <tr>
	      <td>{"URL"|i18n("design/standard/setup/init")}: </td>
	      <td><a href="{$:item.url|wash}">{"User site"|i18n('design/standard/setup/init')}</a>, <a href="{$:item.admin_url|wash}">{"Admin site"|i18n('design/standard/setup/init')}</a></td>
	    </tr>
	    <tr>
	      <td>{"Admin e-mail"|i18n("design/standard/setup/init")}: </td>
	      <td>{$:item.email|wash}</td>
	    </tr>
{*	    <tr>
	      <td>{"Site access"|i18n("design/standard/setup/init")} {$:item.access_type|wash}: </td>
	      <td>{$:item.access_type_value|wash}</td>
	    </tr>*}
	  </table>

        </div>
      </td>

      {delimiter module=2}
        </tr>
        <tr>
      {/delimiter}

    {/section}
    </tr>

  </table>      
</p>
