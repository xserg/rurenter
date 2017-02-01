<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom"
      xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#"
      xmlns:georss="http://www.georss.org/georss">
  <title>{PAGE_TITLE}</title>
 <!-- BEGIN VILLA -->
  <entry>
    <title>{TITLE}</title>
    <link rel="alternate" type="text/html" href="{VILLA_LINK}"/>
	<content type="html">{SUMMARY}</content>
    <georss:point>{LAT} {LON}</georss:point>
    <geo:lat>{LAT}</geo:lat>
    <geo:long>{LON}</geo:long>
  </entry>
 <!-- END VILLA -->
</feed>