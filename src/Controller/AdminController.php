<?php

namespace CRM\Controller;


use CRM\Domain\Site;
use CRM\Domain\User;
use CRM\Form\Type\SiteType;
use CRM\Form\Type\UserType;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController {
    public function indexAction(Application $app) {
        if (!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
            return new Response('Vous devez être administrateur pour accèder a cette page');
        }
        $listeProspect = $app['dao.prospect']->findAll();
        return $app['twig']->render('Admin/index.html.twig', ['listeProspect' => $listeProspect, 'admin' => true]);
    }

    public function addSiteAction(Application $app, Request $request) {
        $site = new Site();
        $form = $app['form.factory']->create(SiteType::class, $site);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            $app['dao.site']->save($site);
            return $app->redirect($app['url_generator']->generate('admin_add_site'));
        }

        $sites = $app['dao.site']->findAll();
        return $app['twig']->render('Admin/add-site.html.twig', ['form' => $form->createView(), 'sites' => $sites, 'site' => true]);
    }

    public function addUserAction(Application $app, Request $request) {
        $user = new User();
        $form = $app['form.factory']->create(UserType::class, $user);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $plainPassword = $user->getPassword();
            $encoder = $app['security.encoder.bcrypt'];
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);

            $site = $app['dao.site']->find($request->request->get('site'));
            $user->setSite($site);
            $app['dao.user']->save($user);
            return $app->redirect($app['url_generator']->generate('admin_add_user'));
        }

        $listUsers = $app['dao.user']->findAll();
        $sites = $app['dao.site']->findAll();
        return $app['twig']->render('Admin/add-user.html.twig', ['form' => $form->createView(), 'listUsers' => $listUsers, 'sites' => $sites, 'users' => true]);
    }
}