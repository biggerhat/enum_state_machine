<?php

namespace Enums;

use Contracts\EnumStateMachineContract;

enum TreatmentStatusEnum: string implements EnumStateMachineContract {
    case Arrived = "waiting";
    case Removed = "removed";
    case Seeing = "seeing";
    case Temp = "temp";
    case Complete = "complete";
    case Locked = "locked";


    public static function getTransitions(): array {
        return [
            self::Arrived->value => [self::Temp],
            self::Temp->value => [self::Seeing],
            self::Seeing->value => [self::Complete],
        ];
    }

    public static function getTransitionEvents(): array {
        return [];
    }

    public static function getDefaultState(): EnumStateMachineContract {
        return self::Arrived;
    }

    public static function getFinalStates(): array {
        return [
            self::Complete,
            self::Removed,
        ];
    }
}
