{*
<textarea class="box" name="ContentObjectAttribute_data_text_{$attribute.id}" cols="70" rows="{$attribute.contentclass_attribute.data_int1}">  {$attribute.content.input.input_xml}</textarea>
*}
<!-- WYSIWYG editor textarea field -->
 <textarea class="box" name="ContentObjectAttribute_data_text_{$attribute.id}" cols="97" rows="{$attribute.contentclass_attribute.data_int1}">
  {$attribute.content.input.input_xml}
  </textarea>
  <script language="javascript">
  var Editor = new eZEditor( 'ContentObjectAttribute_data_text_{$attribute.id}', {'/extension/xmleditor/dhtml/images/'|ezroot},
'', '{$attribute.contentobject_id}', '{$attribute.version}', {ezsys('indexdir')}, {ezsys('imagesize')} );
  Editor.startEditor();
  </script>
<!-- End editor -->
