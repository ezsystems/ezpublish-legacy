<form action={concat( $module.functions.edit.uri, '/', $role.id, '/' )|ezurl} method="post" >

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Create a new policy for the <%role_name> role'|i18n( 'design/admin/role',, hash( '%role_name', $role.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<h2>{'Step one: select module'|i18n( 'design/admin/role' )}</h2>

<p>
{'Welcome to the policy creation wizard. It will help you set up a new policy for the role that is currently being edited. Instructions:'|i18n( 'design/admin/role' )}
<ul>
<li>{'Use the dropdown menu below to select the module that you wish to grant access to.'|i18n( 'design/admin/role' )}</li>
<li>{'Click one of the "Grant.." buttons or click "Cancel" to abort the wizard.'|i18n( 'design/admin/role' )}</li>
</ul>
{'The "Grant access to all functions" button will create a policy that grants unlimited access to the selected module. If you wish to limit the access method in some way, you should click the "Grant access with limitations" button; however, this functionality is only supported by some modules.'|i18n( 'design/admin/role' )}
</p>

<div class="block">
    <label>{'Module'|i18n( 'design/admin/role' )}</label>
    <select name="Modules">
    <option value="*">{'Every module'|i18n( 'design/admin/role' )}</option>
    {section var=Modules loop=$modules }
    <option value="{$Modules.item}">{$Modules.item}</option>
    {/section}
    </select>
</div>

<div class="block">
<input class="button" type="submit" name="AddModule" value="{'Grant access to all functions'|i18n( 'design/admin/role' )}" />
<input class="button" type="submit" name="CustomFunction" value="{'Grant access with limitations'|i18n( 'design/admin/role' )}" />
</div>

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
