<div class="context-block">
<h2 class="context-title">{'section'|icon( 'normal', 'Section'|i18n( 'design/admin/section/view' ) )}&nbsp;{$section.name}&nbsp;[{'Section'|i18n( '/design/admin/section/view' )}]</h2>

{$section|attribute(show)}


<div class="controlbar">
<div class="block">
<form action={concat( '/section/edit/', $section.id )|ezurl} method="post">
<input class="button" type="submit" name="" value="{'Edit'|i18n( 'design/admin/section/view' )}" />
</form>

</div>
</div>

</div>

