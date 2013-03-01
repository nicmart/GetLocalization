<?php
/*
 * This file is part of GetLocalization.
 *
 * (c) 2013 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GetLocalization\Test;

use GetLocalization\Test;
use GetLocalization\Client;
use Guzzle\Http\Client as GuzzleClient;

/**
 * Unit tests for class Client
 *
 * @package    GetLocalization
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    protected $apiConfig = array(
        'baseUrl' => 'https://api.getlocalization.com/[project-name]/api/',
        'createMaster' => 'create-master/[file-format]/[language-tag]/',
        'updateMaster' => 'update-master/',
        'listMaster' => 'list-master/json/',
        'translationsZip' => 'translations/zip/',
        'translation' => 'translations/file/[master-file-name]/[language-tag]/',
        'translationsJson' => 'translations/list/json/',
        'translators' => 'translators/json/',
    );

    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = new Client('test', 'user', 'pswd', new GuzzleClient);
        $this->client->setApiConfig($this->apiConfig);
    }

    public function apiArgsProvider()
    {
        return array(
            array(
                'createMaster',
                array('file-format' => 'txt', 'language-tag' => 'en'),
                'https://api.getlocalization.com/test/api/create-master/txt/en/'
            ),
            array(
                'createMaster',
                array('file-format' => 'yml', 'language-tag' => 'en'),
                'https://api.getlocalization.com/test/api/create-master/yml/en/'
            ),
            array(
                'updateMaster',
                array(),
                'https://api.getlocalization.com/test/api/update-master/'
            ),
            array(
                'translation',
                array('master-file-name' => 'yml', 'language-tag' => 'it'),
                'https://api.getlocalization.com/test/api/translations/file/yml/it/'
            ),
        );
    }

    /**
     * @dataProvider apiArgsProvider
     */
    public function testGetApiUrl($name, $options, $expected)
    {
        $this->assertEquals($expected, $this->client->getApiUrl($name, $options));
    }

    public function testSetApiConfig()
    {
        $config = array('test');

        $this->assertEquals($config, $this->client->setApiConfig($config)->getApiConfig());
    }
}