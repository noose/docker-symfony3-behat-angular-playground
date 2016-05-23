<?php

use AppBundle\Entity\Article;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit_Framework_Assert as Assertions;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var Response
     */
    protected $response;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(Client $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    /**
     * @BeforeScenario
     */
    public static function beforeScenerio(BeforeScenarioScope $scope)
    {
        echo shell_exec('bin/console doctrine:schema:drop --env=test --force');
        echo shell_exec('bin/console doctrine:schema:create --env=test');
    }

    /**
     * @Given when consuming the endpoint I use the :header of :value
     */
    public function whenConsumingTheEndpointIUseTheOf($header, $value)
    {
        $this->headers[$header] = $value;
    }


    /**
     * @Given there are articles with the following details:
     */
    public function thereAreArticlesWithTheFollowingDetails(TableNode $table)
    {

        foreach($table->getIterator() as $row) {
            $article = new Article();
            $article->setAuthor($row['author']);
            $article->setDateAdd(new \DateTime($row['date_add']));
            $article->setDateCreate(new \DateTime($row['date_create']));
            $article->setTitle($row['title']);
            $article->setDescription($row['description']);
            $article->setLink($row['link']);
            $article->setThumbnail($row['thumbnail'] ?: '');

            $this->entityManager->persist($article);
        }
        $this->entityManager->flush();
    }

    /**
     * @When I send a :method request to :url
     */
    public function iSendARequestTo($method, $url)
    {
        $this->response = $this->client->request($method, $url, ['headers' => $this->headers]);
    }

    /**
     * @Then the response code should :httpCode
     */
    public function theResponseCodeShould($httpCode)
    {
        Assertions::assertEquals($httpCode, $this->response->getStatusCode());
    }

    /**
     * @Then the response header :header should be equal to :value
     */
    public function theResponseHeaderShouldBeEqualTo($header, $value)
    {
        Assertions::assertEquals($value, $this->response->getHeader($header)[0]);
    }

    /**
     * @Then the response should contain json:
     */
    public function theResponseShouldContainJson(PyStringNode $string)
    {
        $body = $this->response->getBody()->getContents();
        foreach ($string->getStrings() as $expectedString) {
            Assertions::assertContains($expectedString, $body);
        }
    }

}
