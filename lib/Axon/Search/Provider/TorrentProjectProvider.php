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
        $url = sprintf(
            'http://%s%s/?s=%s&orderby=seeders&out=json',
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
    protected function transformResponse($rawResponse)
    {
        if (!($stdClass = json_decode($rawResponse))) {
            throw new UnexpectedResponseException(
                'Could not parse response'
            );
        }
        if (isset($stdClass->error)) {
            throw new UnexpectedResponseException(
                sprintf(
                    '%s : %s %s',
                    $this->getName(),
                    $stdClass->error,
                    $stdClass->reason
                )
            );
        }

        return array_map(function ($result) {
            $torrent = new Torrent();
            $torrent->setName($result->name);
            $torrent->setHash($result->hash);
            $torrent->setSize($result->size);
            $torrent->setSeeds($result->seeds);
            $torrent->setPeers($result->peers);

            return $torrent;
        }, $stdClass);
    }
}
