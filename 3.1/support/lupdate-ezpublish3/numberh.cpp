/**********************************************************************
**   Copyright (C) 2000 Trolltech AS.  All rights reserved.
**
**   numberh.cpp
**
**   This file is part of Qt Linguist.
**
**   See the file LICENSE included in the distribution for the usage
**   and distribution terms.
**
**   The file is provided AS IS with NO WARRANTY OF ANY KIND,
**   INCLUDING THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR
**   A PARTICULAR PURPOSE.
**
**********************************************************************/

#include <qmemarray.h>
#include <qcstring.h>
#include <qmap.h>
#include <qstringlist.h>

#include <ctype.h>
#include <metatranslator.h>

typedef QMap<QCString, MetaTranslatorMessage> TMM;
typedef QValueList<MetaTranslatorMessage> TML;

static bool isDigitFriendly( int c )
{
    return ispunct( c ) || isspace( c );
}

static int numberLength( const char *s )
{
    int i = 0;

    if ( isdigit(s[0]) ) {
	do {
	    i++;
	} while ( isdigit(s[i]) ||
		  (isDigitFriendly(s[i]) &&
		   (isdigit(s[i + 1]) ||
		    (isDigitFriendly(s[i + 1]) && isdigit(s[i + 2])))) );
    }
    return i;
}

/*
  Returns a version of 'key' where all numbers have been replaced by zeroes.  If
  there were none, returns "".
*/
static QCString zeroKey( const char *key )
{
    QCString zeroed( strlen(key) + 1 );
    char *z = zeroed.data();
    int i = 0, j = 0;
    int len;
    bool metSomething = FALSE;

    while ( key[i] != '\0' ) {
	len = numberLength( key + i );
	if ( len > 0 ) {
	    i += len;
	    z[j++] = '0';
	    metSomething = TRUE;
	} else {
	    z[j++] = key[i++];
	}
    }
    z[j] = '\0';

    if ( metSomething )
	return zeroed;
    else
	return "";
}

static QString translationAttempt( const QString& oldTranslation,
				   const char *oldSource,
				   const char *newSource )
{
    int p = zeroKey( oldSource ).contains( '0' );
    int oldSourceLen = qstrlen( oldSource );
    QString attempt;
    QStringList oldNumbers;
    QStringList newNumbers;
    QMemArray<bool> met( p );
    QMemArray<int> matchedYet( p );
    int i, j;
    int k = 0, ell, best;
    int m, n;
    int pass;

    /*
      This algorithm is hard to follow, so we'll consider an example
      all along: oldTranslation is "XeT 3.0", oldSource is "TeX 3.0"
      and newSource is "XeT 3.1".

      First, we set up two tables: oldNumbers and newNumbers. In our
      example, oldNumber[0] is "3.0" and newNumber[0] is "3.1".
    */
    for ( i = 0, j = 0; i < oldSourceLen; i++, j++ ) {
	m = numberLength( oldSource + i );
	n = numberLength( newSource + j );
	if ( m > 0 ) {
	    oldNumbers.append( QCString(oldSource + i, m + 1) );
	    newNumbers.append( QCString(newSource + j, n + 1) );
	    i += m;
	    j += n;
	    met[k] = FALSE;
	    matchedYet[k] = 0;
	    k++;
	}
    }

    /*
      We now go over the old translation, "XeT 3.0", one letter at a
      time, looking for numbers found in oldNumbers. Whenever such a
      number is met, it is replaced with its newNumber equivalent. In
      our example, the "3.0" of "XeT 3.0" becomes "3.1".
    */
    for ( i = 0; i < (int) oldTranslation.length(); i++ ) {
	attempt += oldTranslation[i];
	for ( k = 0; k < p; k++ ) {
	    if ( oldTranslation[i] == oldNumbers[k][matchedYet[k]] )
		matchedYet[k]++;
	    else
		matchedYet[k] = 0;
	}

	/*
	  Let's find out if the last character ended a match. We make
	  two passes over the data. In the first pass, we try to
	  match only numbers that weren't matched yet; if that fails,
	  the second pass does the trick. This is useful in some
	  suspicious cases, flagged below.
	*/
	for ( pass = 0; pass < 2; pass++ ) {
	    best = p; // an impossible value
	    for ( k = 0; k < p; k++ ) {
		if ( (!met[k] || pass > 0) &&
		     matchedYet[k] == (int) oldNumbers[k].length() &&
		     numberLength(oldTranslation.latin1() + (i + 1) -
				  matchedYet[k]) == matchedYet[k] ) {
		    // the longer the better
		    if ( best == p || matchedYet[k] > matchedYet[best] )
			best = k;
		}
	    }
	    if ( best != p ) {
		attempt.truncate( attempt.length() - matchedYet[best] );
		attempt += newNumbers[best];
		met[best] = TRUE;
		for ( k = 0; k < p; k++ )
		    matchedYet[k] = 0;
		break;
	    }
	}
    }

    /*
      We flag two kinds of suspicious cases. They are identified as
      such with comments such as "{2000?}" at the end.

      Example of the first kind: old source text "TeX 3.0" translated
      as "XeT 2.0" is flagged "TeX 2.0 {3.0?}", no matter what the
      new text is.
    */
    for ( k = 0; k < p; k++ ) {
	if ( !met[k] )
	    attempt += QString( " {" ) + newNumbers[k] + QString( "?}" );
    }

    /*
      Example of the second kind: "1 of 1" translated as "1 af 1",
      with new source text "1 of 2", generates "1 af 2 {1 or 2?}"
      because it's not clear which of "1 af 2" and "2 af 1" is right.
    */
    for ( k = 0; k < p; k++ ) {
	for ( ell = 0; ell < p; ell++ ) {
	    if ( k != ell && oldNumbers[k] == oldNumbers[ell] &&
		    newNumbers[k] < newNumbers[ell] )
		attempt += QString( " {" ) + newNumbers[k] + QString( " or " ) +
			   newNumbers[ell] + QString( "?}" );
	}
    }
    return attempt;
}

/*
  Augments a MetaTranslator with translations easily derived from
  similar existing (probably obsolete) translations.

  For example, if "TeX 3.0" is translated as "XeT 3.0" and "TeX 3.1"
  has no translation, "XeT 3.1" is added to the translator and is
  marked Unfinished.
*/
void applyNumberHeuristic( MetaTranslator *tor, bool verbose )
{
    TMM translated, untranslated;
    TMM::Iterator t, u;
    TML all = tor->messages();
    TML::Iterator it;
    int inserted = 0;

    for ( it = all.begin(); it != all.end(); ++it ) {
	if ( (*it).type() == MetaTranslatorMessage::Unfinished ) {
	    if ( (*it).translation().isEmpty() )
		untranslated.insert( zeroKey((*it).sourceText()), *it );
	} else if ( !(*it).translation().isEmpty() ) {
	    translated.insert( zeroKey((*it).sourceText()), *it );
	}
    }

    for ( u = untranslated.begin(); u != untranslated.end(); ++u ) {
	t = translated.find( u.key() );
	if ( t != translated.end() && !t.key().isEmpty() &&
	     qstrcmp((*t).sourceText(), (*u).sourceText()) != 0 ) {
	    MetaTranslatorMessage m( *u );
	    m.setTranslation( translationAttempt((*t).translation(),
						 (*t).sourceText(),
						 (*u).sourceText()) );
	    tor->insert( m );
	    inserted++;
	}
    }
    if ( verbose && inserted != 0 )
	qWarning( " number heuristic provided %d translation%s",
		  inserted, inserted == 1 ? "" : "s" );
}
