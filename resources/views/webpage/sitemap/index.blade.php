<?xml version="1.0" encoding="utf-8"?>
<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"><sitemap>
        <loc>{{env("WEBPAGE_URL")}}/post-sitemap.xml</loc>
        <lastmod>{{date('c', strtotime($lastTimeCategory))}}</lastmod>
    </sitemap><sitemap>
        <loc>{{env("WEBPAGE_URL")}}/category-sitemap.xml</loc>
        <lastmod>{{date('c', strtotime($lastTimeSong))}}</lastmod>
    </sitemap></sitemapindex>
