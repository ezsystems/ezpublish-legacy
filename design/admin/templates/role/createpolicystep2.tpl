<form method="post" action={concat( $module.functions.edit.uri, '/', $role.id, '/' )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Create a new policy for the <%role_name> role'|i18n( 'design/admin/role',, hash( '%role_name', $role.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<h2>{'Step one: select module [completed]'|i18n( 'design/admin/role' )}</h2>

<div class="block">
<label>{'Selected module'|i18n( 'design/admin/role' )}</label>
{section show=$current_module|eq( '*' )}
{'All modules'|i18n( 'design/admin/role' )}
{section-else}
{$current_module|upfirst()}
{/section}
</div>

<div class="block">
<label>{'Selected access method'|i18n( 'design/admin/role' )}</label>
{'Limited'|i18n( 'design/admin/role' )}
</div>

<hr />

<h2>{'Step two: select function'|i18n( 'design/admin/role' )}</h2>

<p>{'In this step you have to select the function that you wish to grant access to. Instructions:'|i18n( 'design/admin/role' )}
<ul>
<li>{'Use the dropdown menu below to select the function that you wish to grant access to.'|i18n( 'design/admin/role' )}</li>
<li>{'Click on one of the "Grant.." buttons.'}</li>
</ul>
{'The "Grant full access" button will create a policy that grants unlimited access to the selected module and function. If you wish to limit the access method in some way, you should click the "Grant limited access" button; however, this functionality is only supported by some functions. If you click "Grant limited access" and function limitation is unsupported, eZ publish will simply set up a policy with unlimited access to the selected function within the selected module.'|i18n( 'design/admin/role' )}
</p>

{section show=$no_functions|not}

<div class="block">
<label>{'Function'|i18n( 'design/admin/role' )}</label>
<select name="ModuleFunction">
{section name=Functions loop=$functions}
<option value="{$Functions:item}">{$Functions:item}</option>
{/section}
</select>
<input type="hidden" name="CurrentModule" value="{$current_module}" />
</div>

<input class="button" type="submit" name="AddFunction" value="{'Grant full access'|i18n( 'design/admin/role' )}" />
<input class="button" type="submit" name="Limitation" value="{'Grant limited access'|i18n( 'design/admin/role' )}" />

{section-else}

<p>
{section show=$current_module|eq( '*' )}
{'It is not possible to grant limited access to all modules at once. To grant unlimited acess to all modules, go back to step one and select "Grant access to all functions"; however, be careful because this will basically allow everything. In order to grant limited access to different functions within different modules, you need to set up a collection of policies.'|i18n( 'design/admin/role',, hash( '%module_name', $current_module ) )}
{section-else}
{'The selected module (%module_name) does not support limitations on the function level. Please go back to step one and use the "Grant access to all functions" option instead.'|i18n( 'design/admin/role',, hash( '%module_name', $current_module ) )}
{/section}
</p>

{/section}

<hr />

<input class="button" type="submit" name="Step1" value="{'Go back to step one'|i18n( 'design/admin/role' )}" />


</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" value="{'Cancel'|i18n( 'design/admin/role' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
