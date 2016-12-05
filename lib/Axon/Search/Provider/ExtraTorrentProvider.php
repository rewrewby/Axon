<?php
namespace Axon\Search\Provider;

use Buzz\Browser;
use Nomnom\Nomnom;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DomCrawler\Crawler;
use Axon\Search\Model\Torrent;
use Axon\Search\Exception\ConnectionException;
use Axon\Search\Exception\UnexpectedResponseException;

/**
 * @author Yoann
 */
class ExtraTorrentProvider extends AbstractProvider
{
    /**
     * @var string
     */
    const DEFAULT_HOST = 'extratorrent.cc';

    /**
     * @var string
     */
    const DEFAULT_PATH = '';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'ExtraTorrent';
    }

    /**
     * {@inheritDoc}
     */
    public function getCanonicalName()
    {
        return 'extratorrent';
    }

    /**
     * Generate the url for a search query
     *
     * @param string       $query
     * @param integer|null $page
     */
    public function getUrl($query, $page = null)
    {
        if (!is_integer($page)) {
            $page = 1;
        }
        $num_by_page = 20;

        $url = sprintf(
            'https://%s%s/search/?search=%s&s_cat=0&srt=seeds&order=desc&pp=%d&page=%d',
            self::DEFAULT_HOST,
            self::DEFAULT_PATH,
            rawurlencode($query),
            $num_by_page,
            $page
        );

        return $url;
    }

    /**
     * @param string $html
     *
     * @return Torrent[]
     */
    protected function transformResponse($rawResponse)
    {
        $crawler = new Crawler($rawResponse);

        return $crawler->filter('.tl .tlr, .tl .tlz')->each(function ($node) {
            $link = $node->filter('a[title="Magnet link"]');
            $magnet = $link->attr('href');
            preg_match('/btih\:([0-9A-Za-z]+)\&/', $magnet, $matches);
            $hash = $matches[1];

            $torrentSize = $node->filter('td:nth-child(5)')->text();

            preg_match('/([0-9\.]+)/', $torrentSize, $matches);
            $size = $matches[1];

            preg_match('/([A-Za-z]+)/', $torrentSize, $matches);
            $unit = strtoupper($matches[1]);
            $unit = ($unit == 'KB') ? 'kB' : $unit;

            $converter = new Nomnom($size);

            $torrent = new Torrent();
            $torrent->setName($node->filter('.tli > a')->text());
            $torrent->setHash($hash);
            $torrent->setSize($converter->from($unit)->to('B'));
            $torrent->setSeeds($node->filter('.sy, .sn')->text());
            $torrent->setPeers($node->filter('.ly, .ln')->text());

            return $torrent;
        });
    }
}
