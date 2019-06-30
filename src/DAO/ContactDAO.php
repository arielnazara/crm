<?php
namespace CRM\DAO;


use CRM\Domain\Contact;

class ContactDAO extends DAO {

    private $entrepriseDAO;

    public function setEntrepriseDAO(EntrepriseDAO $entrepriseDAO) {
        $this->entrepriseDAO = $entrepriseDAO;
    }

    public function find($id) {
        $sql = "select * from contact where id = ?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));
        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("Pas de contact trouver" . $id);
    }

    public function findAll() {
        $sql = "select * from contact order by id desc";
        $result = $this->getDb()->fetchAll($sql);

        $contacts = array();
        foreach ($result as $row) {
            $contactID = $row['id'];
            $contacts[$contactID] = $this->buildDomainObject($row);
        }
        return $contacts;

    }

    public function save(Contact $contact) {
        $contactData = [
            'civilite' => $contact->getCivilite(),
            'nom' => $contact->getNom(),
            'prenom' => $contact->getPrenom(),
            'fonction' => $contact->getFonction(),
            'telephone' => $contact->getTelephone(),
            'email' => $contact->getEmail(),
            'isDefault' => $contact->getIsDefault(),
            'entreprise_id' => $contact->getEntreprise()->getId()
        ];

        if ($contact->getId()) {
            $this->getDb()->update('contact', $contactData, ['id' => $contact->getId()]);
        } else {
            $this->getDb()->insert('contact', $contactData);
            $id = $this->getDb()->lastInsertId();
            $contact->setId($id);
        }
    }

    public function delete($id) {
        $this->getDb()->delete('contact', ['id' => $id]);
    }

    public function deleteAllByEntreprise($entrepriseID) {
        $this->getDb()->delete('contact', ['entreprise_id' => $entrepriseID]);
    }

    protected function buildDomainObject(array $row) {
        $contact = new Contact([
            'id' => $row['id'],
            'civilite' => $row['civilite'],
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'fonction' => $row['fonction'],
            'telephone' => $row['telephone'],
            'email' => $row['email'],
            'isDefault' => (bool) $row['isDefault']
        ]);

        if ( array_key_exists('entreprise_id', $row) && $row['entreprise_id'] != null ) {
            $entreprise = $this->entrepriseDAO->find($row['entreprise_id']);
            $contact->setEntreprise($entreprise);
        }

        return $contact;
    }
}