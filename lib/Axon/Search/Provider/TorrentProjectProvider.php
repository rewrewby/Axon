<?php
namespace Axon\Search\Provider;

use Buzz\Browser;
use Nomnom\Nomnom;
use Symfony\Component\DomCrawler\Crawler;
use Axon\Search\Model\Torrent;
use Axon\Search\Exception\ConnectionException;
use Axon\Search\Exception\UnexpectedResponseException;

/**
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class TorrentProjectProvider extends AbstractProvider
{
    /**
     * @var string
     */
    const DEFAULT_HOST = 'torrentproject.se';

    /**
     * @var string
     */
    const DEFAULT_PATH = '';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'TorrentProject';
    }

    /**
     * {@inheritDoc}
     */
    public function getCanonicalName()
    {
        return 'torrentproject';
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
            'http://%s%s/?s=%s&orderby=seeders&num=%d&start=%d',
            self::DEFAULT_HOST,
            self::DEFAULT_PATH,
            rawurlencode($query),
            $num_by_page,
            ($page * $num_by_page) - $num_by_page
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

        return $crawler->filter('#ires .torrent')->each(function ($node) {
            $link = $node->filter('h3 a');
            $magnet = $link->attr('href');
            preg_match('/\/([0-9A-Za-z]+)\//', $magnet, $matches);
            $hash = $matches[1];

            $detDesc = $node->filter('.torrent-size')->text();

            preg_match('/([0-9\.]+)/', $detDesc, $matches);
            $size = $matches[1];

            preg_match('/([A-Za-z]+)/', $detDesc, $matches);
            $unit = strtoupper($matches[1]);
            $unit = ($unit == 'KB') ? 'kB' : $unit;

            $converter = new Nomnom($size);
            $torrent = new Torrent();
            $torrent->setName($link->text());
            $torrent->setHash($hash);
            $torrent->setSize($converter->from($unit)->to('B'));
            $torrent->setSeeds($node->filter('.seeders b')->text());
            $torrent->setPeers($node->filter('.leechers b')->text());

            return $torrent;
        });
    }
}
