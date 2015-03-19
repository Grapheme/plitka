{{ '<' }}?xml version="1.0" encoding="UTF-8"?{{ '>' }}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ URL::route('mainpage') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    @if (isset($data['pages']) && is_array($data['pages']) && count($data['pages']))
        @foreach ($data['pages'] as $page)
            <url>
                <loc>{{ URL::route('mainpage') }}#!/{{ $page['slug'] }}</loc>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>
        @endforeach
    @endif
</urlset>