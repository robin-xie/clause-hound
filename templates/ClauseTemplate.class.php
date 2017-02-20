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

    private $docNumbers = array();

    private $clauses = array();


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


    public function getDocNumber(): array
    {
        return $this->docNumbers;
    }


    public function fetch($docNumbers)
    {

        $this->clauses = array();
        if(!$docNumbers) {
            return $this;
        }

        $promises = array();
        $client = new Client(['base_uri' => $this->baseUri, 'timeout'  => 2.0]);

        $this->docNumbers = is_array($docNumbers) ? $docNumbers : (array)$docNumbers;
        foreach($this->docNumbers as $docNumber) {

            $promises["doc_{$docNumber}"] = $client->postAsync($this->baseUri, ['form_params' => ['docnum' => $docNumber]]);
        }

        $results = Promise\unwrap($promises);
        foreach($results as $result) {

            $parsed = self::parseDomByClassname($result->getBody(), $this->clauseclassName);;
            if($parsed) {
                $this->clauses[] = $parsed;
            }

        }
        return $this->jsonSerialize();
    }


    public static function parseDomByClassname(string $html = '', string $className) : array
    {
        $data = [];

        if(!$html) {
            return $data;
        }

        $dom = new DomDocument();
        $dom->loadHTML($html);

        $finder = new DomXPath($dom);
        $entries = $finder->query("//*[contains(@class, '{$className}')]");

        foreach ($entries as $entry) {
            $data[] = $entry->nodeValue;
        }

        return $data;
    }


    public function jsonSerialize() : ImmArray
    {
        return ImmArray::fromArray($this->clauses);
    }


}