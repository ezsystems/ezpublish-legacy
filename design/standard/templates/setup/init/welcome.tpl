{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup step_header=false()}

<div align="center">
  <h1>{"Welcome to eZ publish %1"|i18n("design/standard/setup/init",,array($#version.alias))}</h1>
</div>

<p>
{"Welcome to the eZ publish content management system and development framework. This wizard will help you set up eZ publish. Press <i>Next &gt;</i> to continue."|i18n("design/standard/setup/init")}
</p>

<form method="post" action="{$script}">
  {include uri='design:setup/persistence.tpl'}

  {include uri='design:setup/init/navigation.tpl' dont_show_back=1}

</form>


  </div>
</form>
