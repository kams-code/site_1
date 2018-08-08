<?php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Category;
use OC\PlatformBundle\Entity\Skill;

class LoadCategory implements FixtureInterface
{
  // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
  public function load(ObjectManager $manager)
  {
    // Liste des noms de competences à ajouter
     $names = array(
       'PHP',
       'Symfony2',
       'C++',
       'Java',
       'Photoshop',
       'Blender',
       'Bloc-note'
     );

     foreach ($names as $name){
       //on cree la competence
       $skill = new Skill();
       $skill->setName($name);

       //on persiste
       $manager->persist($skill);
     }
        // On déclenche l'enregistrement de toutes les catégories
        $manager->flush();
     }
     


     public function load1(ObjectManager $manager)
     {
       //liste des noms de category a ajouter
       $names = array(
      'Développement web',
      'Développement mobile',
      'Graphisme',
      'Intégration',
      'Réseau'
    );

     foreach ($names as $name) {
      // On crée la catégorie
        $category = new Category();
        $category->setName($name);


        // On la persiste
        $manager->persist($category);
     }
     // On déclenche l'enregistrement de toutes les catégories
     $manager->flush();
     }

}