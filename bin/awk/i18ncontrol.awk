#!/bin/awk -f
BEGIN {
  FileContext="";
}

{
# fetch the translationstring and the context. This regexp is very messy right now. Feel free to fix it :)
  #TODO: need to fetch the rest of the line to see if there are more than one i18n per line
  gotMatch = match( $0, /[\'\"]((\\\'|[^\'])*)[\'\"][ \t\f\n\r\v]*\|[ \t\f\n\r\v]*i18n\([ \t\f\n\r\v]*[\'\"]([^\'\"]*)[\'\"]/, matches );
  if( gotMatch ) # Line contains i18n
  {
    transString = matches[1];
    context = matches[3];
#    print transString;
#    print context;
    # context related checks
    if( FileContext == "" )
    {
      FileContext = context;
      printf( "Context: %s\n", context );
      # sanity check context
      if( FileContext ~ /^\// || FileContext ~ /\/$/ )
      {
        printf( "%i: Bad context\n", NR );
        exit;
      }
      else if( FileContext !~ /^design/ )
        printf( "%i: Context should start with design\n", NR );
    }
    else if( context != FileContext ) # check that new context is similar to old one
    {
        print "Detected context inconsistency\n";
    }

    # translation string related checks
    if( transString ~ /:$/ )
    {
      printf( "%i: Translation string ends with :\n", NR );
    }
  }
}
