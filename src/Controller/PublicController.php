<?php

namespace CRM\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use CRM\Domain\Tache;
use CRM\Domain\Consigne;
use CRM\Domain\Contact;
use CRM\Domain\Prospect;
use CRM\Form\Type\ProspectType;
use CRM\Form\Type\ContactType;

class PublicController {
    public function indexAction(Application $app, Request $request) {
        $contact = new Contact();
        $form = $app['form.factory']->create(ContactType::class, $contact);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $entreprise = $form->getData()->getEntreprise();
            $app['dao.entreprise']->save($entreprise);
            $contact->setEntreprise($entreprise);
            $app['dao.contact']->save($contact);

            $prospect = new Prospect(['contact' => $contact, 'site' => $app['dao.site']->find(1), 'user' => $app['dao.user']->find(1) ]);
            $app['dao.prospect']->save($prospect);
            return $app->redirect($app['url_generator']->generate('fiche_prospect', ['id' => $prospect->getId()]));
        }

        return $app['twig']->render('index.html.twig', ['form' => $form->createView(), 'index' => true]);
    }

    public function newPisteAction(Application $app, Request $request) {
        $contact = new Contact();
        $form = $app['form.factory']->create(ContactType::class, $contact);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            $entreprise = $form->getData()->getEntreprise();
            $app['dao.entreprise']->save($entreprise);
            $contact->setEntreprise($entreprise);
            $app['dao.contact']->save($contact);

            $prospect = new Prospect(['contact' => $contact, 'site' => $app['dao.site']->find(1), 'user' => $app['dao.user']->find(1), 'isLooked' => true ]);
            $app['dao.prospect']->save($prospect);
            return $app->redirect($app['url_generator']->generate('fiche_prospect', ['id' => $prospect->getId()]));
        }

        return $app['twig']->render('new-piste.html.twig', ['form' => $form->createView(), 'piste' => true]);
    }

    public function listeProspectAction(Application $app) {
        $user = ( $this->getCurrentUser($app) !== null ) ? $this->getCurrentUser($app) : null;
        $listeProspect = $app['dao.prospect']->findAllByUser($user->getId());
        return $app['twig']->render('liste-prospect.html.twig', ['listeProspect' => $listeProspect, 'lp' => true]);
    }

    public function listeTacheAction(Application $app, Request $request) {
        $listeTaches = $app['dao.tache']->findAllByUser($this->getCurrentUser($app)->getId());

        $contact = new Contact();
        $form = $app['form.factory']->create(ContactType::class, $contact);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            $entreprise = $form->getData()->getEntreprise();
            $app['dao.entreprise']->save($entreprise);
            $contact->setEntreprise($entreprise);
            $app['dao.contact']->save($contact);

            $prospect = new Prospect(['contact' => $contact, 'site' => $app['dao.site']->find(1), 'user' => $app['dao.user']->find(1) ]);
            $app['dao.prospect']->save($prospect);
            return $app->redirect($app['url_generator']->generate('fiche_prospect', ['id' => $prospect->getId()]));
        }

        return $app['twig']->render('liste-tache.html.twig', ['listeTaches' => $listeTaches, 'lt' => true, 'form' => $form->createView(), 'index' => true]);
    }

    public function prospectAction(Application $app, Request $request, $id) {
        $prospect = $app['dao.prospect']->find($id);
        $consignes = $app['dao.consigne']->findAllByProspect($id);
        $taches = $app['dao.tache']->findAllByProspect($id);
        $form = $app['form.factory']->create(ProspectType::class, $prospect);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            $contact = $form->getData()->getContact();
            $entreprise = $contact->getEntreprise();
            $app['dao.entreprise']->save($entreprise);
            $app['dao.contact']->save($contact);
            $prospect->setContact($contact);
            $app['dao.prospect']->save($prospect);
        }
        return $app['twig']->render('prospect.html.twig', array(
            'form' => $form->createView(), 'prospect' => $prospect,
            'consignes' => $consignes, 'taches' => $taches
        ));
    }

    public function consigneAction(Application $app, Request $request, $id) {
        if ( $request->isMethod('post') ) {
            $prospect = $app['dao.prospect']->find($id);
            $consigne = new Consigne([
                'prospect' => $prospect,
                'commentaire' => $request->request->get('comment')
            ]);
            $app['dao.consigne']->save($consigne);
            return $app->redirect($app['url_generator']->generate('fiche_prospect', ['id' => $prospect->getId()]));
        }

        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Vous ne pouvez pas accéder a cette page');
    }

    public function tacheAction(Application $app, Request $request, $id) {
        if ( $request->isMethod('post') ) {
            $prospect = $app['dao.prospect']->find($id);
            $dateRappel = \DateTime::createFromFormat('d/m/Y', $request->request->get('dateRappel'));
            $tache = new Tache([
                'prospect' => $prospect,
                'dateRappel' => $dateRappel,
                'dateFin' => $dateRappel,
                'objet' => $request->request->get('objet'),
                'description' => $request->request->get('description')
            ]);
            $app['dao.tache']->save($tache);
            return $app->redirect($app['url_generator']->generate('fiche_prospect', ['id' => $prospect->getId()]));
        }

        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Vous ne pouvez pas accéder a cette page');
    }

    public function closeTaskAction(Application $app, Request $request) {
        if ( $request->isXmlHttpRequest() ) {
            $tache = $app['dao.tache']->find($request->request->get('id'));
            $tache->setIsComplete(true);
            $app['dao.tache']->save($tache);
            return new \Symfony\Component\HttpFoundation\Response('ok');
        }

        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Page introuvable');
    }

    public function getCurrentUser(Application $app) {
        $token = $app['security.token_storage']->getToken();
        return ( null !== $token ) ? $token->getUser() : null;
    }
}