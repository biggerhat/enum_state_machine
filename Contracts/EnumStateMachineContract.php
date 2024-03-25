<?php

namespace Contracts;

interface EnumStateMachineContract {
    public static function getTransitions(): array;

    public static function getTransitionEvents(): array;

    public static function getDefaultState(): self;

    public static function getFinalStates(): array;
}
