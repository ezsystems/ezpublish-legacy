{let name="Sub1" 
     var1="kake"
     var2=concat($Sub1:var1, "mann")}
{set var1="balle"}
{$Sub1:var1}
{$Sub1:var2}
{$:var1}
{$:var2}
{$var1}
{$var2}
{let name="Sub2"
     var1="eZ"
     var2=concat($Sub1:var1, "brok")}
{$Sub1:Sub2:var1}
{$Sub1:Sub2:var2}
{$:var1}
{$:var2}
{$Sub1:var1}
{$Sub1:var2}
{/let}
{/let}