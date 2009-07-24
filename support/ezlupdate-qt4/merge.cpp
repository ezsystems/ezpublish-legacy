/****************************************************************************
**
** Copyright (C) 1992-2005 Trolltech AS. All rights reserved.
**
** This file is part of the Qt Linguist of the Qt Toolkit.
**
** This file may be used under the terms of the GNU General Public
** License version 2.0 as published by the Free Software Foundation
** and appearing in the file LICENSE.GPL included in the packaging of
** this file.  Please review the following information to ensure GNU
** General Public Licensing requirements will be met:
** http://www.trolltech.com/products/qt/opensource.html
**
** If you are unsure which license is appropriate for your use, please
** review the following information:
** http://www.trolltech.com/products/qt/licensing.html or contact the
** sales department at sales@trolltech.com.
**
** This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING THE
** WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.
**
****************************************************************************/

#include <iostream>

#include <metatranslator.h>
#include <stdio.h>

// defined in numberh.cpp
extern void applyNumberHeuristic( MetaTranslator *tor, bool verbose );
// defined in sametexth.cpp
extern void applySameTextHeuristic( MetaTranslator *tor, bool verbose );

typedef QList<MetaTranslatorMessage> TML;

static void printOut( const QString & out )
{
    std::cout << out.toUtf8().data() << std::endl;
}

/*
  Merges two MetaTranslator objects into the first one. The first one
  is a set of source texts and translations for a previous version of
  the internationalized program; the second one is a set of fresh
  source texts newly extracted from the source code, without any
  translation yet.
*/

void merge( MetaTranslator *tor, const MetaTranslator *virginTor, bool verbose )
{
    int known = 0;
    int neww = 0;
    int obsoleted = 0;
    TML all = tor->messages();
    TML::Iterator it;

    /*
      The types of all the messages from the vernacular translator
      are updated according to the virgin translator.
    */
    for ( it = all.begin(); it != all.end(); ++it ) {
        MetaTranslatorMessage::Type newType;
        MetaTranslatorMessage m = *it;

        // skip context comment
        if ( !QByteArray((*it).sourceText()).isEmpty() ) {
            if ( !virginTor->contains((*it).context(), (*it).sourceText(),
                                      (*it).comment()) ) {
                newType = MetaTranslatorMessage::Obsolete;
                if ( m.type() != MetaTranslatorMessage::Obsolete )
                    obsoleted++;
            } else {
                switch ( m.type() ) {
                case MetaTranslatorMessage::Finished:
                default:
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
            if ( !QByteArray((*it).sourceText()).isEmpty() )
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

    if ( verbose )
        printOut( QString( " %1 known, %2 new, and %3 obsoleted messages" ).arg( known ).arg( neww ).arg( obsoleted ) );
}
