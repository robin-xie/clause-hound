<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 19/02/17
 * Time: 8:04 PM
 */

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Qaribou\Collection\ImmArray;

class ClauseTemplate implements JsonSerializable
{

    private $baseUri = 'https://clausehound.com/wp-content/themes/knowhow/actions/load-template.php';

    private $clauseclassName = 'dat_clause_language';

    private $docNumber;

    private $clauses;


    public function __construct($baseUri = null, $clauseclassName = null)
    {
        $this->setBaseUri($baseUri);
        $this->setClauseclassName($clauseclassName);
    }

    public function setBaseUri($baseUri)
    {
        if(!empty($baseUri)) {
            $this->baseUri = $baseUri;
        }
    }

    public function getBaseUri() : string
    {
        return $this->baseUri;
    }

    public function setClauseclassName($clauseclassName)
    {
        if(!empty($clauseclassName)) {
            $this->clauseclassName = $clauseclassName;
        }
    }

    public function getClauseclassName() : string
    {
        return $this->clauseclassName;
    }


    public function setDocNumber(string $docNumber)
    {
        $this->docNumber = $docNumber;
    }

    public function getDocNumber(): string
    {
        return $this->docNumber;
    }


    public function fetch(string $docNumber)
    {

        $this->setDocNumber($docNumber);

        $client = new Client(['base_uri' => $this->baseUri, 'timeout'  => 2.0]);
        $promises = ['response' => $client->postAsync($this->baseUri, ['form_params' => ['docnum' => $this->docNumber]])];
        $results = Promise\unwrap($promises);

        $html = $results['response']->getBody();
        $this->clauses = self::parseDomByClassname($html, $this->clauseclassName);

    }


    public static function parseDomByClassname(string $html = '', string $className) : ImmArray
    {
        $dom = new DomDocument();
        $dom->loadHTML($html);

        $finder = new DomXPath($dom);
        $entries = $finder->query("//*[contains(@class, '{$className}')]");
        $data = array();
        foreach ($entries as $entry) {
            $data[] = $entry->nodeValue;
        }
        return ImmArray::fromArray($data);
    }


    public function jsonSerialize() : ImmArray
    {
        return $this->clauses;
    }


}