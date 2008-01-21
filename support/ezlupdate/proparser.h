/**********************************************************************
**   Copyright (C) 2000 Trolltech AS.  All rights reserved.
**
**   proparser.h
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

#ifndef PROPARSER_H
#define PROPARSER_H

#include <qmap.h>
#include <qstring.h>

QMap<QString, QString> proFileTagMap( const QString& text );

#endif
