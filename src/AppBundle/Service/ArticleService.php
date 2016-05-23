<?php
namespace AppBundle\Service;
use AppBundle\Entity\Article;
use AppBundle\Event\ArticleEvent;
use AppBundle\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ArticleService {
	protected $dateFormat = 'Y-m-d H:i:s';

	/**
	 * @var ArticleRepository
	 */
	protected $repository;

	/**
	 * @var EntityManager
	 */
	protected $entityManager;

	/**
	 * @var EventDispatcherInterface
	 */
	protected $eventDistpatcher;

	public function setRepository(ArticleRepository $articleRepository)
	{
		$this->repository = $articleRepository;
	}

	public function setEntityManager(EntityManager $em) {
		$this->entityManager = $em;
	}

	public function setEventDispatcher(EventDispatcherInterface $eventDispatcher) {
		$this->eventDistpatcher = $eventDispatcher;
	}

	public function create(Article $article, array $options = [])
	{
		$this->entityManager->persist($article);
		$options = array_merge([
			'flush' => true
		], $options);
		if ($options['flush']) {
			$this->entityManager->flush();
		}

		$event = new ArticleEvent($article);
		$this->eventDistpatcher->dispatch(ArticleEvent::ADD, $event);
	}

	public function flush()
	{
		$this->entityManager->flush();
	}

	public function findById($id)
	{
		return $this->repository->find($id);
	}

	public function findByTitle($title)
	{
		return $this->repository->findByTitle($title);
	}

	public function setDateFormat($format)
	{
		$this->dateFormat = $format;
	}

	public function findByTitlePart($title, array $options = [])
	{
		$options = array_merge(['output' => 'scalar'], $options);
		$data = $this->repository->findByTitlePart($title);
		if ('scalar' === $options['output']) {
			return $data->getResult();
		}
		$results = $data->getArrayResult();
		array_walk($results, function(&$item) {
			$item['dateAdd'] = $item['dateAdd']->format($this->dateFormat);
			$item['dateCreate'] = $item['dateCreate']->format($this->dateFormat);
		});
		return $results;
	}
}