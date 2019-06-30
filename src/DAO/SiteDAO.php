<?php

namespace CRM\DAO;


use CRM\Domain\Site;

class SiteDAO extends DAO {

    public function find($id) {
        $sql = "select * from site where id= ?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));
        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("Pas d'entrée trouver" . $id);
    }

    public function findAll() {
        $sql = "select * from site order by id desc";
        $result = $this->getDb()->fetchAll($sql);

        $sites = array();
        foreach ($result as $row) {
            $siteID = $row['id'];
            $sites[$siteID] = $this->buildDomainObject($row);
        }
        return $sites;

    }

    public function save(Site $site) {
        $siteData = ['nom' => $site->getNom()];

        if ($site->getId()) {
            $this->getDb()->update('site', $siteData, ['id' => $site->getId()]);
        } else {
            $this->getDb()->insert('site', $siteData);
            $id = $this->getDb()->lastInsertId();
            $site->setId($id);
        }

    }

    public function delete($id) {
        $this->getDb()->delete('site', ['id' => $id]);
    }

    protected function buildDomainObject(array $row) {
        $site = new Site([
            'id' => $row['id'],
            'nom' => $row['nom']
        ]);
        return $site;
    }
}