<?php

namespace Subscribers;

class TreatmentSubscriber {
    public function __construct(): void {
    }

    public function subscribe(Dispatcher $events) {
        $events->listen(TreatmentComplete::class, function (TreatmentComplete $event) {
            //Whatever logic needs to be done for this state change.
        });
    }
}
