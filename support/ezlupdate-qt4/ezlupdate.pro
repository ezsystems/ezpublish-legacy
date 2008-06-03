#
# QMake project file for ezlupdate
#
# Gunnstein Lye <gl@ez.no>
# Created on: <9-December-2002 11:34:26 gl>
#
# Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
#

PROJECT     = ezlupdate
TEMPLATE    = app
CONFIG     += qt warn_on release console

unix {
    macx {
        !exists( $(QTDIR)/lib/libqt.3* ) {
            message( "Single-threaded Qt not found." )
            message( "Configuring for multi-threaded Qt..." )
            CONFIG += thread
        }
    }
    !macx {
        !exists( $(QTDIR)/lib/libqt.* ) {
            message( "Single-threaded Qt not found." )
            message( "Configuring for multi-threaded Qt..." )
            CONFIG += thread
        }
    }
}

OBJECTS_DIR = obj
MOC_DIR     = moc
INCLUDEPATH = .

HEADERS     = metatranslator.h \
              translator.h \
              proparser.h

SOURCES     = fetchtr_php.cpp \
              fetchtr_tpl.cpp \
              main.cpp \
              merge.cpp \
              numberh.cpp \
              sametexth.cpp \
              metatranslator.cpp \
              translator.cpp \
              proparser.cpp

unix {
    macx {
        DESTDIR = ../../bin/macosx
    }
    !macx {
        DESTDIR = ../../bin/linux
    }
}
win32:DESTDIR = ../../bin/win32

TARGET      = ezlupdate
#The following line was inserted by qt3to4
QT += xml qt3support
