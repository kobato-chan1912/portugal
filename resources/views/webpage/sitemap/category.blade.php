<?xml version="1.0" encoding="utf-8"?>
<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
                                                http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd
                                                http://www.google.com/schemas/sitemap-image/1.1
                                                http://www.google.com/schemas/sitemap-image/1.1/sitemap-image.xsd
                                                http://www.w3.org/1999/xhtml
                                                http://www.w3.org/2002/08/xhtml/xhtml1-strict.xsd">
    @foreach($categories as $url)
        @php
            $lastModTime = \App\Models\Song::where("category_id", $url->id)->latest();
            if ($lastModTime->exists()){
                $lastModTime = $lastModTime->first()->created_at;
            } else {
                $lastModTime = $url->created_at;
            }
            @endphp
        <url>
            <loc>{{env("WEBPAGE_URL")}}/{{$url->category_slug}}</loc>
            <lastmod>{{date('c', strtotime($lastModTime))}}</lastmod>
            <xhtml:link rel="alternate" href="{{env("WEBPAGE_URL")}}/{{$url->category_slug}}"/>
        </url>
    @endforeach
</urlset>
