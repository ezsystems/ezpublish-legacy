/**********************************************************************
**   Copyright (C) 2000 Trolltech AS.  All rights reserved.
**
**   merge.cpp
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

#include <iostream>

#include <metatranslator.h>

// defined in numberh.cpp
extern void applyNumberHeuristic( MetaTranslator *tor, bool verbose );
// defined in sametexth.cpp
extern void applySameTextHeuristic( MetaTranslator *tor, bool verbose );

typedef QValueList<MetaTranslatorMessage> TML;

static void printOut( const QString & out )
{
    std::cout << out.utf8() << std::endl;
}

/*
  Merges two MetaTranslator objects into the first one.  The first one is a set
  of source texts and translations for a previous version of the
  internationalized program; the second one is a set of fresh source text newly
  extracted from the source code, without any translation yet.
*/

void merge( MetaTranslator *tor, const MetaTranslator *virginTor, const QString &language, bool verbose )
{
    int known = 0;
    int neww = 0;
    int obsoleted = 0;
    TML all = tor->messages();
    TML::Iterator it;

    /*
      The types of all the messages from the vernacular translator are updated
      according to the virgin translator.
    */
    for ( it = all.begin(); it != all.end(); ++it ) {
	MetaTranslatorMessage::Type newType;
	MetaTranslatorMessage m = *it;

	// skip context comment
	if ( !QCString((*it).sourceText()).isEmpty() ) {
	    if ( !virginTor->contains((*it).context(), (*it).sourceText(),
				      (*it).comment()) ) {
		newType = MetaTranslatorMessage::Obsolete;
		if ( m.type() != MetaTranslatorMessage::Obsolete )
		    obsoleted++;
	    } else {
		switch ( m.type() ) {
		case MetaTranslatorMessage::Finished:
		    newType = MetaTranslatorMessage::Finished;
		    known++;
		    break;
		case MetaTranslatorMessage::Unfinished:
		    newType = MetaTranslatorMessage::Unfinished;
		    known++;
		    break;
		case MetaTranslatorMessage::Obsolete:
		    newType = MetaTranslatorMessage::Unfinished;
		    neww++;
		}
	    }

	    if ( newType != m.type() ) {
		m.setType( newType );
		tor->insert( m );
	    }
	}
    }

    /*
      Messages found only in the virgin translator are added to the
      vernacular translator. Among these are all the context comments.
    */
    all = virginTor->messages();

    for ( it = all.begin(); it != all.end(); ++it ) {
	if ( !tor->contains((*it).context(), (*it).sourceText(),
			    (*it).comment()) ) {
	    tor->insert( *it );
	    if ( !QCString((*it).sourceText()).isEmpty() )
		neww++;
	}
    }

    /*
      The same-text heuristic handles cases where a message has an
      obsolete counterpart with a different context or comment.
    */
    applySameTextHeuristic( tor, verbose );

    /*
      The number heuristic handles cases where a message has an
      obsolete counterpart with mostly numbers differing in the
      source text.
    */
    applyNumberHeuristic( tor, verbose );

    printOut( QString( " %1: %2 known, %3 new and %4 obsoleted messages" ).arg( language.latin1() ).arg( known ).arg( neww ).arg( obsoleted ) );
}
