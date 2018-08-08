<?php

namespace OC\PlatformBundle\Controller;

use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\AdvertSkill;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{
    public function indexAction($page)
    {
      /* $url = $this->get('router')->generate(
           'oc_platform_view',
           array('id' => 5),
           true
       );

       return new Response("l'URL de l'annonce d' id 5 est : ".$url);   
      */ 
       
       if($page < 1 ){
        return $this->render('OCPlatformBundle:Advert:error.html.twig');
      }
      
         // Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
    $nbPerPage = 3;


     /* return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
          'listAdverts' => array()
      ));*/

          // Notre liste d'annonce en dur

    /*$listAdverts = array(
        array(
          'title'   => 'Recherche développpeur Symfony2',
          'id'      => 1,
          'author'  => 'Alexandre',
          'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
          'date'    => new \Datetime()),
        array(
          'title'   => 'Mission de webmaster',
          'id'      => 2,
          'author'  => 'Hugo',
          'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
          'date'    => new \Datetime()),
  
        array(
          'title'   => 'Offre de stage webdesigner',
          'id'      => 3,
          'author'  => 'Mathieu',
          'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
          'date'    => new \Datetime())
  
    );*/ 
    //pour recuperer la liste de toutes les annonces : on utilise findAll()
    $listAdverts = $this->getDoctrine()
        ->getManager()
        ->getRepository('OCPlatformBundle:Advert')
        ->getAdverts($page, $nbPerPage)
        ;
  
  
      // Et modifiez le 2nd argument pour injecter notre liste
  
      return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
        'listAdverts' => $listAdverts
      ));
    }

    public function viewAction($id,Request $request )
    {
      /*  1
      //manipuler les request
       // $tag = $request->query->get('tag');
        //return new Response("Affichage de l'annonce d' id: ".$id.", avec le tag: ".$tag);
       */
       /* 2
       // manipuler les responses
       $response = new Response();
        //on definit le contenu
        $response->setContent("ceci est une page d'erreur 404");
        //on defini le code HTTP a <<Not Found>>
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
        return $response;

      //pour renvoyer la page d'erreur
        return $this->render('OCPlatformBundle:Advert:view.html.twig',
        array('id'=> $id));

     //testons la redirection vers une page
     $url = $this->get('router')->generate('oc_platform_home');
     return new RedirectResponse($url);
     //autre methode plus courte
     return $this->redirectToRoute('oc_platform_home');
    
    //renvoyer des reponses ajax au serveur
    $response = new Response(json_encode(array('id'=> $id)));
    $response->headers->set('Content-Type', 'application/json');
    // return $response;
       //autre methode plus courte
    return new JsonResponse(array('id' => $id));
    */
     
   /*
    // manipuler le sessions

    //recuperation de la session
    $session = $request->getSession();
    //on recupere le contenu de la variable user_id
    $userId = $session->get('user_id');
    //on definit une nouvelle valeur pour cette variable user_id
    $session->set('user_id', 91);
    //apres on renvoie la reponse
    return new Response("je suis une page test, je n'ai rien a dire pour le moment");
    */

     /*return $this->render('OCPlatformBundle:Advert:view.html.twig',
      array('id'=>$id));*/


      /*$advert = array(
        'title'   => 'Recherche développpeur Symfony2',
        'id'      => $id,
        'author'  => 'Alexandre',
        'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
        'date'    => new \Datetime()
      );
      */
        // on recupere l'Entity manager
       $em = $this->getDoctrine()->getManager();

       //on recupere l'annonce $id
       $advert = $em->getRepository('OCPlatformBundle:Advert')
                    ->find($id);

       //$advert est donc une instance de OCPlatformBundle:Advert
       //ou null si l'id $id n'existe pas d'ou ce if

       if(null === $advert)
       {
        return $this->render('OCPlatformBundle:Advert:error.html.twig');  
        //throw new NotFoundHttpException("l' annonce d'id ".$id."n'existe pas");
       }

        /*// on recupere la liste des candidatures de cette annonce
         $listApplications = $em
         ->getRepository('OCPlatformBundle:Application')
         ->findBy(array('advert'=>$advert));
          */


         //on recupere maintenant la liste des AdvertSkill
         $listAdvertSkills = $em
            ->getRepository('OCPlatformBundle:AdvertSkill')
            ->findByAdvert( $advert);

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
        'advert'           => $advert,
        //'listApplications' => $listApplications,
        'listAdvertSkills' => $listAdvertSkills
      ));

    }

    public function addAction(Request $request)
    {
       /*
        $session = $request->getSession();
        //ajoutons une annonce
        $session->getFlashBag()->add('info', 'annonce bien enregistree!');
       $session->getFlashBag()->add('info', 'oui oui, elle est bien enregistree');
        //on redirige vers la pag view
        return $this->redirectToRoute('oc_platform_view', array('id' => 5));
        */

       // si la methode d'envoie du formulaire est POST on suit les instructions suivantes:
        if ($request ->isMethod('POST')){
            //on recupere la creation et la gestion du formulaire
            $request->getSession()->getFlashBag()->add('info', 'annonce bien  enregistree!');
            //on redirige vers la page de visualisation de cette annonce
            return $this->redirectToRoute('oc_platform_view', array('id'=>1));
        }

        //si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('OCPlatformBundle:Advert:add.html.twig');
      
      
       /*//on recupere le service
       $antispam = $this->container->get('oc_platform.antispam');

       //je pars du principe que $test contient le texte d'un message quelconque
       $text ="...";
       if($antispam->isSpam($text)){
         throw new \Exception('votre message a ete detecte comme spam!');
       }
      */
      
      /*$em = $this->getDoctrine()->getManager();
      
      //creation de l'entite Advert
      $advert = new Advert();
      $advert->setTitle('recherche de developpeur Symfony2');
      $advert->setAuthor('Alexandre');
      $advert->setContent("nous recherchons un developpeur symfony2 debutant a cotafric");
      
      // on recupere toutes les competences possibles
      $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();

      //pour chaque competence
    foreach ($listSkills as $skill) {

      // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
      $advertSkill = new AdvertSkill();

      // On la lie à l'annonce, qui est ici toujours la même
      $advertSkill->setAdvert($advert);

      // On la lie à la compétence, qui change ici dans la boucle foreach
      $advertSkill->setSkill($skill);


      // Arbitrairement, on dit que chaque compétence est requise au niveau 'Expert'
      $advertSkill->setLevel('Expert');


      //  on persiste cette entité de relation, propriétaire des deux autres relations
      $em->persist($advertSkill);

    }


      //creation de la premiere candidature
      $application1 = new Application();
      $application1 ->setAuthor('marine');
      $application1 -> setContent("j'ai toutes les qualites requises.");

      //creation  d'une deuxieme candidature
      $application2 = new Application();
      $application2 ->setAuthor('pierre');
      $application2 ->setContent("je suis tres motive!");

      //on lie les candidatures a l'annonce
      $application1 ->setAdvert($advert);
      $application2 ->setAdvert($advert);

      //creation de l'entite image
       $image = new Image();
       $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
       $image->setAlt('travail de reve');

      //on lie l'image a l'annonce
      $advert->setImage($image);

      //on peut ne pas defini la date ni la publication,
      //car ces attributs sont definis automatiquement dans le constructeur

      //on recupere actuellement l'EntityManager
      $em = $this->getDoctrine()->getManager();

      //etape1: on persiste l'entite
      $em -> persist($advert);
      
      //etape1 bis: pour cette relation pas de cascade lorsqu'on persiste 
      //definnie dans l'entite application et non Advert. on doit douc tout persister
      $em->persist($application1);
      $em->persist($application2);
      //etape2 : on <<flush> tout ce qui a ete persiste avant
      $em -> flush();

      //reste de la methode qu'on avait deja ecrit
      if($request -> isMethod('POST')){
        $request -> getsession()->getFlashBag()->add('notice', 'Annonce bien enregistree!.');
        return $this->redirect($this->generateUrl('oc_platform_view', array(
          $advert->getId())));
        }
        return $this->render('OCPlatformBundle:Advert:add.html.twig');
      */
    }
 
    public function editAction($id , Request $request)
    {
  /* //on recupere l'annonce correspondante a $id
         if ($request->isMethod('POST'))
         {
             $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiee!');
             return $this->render->redirectToRoute('oc_platformBundle_view', array('id' => 5));
         }
      return $this->render('OCPlatformBundle:Advert:edit:html:twig');
    */
    /* 
    $advert = array(
        'title'   => 'Recherche développpeur Symfony2',
        'id'      => $id,
        'author'  => 'Alexandre',
        'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
        'date'    => new \Datetime()
      );
  
      return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
        'advert' => $advert
      ));*/

      $em = $this->getDoctrine()->getManager();

      //on recupere l'annonce $id
      $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

      if( $advert == null )
      {
        return $this->render('OCPlatformBundle:Advert:error.html.twig');
      }

     /* //la methode findAll retourne toutes les categories de la base de donnees
      $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();

      //on boucle sur les categories pour les lier a l'annonce
      foreach($listCategories as $category)
      {
        $advert->addCategory($category);
      }

      $em->flush();*/

      return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
        'advert' => $advert
      ));

    }


    public function menuAction($limit = 3)
    {
        // on fixe en dure une liste ici, bien entendu par la suite
        // on la recupera depuis la bdd
       /* $listAdverts =array(
            array('id'=> 2, 'title'=> 'recherche developpeur Symfony2'),
            array('id'=>5, 'title'=>'mission de webmaster'),
            array('id'=>9, 'title'=>'offre de stage webdesigner')
        );*/

        $listAdverts = $this->getDoctrine()
      ->getManager()
      ->getRepository('OCPlatformBundle:Advert')
      ->findBy(
        array(),                 // Pas de critère
        array('date' => 'desc'), // On trie par date décroissante
        $limit,                  // On sélectionne $limit annonces
        0                        // À partir du premier
    );

        return $this -> render('OCPlatformBundle:Advert:menu.html.twig', array(
            //tout l'interet est ici: le controller passe
            // les variables necessaires au template!

            'listAdverts'=>$listAdverts
        ));
    }

    public function deleteAction($id, Request $request)
    {  
   
     // on recupere l'entity manager
     $em = $this->getDoctrine()->getManager();

     //on recupere l'annonce $id
     $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

     if($advert == null)
     {
       return $this->render('OCPlatformBundle:Advert:error.html.twig');
     }

    /* //on boucle sur les categories de l'annonce pour les supprimer
     //pour recuperer toutes les categories de notre annonce est 
     //$advert->getCategories();
     foreach($advert -> getCategories() as $category)
     {
       $dvert -> removeCategory($category);
     }


      $em->remove($advert);
      $em->flush();
        //on recupere l'annonce correspondant a l'id 
        //on supprimme l'annonce qui porte cet id
        return $this->render('OCPlatformBundle:Advert:index.html.twig');
    */
  
    if ($request->isMethod('POST')) {
      // Si la requête est en POST, on deletea l'article

      $request->getSession()->getFlashBag()->add('info', 'Annonce bien supprimée.');

      // Puis on redirige vers l'accueil
      return $this->redirect($this->generateUrl('oc_platform_home'));
    }

    // Si la requête est en GET, on affiche une page de confirmation avant de delete
    return $this->render('OCPlatformBundle:Advert:delete.html.twig', array(
      'advert' => $advert
    ));

   }



    public function viewSlugAction($year , $slug , $format){
        return new Response(

            "On pourrait afficher l'annonce correspondant au
            slug '".$slug."', créée en ".$year." et au format ".$format."."

        );
       
    }
}