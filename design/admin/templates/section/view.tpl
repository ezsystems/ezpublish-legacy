<div class="context-block">
<h2 class="context-title">{'section'|icon( 'normal', 'Section'|i18n( 'design/admin/section/view' ) )}&nbsp;{$section.name}&nbsp;[{'Section'|i18n( '/design/admin/section/view' )}]</h2>

<div class="context-attributes">

<div class="block">
<label>Name</label>
{$section.name}
</div>

<div class="block">
<label>ID</label>
{$section.id}
</div>

<div class="block">
<label>Objects within this section</label>
{$section.objects}
</div>




{$section|attribute(show)}

</div>

{* Buttons. *}
<div class="controlbar">
<div class="block">
<form action={concat( '/section/edit/', $section.id )|ezurl} method="post">
<input class="button" type="submit" name="" value="{'Edit'|i18n( 'design/admin/section/view' )}" />
</form>

</div>
</div>

</div>

