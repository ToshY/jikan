<?php /** @noinspection ALL */

/** @noinspection PhpCSValidationInspection */

namespace JikanTest\Parser\Forum;

use Jikan\Http\HttpClientWrapper;
use Jikan\Model\Forum\ForumPost;
use Jikan\Parser\Forum\ForumTopicParser;
use JikanTest\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ForumTopicParserTest
 */
class ForumTopicParserTest extends TestCase
{
    /**
     * @var ForumTopicParser
     */
    private $parser;

    public function setUp(): void
    {
        parent::setUp();

        $client = new HttpClientWrapper($this->httpClient);
        $crawler = $client->request('GET', 'https://myanimelist.net/anime/1/_/forum');
        $this->parser = new ForumTopicParser($crawler->filterXPath('//tr[contains(@id, "topicRow")]')->eq(2));
    }

    #[Test]
    public function it_gets_the_post_id(): void
    {
        self::assertEquals(40846, $this->parser->getTopicId());
    }

    #[Test]
    public function it_gets_the_post_url(): void
    {
        $url = $this->parser->getUrl();
        $this->assertIsString($url);
        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/forum/', parse_url($url)['path']);
    }

    #[Test]
    public function it_gets_the_post_title(): void
    {
        self::assertEquals('Cowboy Bebop Episode 20 Discussion', $this->parser->getTitle());
    }

    #[Test]
    public function it_gets_the_post_date(): void
    {
        self::assertEquals('2008-08-29', $this->parser->getPostDate()->format('Y-m-d'));
    }

    #[Test]
    public function it_gets_the_author_name(): void
    {
        self::assertEquals('Issun', $this->parser->getAuthorName());
    }

    #[Test]
    public function it_gets_the_author_url(): void
    {
        $url = $this->parser->getAuthorUrl();
        $this->assertIsString($url);
        $this->assertNotFalse(filter_var($url, FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/profile/', parse_url($url)['path']);
    }

    #[Test]
    public function it_gets_the_replies(): void
    {
        self::assertEquals(256, $this->parser->getReplies());
    }

    #[Test]
    public function it_gets_the_last_post(): void
    {
        $lastPost = $this->parser->getLastPost();
        self::assertInstanceOf(ForumPost::class, $lastPost);
        self::assertEquals('keyboardeater', $lastPost->getAuthorUsername());

        $this->assertIsString($lastPost->getAuthorUrl());
        $this->assertNotFalse(filter_var($lastPost->getAuthorUrl(), FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/profile/', parse_url($lastPost->getAuthorUrl())['path']);

        $this->assertIsString($lastPost->getUrl());
        $this->assertNotFalse(filter_var($lastPost->getUrl(), FILTER_VALIDATE_URL));
        $this->assertStringStartsWith('/forum/', parse_url($lastPost->getUrl())['path']);

        // Last post is 'by  Today, 6:29 AM, so just check hour, not day
        self::assertEquals('08:49', $lastPost->getDate()->format('H:i'));
    }
}
