{section show=$current_step.next_step}
    <div class="navigator">
        <input class="button" type="submit" name="NextStepButton" value="{'Next %arrowright'|i18n( 'design/standard/package',, hash( '%arrowright', '&raquo;' ) )}" />
    </div>
{section-else}
    <div class="navigator">
        <input class="button" type="submit" name="NextStepButton" value="{'Finish'|i18n( 'design/standard/package' )}" />
    </div>
{/section}
