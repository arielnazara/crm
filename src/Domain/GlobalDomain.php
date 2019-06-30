<?php

namespace CRM\Domain;


use CRM\Service\Hydrator;

abstract class GlobalDomain {
    use Hydrator;

    public function __construct(array $data = []) {
        if ( !empty($data) ) {
            $this->hydrate($data);
        }
    }
}