<?php

namespace CRM\DAO;


use CRM\Domain\Tache;

class TacheDAO extends DAO {

    private $prospectDAO;
    private $userDAO;

    public function setProspectDAO($prospectDAO) {
        $this->prospectDAO = $prospectDAO;
    }

    public function setUserDAO($userDAO) {
        $this->userDAO = $userDAO;
    }

    public function find($id) {
        $sql = "select * from tache where id = ?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));
        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("Pas de tache trouver" . $id);
    }

    public function findAll() {
        $sql = "select * from tache order by id desc";
        $result = $this->getDb()->fetchAll($sql);

        $taches = array();
        foreach ($result as $row) {
            $tachesID = $row['id'];
            $taches[$tachesID] = $this->buildDomainObject($row);
        }
        return $taches;
    }


    public function findAllByUser($userID) {
        $user = $this->userDAO->find($userID);

        $sql = "SELECT id, date, dateRappel, dateFin, objet, description, isComplete FROM tache WHERE user_id = ? ORDER BY id desc";
        $result = $this->getDb()->fetchAll($sql, [$userID]);

        $taches = [];
        foreach ($result as $row) {
            $tache = $this->buildDomainObject($row);
            $tache->setUser($user);
            $taches[$row['id']] = $tache;
        }

        return $taches;
    }

    public function findAllByProspect($prospectID) {
        $prospect = $this->prospectDAO->find($prospectID);

        $sql = "SELECT id, date, dateRappel, dateFin, objet, description, isComplete FROM tache WHERE prospect_id = ? AND isComplete = 0 ORDER BY id desc";
        $result = $this->getDb()->fetchAll($sql, [$prospectID]);

        $taches = [];
        foreach ($result as $row) {
            $tache = $this->buildDomainObject($row);
            $tache->setProspect($prospect);
            $taches[$row['id']] = $tache;
        }

        return $taches;
    }

    public function save(Tache $tache) {
        $tacheData = [
            'date' => $tache->getDate()->format('Y-m-d H:i:s'),
            'dateRappel' => $tache->getDateRappel()->format('Y-m-d H:i:s'),
            'dateFin' => $tache->getDateFin()->format('Y-m-d H:i:s'),
            'objet	' => $tache->getObjet(),
            'description' => $tache->getDescription(),
            'isComplete' => $tache->getIsComplete(),
            'prospect_id' => $tache->getProspect()->getId()
        ];

        if ($tache->getId()) {
            $this->getDb()->update('tache', $tacheData, ['id' => $tache->getId()]);
        } else {
            $this->getDb()->insert('tache', $tacheData);
            $id = $this->getDb()->lastInsertId();
            $tache->setId($id);
        }
    }

    public function delete($id) {
        $this->getDb()->delete('tache', ['id' => $id]);
    }

    public function deleteAllByProspect($prospectID) {
        $this->getDb()->delete('tache', ['prospect_id' => $prospectID]);
    }

    protected function buildDomainObject(array $row) {
        $tache = new Tache([
            'id' => $row['id'],
            'objet' => $row['objet'],
            'description' => $row['description'],
            'isComplete' => $row['isComplete'],
            'date' => new \DateTime($row['date']),
            'dateRappel' => new \DateTime($row['dateRappel']),
            'dateFin' => new \DateTime($row['dateFin'])
        ]);

        if ( array_key_exists('prospect_id', $row) ) {
            $prospect = $this->prospectDAO->find($row['prospect_id']);
            $tache->setProspect($prospect);
        }

        if ( array_key_exists('user_id', $row) ) {
            $user = $this->userDAO->find($row['user_id']);
            $tache->setUser($user);
        }

        return $tache;
    }
}