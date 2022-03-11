<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class GeneratedPins extends Model
{
    protected $fillable = [
        'name',
        'email',
        'pin'
    ];

    /**
     * @return int
     */
    public static function generatePIN(): int
    {
        try {
            $pin = random_int(pow(10, 4 - 1), pow(10, 4) - 1);

            while (true) {
                if (static::validatePin($pin)) {
                    return $pin;
                } else {
                    $pin = random_int(pow(10, 4 - 1), pow(10, 4) - 1);
                }
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * validate PIN non sequential
     * @param $pin
     * @return bool
     */
    private function validatePin($pin): bool
    {
        $parts = str_split($pin);

        $previousPart = null;

        foreach ($parts as $part) {
            if (!$previousPart) {
                $previousPart = $part;
                continue;
            }

            if ($part === $previousPart) {
                return false;
            }

            if ($part === $previousPart + 1) {
                return false;
            }

            $previousPart = $part;
        }

        return true;
    }
}
