<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{'section'|icon( 'normal', 'Section'|i18n( 'design/admin/section/view' ) )}&nbsp;{'%section_name [Section]'|i18n( '/design/admin/section/view',, hash( '%section_name', $section.name ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Name'|i18n( 'design/admin/section/view' )}</label>
{$section.name}
</div>

<div class="block">
<label>{'ID'|i18n( 'design/admin/section/view' )}</label>
{$section.id}
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<form action={concat( '/section/edit/', $section.id )|ezurl} method="post">
<input class="button" type="submit" name="" value="{'Edit'|i18n( 'design/admin/section/view' )}" />
</form>
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

{let roles_array=fetch('section','roles',hash('section_id',$section.id))
     roles=$roles_array.roles
     limited_policies=$roles_array.limited_policies}
<div class="context-block">

{* DESIGN: Header START *}
<div class="box-header">
<div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Roles containg limitation on this section [%number_of_roles]'|i18n( 'design/admin/section/view',,hash('%number_of_roles',count($roles) ) )|wash}</h2>

{* DESIGN: Mainline *}
<div class="header-subline"></div>

{* DESIGN: Header END *}
</div></div></div></div></div></div>

{* DESIGN: Content START *}
<div class="box-bc"><div class="box-ml"><div class="box-mr">
<div class="box-bl"><div class="box-br">
<div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th>{'Role'|i18n( 'design/admin/section/view' )}</th>
    <th>{'Policies being limited'|i18n( 'design/admin/section/view' )}</th>
</tr>

{section var=role loop=$roles sequence=array(bglight,bgdark)}
<tr class="{$role.sequence}">
<td><a href={concat('/role/view/',$role.id)|ezurl}>{$role.name}</a></td>
<td>
{section var=policy loop=$limited_policies[$role.id]}
{$policy.module_name}/{$policy.function_name}
{delimiter}, {/delimiter}
{/section}
</td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}
</div>
</div></div>
</div></div></div>

</div>
{/let}

{let user_roles=fetch('section','user_roles',hash('section_id',$section.id))}
<div class="context-block">

{* DESIGN: Header START *}
<div class="box-header">
<div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'User groups and users which have set limitation on this section [%number_of_roles]'|i18n( 'design/admin/section/view',,hash('%number_of_roles',count($user_roles) ) )|wash}</h2>

{* DESIGN: Mainline *}
<div class="header-subline"></div>

{* DESIGN: Header END *}
</div></div></div></div></div></div>

{* DESIGN: Content START *}
<div class="box-bc"><div class="box-ml"><div class="box-mr">
<div class="box-bl"><div class="box-br">
<div class="box-content">

<table class="list" cellspacing="0">
<tr>
    <th>{'User group or user'|i18n( 'design/admin/section/view' )}</th>
    <th>{'Role'|i18n( 'design/admin/section/view' )}</th>
</tr>

{section var=user_role loop=$user_roles sequence=array(bglight,bgdark)}
<tr class="{$user_role.sequence}">
    <td><a href={$user_role.user.main_node.url_alias|ezurl}>{$user_role.user.name}</a></td>
    <td><a href={concat('/role/view/',$user_role.role.id)|ezurl}>{$user_role.role.name}</a></td>
</tr>
{/section}
</table>

{* DESIGN: Content END *}
</div>
</div></div>
</div></div></div>

</div>
{/let}

{let item_type=ezpreference( 'items' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     objects=fetch('section','object_list',hash('section_id',$section.id,'limit',$number_of_items,'offset',$view_parameters.offset))
     objects_count=fetch('section','object_list_count',hash('section_id',$section.id))}

<div class="context-block">

{* DESIGN: Header START *}
<div class="box-header">
<div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Objects in this section [%number_of_objects]'|i18n( 'design/admin/section/view',,hash('%number_of_objects',$objects_count ) )|wash}</h2>

{* DESIGN: Mainline *}
<div class="header-subline"></div>

{* DESIGN: Header END *}
</div></div></div></div></div></div>

{* DESIGN: Content START *}
<div class="box-bc"><div class="box-ml"><div class="box-mr">
<div class="box-bl"><div class="box-br">
<div class="box-content">

<table class="list" cellspacing="0">

{section var=object loop=$objects}
<tr class="bgdark"><td><a href={$object.main_node.url_alias|ezurl}>{$object.name}</a></td></tr>
{/section}

</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat('section/view/',$section.id)
         item_count=$objects_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>

{* DESIGN: Content END *}
</div>
</div></div>
</div></div></div>

</div>
{/let}
