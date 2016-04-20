<?php
/**
 * Manages the communication with the Intraface system. This class automatically
 * caches the stuff retrieved from Intraface.
 *
 * PHP version 5
 *
 * @category  IntrafacePublic
 * @package   IntrafacePublic_Shop_XMLRPC
 * @author    Lars Olesen <lars@legestue.net>
 * @author    Sune Jensen <sj@sunet.dk>
 * @copyright 2007 The Authors
 * @license   http://creativecommons.org/licenses/by-sa/2.5/legalcode Creative Commons / Share A Like license
 * @version   @package-version@
 * @link      http://public.intraface.dk/index.php?package=IntrafacePublic_Shop_XMLRPC
 */

/**
 * Manages the communication with the Intraface system. This class automatically
 * caches the stuff retrieved from Intraface.
 *
 * @category  IntrafacePublic
 * @package   IntrafacePublic_Shop_XMLRPC
 * @author    Lars Olesen <lars@legestue.net>
 * @author    Sune Jensen <sj@sunet.dk>
 * @copyright 2007 The Authors
 * @license   http://creativecommons.org/licenses/by-sa/2.5/legalcode Creative Commons / Share A Like license
 * @version   @package-version@
 * @link      http://public.intraface.dk/index.php?package=IntrafacePublic_Shop_XMLRPC
 */
class IntrafacePublic_CMS
{
    private $client;
    private $cache;

    /**
     * Constructor
     *
     * @param struct  $client The client to use with the server
     * @param boolean $cache  The cache to use
     *
     * @return void
     */
    public function __construct($client, $cache)
    {
        $this->client = $client;
        $this->cache  = $cache;
        $this->cache_group = 'IntrafacePublic_CMS-'.$this->client->getSiteId();
    }

    /**
     * Gets a page
     *
     * @param string $identifier Identifier for the page
     *
     * @return array with all info about the page
     */
    public function getPage($identifier = '')
    {
        $cache_id = 'get_page-'.$identifier;
        if ($data = $this->cache->get($cache_id, $this->cache_group)) {
            return unserialize($data);
        }

        try {
            $page = $this->client->getPage($identifier);
            $this->cache->save(serialize($page));
        } catch (Exception $e) {
            throw $e;
        }

        return $page;
    }

    /**
     * Clears a page page
     *
     * @param string $identifier Identifier for the page
     *
     * @return boolean true
     */
    public function clearPageCache($identifier = '')
    {
        $cache_id = 'get_page-'.$identifier;
        $this->cache->remove($cache_id, $this->cache_group);

        return true;
    }

    /**
     * Gets a page list
     *
     * @param array $search A search array
     *
     * @return array with pages
     */
    public function getPageList($search = array())
    {
        $cache_id = 'get_page_list-'.serialize($search);
        if ($data = $this->cache->get($cache_id, $this->cache_group)) {
            return unserialize($data);
        }
        try {
            $list = $this->client->getPageList($search);
            $this->cache->save(serialize($list));
            return $list;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Gets page tree
     *
     * @return array with pages
     */
    /*
    public function getPageList()
    {
        $cache_id = 'get_page_tree-'.serialize($search);
        if ($data = $this->cache->get($cache_id, $this->cache_group)) {
            return unserialize($data);
        }
        try {
            $list = $this->client->getPageTree();
            $this->cache->save(serialize($list));
            return $list;
        } catch (Exception $e) {
            throw $e;
        }
    }
    */

    /**
     * Clears page list cache
     *
     * @param array $search A search array
     *
     * @return boolean true
     */
    public function clearPageListCache($search = array())
    {
        $cache_id = 'get_page_list-'.serialize($search);
        $this->cache->remove($cache_id, $this->cache_group);
        return true;
    }
}