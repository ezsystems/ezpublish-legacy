{switch name=Sw match=$attribute.xml_editor}
  {case match="dhtml"}
  <!-- WYSIWYG editor textarea field -->
  <textarea class="box" name="ContentObjectAttribute_data_text_{$attribute.id}" cols="102" rows="{$attribute.contentclass_attribute.data_int1}">
  {$attribute.input_xml}
  </textarea>
  <script language="javascript">
  var Editor = new eZEditor( 'ContentObjectAttribute_data_text_{$attribute.id}', {'/extension/xmleditor/dhtml/images/'|ezroot}, '', '{$attribute.contentobject_id}', '{$attribute.version}', {ezsys('indexdir')} );
  Editor.startEditor();
  </script>
  <!-- End editor -->
  {/case}
  {case match="standard"}
  <textarea class="box" name="ContentObjectAttribute_data_text_{$attribute.id}" cols="70" rows="{$attribute.contentclass_attribute.data_int1}">{$attribute.input_xml}</textarea>
  {/case}
{/switch}