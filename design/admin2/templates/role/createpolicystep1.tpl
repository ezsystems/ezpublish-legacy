<form action={concat( $module.functions.edit.uri, '/', $role.id, '/' )|ezurl} method="post" >

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Create a new policy for the <%role_name> role'|i18n( 'design/admin/role/createpolicystep1',, hash( '%role_name', $role.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">
<p>
{'Welcome to the policy wizard. This three-step wizard will help you create a new policy that will be added to the role that is currently being edited. The wizard can be aborted at any stage by using the "Cancel" button.'|i18n( 'design/admin/role/createpolicystep1' )}
</p>

<hr />

<h2>{'Step one: select module'|i18n( 'design/admin/role/createpolicystep1' )}</h2>
<p>
{'Instructions'|i18n( 'design/admin/role/createpolicystep1' )}:
</p>
<ol>
<li>{'Use the drop-down menu to select the module that you want to grant access to.'|i18n( 'design/admin/role/createpolicystep1' )}</li>
<li>{'Click one of the "Grant.." buttons (explained below) in order to go to the next step.'|i18n( 'design/admin/role/createpolicystep1' )}</li>
</ol>
<p>
{'The "Grant access to all functions" button will create a policy that grants unlimited access to all functions of the selected module. If you wish to limit the access method to a specific function, use the "Grant access to a function" button. Please note that function limitation is only supported by some modules (the next step will reveal if it works or not).'|i18n( 'design/admin/role/createpolicystep1' )}
</p>

<div class="block">
    <label for="ezrole_createpolizy_module">{'Module'|i18n( 'design/admin/role/createpolicystep1' )}:</label>
    <select id="ezrole_createpolizy_module" name="Modules">
    <option value="*">{'Every module'|i18n( 'design/admin/role/createpolicystep1' )}</option>
    {section var=Modules loop=$modules }
    <option value="{$Modules.item}">{$Modules.item}</option>
    {/section}
    </select>
</div>

<div class="block">
<input class="button" type="submit" name="AddModule" value="{'Grant access to all functions'|i18n( 'design/admin/role/createpolicystep1' )}" />
<input class="button" type="submit" name="CustomFunction" value="{'Grant access to one function'|i18n( 'design/admin/role/createpolicystep1' )}" />
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button-disabled" type="submit" name="" value="{'OK'|i18n( 'design/admin/role/createpolicystep1' )}" disabled="disabled" />
<input class="button" type="submit" value="{'Cancel'|i18n( 'design/admin/role/createpolicystep1' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
