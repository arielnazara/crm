<?php
/**
 * Created by PhpStorm.
 * User: ariel
 * Date: 07/06/2018
 * Time: 18:06
 */

namespace CRM\DAO;


use CRM\Domain\Consigne;

class ConsigneDAO extends DAO {

    private $prospectDAO;

    public function setProspectDAO($prospectDAO) {
        $this->prospectDAO = $prospectDAO;
    }

    public function find($id) {
        $sql = "select * from consigne where id = ?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));
        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("Pas de consigne trouver" . $id);
    }

    public function findAll() {
        $sql = "select * from consigne order by id desc";
        $result = $this->getDb()->fetchAll($sql);

        $consignes = array();
        foreach ($result as $row) {
            $consigneID = $row['id'];
            $consignes[$consigneID] = $this->buildDomainObject($row);
        }
        return $consignes;
    }

    public function findAllByProspect($prospectID) {
        $prospect = $this->prospectDAO->find($prospectID);

        $sql = "SELECT id, date, commentaire FROM consigne WHERE prospect_id = ? ORDER BY id desc";
        $result = $this->getDb()->fetchAll($sql, [$prospectID]);

        $consignes = [];
        foreach ($result as $row) {
            $consigne = $this->buildDomainObject($row);
            $consigne->setProspect($prospect);
            $consignes[$row['id']] = $consigne;
        }

        return $consignes;
    }

    public function save(Consigne $consigne) {
        $consigneData = [
            'date' => $consigne->getDate()->format('Y-m-d H:i:s'),
            'commentaire' => $consigne->getCommentaire(),
            'prospect_id' => $consigne->getProspect()->getId()
        ];

        if ($consigne->getId()) {
            $this->getDb()->update('consigne', $consigneData, ['id' => $consigne->getId()]);
        } else {
            $this->getDb()->insert('consigne', $consigneData);
            $id = $this->getDb()->lastInsertId();
            $consigne->setId($id);
        }
    }

    public function delete($id) {
        $this->getDb()->delete('consigne', ['id' => $id]);
    }

    public function deleteAllByProspect($prospectID) {
        $this->getDb()->delete('consigne', ['prospect_id' => $prospectID]);
    }

    protected function buildDomainObject(array $row) {
        $consigne = new Consigne([
            'id' => $row['id'],
            'date' =>  new \DateTime($row['date']),
            'commentaire' => $row['commentaire']
        ]);

        if ( array_key_exists('prospect_id', $row) ) {
            $prospect = $this->prospectDAO->find('prospect_id');
            $consigne->setProspect($prospect);
        }

        return $consigne;
    }
}