<?php
declare(strict_types=1);

namespace PharmaApp\Enum;


class BatchStatus {
    public const ACTIF = 'ACTIF';
    public const EXPIRED = 'EXPIRED';

    public string $value;

    private function __construct(string $value) {
        $this->value = $value;
    }

    public static function from(string $value): self {
        $upperValue = strtoupper($value);
        if ($upperValue === self::ACTIF || $upperValue === 'ACTIF') {
            return new self(self::ACTIF);
        }
        return new self(self::EXPIRED);
    }
}