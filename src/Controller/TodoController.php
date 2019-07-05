<?php

 namespace App\Controller;


 use App\Entity\Todo;
 use Symfony\Component\Form\Extension\Core\Type\SubmitType;
 use Symfony\Component\Form\Extension\Core\Type\TextType;
 use Symfony\Component\HttpFoundation\Request;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\Routing\Annotation\Route;

 class TodoController extends AbstractController {
     /**
      * @Route(path="/", name="index")
      */
     public function index() {

         $todos = $this->getDoctrine()->getRepository(Todo::class)->findAll();

        return $this->render('index.html.twig', array('todos' => $todos));

     }

     /**
      * @Route(path="/todo/create", methods={"GET","POST"})
      */
     public function create(Request $request){
         $todo = new Todo();
         $form = $this->createFormBuilder($todo)
             ->add('title',TextType::class, array('label' => 'Nazwa zadania','attr'=> array('class' => 'form-control')))
             ->add('save',SubmitType::class, array('label' => 'Dodaj', 'attr' => array('class' => 'btn btn-info')))
             ->getForm();

         $form -> handleRequest($request);

         if($form->isSubmitted() && $form->isValid()) {
             $todo = $form -> getData();

             $entityManager = $this -> getDoctrine() ->getManager();
             $entityManager -> persist($todo);
             $entityManager -> flush();

             return $this->redirectToRoute('index');
         }

         return $this->render('create.html.twig', array('form' => $form -> createView()));
     }
     /**
      * @Route(path="/todo/edit/{id}")
      */
     public function edit(Request $request,$id){

         $todo = new Todo();

         $todo = $this->getDoctrine()->getRepository(Todo::class)->find($id);

         $form = $this->createFormBuilder($todo)
             ->add('title',TextType::class, array('label' => 'Nazwa zadania','attr'=> array('class' => 'form-control')))
             ->add('save',SubmitType::class,array('label' => 'Edytuj', 'attr' => array('class' => 'btn btn-info ')))
             ->getForm();

         $form -> handleRequest($request);

         if($form->isSubmitted() && $form->isValid()) {

             $entityManager = $this -> getDoctrine() ->getManager();
             $entityManager -> flush();

             return $this->redirectToRoute('index');
         }

         return $this->render('edit.html.twig', array('form' => $form -> createView()));

     }
     /**
      * @Route(path="/todo/delete/{id}", methods={"DELETE"})
      */
     public function delete(Request $request, $id) {
         $todo = $this->getDoctrine()->getRepository(Todo::class)->find($id);
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->remove($todo);
         $entityManager->flush();

         $response = new Response();
         $response->send();
     }
     /**
      * @Route(path="/todo/show/{id}", methods={"GET","POST"})
      */
     public function show($id){
         $todo = $this->getDoctrine()->getRepository(Todo::class)->find($id);

         return $this->render('show.html.twig',array('todo' => $todo));

     }

 }