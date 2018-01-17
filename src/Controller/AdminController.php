<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

use App\Entity\User;

class AdminController extends BaseAdminController
{
	/**
	 * @Route("/admin", name="admin")
	 */
	public function index() {
		return $this->render('pages/admin/index.html.twig');
	}
	/**
	 * @Route("/admin/users", name="admin_users")
	 */
	public function users(){
		$users = $this->getDoctrine()
			->getRepository(User::class)
			->findAll();
		var_dump($users);

		$forms = [];
		foreach($users as $user){
			$forms[$user->getId()] = $this->createNamedBuilder('user-'.$user->getId(), $user)
				->add('firstName', TextType::class)
				->add('lastName', TextType::class)
				->add('email', EmailType::class)
				->add('role', ChoiceType::class, array(
					'choices'  => array(
						'User' => 'ROLE_USER',
						'Admin' => 'ROLE_ADMIN',
					),
				))
				->add('save', SubmitType::class, array('label' => 'Save user'))
				->getForm()
				->createView();
		}
		return $this->render('pages/admin/users.html.twig', array(
			'userForms' => $forms,
		));
	}
}
