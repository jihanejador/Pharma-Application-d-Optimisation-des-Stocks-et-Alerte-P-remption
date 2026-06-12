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

    public static function from($value): self {
        if ($value instanceof self) {
            return $value;
        }
        $upperValue = is_string($value) ? strtoupper($value) : 'EXPIRED';
        if ($upperValue === self::ACTIF) {
            return new self(self::ACTIF);
        }
        return new self(self::EXPIRED);
    }
}