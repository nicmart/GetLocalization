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
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Guzzle\Tests\GuzzleTestCase;

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

    /**
     * @var Response
     */
    protected $mockedResponse;

    /**
     * @var callable
     */
    protected $responseStrategy;

    public function setUp()
    {
        $httpClient = $this->getMockBuilder('Guzzle\Http\Client')
            ->setMethods(array('send'))
            ->getMock()
        ;

        // This client mock returns always a response object which body is a json-encoded array
        // containing the url and the method of the request.
        $httpClient->expects($this->any())->method('send')->will($this->returnCallback(function($request)
        {
            $response = new Response(200);
            $response->setBody(json_encode(array(
                'url' => $request->getUrl(),
                'method' => $request->getMethod(),
                'mime-type' => $request->getHeader('Content-Type', true)
            )));
            return $response;
        }));

        $this->client = new Client('test', 'user', 'pswd', $httpClient);
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

    public function testListMaster()
    {
        $url = $this->client->getApiUrl('listMaster');

        $response = $this->client->listMaster();

        $this->assertEquals($url, $response['url']);
        $this->assertEquals('GET', $response['method']);
    }

    public function testCreateMaster()
    {
        $url = $this->client->getApiUrl('createMaster', array('file-format' => 'txt', 'language-tag' => 'en'));

        $response = json_decode($this->client->createMaster('txt', 'en', 'zazaza', 'filename'), true);

        $this->assertEquals($url, $response['url']);
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('multipart/form-data', $response['mime-type']);
    }

    public function testUpdateMaster()
    {
        $url = $this->client->getApiUrl('updateMaster');

        $response = json_decode($this->client->updateMaster('body', 'filename'), true);

        $this->assertEquals($url, $response['url']);
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('multipart/form-data', $response['mime-type']);
    }

    public function testGetZippedTranslations()
    {
        $url = $this->client->getApiUrl('translationsZip');

        $response = json_decode($this->client->getZippedTranslations(), true);

        $this->assertEquals($url, $response['url']);
        $this->assertEquals('GET', $response['method']);
    }

    public function testGetTranslation()
    {
        $url = $this->getApiUrl('translation', array('master-file-name' => 'filename', 'language-tag' => 'it'));

        $response = json_decode($this->client->getTranslation('filename', 'it'), true);

        $this->assertEquals($url, $response['url']);
        $this->assertEquals('GET', $response['method']);
    }

    public function testUpdateTranslation()
    {
        $url = $this->getApiUrl('translation', array('master-file-name' => 'filename', 'language-tag' => 'it'));

        $response = json_decode($this->client->updateTranslation('filename', 'it', 'filecontent'), true);

        $this->assertEquals($url, $response['url']);
        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('multipart/form-data', $response['mime-type']);
    }
}