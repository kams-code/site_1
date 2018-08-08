<?php

namespace OC\PlatformBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdvertRepository extends EntityRepository
{

  public function myFindAll()
  {
    // Méthode 1 : en passant par l'EntityManager
    $queryBuilder = $this->_em->createQueryBuilder()
      ->select('a')
      ->from($this->_entityName, 'a')
    ;

    // Dans un repository, $this->_entityName est le namespace de l'entité gérée
    // Ici, il vaut donc OC\PlatformBundle\Entity\Advert
    // Méthode 2 : en passant par le raccourci (je recommande)

    $queryBuilder = $this->createQueryBuilder('a');
    // On n'ajoute pas de critère ou tri particulier, la construction
    // de notre requête est finie
    // On récupère la Query à partir du QueryBuilder

    $query = $queryBuilder->getQuery();

    // On récupère les résultats à partir de la Query
    $results = $query->getResult();

    // On retourne ces résultats
    return $results;

  }



  public function whereCurrentYear(QueryBuilder $qb)
  {
      $qb
        ->andWhere('a.date BETWEEN :start And :end')
        ->setParameter('start', new \Datetime(date('Y').'-01-01')) //Date entre le 1er janvier de cette annee
        ->setParameter('end', new \Datetime(date('Y').'-12-31')) //et le 31 decembre de cette annee
        ;
  }


  public function getAdvertWithCategories(array $categoryNames)
  {
      $qb = $this->createQueryBuilder('a');

      //on fait une jointure avec l'entite category avec pour alias <<c>>
      $qb
        ->join('a.cateories', 'c')
        ->addSelect('c')
        ;

        //puis on filtre sur le nom des categories a l'aide d'un IN
        $qb->where($qb->expr()->in('c.name', $categoryNames));
        // la syntaxe du IN et d'autres expressions se trouve dans la documentation Doctrine

        //Enfin, on retourne le resultat
        return $qb
                ->getQuery()
                ->getResult()
                ;
  }


  public function getAdverts($page, $nbPerPage)
  {
    $query = $this->createQueryBuilder('a')
    //jointure sur l'attribut image
      ->leftJoin('a.image','i')
      ->addSelect('i')
      //jointure sur l'attribut categories
      ->leftJoin('a.categories', 'c')
      ->addSelect('i')
      
      ->orderBy('a.date', 'DESC')
      ->getQuery()
    ;
    //return $query->getResult();

    $query
    //essayons de definir l'annonce de debut de liste
    ->setFirstResult(($page-1)*$nbPerPage)
    //ainsi que le nombre d'annonce a afficher sur une page
    ->setMaxResults($nbPerPage)
    ;
    // retournons l'objet pagination correspondant a la requette
    return new Paginator($query, true);
  }


}