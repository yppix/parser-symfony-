<?php

namespace App\Repository;

use App\Entity\NewsList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method NewsList|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsList|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsList[]    findAll()
 * @method NewsList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsListRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, NewsList::class);
        $this->entityManager = $entityManager;

    }

    public function getAll(): array
    {
        return parent::findAll();
    }

    public function create($news_array, NewsList $news, $themeNews ) {
        foreach ( $news_array as $news_item ) {
            $is_news = $this->findOneBySomeField(trim($news_item['text']));
            if(is_null($is_news)) {
                if (!($news_item['images'])){
                    $img = null;
                } else{
                    $img = $news_item['images']->src;
                }
                $theme_id = $themeNews->findByThemeName($news_item['theme']);
                $news->setText($news_item['text']);
                $news->setHref($news_item['link']);
                $news->setImage($img);
                $news->setDatePublication($news_item['time_publication']);
                $news->setThemeId($theme_id);
                $news->setCreatedAt(new \DateTime());
                $news->setUpdatedAt(new \DateTime());
                $this->entityManager->persist($news);
                $this->entityManager->flush();
            }
        }

    }

    public function findOneBySomeField($value): ?NewsList
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.text = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }




}
