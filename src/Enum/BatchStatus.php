<?php
declare(strict_types=1);

namespace PharmaApp\Enum;

enum BatchStatus: string{
    case ACTIF = 'ACTIF';
    case ALERTE = 'ALERTE';
    case EXPIRED = 'EXPIRED';
    case RETOUR = 'RETOUR';
}