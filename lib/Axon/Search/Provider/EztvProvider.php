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
class EztvProvider extends AbstractProvider
{
    /**
     * @var string
     */
    const DEFAULT_HOST = 'eztv.it';

    /**
     * @var string
     */
    const DEFAULT_PATH = '/search';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'EZTV';
    }

    /**
     * {@inheritDoc}
     */
    public function getCanonicalName()
    {
        return 'eztv';
    }

    /**
     * Generate the url for a search query
     *
     * @param string       $query
     * @param integer|null $page
     */
    public function getUrl($query, $page = null)
    {
        $url = sprintf(
            'http://%s%s/%s',
            self::DEFAULT_HOST,
            self::DEFAULT_PATH,
            rawurlencode($query)
        );

        return $url;
    }

    /**
     * @param string $html
     *
     * @return Torrent[]
     */
    protected function transformResponse($html)
    {
        $crawler = new Crawler($html);

        return $crawler->filter('tr.forum_header_border')->each(function ($node) {
            $magnet = $node->filter('a.magnet')->first()->attr('href');
            preg_match('/btih:([0-9A-Za-z]+)&/', $magnet, $matches);
            $hash = $matches[1];

            $infoTitle = $node->filter('a.epinfo')->attr('title');

            preg_match('/\(([0-9\.]+) ([A-Za-z]+)\)/', $infoTitle, $matches);
            $size = 0; $unit = 'KB';
            if (isset($matches[1]) && isset($matches[2])) {
                $size = $matches[1];
                $unit = $matches[2];
            }

            $converter = new Nomnom($size);

            $torrent = new Torrent();
            $torrent->setName($node->filter('td.forum_thread_post')->eq(1)->text());
            $torrent->setHash($hash);
            $torrent->setSize($converter->from($unit)->to('B'));

            return $torrent;
        });
    }
}
