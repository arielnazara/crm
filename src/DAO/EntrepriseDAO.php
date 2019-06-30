<?php

namespace CRM\DAO;

use CRM\Domain\Entreprise;

class EntrepriseDAO extends DAO {

    public function find($id) {
        $sql = "select * from entreprise where id = ?";
        $row = $this->getDb()->fetchAssoc($sql, [$id]);
        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("Pas d'entreprise trouver" . $id);
    }

    public function findAll() {
        $sql = "select * from entreprise order by id desc";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $entreprises = array();
        foreach ($result as $row) {
            $entrepriseID = $row['id'];
            $entreprises[$entrepriseID] = $this->buildDomainObject($row);
        }
        return $entreprises;

    }

    public function save(Entreprise $entreprise) {
        $entrepriseData = [
            'raisonSocial' => $entreprise->getRaisonSocial(),
            'nomCommercial' => $entreprise->getNomCommercial(),
            'siret' => $entreprise->getSiret(),
            'rue' => $entreprise->getRue(),
            'adresse' => $entreprise->getAddresse(),
            'codePostal' => $entreprise->getCodePostal(),
            'ville' => $entreprise->getVille(),
            'pays' => $entreprise->getPays(),
            'email' => $entreprise->getEmail()
        ];

        if ($entreprise->getId()) {
            $this->getDb()->update('entreprise', $entrepriseData, ['id' => $entreprise->getId()]);
        } else {
            $this->getDb()->insert('entreprise', $entrepriseData);
            $id = $this->getDb()->lastInsertId();
            $entreprise->setId($id);
        }
    }

    /**
     * @param $id
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    public function delete($id) {
        $this->getDb()->delete('entreprise', ['id' => $id]);
    }

    protected function buildDomainObject(array $row) {
        $entreprise = new Entreprise([
            'id' => $row['id'],
            'raisonSocial' => $row['raisonSocial'],
            'nomCommercial' => $row['nomCommercial'],
            'siret' => $row['siret'],
            'rue' => $row['rue'],
            'addresse' => $row['adresse'],
            'codePostal' => $row['codePostal'],
            'ville' => $row['ville'],
            'pays' => $row['pays'],
            'email' => $row['email']

        ]);

        return $entreprise;
    }
}