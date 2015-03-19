{{ '<' }}?xml version="1.0" encoding="UTF-8"?{{ '>' }}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ URL::route('mainpage') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    @foreach ($data['pages'] as $page)
        <url>
            <loc>{{ URL::route('page', $page['slug']) }}</loc>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>