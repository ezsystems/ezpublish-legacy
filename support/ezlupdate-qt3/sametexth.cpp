/**********************************************************************
**   Copyright (C) 2000 Trolltech AS.  All rights reserved.
**
**   sametexth.cpp
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

#include <qcstring.h>
#include <qmap.h>

#include <metatranslator.h>

typedef QMap<QCString, MetaTranslatorMessage> TMM;
typedef QValueList<MetaTranslatorMessage> TML;

/*
  Augments a MetaTranslator with trivially derived translations.

  For example, if "Enabled:" is consistendly translated as "Eingeschaltet:" no
  matter the context or the comment, "Eingeschaltet:" is added as the
  translation of any untranslated "Enabled:" text and is marked Unfinished.
*/

void applySameTextHeuristic( MetaTranslator *tor, bool verbose )
{
    TMM translated, avoid;
    TMM::Iterator t;
    TML untranslated;
    TML::Iterator u;
    TML all = tor->messages();
    TML::Iterator it;
    int inserted = 0;

    for ( it = all.begin(); it != all.end(); ++it ) {
	if ( (*it).type() == MetaTranslatorMessage::Unfinished ) {
	    if ( (*it).translation().isEmpty() )
		untranslated.append( *it );
	} else {
	    QCString key = (*it).sourceText();
	    t = translated.find( key );
	    if ( t != translated.end() ) {
		/*
		  The same source text is translated at least two
		  different ways. Do nothing then.
		*/
		if ( (*t).translation() != (*it).translation() ) {
		    translated.remove( key );
		    avoid.insert( key, *it );
		}
	    } else if ( !avoid.contains(key) ) {
		translated.insert( key, *it );
	    }
	}
    }

    for ( u = untranslated.begin(); u != untranslated.end(); ++u ) {
	QCString key = (*u).sourceText();
	t = translated.find( key );
	if ( t != translated.end() ) {
	    MetaTranslatorMessage m( *u );
	    m.setTranslation( (*t).translation() );
	    tor->insert( m );
	    inserted++;
	}
    }
    if ( verbose && inserted != 0 )
	qWarning( " same-text heuristic provided %d translation%s",
		  inserted, inserted == 1 ? "" : "s" );
}
