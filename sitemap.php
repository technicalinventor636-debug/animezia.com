<?php
header('Content-Type: application/xml; charset=UTF-8');

// Get and sanitize domain
$domain = htmlspecialchars($_SERVER['SERVER_NAME'], ENT_QUOTES, 'UTF-8');

// Helper function to get last modification date of a file in ISO 8601 format
function get_lastmod($filepath) {
    if (file_exists($filepath)) {
        return gmdate('Y-m-d\TH:i:s+00:00', filemtime($filepath));
    }
    return null;
}

// List of local sitemaps (PHP files) and their XML equivalents
$local_sitemaps = [
    '/sitemaps/sitemap.php' => '/sitemaps/sitemap.xml',
    '/sitemaps/ongoing-sitemap.php' => '/sitemaps/ongoing-sitemap.xml',
    '/sitemaps/recentCN-sitemap.php' => '/sitemaps/recentCN-sitemap.xml',
    '/sitemaps/recentDUB-sitemap.php' => '/sitemaps/recentDUB-sitemap.xml',
    '/sitemaps/recentSUB-sitemap.php' => '/sitemaps/recentSUB-sitemap.xml'
];

// External sitemaps (no lastmod available)
$external_sitemaps = [
    'https://tech.animezia.com/post-sitemap.xml',
    'https://animezia.com/sitemap.xml',
    'https://snaply.in/sitemap.xml'
];

// Allanime paginated sitemaps
$allanime_file = __DIR__ . '/sitemaps/allanime-sitemap.php';
$allanime_lastmod = get_lastmod($allanime_file);
$allanime_pages = 85;

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Output local sitemaps with lastmod
foreach ($local_sitemaps as $php_path => $xml_path) {
    $lastmod = get_lastmod(__DIR__ . $php_path);
    echo "  <sitemap>\n";
    echo "    <loc>https://{$domain}{$xml_path}</loc>\n";
    if ($lastmod) {
        echo "    <lastmod>{$lastmod}</lastmod>\n";
    }
    echo "  </sitemap>\n";
}

// Output external sitemaps
foreach ($external_sitemaps as $url) {
    echo "  <sitemap>\n";
    echo "    <loc>{$url}</loc>\n";
    echo "  </sitemap>\n";
}

// Output allanime paginated sitemaps
for ($i = 1; $i <= $allanime_pages; $i++) {
    echo "  <sitemap>\n";
    echo "    <loc>https://{$domain}/sitemaps/allanime-sitemap.xml?page={$i}</loc>\n";
    if ($allanime_lastmod) {
        echo "    <lastmod>{$allanime_lastmod}</lastmod>\n";
    }
    echo "  </sitemap>\n";
}

echo '</sitemapindex>';
?>
