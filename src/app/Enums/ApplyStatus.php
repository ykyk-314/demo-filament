<?php
declare(strict_types=1);

namespace App\Enums;

enum ApplyStatus: int
{
    case New           = 0;
    case Interviewed   = 1;
    case Inreview      = 2;
    case Passed        = 3;
    case Failed        = 4;

    public function label(): string
    {
        return match ($this) {
            self::New => '新規受付',
            self::Interviewed => '面接案内済み',
            self::Inreview => '審査中',
            self::Passed => '合格',
            self::Failed => '不合格',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::New => '#3490dc',
            self::Interviewed => '#38c172',
            self::Inreview => '#ffed4a',
            self::Passed => '#f6993f',
            self::Failed => '#e3342f',
        };
    }

    public static function options(array $except = [], string $access = 'label'): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            if (in_array($case->value, $except)) continue;
            $options[$case->value] = $case->$access();
        }
        return $options;
    }
}