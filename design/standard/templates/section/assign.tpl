<form method="post" action={concat("/section/assign/",$section.id,"/")|ezurl}>

<div class="maincontentheader">
<h1>Assign section - {$section.name}</h1>
</div>

<div class="buttonblock">
<input class="button" type="submit" name="BrowseNodeButton" value="Assign section to node" />
</div>

</form>