{*?template charset=latin1?*}
{include uri='design:setup/setup_header.tpl' setup=$setup step_header=false()}

<div align="center">
  <h1>{"Welcome to eZ publish %1"|i18n("design/standard/setup/init",,array($#version.alias))}</h1>
</div>

<p>
{"Welcome to the eZ publish content management system and development framework. This wizard will help you set up eZ publish. Please read the requirements section below and click next to proceed."|i18n("design/standard/setup/init")}
</p>
<p>
{"Requirements"|i18n("design/standard/setup/init")}
</p>
<p>
{"You will need to have information about a database server eZ publish can connect to. The following database servers are supported (both are free):"|i18n("design/standard/setup/init")}
</p>
<p>
1) MySQL: <a href="http://www.mysql.com">http://www.mysql.com</a> ({"recommended"|i18n("design/standard/setup/init")})<br>
2) PostgreSQL: <a href="http://www.postgresql.org">http://www.postgresql.org</a>
</p>
<p>
{'Click "Next" to start the configuration of up eZ publish.'|i18n("design/standard/setup/init")}
</p>

<form method="post" action="{$script}">
  {include uri='design:setup/persistence.tpl'}

  {include uri='design:setup/init/navigation.tpl' dont_show_back=1}

</form>


  </div>
</form>
