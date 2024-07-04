<?php
    header("Content-type: text/xml");

    echo "<?xml version='1.0' encoding='UTF-8'?>";
        echo "<urlset xmlns='https://www.sitemaps.org/schemas/sitemap/0.9'>";

        echo "<url>";
            echo "<loc>https://xn--smartrcup-g4a.fr/index.php</loc>";
            echo "<lastmod>" . date("Y-m-d") . "</lastmod>";
            echo "<changefreq>daily</changefreq>";
            echo "<priority>1</priority>";
        echo "</url>";

        echo "<url>";
            echo "<loc>https://xn--smartrcup-g4a.fr/vue/tarification.php</loc>";
            echo "<lastmod>" . date("Y-m-d") . "</lastmod>";
            echo "<changefreq>daily</changefreq>";
            echo "<priority>1</priority>";
        echo "</url>";

        echo "<url>";
            echo "<loc>https://xn--smartrcup-g4a.fr/menu.php</loc>";
            echo "<lastmod>" . date("Y-m-d") . "</lastmod>";
            echo "<changefreq>daily</changefreq>";
            echo "<priority>1</priority>";
        echo "</url>";

        echo "<url>";
            echo "<loc>https://xn--smartrcup-g4a.fr/vue/about.php</loc>";
            echo "<lastmod>" . date("Y-m-d") . "</lastmod>";
            echo "<changefreq>daily</changefreq>";
            echo "<priority>1</priority>";
        echo "</url>";

    echo "</urlset>";
?>