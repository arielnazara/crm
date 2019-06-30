<?php

namespace CRM\DAO;


use CRM\Domain\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserDAO extends DAO implements UserProviderInterface {

    private $siteDAO;

    public function setSiteDAO(SiteDAO $siteDAO) {
        $this->siteDAO = $siteDAO;
    }

    public function find($id) {
        $row = $this->getDb()->fetchAssoc("select * from user where id = ? ", [$id]);

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception(' No user matching');
    }

    public function findAll() {
        $sql = 'select * from user order by id desc';
        $result = $this->getDb()->fetchAll($sql);

        $users = [];
        foreach ($result as $row) {
            $users[$row['id']] = $this->buildDomainObject($row);
        }

        return $users;
    }

    public function save(User $user) {
        $userData = array(
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'username' => $user->getUsername(),
            'salt' => $user->getSalt(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
            'email' => $user->getEmail(),
            'site_id' => $user->getSite()->getId()
        );

        if ($user->getId()) {
            $this->getDb()->update('user', $userData, array('id' => $user->getId()));
        } else {
            $this->getDb()->insert('user', $userData);
            $id = $this->getDb()->lastInsertId();
            $user->setId($id);
        }
    }

    public function delete($id) {
        $this->getDb()->delete('user', array('id' => $id));
    }

    public function deleteAllBySite($siteID) {
        $this->getDb()->delete('user', array('site_id' => $siteID));
    }

    protected function buildDomainObject(array $row) {
        $user = new User([
            'id' => $row['id'],
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'username' => $row['username'],
            'password' => $row['password'],
            'salt' => $row['salt'],
            'role' => $row['role'],
            'email' => $row['email']
        ]);

        if ( array_key_exists('site_id', $row) ) {
            $site = $this->siteDAO->find($row['site_id']);
            $user->setSite($site);
        }

        return $user;
    }

    public function loadUserByUsername($username) {
        $sql = "select * from user where username = ?";
        $row = $this->getDb()->fetchAssoc($sql, [$username]);

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
    }

    public function refreshUser(UserInterface $user) {
        $class = get_class($user);
        if ( !$this->supportsClass($class) ) {
            throw new UnsupportedUserException(sprintf('Instance of "%s" are not supported.', $class));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class) {
        return "CRM\Domain\User" === $class;
    }
}