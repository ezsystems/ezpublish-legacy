{*?template charset=latin1?*}

<div align="center">
  <h1>{"Welcome to eZ publish %1"|i18n("design/standard/setup/init",,array($#version.alias))}</h1>
</div>

<p>
{"Welcome to the eZ publish content management system and development framework. This wizard will help you set up eZ publish.<br>Click <i>Next</i> to continue."|i18n("design/standard/setup/init")}
</p>

<form method="post" action="{$script}">
  {include uri='design:setup/persistence.tpl'}

  {include uri='design:setup/init/navigation.tpl' dont_show_back=1}

</form>


  </div>
</form>
