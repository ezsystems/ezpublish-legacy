{def $hasXajaxAccess=fetch('user','has_access_to',hash('module','xajax','function','all'))}
{if $hasXajaxAccess}{run-once}{xajax_javascript()}{/run-once}{/if}

<script type="text/javascript">
<!--
{literal}

function removeAllOptions( selectid )
{
    //window.alert( 'removing all options' );
    var i;
    var select;

    select = document.getElementById( selectid );

    if ( select )
    {
        for ( i = ( select.length - 1 ); i >= 0; i-- )
        {
            select.options[i] = null;
        }
    }
}

function addOption( selectid, value, text )
{
    //window.alert( 'adding option ' + text );
    var select = document.getElementById( selectid );

    if(select)
    {
        select.options[select.options.length] = new Option( text, value, false, false );
    }
}

{/literal}

-->
</script>

<form action={concat( $module.functions.edit.uri, '/', $role.id, '/' )|ezurl} method="post" >

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Create a new policy for the <%role_name> role'|i18n( 'design/admin/role/createpolicystep1',, hash( '%role_name', $role.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<h2>{'Step one: select module and function'|i18n( 'design/admin/role/createpolicystep1' )}</h2>

<div class="block">
    <div class="element">
    <label>{'Module'|i18n( 'design/admin/role/createpolicystep1' )}:</label>
    <select name="Modules" onchange="xajax_moduleFunctions(this.options[this.selectedIndex].value);">
    <option value="*">{'Every module'|i18n( 'design/admin/role/createpolicystep1' )}</option>
    {section var=Modules loop=$modules }
    <option value="{$Modules.item}">{$Modules.item}</option>
    {/section}
    </select>
    <input type="hidden" name="CurrentModule" id="CurrentModule" value="" />
    </div>

    <div class="element">
    <label>{'Function'|i18n( 'design/admin/role/createpolicystep2' )}:</label>
    <select name="ModuleFunction" id="ModuleFunction">
    {if is_set( $functions )}
      {section name=Functions loop=$functions}
         <option value="{$Functions:item}">{$Functions:item}</option>
      {/section}
    {/if}
    </select>
    </div>
</div>

<div class="block">
<input class="button" type="submit" name="AddModule" value="{'Grant access to all functions'|i18n( 'design/admin/role/createpolicystep1' )}" />
<input class="button-disabled" type="submit" name="AddFunction" id="AddFunction" value="{'Grant full access to function'|i18n( 'design/admin/role/createpolicystep2' )}" disabled="disabled" />
<input class="button-disabled" type="submit" name="Limitation" id="Limitation" value="{'Grant limited access to function'|i18n( 'design/admin/role/createpolicystep2' )}" disabled="disabled" />
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button-disabled" type="submit" name="" value="{'OK'|i18n( 'design/admin/role/createpolicystep1' )}" disabled="disabled" />
<input class="button" type="submit" name="CancelPolicyButton" value="{'Cancel'|i18n( 'design/admin/role/createpolicystep1' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>
