<div class="warning">
{section show=$parameters.check.view_checked}
<h2>{"View is disabled"|i18n("design/standard/error/kernel",,array($parameters.check.view,$parameters.check.module))}</h2>
<ul>
    <li>{"The view %2/%1 is disabled and cannot be accessed."|i18n("design/standard/error/kernel",,array($parameters.check.view,$parameters.check.module))}</li>
</ul>
{section-else}
<h2>{"Module is disabled"|i18n("design/standard/error/kernel",,array($parameters.check.module))}</h2>
<ul>
    <li>{"The module %1 is disabled and cannot be accessed."|i18n("design/standard/error/kernel",,array($parameters.check.module))}</li>
</ul>
{/section}
</div>
