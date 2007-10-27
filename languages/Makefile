
CATALOGS=theme-pt.po theme-fr.po theme-de.po personal-pt.po

.SUFFIXES: .po .mo

.po.mo:
	msgfmt -c -v -o $@ $<

all: ${CATALOGS:.po=.mo}

clean:
	-rm *.mo
