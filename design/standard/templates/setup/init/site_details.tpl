{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup}

<form method="post" action="{$script}">

  <div align="center">
    <h1>{"Site details"|i18n("design/standard/setup/init")}</h1>
  </div>

  <p>
    {"You need to specify some information about every site you've chosen to install."|i18n("design/standard/setup/init")}
  </p>

{section show=eq( $site_access_illegal, 1 )}
<h2>{"Warning"|i18n("design/standard/setup/init")}</h2>
<p>
  {"Do not use 'admin', 'user' or equal site access values. Please change site illegal access values on sites indicated by *"|i18n("design/standard/setup/init")}
</p>
 {/section}

{section show=eq( $db_already_chosen, 1 )}
<h2>{"Warning"|i18n("design/standard/setup/init")}</h2>
<p>
  {"You have chosen the same database for two or more site templates. Please change where indicated by *"|i18n("design/standard/setup/init")}
</p>
{/section}

{section show=eq( $db_not_empty, 1 )}
<h2>{"Warning"|i18n("design/standard/setup/init")}</h2>
<p>
 {"One or more of your databases already contain data."|i18n("design/standard/setup/init")}
 {"The setup can continue with the initialization but may damage the present data."|i18n("design/standard/setup/init")}
</p>
<p>
 {"Select what to do from the dropdown box(es)."|i18n("design/standard/setup/init")}
</p>
{/section}

  <p>
  <table border="0" cellspacing="0" cellpadding="0">
    
    <tr>
    {section name=SiteTemplate loop=$site_templates}

      <td class="setup_site_templates">
        <div align="top">
	  {section show=eq( $:item.site_access_illegal, 1 )}*{/section}
          {section show=$:item.image_file_name}
            <img src={$:item.image_file_name|ezroot}>
          {section-else}
            <img src={"design/standard/images/setup/eZ_setup_template_default.png"|ezroot}>
          {/section}
        </div>
        <div align="bottom">

          <table border="0" cellspacing="0" cellpadding="0">
                <tr>
	      <td>{"Title"|i18n("design/standard/setup/init")}: </td>
	      <td><input type="text" size="30" name="eZSetup_site_templates_{$:index}_title" value="{$:item.name|wash}" /></td>
	    </tr>
	    <tr>
	      <td>{"Site url"|i18n("design/standard/setup/init")}: </td>
	      <td><input type="text" size="30" name="eZSetup_site_templates_{$:index}_url" value="{$:item.url|wash}" /></td>
	    </tr>
	    <tr>
	      <td>{"Admin e-mail"|i18n("design/standard/setup/init")}: </td>
	      <td><input type="text" size="30" name="eZSetup_site_templates_{$:index}_email" value="{$:item.email|wash}" /></td>
	    </tr>
	    <tr>
              {switch match=$:item.access_type}
              {case match='url'}
                <td>{"URL for access"|i18n("design/standard/setup/init")}: </td>
              {/case}
              {case match='port'}
                <td>{"Port for access"|i18n("design/standard/setup/init")}: </td>
              {/case}
              {case match='hostname'}
                <td>{"Hostname for access"|i18n("design/standard/setup/init")}: </td>
              {/case}
              {case/}
              {/switch}
	      <td><input type="text" size="30" name="eZSetup_site_templates_{$:index}_value" value="{$:item.access_type_value|wash}" /></td>
	    </tr>
	    <tr>
              {switch match=$:item.access_type}
              {case match='url'}
                <td>{"URL for admin access"|i18n("design/standard/setup/init")}: </td>
              {/case}
              {case match='port'}
                <td>{"Port for admin access"|i18n("design/standard/setup/init")}: </td>
              {/case}
              {case match='hostname'}
                <td>{"Hostname for admin access"|i18n("design/standard/setup/init")}: </td>
              {/case}
              {case/}
              {/switch}
	      <td><input type="text" size="30" name="eZSetup_site_templates_{$:index}_admin_value" value="{$:item.admin_access_type_value|wash}" /></td>
	    </tr>

	    <tr>
	      <td>{"Database"|i18n("design/standard/setup/init")}{section show=eq( $:item.db_already_chosen, 1 )}*{/section}: </td>
	      <td>
	        {section show=count( $database_available )|gt(0) }
		  <select name="eZSetup_site_templates_{$:index}_database">
		  {section name=Database loop=$database_available}
		    <option value="{$:item}" {section show=$:item|eq( $SiteTemplate:item.database )}selected="selected"{/section}>{$:item|wash}</option>
		  {/section}
		  </select>
		{section-else}
		  <input type="text" size="20" name="eZSetup_site_templates_{$:index}_database" value="{section show=count($SiteTemplate:item.database)}{$SiteTemplate:item.database}{section-else}{$database_default}{/section}" />
		{/section}
	      </td>
	    </tr>
	    {section show=eq( $:item.db_not_empty, 1 )}
	      <tr>
	        <td>{"Database not empty"|i18n("design/standard/setup/init")}</td>
		<td><select name="eZSetup_site_templates_{$SiteTemplate:index}_existing_database">
		      <option value="1">{"Leave the data and add new"|i18n("design/standard/setup/init")}</option>
		      <option value="2">{"Remove existing data"|i18n("design/standard/setup/init")}</option>
		      <option value="3">{"Leave the data and do nothing"|i18n("design/standard/setup/init")}</option>
		      <option value="4">{"I've chosen a new database"|i18n("design/standard/setup/init")}</option>
		    </select>
		</td>
	      </tr>
	    {/section}
	  </table>

        </div>
      </td>

      {delimiter modulo=1}
        </tr>
        <tr>
      {/delimiter}

    {/section}
    </tr>

  </table>      
  </p>

  {include uri="design:setup/init/steps.tpl"}
  {include uri="design:setup/persistence.tpl"}

  <div class="buttonblock">
      <input class="defaultbutton" type="submit" name="StepButton" value="&gt;&gt;" />
  </div>


</form>
