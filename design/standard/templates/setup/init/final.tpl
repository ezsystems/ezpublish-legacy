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
  <table border="0" cellspacing="0" cellpadding="0">
    
    <tr>
    {section name=SiteTemplate loop=$site_templates}

      <td class="setup_site_templates">
        <div align="top">
          <img src={concat( "/design/standard/images/setup/", $:item.image_file_name )|ezroot}>
        </div>
        <div align="bottom">
	  <table border="0" cellspacing="0" cellpadding="0">
            <tr>
	      <td>{"Title"|i18n("design/standard/setup/init")}: </td>
	      <td>{$:item.name|wash}</td>
	    </tr>
	    <tr>
	      <td>{"URL"|i18n("design/standard/setup/init")}: </td>
	      <td><a href="{$:item.url|wash}">{$:item.url|wash}</a></td>
	    </tr>
	    <tr>
	      <td>{"Admin e-mail"|i18n("design/standard/setup/init")}: </td>
	      <td>{$:item.email|wash}</td>
	    </tr>
	    <tr>
	      <td>{"Site access"|i18n("design/standard/setup/init")} {$:item.access_type|wash}: </td>
	      <td>{$:item.access_type_value|wash}</td>
	    </tr>
	  </table>

        </div>
      </td>

      {section show=eq( mod( $:index, 2 ), 1 )}
        </tr>
        <tr>
      {/section}

    {/section}
    </tr>

  </table>      
</p>
