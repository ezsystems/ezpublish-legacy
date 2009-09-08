<p>This is my template</p>
<form method="post" action={$uri|ezurl}>
    <input type="submit" name="PublishButton" value="Repeat" />
    <input type="hidden" name="HasObjectInput" value="0" />
</form>
<form method="post" action={$uri|ezurl}>
    <input type="submit" name="PublishButton" value="Publish !" />
    <input type="hidden" name="HasObjectInput" value="0" />
    <input type="hidden" name="CompletePublishing" value="1" />
</form>