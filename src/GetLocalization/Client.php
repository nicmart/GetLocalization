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

use Guzzle\Http\ClientInterface;

/**
 * Class Description
 *
 * @package    GetLocalization
 * @author     Nicolò Martini <nicmartnic@gmail.com>
 */
class Client implements ApiInterface
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

        $this->httpClient = $httpClient;
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

    public function listMaster()
    {
        // TODO: Implement listMaster() method.
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