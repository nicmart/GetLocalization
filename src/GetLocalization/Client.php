<?php
/*
 * This file is part of GetLocalization.
 *
 * (c) 2013 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace GetLocalization;

use Guzzle\Common\Exception\RuntimeException;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Request;
use Guzzle\Common\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class Description
 *
 * @package    GetLocalization
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class Client implements ApiInterface, EventSubscriberInterface
{
    private $apiConfig = array(
        'baseUrl' => 'https://api.getlocalization.com/[project-name]/api/',
        'createMaster' => 'create-master/[file-format]/[language-tag]/',
        'updateMaster' => 'update-master/',
        'listMaster' => 'list-master/json/',
        'translationsZip' => 'translations/zip/',
        'translation' => 'translations/file/[master-file-name]/[language-tag]/',
        'translationsJson' => 'translations/list/json/',
        'translators' => 'translators/json/',
    );

    private $projectname;
    private $username;
    private $password;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @param string $projectname
     * @param string $username
     * @param string $password
     * @param ClientInterface $httpClient
     */
    public function __construct($projectname, $username, $password, ClientInterface $httpClient)
    {
        $this->projectname = $projectname;
        $this->username = $username;
        $this->password = $password;

        $this->httpClient = $httpClient;

        $this->httpClient->addSubscriber($this);
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array('client.create_request' => 'onCreateRequest');
    }

    /**
     * Inject into the request authentication options
     *
     * @param \Guzzle\Common\Event $event
     */
    public function onCreateRequest(Event $event)
    {
        $data = $event->toArray();

        /** @var $request Request */
        $request = $data['request'];
        $request->setAuth($this->username, $this->password);
    }

    /**
     * @param array $apiConfig
     * @return $this The current instance
     */
    public function setApiConfig(array $apiConfig)
    {
        $this->apiConfig = $apiConfig;

        return $this;
    }

    /**
     * @return array
     */
    public function getApiConfig()
    {
        return $this->apiConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function listMaster()
    {
        $url = $this->getApiUrl('listMaster');
        $response = $this->httpClient->get($url)->send();

        return $response->json();
    }

    /**
     * {@inheritdoc}
     */
    public function createMaster($format, $language, $body, $filename)
    {
        $url = $this->getApiUrl('createMaster', array('file-format' => $format, 'language-tag' => $language));
        $response = $this->httpClient->post($url, array('content-type' => 'multipart/form-data'), $body)->send();

        if (!$response->isSuccessful()) {
            throw new RuntimeException('Something went wrong dialing with the api server: ' . $response->getBody());
        }

        return $response->getBody(true);
    }

    /**
     * {@inheritdoc}
     */
    public function updateMaster($body, $filename)
    {
        $url = $this->getApiUrl('updateMaster');
        $response = $this->httpClient->post($url, array('content-type' => 'multipart/form-data'), $body)->send();

        if (!$response->isSuccessful()) {
            throw new RuntimeException('Something went wrong dialing with the api server: ' . $response->getBody());
        }

        return $response->getBody(true);
    }

    /**
     * {@inheritdoc}
     */
    public function getZippedTranslations()
    {
        $url = $this->getApiUrl('translationsZip');
        $response = $this->httpClient->get($url)->send();

        return $response->getBody(true);
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslation($masterfile, $lang)
    {
        // TODO: Implement getTranslation() method.
    }

    /**
     * {@inheritdoc}
     */
    public function updateTranslation($masterfile, $lang)
    {
        // TODO: Implement updateTranslation() method.
    }

    /**
     * Gives the base url of the api appended with the given arguments.
     *
     * @param string $name
     * @param array $vars
     *
     * @return string
     */
    public function getApiUrl($name, array $vars = array())
    {
        $vars['project-name'] = $this->projectname;

        $url = $this->apiConfig['baseUrl'] . $this->apiConfig[$name];

        foreach ($vars as $varname => $value) {
            $url = str_replace("[$varname]", $value, $url);
        }

        return $url;
    }
}