<?php
namespace Axon\Search\Model;

/**
 * Represents a search result for torrents
 *
 * @author Ramon Kleiss <ramonkleiss@gmail.com>
 */
class Torrent
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var integer
     */
    protected $size;

    /**
     * @var integer
     */
    protected $seeds;

    /**
     * @var integer
     */
    protected $peers;

    /**
     * @var string
     */
    protected $url;


    /**
     * @var string
     */
    protected $magnet;

    /**
     * @return string
     */
    public function getMagnet()
    {
        return $this->magnet;
    }

    /**
     * @param string $magnet
     */
    public function setMagnet($magnet)
    {
        $this->magnet = $magnet;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = trim((string) $name);
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = strtoupper((string) $hash);
    }

    /**
     * @return string|null
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param integer $size
     */
    public function setSize($size)
    {
        $this->size = (integer) $size;
    }

    /**
     * @return integer|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param integer $seeds
     */
    public function setSeeds($seeds)
    {
        $this->seeds = (integer) $seeds;
    }

    /**
     * @return integer|null
     */
    public function getSeeds()
    {
        return $this->seeds;
    }

    /**
     * @param integer $peers
     */
    public function setPeers($peers)
    {
        $this->peers = (integer) $peers;
    }

    /**
     * @return integer
     */
    public function getPeers()
    {
        return $this->peers;
    }
}
