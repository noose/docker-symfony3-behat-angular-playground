<?php

namespace AppBundle\Command;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FillDatabaseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:fill-database')
            ->addOption('limit', 'l', InputOption::VALUE_OPTIONAL, 'limit (unique) articles for import', 300)
            ->setDescription('Fill database with articles from news portals')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = (int) $input->getOption('limit');
        $output->writeln('<info>Command will import <options=bold>'.$limit.'</> unique articles.</info>');
        $start = microtime(true);
        /**
         * @var $feed \SimplePie
         */
        $feed = $this->getContainer()->get('fkr_simple_pie.rss');

        $feed->set_feed_url([
            'http://feeds.bbci.co.uk/news/rss.xml',
            'http://feeds.bbci.co.uk/news/world/rss.xml',
            'http://feeds.bbci.co.uk/news/uk/rss.xml',
            'http://feeds.bbci.co.uk/news/business/rss.xml',
            'http://feeds.bbci.co.uk/news/politics/rss.xml',
            'http://feeds.bbci.co.uk/news/health/rss.xml',
            'http://feeds.bbci.co.uk/news/education/rss.xml',
            'http://feeds.bbci.co.uk/news/technology/rss.xml',
            'http://feeds.bbci.co.uk/news/world/africa/rss.xml',
            'http://feeds.bbci.co.uk/news/world/asia/rss.xml',
            'http://feeds.bbci.co.uk/news/world/europe/rss.xml',
            'http://feeds.bbci.co.uk/news/england/rss.xml',
            'http://feeds.bbci.co.uk/news/scotland/rss.xml',
            'http://feeds.bbci.co.uk/news/wales/rss.xml',
            'http://feeds.bbci.co.uk/news/magazine/rss.xml',
            'http://www.bbc.co.uk/blogs/theeditors/rss.xml',
        ]);

        $feed->init();
        $imported = 0;
        $articleService = $this->getContainer()->get('app.articles');
        $links = []; // for demo we want only unique articles
        foreach($feed->get_items() as $item) {
            if ($imported === $limit) {
                break;
            }

            $link = $item->get_link();
            if (in_array($link, $links)) {
                continue;
            }
            $links[] = $link;
            $domain = parse_url($link, PHP_URL_HOST);

            $article = new Article();
            $article->setAuthor($item->get_author() ?: $domain);
            $article->setDateAdd(new \DateTime());
            $article->setDateCreate(new \DateTime($item->get_date()));
            $article->setTitle($item->get_title());
            $article->setDescription($item->get_description());
            $article->setLink($link);
            $thumbnail = $item->get_thumbnail();
            if (is_array($thumbnail) && array_key_exists('url', $thumbnail))
            {
                $article->setThumbnail($thumbnail['url']);
            } else
            {
                $article->setThumbnail('');
            }
            $articleService->create($article, ['flush' => false]);
            $output->write('.');
            ++$imported;
        }
        $articleService->flush();
        $output->writeln('');

        $output->writeln('Done in '.(microtime(true) - $start).'s.');
    }

}
