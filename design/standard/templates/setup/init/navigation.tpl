  <div class="buttonblock" align="right">
    <input type="hidden" name="eZSetup_current_step" value="{$setup_current_step|wash}" />
    {section show=eq( $dont_show_back, 1 )|not()}
      <input class="button" type="submit" name="eZSetup_back_button" value="&lt;&lt; {"Back"|i18n("design/standard/setup/init", "back button in installation")}" />
    {/section}
    {section show=eq( $refresh, 1 )}
      <input class="button" type="submit" name="eZSetup_refresh_button" value="{"Refresh"|i18n("design/standard/setup/init", "Refresh button in installation")}" /> 
    {/section}
    <input class="defaultbutton" type="submit" name="eZSetup_next_button" value="{"Next"|i18n("design/standard/setup/init", "next button in installation")} &gt;&gt;" />
  </div>
