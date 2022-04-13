<?php

// espace de nom / dossier virtuelle
namespace App\Service;

class TvaService {

    public function calcul(int $prix) : float {
        $calcul=$prix*1.2;
        return $calcul;
    }

}

?>