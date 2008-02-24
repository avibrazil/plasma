
CATALOGS=theme-pt.po personal-pt.po

.SUFFIXES: .po .mo

.po.mo:
	msgfmt -c -v -o $@ $<

all: ${CATALOGS:.po=.mo}

po:
	xgettext -j --keyword=__ --keyword=_e -L PHP --output=theme-pt.po ../*php

clean:
	-rm *.mo
