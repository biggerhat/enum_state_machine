<?php

namespace App\Traits;

use Exceptions\InFinalStateException;
use Exceptions\NonExistentStateException;
use Exceptions\NoTransitionsException;
use Exceptions\UnableToTransitionException;
use Contracts\EnumStateMachineContract;

trait HasStateMachine {
    public function bootHasStateMachine(): void {
        $field = $this->stateMachineField;
        $stateEnum = $this->casts[$field];

        static::creating(function (self $model) use ($field, $stateEnum) {
            $model->{$field} = $stateEnum::getDefaultState();
        });

        static::updating(function (self $model) use ($field, $stateEnum) {
            if ($model->isDirty($field)) {
                $newStatus = $model->{$field};
                if (array_key_exists($newStatus->value, $stateEnum::getTransitionEvents())) {
                    //Events will need a consistent set of parameters to ensure no issues
                    //This changes if you want multiple events or just one but:
                    $stateEnum::getTransitionEvents()[$newStatus->value]::dispatch();
                }
            }
        });
    }

    /**
     * @throws NonExistentStateException
     * @throws NoTransitionsException
     * @throws UnableToTransitionException
     * @throws InFinalStateException
     */
    public function transitionTo(EnumStateMachineContract|string $transitionTo): self {
        $field = $this->stateMachineField;

        $stateEnum = $this->casts[$field];
        $transitionToEnum = $transitionTo;

        if (is_string($transitionToEnum)) {
            $transitionToEnum = $stateEnum::tryFrom($transitionTo);
        }

        if (! $transitionToEnum) {
            throw new NonExistentStateException();
        }

        if (in_array($this->{$field}, $stateEnum::getFinalStates())) {
            throw new InFinalStateException();
        }

        if (! array_key_exists($this->{$field}->value, $stateEnum::getTransitions())) {
            throw new NoTransitionsException();
        }

        $transitions = $stateEnum::getTransitions()[$this->{$field}->value];

        if (! $transitions) {
            throw new NoTransitionsException();
        }

        if (in_array($transitionToEnum, $transitions)) {
            $this->{$field} = $transitionToEnum;
        } else {
            throw new UnableToTransitionException();
        }

        return $this;
    }
}
