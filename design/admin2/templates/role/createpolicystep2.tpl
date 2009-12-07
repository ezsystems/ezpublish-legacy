<form method="post" action={concat( $module.functions.edit.uri, '/', $role.id, '/' )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Create a new policy for the <%role_name> role'|i18n( 'design/admin/role/createpolicystep2',, hash( '%role_name', $role.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<p>
{'Welcome to the policy wizard. This three-step wizard will help you set up a new policy. The policy will be added to the role that is currently being edited. The wizard can be aborted at any stage by using the "Cancel" button.'|i18n( 'design/admin/role/createpolicystep2' )}
</p>

<hr />

<h2>{'Step one: select module [completed]'|i18n( 'design/admin/role/createpolicystep2' )}</h2>

<div class="block">
<label>{'Selected module'|i18n( 'design/admin/role/createpolicystep2' )}:</label>
{if $current_module|eq( '*' )}
{'All modules'|i18n( 'design/admin/role/createpolicystep2' )}
{else}
{$current_module|upfirst()}
{/if}
</div>

<div class="block">
<label>{'Selected access method'|i18n( 'design/admin/role/createpolicystep2' )}:</label>
{'Limited'|i18n( 'design/admin/role/createpolicystep2' )}
</div>

<hr />

<h2>{'Step two: select function'|i18n( 'design/admin/role/createpolicystep2' )}</h2>

{section show=$no_functions|not}

<p>
{'Instructions'|i18n( 'design/admin/role/createpolicystep2' )}:
</p>
<ul>
<li>{'Use the drop-down menu to select the function that you want to grant access to.'|i18n( 'design/admin/role/createpolicystep2' )}</li>
<li>{'Click on one of the "Grant.." buttons (explained below) in order to go to the next step.'|i18n( 'design/admin/role/createpolicystep2' )}</li>
</ul>
<p>
{'The "Grant full access" button will create a policy that grants unlimited access to the selected function within the module that was specified in step one. If you want to limit the access method, click the "Grant limited access" button. Function limitation is only supported by some functions. If unsupported, eZ Publish will set up a policy with unlimited access to the selected function.'|i18n( 'design/admin/role/createpolicystep2' )}
</p>

<div class="block">
<label for="ezrole_createpolizy_function">{'Function'|i18n( 'design/admin/role/createpolicystep2' )}:</label>
<select id="ezrole_createpolizy_function" name="ModuleFunction">
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
{if $current_module|eq( '*' )}
{'It is not possible to grant limited access to all modules at once. To grant unlimited access to all modules and their functions, go back to step one and select "Grant access to all functions". To grant limited access to different functions within different modules, you must set up a collection of policies.'|i18n( 'design/admin/role/createpolicystep2',, hash( '%module_name', $current_module ) )}
{else}
{'The selected module (%module_name) does not support limitations on the function level. Please go back to step one and use the "Grant access to all functions" option instead.'|i18n( 'design/admin/role/createpolicystep2',, hash( '%module_name', $current_module ) )}
{/if}
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
