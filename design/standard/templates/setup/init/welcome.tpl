{*?template charset=latin1?*}

<div align="center">
  <h1>{"Welcome to eZ publish %1"|i18n("design/standard/setup/init",,array($#version.alias))}</h1>
</div>

{section show=eq( $test.result, 2 )}
    <p>
        {"Welcome to the eZ publish content management system and development framework. This wizard will help you set up eZ publish.<br>There are some important issues that have to be resolved. Click <i>Next</i> to continue."|i18n("design/standard/setup/init")}
</p>
{section-else}
    {section show=eq( $optional_test.result, 2 )}
        <p>
            {"Welcome to the eZ publish content management system and development framework. This wizard will help you set up eZ publish.<br>Your system is not optimal, if you wish you can click the <i>Finetune</i> button. This will present hints on how to fix these issues.<br/> Click <i>Next</i> to continue without finetuning."|i18n("design/standard/setup/init")}
        </p>
    {section-else}
        <p>
            {"Welcome to the eZ publish content management system and development framework. This wizard will help you set up eZ publish.<br>Click <i>Next</i> to continue."|i18n("design/standard/setup/init")}
        </p>
    {/section}
{/section}

<form method="post" action="{$script}">
  {include uri='design:setup/persistence.tpl'}

  {include uri='design:setup/init/navigation.tpl' dont_show_back=1 finetune=and( eq( $test.result, 1 ), eq( $optional_test.result, 2 ) )}

</form>


  </div>
</form>
