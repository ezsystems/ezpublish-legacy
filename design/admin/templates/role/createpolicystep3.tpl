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

<h2>{'Step two: select function [completed]'|i18n( 'design/admin/role' )}</h2>

<div class="block">
<label>{'Selected function'|i18n( 'design/admin/role' )}</label>
{$current_function|upfirst()}
</div>

<div class="block">
<label>{'Selected access method'|i18n( 'design/admin/role' )}</label>
{'Limited'|i18n( 'design/admin/role' )}
</div>

<hr />

<h2>{'Step three: define function limitations'|i18n( 'design/admin/role' )}</h2>

<div class="block">
{section name=Limitations loop=$function_limitations}
{section-exclude match=$Limitations:item.name|eq('Subtree')}
{section-exclude match=$Limitations:item.name|eq('Node')}
<div class="element">
<label>{$Limitations:item.name}</label>
<select name="{$Limitations:item.name}[]" size="8" multiple >
<option value="-1" {switch match=$current_limitation_list[$Limitations:item.name]}
{case match=-1} selected="selected"{/case}{case}{/case}{/switch}>{'Any'|i18n( 'design/admin/role' )}</option>
{section name=LimitationValues loop=$Limitations:item.values}
<option value="{$Limitations:LimitationValues:item.value}" {switch match=$Limitations:LimitationValues:item.value}
{case in=$current_limitation_list[$Limitations:item.name]}selected="selected"{/case}{case}{/case}{/switch}>{$Limitations:LimitationValues:item.Name}</option>
{/section}
</select>
</div>
{/section}
</div>


{section var=Limitations loop=$function_limitations}

{switch match=$Limitations.item.name}

{* Nodes *}
{case match="Node"}
<div class="block">
<label>{'Nodes [%node_count]'|i18n( 'design/admin/role',, hash( '%node_count', $node_list|count ) )}</label>
{section show=$node_list}
<table class="list" cellspacing="0">
<tr>
<th class="tight">&nbsp;</th>
<th>{'Name'|i18n( 'design/admin/role' )}</th>
</tr>
{section var=Nodes loop=$node_list sequence=array( bglight, bgdark )}
<tr class="{$Nodes.sequence}">
<td><input type="checkbox" name="DeleteNodeIDArray[]" value={$Nodes.item.node_id} /></td>
<td>{$Nodes.item.name}</td>
</tr>
{/section}
</table>
{section-else}
<p>
{'The node list is empty.'|i18n( 'design/admin/role' )}
</p>
{/section}
<input class="button" type="submit" name="DeleteNodeButton" value="{'Remove selected'|i18n( 'design/admin/role' )}" {section show=$node_list|not}disabled="disabled"{/section} />
<input class="button" type="submit" name="BrowseLimitationNodeButton" value="{'Add nodes'|i18n( 'design/admin/role' )}" />
</div>
{/case}

{* Subtrees *}
{case match="Subtree"}
<div class="block">
<label>{'Subtrees [%subtree_count]'|i18n( 'design/admin/role',, hash( '%subtree_count', $subtree_list|count ) )}</label>

{section show=$subtree_list}
<table class="list" cellspacing="0">
<tr>
<th class="tight">&nbsp;</th>
<th>{'Subtree'|i18n( 'design/admin/role' )}</th>
</tr>
{section var=Subtrees loop=$subtree_list sequence=array( bglight, bgdark )}
<tr class="{$Subtrees.sequence}">
<td><input type="checkbox" name="DeleteSubtreeIDArray[]" value={$Subtrees.item.node_id} /></td>
<td>{$Subtrees.item.name}</td>
</tr>
{/section}
</table>
{section-else}
<p>{'The subtree list is empty.'|i18n( 'design/admin/role' )}</p>
{/section}
<input class="button" type="submit" name="DeleteSubtreeButton" value="{'Remove selected'|i18n( 'design/admin/role' )}" {section show=$subtree_list|not}disabled="disabled"{/section} />
<input class="button" type="submit" name="BrowseLimitationSubtreeButton" value="{'Add subtrees'|i18n( 'design/admin/role' )}" />
</div>
{/case}

{case}
{/case}

{/switch}

{/section}

<hr />

<input class="button" type="submit" name="Step2" value="{'Go back to step two'|i18n( 'design/admin/role' )}" />

</div>


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input type="hidden" name="CurrentModule" value="{$current_module}" />
<input type="hidden" name="CurrentFunction" value="{$current_function}" />
<input class="button" type="submit" name="AddLimitation" value="{'OK'|i18n( 'design/admin/role' )}" />
<input class="button" type="submit" name="Cancel" value="{'Cancel'|i18n( 'design/admin/role' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>


</div>

</form>
