<?php

namespace App\Enum;

enum HotelBoardBasis: string
{
    case RoomOnly = 'room_only';
    case BedAndBreakfast = 'bed_and_breakfast';
    case HalfBoard = 'half_board';
    case FullBoard = 'full_board';
    case AllInclusive = 'all_inclusive';

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return [
            self::RoomOnly->value => 'Room only',
            self::BedAndBreakfast->value => 'Bed & breakfast',
            self::HalfBoard->value => 'Half board',
            self::FullBoard->value => 'Full board',
            self::AllInclusive->value => 'All inclusive',
        ];
    }

    public function label(): string
    {
        return __("hotels.board_basis.{$this->value}");
    }
}
