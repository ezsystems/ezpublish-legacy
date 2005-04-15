custom,%Y-%m-%d:
      '{$timestamp|datetime( custom, "%Y-%m-%d" )}'
custom,klokka %H:%i:%s:
      '{$timestamp|datetime( custom, "klokka %H:%i:%s" )}'
custom,%B %r %S %a %A %d %D %F %g %G %h %H %i %I %j %l %L %m %M %n %s %t %w %W %Y %y %z:
      '{$timestamp|datetime( custom, "%B %r %S %a %A %d %D %F %g %G %h %H %i %I %j %l %L %m %M %n %s %t %w %W %Y %y %z" )}'
custom,abcdefghijklmnopqrstuvwxyz-ABCDEFGHIJKLMNOPQRSTUVWXYZ-1234567890:
      '{$timestamp|datetime( custom, "abcdefghijklmnopqrstuvwxyz-ABCDEFGHIJKLMNOPQRSTUVWXYZ-1234567890" )}'
