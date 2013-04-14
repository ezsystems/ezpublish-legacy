{* Section window. *}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h1 class="context-title">{'section'|icon( 'normal', 'Section'|i18n( 'design/admin/section/view' ) )}&nbsp;{'%section_name [Section]'|i18n( 'design/admin/section/view',, hash( '%section_name', $section.name ) )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-attributes">

<div class="block">
<label>{'Name'|i18n( 'design/admin/section/view' )}:</label>
{$section.name|wash}
</div>

<div class="block">
<label>{'Identifier'|i18n( 'design/admin/section/view' )}:</label>
{$section.identifier|wash}
</div>

<div class="block">
<label>{'ID'|i18n( 'design/admin/section/view' )}:</label>
{$section.id}
</div>

</div>

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml">
<div class="block">
<form method="post" action={concat( '/section/edit/', $section.id )|ezurl}>
<input class="button" type="submit" name="_DefaultButton" value="{'Edit'|i18n( 'design/admin/section/view' )}" title="{'Edit this section.'|i18n( 'design/admin/section/view' )}" />
</form>
</div>
{* DESIGN: Control bar END *}</div></div>
</div>

</div>




{* Roles window. *}
{let roles_array=fetch( section, roles, hash( section_id, $section.id ) )
     roles=$roles_array.roles
     limited_policies=$roles_array.limited_policies}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h2 class="context-title">{'Roles containing limitations associated with this section (%number_of_roles)'|i18n( 'design/admin/section/view',, hash( '%number_of_roles', $roles|count ) )}</h2>


{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{section show=$roles}
<table class="list" cellspacing="0">
<tr>
    <th>{'Role'|i18n( 'design/admin/section/view' )}</th>
    <th>{'Limited policies'|i18n( 'design/admin/section/view' )}</th>
</tr>

{section var=Roles loop=$roles sequence=array( bglight, bgdark )}
<tr class="{$Roles.sequence}">
<td>{'role'|icon( 'small', 'Role'|i18n( 'design/admin/section/view' ) )}&nbsp;<a href={concat( '/role/view/', $Roles.item.id )|ezurl}>{$Roles.item.name|wash}</a></td>
<td>
{section var=Policies loop=$limited_policies[$Roles.id]}
{$Policies.module_name}/{$Policies.function_name}
{delimiter}, {/delimiter}
{/section}
</td>
</tr>
{/section}
</table>

{section-else}
<div class="block">
<p>{'This section is not used to limit the policies of any role.'|i18n( 'design/admin/section/view' )}</p>
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>

</div>
{/let}




{* Users and user groups window. *}
{let user_roles=fetch('section','user_roles',hash('section_id',$section.id))}
<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h2 class="context-title">{'Users and user groups with role limitations associated with this section (%number_of_roles)'|i18n( 'design/admin/section/view',, hash( '%number_of_roles', $user_roles|count ) )}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

{section show=$user_roles}
<table class="list" cellspacing="0">
<tr>
    <th>{'User or user group'|i18n( 'design/admin/section/view' )}</th>
    <th>{'Role'|i18n( 'design/admin/section/view' )}</th>
</tr>

{section var=UserRoles loop=$user_roles sequence=array( bglight, bgdark )}
<tr class="{$UserRoles.sequence}">
    <td>{$UserRoles.user.main_node.class_identifier|class_icon( 'small', $UserRoles.user.main_node.class_name )}&nbsp;<a href={$UserRoles.user.main_node.url_alias|ezurl}>{$UserRoles.user.name|wash}</a></td>
    <td>{'role'|icon( 'small', 'Role'|i18n( 'design/admin/section/view' ) )}&nbsp;<a href={concat( '/role/view/', $UserRoles.role.id )|ezurl}>{$UserRoles.role.name|wash}</a></td>
</tr>
{/section}
</table>
{section-else}
<div class="block">
<p>{'This section is not used for limiting roles that are assigned to users or user groups. '|i18n( 'design/admin/section/view' )}</p>
</div>
{/section}


{* DESIGN: Content END *}</div></div></div>

</div>
{/let}




{* Objects window. *}
{let item_type=ezpreference( 'admin_list_limit' )
     number_of_items=min( $item_type, 3)|choose( 10, 10, 25, 50 )
     objects_count=fetch( 'section', 'object_list_count', hash( section_id, $section.id ) )}

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-ml">

<h2 class="context-title">{'Objects within this section (%number_of_objects)'|i18n( 'design/admin/section/view',, hash( '%number_of_objects', $objects_count ) )}</h2>



{* DESIGN: Header END *}</div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-content">

{if $objects_count}
<table class="list" cellspacing="0">
<tr>
<th>{'Name'|i18n( 'design/admin/section/view' )}</th>
</tr>

{section var=Objects loop=fetch( section, object_list, hash( section_id, $section.id, limit,$number_of_items, offset, $view_parameters.offset ) ) sequence=array( bgdark, bglight )}
<tr class="{$Objects.sequence}">
    {if $Objects.item.main_node_id}
    <td>{$Objects.item.main_node.class_identifier|class_icon( small, $Objects.item.main_node.class_name )}&nbsp;<a href={$Objects.main_node.url_alias|ezurl}>{$Objects.item.name|wash}</a></td>
    {/if}
</tr>
{/section}
{else}
<div class="block">
<p>{'This section is not assigned to any objects.'|i18n( 'design/admin/section/view' )}</p>
</div>
{/if}


</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=concat( 'section/view/', $section.id )
         item_count=$objects_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}
</div>

{* DESIGN: Content END *}</div></div></div>


<div class='block'>
<div class="controlbar">{* DESIGN: Control bar START *}

<form method="post" action={concat( '/section/assign/', $section.id )|ezurl}>
<input class="button" type="submit" name="_DefaultButton" value="{'Assign subtree'|i18n( 'design/admin/section/view' )}" title="{'Assign subtree of objects to this section'|i18n( 'design/admin/section/view' )}" />
</form>

{* DESIGN: Control bar END *}</div>
</div>

</div>
{/let}
