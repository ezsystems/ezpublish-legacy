<form action="" method="post" name="test">

<p>Choose an item from the combobox below and press <b>Choose</b> to change the output</p>
<select name="List">
{section name=Combobox loop=$list}
<option value="{$Combobox:key}" {switch name=Sw match=$Combobox:key}{case match=$current}selected{/case}{case/}{/switch}>{$Combobox:item}</option>

{/section}
</select>
<input type="submit" value="Choose" />

<p>The text below is the result of a switch block</p>

{switch name=Sw match=$current}
  {case match=1}
  <p>You selected the first item in the list, now try another.</p>
  {/case}
  
  {case match=2}
  <p>The <b>{$list[$Sw:match]}</b> item is a good choice, now try the <b>{$list[3]}</b>.</p>
  {/case}

  {case match=3}
  <p><b>{$list[$Sw:match]}</b> comes right before <b>{$list[4]}</b>, try it.</p>
  {/case}

  {case match=4}
  <p>This is the last choice, please continue to <b>{$list[1]}</b>.</p>
  {/case}

  {case}
  <p>No case match, probably an error in the post variable List</p>
  {/case}
{/switch}

<h3>In array matching</h3>
<p>This example demonstrates the use of the <i>in</i> parameter,
each <i>case</i> matches one ore more items</p>

{switch name=Sw match=$current}
  {case in=array(1,2)}
  <p>You chose among <b>{$list[1]}</b> and <b>{$list[2]}</b>,
  which probably means you never looked at the items in the combobox above.</p>
  {/case}

  {case in=array(2,3)}
  <p>You choose <b>{$list[$Sw:match]}</b>, you're a curious one.</p>
  {/case}

  {case}
  <p>Again no match</p>
  {/case}
{/switch}

<h3>In array matching w/ key</h3>
{switch name=Sw match=$current}
  {case in=$rows key=id}
  <p>The current item is among the stored rows</p>
  {/case}
  {case}
  <p>The current item was not found among the stored rows</p>
  {/case}
{/switch}

</form>
