<?php

namespace App\Enums;

enum CancellationReason: string
{
    case Price = 'price';
    case MissingFeatures = 'missing_features';
    case ClosedBusiness = 'closed_business';
    case Competitor = 'competitor';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Price => __('enums.cancellation_reason.price'),
            self::MissingFeatures => __('enums.cancellation_reason.missing_features'),
            self::ClosedBusiness => __('enums.cancellation_reason.closed_business'),
            self::Competitor => __('enums.cancellation_reason.competitor'),
            self::Other => __('enums.cancellation_reason.other'),
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
