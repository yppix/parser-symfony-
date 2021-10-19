<?php

namespace App\Repository;

use App\Entity\ThemeNews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method ThemeNews|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThemeNews|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThemeNews[]    findAll()
 * @method ThemeNews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThemeNewsRepository extends ServiceEntityRepository
{
    public $entityManager;


    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, ThemeNews::class);
        $this->entityManager = $entityManager;

    }

    public function getAll(): array
    {
        return parent::findAll();
    }


    public function create($theme_name, ThemeNews $theme) {

        $is_theme = $this->findOneBySomeField($theme_name);
            if(!($is_theme)) {
                $theme->setThemeName(trim($theme_name));
                $this->entityManager->persist($theme);
                $this->entityManager->flush();
            }
        }


    public function findOneBySomeField($value): ?array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.theme_name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
            ;
    }

    public function findByThemeName($value): ?ThemeNews
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.theme_name = :val')->setParameter('val', $value)
            ->getQuery()->getOneOrNullResult();
    }
}
