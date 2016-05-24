<?php
namespace AppBundle\Event;

use AppBundle\Entity\Article;
use Symfony\Component\EventDispatcher\Event;

class ArticleEvent extends Event
{
    /**
     * @var Article
     */
    private $article;

    const ADD = 'article.create';
    const REMOVE = 'article.remove';
    const UPDATE = 'article.update';

    /**
     * ArticleEvent constructor.
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }
}