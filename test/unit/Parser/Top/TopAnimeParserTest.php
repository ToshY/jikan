<?php

namespace JikanTest\Parser\Top;

use Jikan\Http\HttpClientWrapper;
use Jikan\Parser\Top\TopListItemParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class TopAnimeParserTest
 */
class TopAnimeParserTest extends TestCase
{
    /**
     * @var \Jikan\Parser\Top\TopListItemParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/topanime.php');

        $this->parser = new TopListItemParser(
            $crawler->filterXPath('//tr[@class="ranking-list"]')->eq(10)
        );
    }

    #[Test]
    public function it_gets_the_mal_url()
    {
        $url = $this->parser->getMalUrl();
        self::assertEquals('Gintama\'', $url->getTitle());

        $url = $url->getUrl();
        $this->assertIsString($url);
        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/anime/', parse_url($url)['path']);
    }

    #[Test]
    public function it_gets_the_rank()
    {
        self::assertEquals(11, $this->parser->getRank());
    }

    #[Test]
    public function it_gets_the_image()
    {
        self::assertMatchesRegularExpression(
            '~https://cdn\.myanimelist\.net/.*~',
            $this->parser->getImage()
        );
    }

    #[Test]
    public function it_gets_the_anime_score()
    {
        self::assertEquals(9.02, $this->parser->getScore());
    }

    #[Test]
    public function it_gets_the_anime_type()
    {
        self::assertEquals('TV', $this->parser->getType());
    }

    #[Test]
    public function it_gets_the_anime_episodes()
    {
        self::assertEquals(51, $this->parser->getEpisodes());
    }

    #[Test]
    public function it_gets_the_anime_members()
    {
        self::assertEquals(604995, $this->parser->getMembers());
    }

    #[Test]
    public function it_gets_the_anime_start_date()
    {
        self::assertEquals('Apr 2011', $this->parser->getStartDate());
    }

    #[Test]
    public function it_gets_the_anime_end_date()
    {
        self::assertEquals('Mar 2012', $this->parser->getEndDate());
    }
}
