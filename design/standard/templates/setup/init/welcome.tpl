{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup step_header=false()}

<div align="center">
  <h1>{"Welcome to eZ publish %1"|i18n("design/standard/setup/init",,array($#version.alias))}</h1>
</div>

<p>
{"Welcome to the installation of eZ publish content management system and development framework. The setup wizard will now guide you through the installation of eZ publish."|i18n("design/standard/setup/init")}
</p>
<p>
{"You will need to have information about a database server you can connect to. You need to have a database which you can use for eZ publish. MySQL and PostgreSQL are supported."|i18n("design/standard/setup/init")}
</p>

<form method="post" action="{$script}">
  {include uri='design:setup/persistence.tpl'}
<p>
  {section show=$system_test_result|eq(1)}
    {"Click >> to continue the installation of eZ publish"|i18n("design/standard/setup/init")}
  {section-else}
    {"Your system cannot install eZ publish as it is. You need to do some modifications. Click >> to see what you have to do."|i18n("design/standard/setup/init")}
  {/section}
</p>

  <div class="buttonblock">
    {include uri="design:setup/init/steps.tpl"}
    <input class="defaultbutton" type="submit" name="button" value="{"Next"|i18n("design/standard/setup/init", "next button in installation")} &gt;&gt;" />
  </div>
</form>


  </div>
</form>
