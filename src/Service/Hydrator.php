<?php

namespace CRM\Service;


trait Hydrator {
    public function hydrate( array $data = [] ) {
        foreach ( $data as $key => $value ) {
            $func = 'set'.ucfirst($key);
            if ( is_callable([$this, $func]) ) {
                $this->$func($value);
            }
        }
    }
}