<form action={$request_uri|ezurl} method="post" >

<div class="maincontentheader">
<h1>{"Wrapping"|i18n("design/standard/workflow/event")}</h1>
</div>

<div class="block">
{'Do you want wrapping in Christmas paper?'|i18n('design/standard/workflow/event')}<br/>
{'No'|i18n('design/standard/workflow/event')} <input type="radio" name="answer" value="no" /><br/>
{'Yes'|i18n('design/standard/workflow/event')}  <input type="radio" name="answer" value="yes" /> <br/>

</div>

<div class="buttonblock">
<input type="submit" name="Next" value="{'Next'|i18n('design/standard/workflow/event')}" />
</div>

</form>
