#
# QMake project file for ezlupdate
#
# Gunnstein Lye <gl@ez.no>
# Created on: <9-December-2002 11:34:26 gl>
#
# Copyright (C) 1999-2002 eZ Systems.  All rights reserved.
#

PROJECT     = ezlupdate
TEMPLATE    = app
CONFIG     += qt warn_on console

unix {
    macx {
        !exists( $(QTDIR)/lib/libqt.3* ) {
            message( "Single-threaded Qt not found." )
            message( "Configuring for multi-threaded Qt..." )
            CONFIG += thread
        }
    }
    !macx {
        !exists( $(QTDIR)/lib/libqt.so* ) {
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
              proparser.h

SOURCES     = fetchtr.cpp \
              fetchtr_ezpublish.cpp \
              main.cpp \
              merge.cpp \
              numberh.cpp \
              sametexth.cpp \
              metatranslator.cpp \
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
