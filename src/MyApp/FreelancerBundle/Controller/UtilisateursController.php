<?php

namespace MyApp\FreelancerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MyApp\FreelancerBundle\Entity\Utilisateurs;
use MyApp\FreelancerBundle\Form\UtilisateursType;

/**
 * Utilisateurs controller.
 *
 */
class UtilisateursController extends Controller
{

    /**
     * Lists all Utilisateurs entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FreelancerBundle:Utilisateurs')->findAll();

        return $this->render('FreelancerBundle:Utilisateurs:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Utilisateurs entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Utilisateurs();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('utilisateurs_show', array('id' => $entity->getId())));
        }

        return $this->render('FreelancerBundle:Utilisateurs:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Utilisateurs entity.
     *
     * @param Utilisateurs $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Utilisateurs $entity)
    {
        $form = $this->createForm(new UtilisateursType(), $entity, array(
            'action' => $this->generateUrl('utilisateurs_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Utilisateurs entity.
     *
     */
    public function newAction()
    {
        $entity = new Utilisateurs();
        $form   = $this->createCreateForm($entity);

        return $this->render('FreelancerBundle:Utilisateurs:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Utilisateurs entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FreelancerBundle:Utilisateurs')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Utilisateurs entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FreelancerBundle:Utilisateurs:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Utilisateurs entity.
     *
     */
    
    
     public function editAction()
    {
         $Request = $this->getRequest();
        if($Request->getMethod()=="POST"){
          $em=$this->getDoctrine()->getEntityManager();
          $id=$Request->get('id');
        $jobowner=$em->getRepository('JobownerBundle:Utilisateurs')->find($id);
        $nom=$Request->get('nom');
        $prenom=$Request->get('prenom');
        $age=$Request->get('age');
        $adresse=$Request->get('adresse');
        $email=$Request->get('email');
        //$mdp=$Request->get('mdp');
        //$photo=uploadImage($Request->get('photo'));
        $jobowner->setNom($nom);
        $jobowner->setPrenom($prenom);
        $jobowner->setAge($age);
        $jobowner->setAdresse($adresse);
        $jobowner->setEmail($email);
        //$jobowner->setPassword($mdp);
       // $jobowner->setPhotoprofil($photo);
        $em->flush();
        return ($this->redirect($this->generateUrl("freelancer_show")));  
        }
    }
    
  
    /**
    * Creates a form to edit a Utilisateurs entity.
    *
    * @param Utilisateurs $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Utilisateurs $entity)
    {
        $form = $this->createForm(new UtilisateursType(), $entity, array(
            'action' => $this->generateUrl('utilisateurs_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Utilisateurs entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FreelancerBundle:Utilisateurs')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Utilisateurs entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('utilisateurs_edit', array('id' => $id)));
        }

        return $this->render('FreelancerBundle:Utilisateurs:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Utilisateurs entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FreelancerBundle:Utilisateurs')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Utilisateurs entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('utilisateurs'));
    }

    /**
     * Creates a form to delete a Utilisateurs entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('utilisateurs_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    public function showfreelancerAction()
    {
        $em=$this->getDoctrine()->getEntityManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $id=$user->getId();
        $jobowner=$em->getRepository('FreelancerBundle:Utilisateurs')->GetJobowner($id);
        return $this->render('FreelancerBundle:Utilisateurs:ParametreJobOwner.html.twig',  array('jobowner'=> $jobowner));
    }
}
