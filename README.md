# osm-on-ol
WIWOSM maps frames.

Base URL:
https://wiwosm.toolforge.org/osm-on-ol/

See some more information below (note that parts of it might be outdated):
https://wiki.openstreetmap.org/wiki/WIWOSM

## Contributors

WIWOSM project was developed by:

* @aiomaster aka Retsam (Christoph Wagner)
* @kolossos
* @simon04 (Simon Legner)
* @harry-wood (Harry Wood)

For language contributors see file headers in `Lang` directory.

For a copy of license of [Extension:CLDR](https://www.mediawiki.org/wiki/Extension:CLDR) see `cldr/LICENSE`.

The project is of course using [OpenStreetMap](https://wiki.openstreetmap.org/wiki/) and is using [OpenLayers library](https://openlayers.org/).

## Updates

### Main pull
1. Review changes (do "Testing" if not sure).
2. Update on the Toolforge:
```
become wiwosm
cd ./public_html/osm-on-ol
git pull
```

### Testing
Before any significant changes:
```
become wiwosm
cd ./public_html/
git clone https://github.com/wikiosm/osm-on-ol.git test-osm-on-ol
```
Check changes as `test-osm-on-ol` and then pull to main.

Remove `test-osm-on-ol` when done.
