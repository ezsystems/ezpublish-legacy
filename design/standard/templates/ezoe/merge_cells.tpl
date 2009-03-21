{set scope=global persistent_variable=hash('title', "Merge table cells"|i18n('design/standard/ezoe'),
                                           'scripts', array('ezoe/ez_core.js',
                                                            'ezoe/popup_utils.js'),
                                           'css', array()
                                           )}
<script type="text/javascript">
<!--
{literal}

function mergeCells()
{
    var args = [], f = document.forms[0];

    tinyMCEPopup.restoreSelection();

    if (!AutoValidator.validate(f)) {
        alert(tinyMCEPopup.getLang('invalid_data'));
        return false;
    }

    args["numcols"] = f.numcols.value;
    args["numrows"] = f.numrows.value;

    tinyMCEPopup.execCommand("mceTableMergeCells", false, args);
    tinyMCEPopup.close();
}

tinyMCEPopup.onInit.add(function()
{
    var f = document.forms[0], v;

    f.numcols.value = tinyMCEPopup.getWindowArg('numcols', 1);
    f.numrows.value = tinyMCEPopup.getWindowArg('numrows', 1);
});

{/literal}
-->
</script>

<div class="merge-cell-view">

<form onsubmit="mergeCells();return false;" action="#" style="width: 220px;">
    <fieldset>
        <legend>{"Merge table cells"|i18n('design/standard/ezoe')}</legend>
          <table border="0" cellpadding="0" cellspacing="3" width="100%">
              <tr>
                <td>{"Columns"|i18n('design/standard/ezoe')}:</td>
                <td align="right"><input type="text" name="numcols" value="" class="number min1 mceFocus" style="width: 30px" /></td>
              </tr>
              <tr>
                <td>{"Rows"|i18n('design/standard/ezoe')}:</td>
                <td align="right"><input type="text" name="numrows" value="" class="number min1" style="width: 30px" /></td>
              </tr>
          </table>
    </fieldset>

    <div class="mceActionPanel float-break block">
        <div class="left">
            <input type="submit" id="insert" name="insert" value="{'OK'|i18n('design/standard/ezoe')}" />
        </div>

        <div class="right">
            <input type="button" id="cancel" name="cancel" value="{'Cancel'|i18n('design/standard/ezoe')}" onclick="tinyMCEPopup.close();" />
        </div>
    </div>
</form>

</div>