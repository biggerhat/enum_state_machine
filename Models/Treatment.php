<?php

namespace Models;

use App\Traits\HasStateMachine;
use Enums\TreatmentStatusEnum;

class Treatment extends Model {
    use HasStateMachine;

    protected $guarded = [];

    protected string $stateMachineField = "status";

    protected $casts = [
        "status" => TreatmentStatusEnum::class,
    ];
}
