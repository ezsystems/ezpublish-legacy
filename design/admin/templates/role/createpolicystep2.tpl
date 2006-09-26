<form method="post" action={concat( $module.functions.edit.uri, '/', $role.id, '/' )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Create a new policy for the <%role_name> role'|i18n( 'design/admin/role/createpolicystep2',, hash( '%role_name', $role.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<h2>{'Step two: select function'|i18n( 'design/admin/role/createpolicystep2' )}</h2>

<div class="block">
<label>{'Selected module'|i18n( 'design/admin/role/createpolicystep2' )}:</label>
{section show=$current_module|eq( '*' )}
{'All modules'|i18n( 'design/admin/role/createpolicystep2' )}
{section-else}
{$current_module|upfirst()}
{/section}
</div>

{section show=$no_functions|not}

<div class="block">
<label>{'Function'|i18n( 'design/admin/role/createpolicystep2' )}:</label>
<select name="ModuleFunction">
{section name=Functions loop=$functions}
<option value="{$Functions:item}">{$Functions:item}</option>
{/section}
</select>
<input type="hidden" name="CurrentModule" value="{$current_module}" />
</div>

<div class="block">
<input class="button" type="submit" name="AddFunction" value="{'Grant full access'|i18n( 'design/admin/role/createpolicystep2' )}" />
<input class="button" type="submit" name="Limitation" value="{'Grant limited access'|i18n( 'design/admin/role/createpolicystep2' )}" />
</div>

{section-else}

<p>
{section show=$current_module|eq( '*' )}
{'It is unfortunately not possible to grant limited access to all modules at once. To grant unlimited access to all modules and their functions, go back to step one and select "Grant access to all functions". In order to grant limited access to different functions within different modules, you need to set up a collection of policies.'|i18n( 'design/admin/role/createpolicystep2',, hash( '%module_name', $current_module ) )}
{section-else}
{'The selected module (%module_name) does not support limitations on the function level. Please go back to step one and use the "Grant access to all functions" option instead.'|i18n( 'design/admin/role/createpolicystep2',, hash( '%module_name', $current_module ) )}
{/section}
</p>

{/section}

<hr />

<div class="block">
<input class="button" type="submit" name="Step1" value="{'Go back to step one'|i18n( 'design/admin/role/createpolicystep2' )}" />
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button-disabled" type="submit" name="" value="{'OK'|i18n( 'design/admin/role/createpolicystep2' )}" disabled="disabled" />
<input class="button" type="submit" value="{'Cancel'|i18n( 'design/admin/role/createpolicystep2' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
