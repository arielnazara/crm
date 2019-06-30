<?php

namespace CRM\DAO;


use CRM\Domain\Prospect;

class ProspectDAO extends  DAO {

    private $userDAO;
    private $siteDAO;
    private $contactDAO;

    public function setUserDAO($userDAO) {
        $this->userDAO = $userDAO;
    }

    public function setSiteDAO($siteDAO) {
        $this->siteDAO = $siteDAO;
    }

    public function setContactDAO($contactDAO) {
        $this->contactDAO = $contactDAO;
    }

    public function find($id) {
        $sql = "select * from prospect where id = ?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));
        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("Pas de prospect trouver" . $id);
    }

    public function findAll() {
        $sql = "select * from prospect order by id desc";
        $result = $this->getDb()->fetchAll($sql);

        $prospects = array();
        foreach ($result as $row) {
            $prospectsID = $row['id'];
            $prospects[$prospectsID] = $this->buildDomainObject($row);
        }
        return $prospects;
    }

    public function findAllByUser($userId) {
        $user = $this->userDAO->find($userId);
        $sql = "select id, date, contact_id, site_id, secteurActivite, isLooked from prospect where user_id = ? order by id desc";
        $result = $this->getDb()->fetchAll($sql, [$userId]);

        $prospects = array();
        foreach ($result as $row) {
            $prospectsID = $row['id'];
            $prospects[$prospectsID] = $this->buildDomainObject($row);
            $prospects[$prospectsID]->setUser($user);
        }
        return $prospects;
    }

    public function save(Prospect $prospect) {
        $prospectsData = [
            'date' => $prospect->getDate()->format('Y-m-d H:i:s'),
            'secteuractivite' => $prospect->getSecteurActivite(),
            'isLooked' => $prospect->getIsLooked(),
            'contact_id' => $prospect->getContact()->getId(),
            'user_id' => $prospect->getUser()->getId(),
            'site_id' => $prospect->getSite()->getId()
        ];

        if ($prospect->getId()) {
            //throw new \Exception('oups ::> ' . $prospect->getDate()->format('Y-m-d H:i:s') . ' --');
            $this->getDb()->update('prospect', $prospectsData, ['id' => $prospect->getId()]);
        } else {
            $this->getDb()->insert('prospect', $prospectsData);
            $id = $this->getDb()->lastInsertId();
            $prospect->setId($id);
        }
    }

    public function delete($id) {
        $this->getDb()->delete('prospect', ['id' => $id]);
    }

    public function deleteAllByUser($userID) {
        $this->getDb()->delete('prospect', ['user_id' => $userID]);
    }

    public function deleteAllByContact($contactID) {
        $this->getDb()->delete('prospect', ['contact_id' => $contactID]);
    }

    public function deleteAllBySite($siteID) {
        $this->getDb()->delete('prospect', ['site_id' => $siteID]);
    }

    protected function buildDomainObject(array $row) {
        $prospect = new Prospect([
            'id' => $row['id'],
            'date' => new \DateTime($row['date']),
            'secteurActivite' => $row['secteurActivite'],
            'isLooked' => $row['isLooked']
        ]);

        if (  array_key_exists('user_id', $row) ) {
            $user = $this->userDAO->find($row['user_id']);
            $prospect->setUser($user);
        }

        if (  array_key_exists('site_id', $row) ) {
            $site = $this->siteDAO->find($row['site_id']);
            $prospect->setSite($site);
        }

        if (  array_key_exists('contact_id', $row) ) {
            $contact = $this->contactDAO->find($row['contact_id']);
            $prospect->setContact($contact);
        }

        return $prospect;
    }
}