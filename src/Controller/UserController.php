<?php


namespace App\Controller;


use App\Entity\Main\Action;
use App\Entity\Main\Permission;
use App\Entity\Main\Subject;
use App\Entity\Main\User;
use App\Service\EmailSenderHelper;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swift_TransportException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class UserController extends AbstractController
{





    /**
     * Renvoie la vue pour se connecter
     * Fait appel au fichier Security/LoginFormAuthenticator.php pour vérifier les informations de l'utilisateur
     *
     * @Route("/login", name="user::login")
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @return Response
     */
	public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
	{

		// si l'utilisateur est déjà connecté
		// redirige vers l'accueil
		if($this->getUser() !== null)
			return $this->redirectToRoute("template::index");

		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('template/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
	}


	/**
     * Deconnecte l'utilisateur et renvoie vers la page de login
     *
	 * @Route("/logout", name="user::logout")
	 *
	 */
	public function logout()
	{ }


	/**
     * Renvoie une vue permettant de reinitialisser son mot de passe en fonction de l'url
     *
	 * @Route(path="/password/forget", name="user::password_forget")
	 *
	 * @Route(path="/reset/password/{token}", name="user::reset_password")
	 *
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param EmailSenderHelper $mailer
	 * @return Response
	 * @throws Exception
	 */
	public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder , EmailSenderHelper $mailer): Response
	{

        if($this->getUser() !== null)
            throw new Exception("Vous ne pouvez pas accèder à cette page tant que vous êtes connecté !");

		if($request->getRequestUri() === "/password/forget")
		{

			if($request->isMethod("POST"))
			{

				if(is_null($request->request->get('username')))
				{
					throw new Exception("Missing username parameter !");
				}

				$user = $this->getDoctrine()->getRepository(User::class)->checkIfUserExist( ["username" => $request->request->get('username')]);

				if(!$user)
				{
					throw new Exception("Mauvais token !");
				}

                if(is_null($request->request->get('email')))
                {
                    throw new Exception("Missing email parameter !");
                }

				// openssl_random_pseudo_bytes generate a pseudo-random string of bytes using length
				// openssl_random_pseudo_bytes(int $length)
				// https://www.php.net/manual/en/function.openssl-random-pseudo-bytes.php
				//
				// bin2hex convert binary data into hexadecimal
				// bin2hex(string $binaryData)
				// https://www.php.net/manual/en/function.bin2hex.php
				$token = bin2hex(openssl_random_pseudo_bytes(64));

				// recupère l'email saisie via le formulaire
				$userEmail = $request->request->get("email");

				$user->setPasswordResetToken($token);

				// maj de la base de donnée
				$this->getDoctrine()->getManager()->flush();


				$mailer->sendEmail("cbaby@infoway.fr", $userEmail,
					"cbaby@infoway.fr", "Reset my password", $this->render(
                        "template/security/resetPasswordEmail.html.twig", [
						'token' => $token
					]), 'text/html');

                // message flash dans la session
                //(new Session())->getFlashBag()->add("message", "Merci de vérifier votre boîte mail, un mail contenant le lien pour réinitialiser votre mot de passe vous a été envoyé !");
			}
		}

		else
		{
			try
			{

				$user = $this->getDoctrine()->getRepository(User::class)->checkIfUserExist(["password_reset_token" => $request->get("token")]);

				if(!$user)
                    throw new Exception("Mauvais token !");

				else
				{
                    if($request->isMethod("POST"))
                    {
                        if(!empty($request->request->get("new_password")))
                        {

                            // on rajoute le token dans le champ "password_reset_token"
                            $user->setUserPassword($passwordEncoder->encodePassword($user, $request->get("new_password")));

                            // on recupère la date actuelle on l'enregistre
                            $user->setPasswordResetAt(new \DateTime());

                            // le token a été utilisé, on peut le supprimer
                            $user->setPasswordResetToken(NULL);

                            // on mets à jour la base de donnée
                            $this->getDoctrine()->getManager()->flush();

                            // message flash dans la session
                            //(new Session())->getFlashBag()->add("message", "Votre mot de passe a bien été modifié ! Vous pouvez vous connecter <a href='". $this->generateUrl("app_login") ."'>içi</a>");
                        }
                        else
                        {
                            throw new Exception("Missing new_password parameter !");
                        }
                    }

				}

			}
			catch (Swift_TransportException $e)
			{
				throw new Exception($e->getMessage());
			}
			catch (Exception $e)
			{
				throw new Exception($e->getMessage());
			}
		}

		return $this->render("template/security/resetPassword.html.twig");

	}


    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED") // user must be logged
     *
     * @Route(path="/interface/admin", name="user::interface_admin")
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
	public function interfaceAdmin(Request $request): Response
    {

        if($request->isMethod("POST"))
        {
            $location = "choice";
            $userSelected = $this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $request->request->get("user")]);
            if(!$userSelected)
                throw new Exception("User not found !");

        }


        else
            $location = "select";

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        if(!$users)
            throw new Exception("Users not found !");

        return $this->render("template/security/interfaceAdmin.html.twig", [
            'users' => $users,
            'location' => $location,
            'userSelected' => (isset($userSelected)) ? $userSelected : null]);
    }


    /**
     * @Route(path="/update/{username}/role", name="user::updateUserRole")
     *
     * @param Request $request
     * @param User $user (doctrine will make query to found user which have the username which is in uri)
     * @return Response
     * @throws Exception
     */
    public function updateUserRole(Request $request, User $user): Response
    {

        dump($this->getDoctrine()->getRepository(User::class)->getUserPermissions($this->getUser()->getUsername()));

        if($request->isMethod("POST"))
        {

            if(!$this->getDoctrine()->getRepository(User::class)->checkIfUserHasPermission($this->getUser(),"Attribuer", "Role"))
                return new Response(1, Response::HTTP_INTERNAL_SERVER_ERROR);

            else
            {

                if(is_null($request->request->get("username")) OR is_null($request->request->get("roleID")))
                    return new Response(3, Response::HTTP_INTERNAL_SERVER_ERROR);


                $role = $this->getDoctrine()->getRepository(Role::class)->findOneBy(["id" => $request->request->get("roleID")]);
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["username" => $request->request->get("username")]);


                if(!$role)
                    return new Response(3, Response::HTTP_INTERNAL_SERVER_ERROR);

                if(!$user)
                    return new Response(3, Response::HTTP_INTERNAL_SERVER_ERROR);


                if($role->getId() > $user->getRole()->getId())
                {
                    $user->setRole($role);
                    $role->addUser($user);

                    $this->getDoctrine()->getManager()->flush();

                    /*if( (new Session())->get("user")['username'] === $user->getUsername())
                    {
                        $userData = (new Session())->get("user");
                        $userData['role'] = $role->getName();
                        (new Session())->set("user", $userData);
                    }*/
die();
                    return new Response();
                }

                return new Response(2, Response::HTTP_INTERNAL_SERVER_ERROR);

            }
        }


        $userRole = $this->getDoctrine()->getRepository(Role::class)->findOneBy(["id" => $user->getRole()->getId()]);
        if(!$userRole)
            throw new Exception("Role not found for user '" . $user->getUsername() ."' !");


        $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();

        if($roles === false)
            throw new Exception("Roles not found !");

        $action = $this->getDoctrine()->getRepository(Action::class)->findOneBy(['name' => "Attribuer"]);
        if(!$action)
            throw new Exception("Permission 'Attribuer' not found !");

        return $this->render(
            "template/security/userRoleManager.html.twig", [
            'userRole' => $userRole,
            'roles' => $roles,
            'action' => $action
        ]);


    }


    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED") // user must be logged
     *
     * @Route(path="/update/{username}/permission", name="user::updateUserPermission")
     *
     * @param Request $request
     * @param User $user (doctrine will make query to found user which have the username which is in uri)
     * @return Response
     * @throws Exception
     */
    public function updateUserPermission(Request $request, User $user): Response
    {

        if($request->isMethod("POST"))
        {

            if(!$this->getDoctrine()->getRepository(User::class)->checkIfUserHasPermission($this->getUser(),"Editer", "Permissions d'un utilisateur"))
                return new Response(1, Response::HTTP_INTERNAL_SERVER_ERROR);


            if(is_null($request->request->get("username")) OR is_null($request->request->get("action"))
                OR is_null($request->request->get("actionId")) OR is_null($request->request->get("subjectId")))
                return new Response(2, Response::HTTP_INTERNAL_SERVER_ERROR);


            $action = $this->getDoctrine()->getRepository(Action::class)->findOneBy(["id" => $request->request->get("actionId")]);
            if(!$action)
                return new Response(2, Response::HTTP_INTERNAL_SERVER_ERROR);


            $subject = $this->getDoctrine()->getRepository(Subject::class)->findOneBy(["id" => $request->request->get("subjectId")]);
            if(!$subject)
                return new Response(2, Response::HTTP_INTERNAL_SERVER_ERROR);


            $permission = $this->getDoctrine()->getRepository(Permission::class)->findOneBy(["action" =>  $action, "subject" => $subject]);
            if(!$permission)
                return new Response(3, Response::HTTP_INTERNAL_SERVER_ERROR);


            if($request->request->get("action") === "add")
            {
                $user->addPermission($permission);
                $permission->addUser($user);

                //$userData = (new Session())->get("user");
                //$userData['permissions'][$subject->getId() . "_" . $subject->getName() ][$action->getName()] = true;
                //(new Session())->set("user", $userData);
                die();
            }
            else
            {
                $user->removePermission($permission);
                $user->getRole()->removePermission($permission);

                $permission->removeRole($user->getRole());
                $permission->removeUser($user);

                // delete permission in session
                //$userData = (new Session())->get("user");
                //unset($userData['permissions'][$subject->getId() . "_" . $subject->getName()][$action->getName()]);
                //(new Session())->set("user", $userData);
                die();
            }


            $this->getDoctrine()->getManager()->flush();

            return new Response();

        }

        $actions = $this->getDoctrine()->getRepository(Action::class)->getNamesOfAllActionsWithId();
        if(!$actions)
            throw new Exception("Actions not found !");


        $userPermissions = $this->getDoctrine()->getRepository(User::class)->getUserPermissionsWithId($user->getUsername());

        dump($userPermissions);

        $subjects = $this->getDoctrine()->getRepository(Subject::class)->getNamesOfAllSubjects();
        if(!$subjects)
            throw new Exception("Subjects not found !");


        return $this->render("template/security/userPermissionManager.html.twig", [
            'actions' => $actions,
            'subjects' =>$subjects,
            'userPermissions' => $userPermissions
        ]);

    }


}