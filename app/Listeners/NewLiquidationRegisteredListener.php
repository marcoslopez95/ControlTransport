<?php

namespace App\Listeners;

use App\Events\NewLiquidationRegisteredEvent;
use App\Models\Travel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class NewLiquidationRegisteredListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NewLiquidationRegisteredEvent $event)
    {
        $liquidation = $event->liquidation;
        $type_travel = $event->type_travel;
        Log::info('liquidacion: ' . json_encode($liquidation->toArray()));
        Log::info('type_travel: ' . $type_travel);

        $travel = Travel::where('status','En Viaje')
                    ->where('vehicle_id',$liquidation->vehicle_id)
                    ->first();
        if(!$travel){
            $travel = Travel::create([
                'date_start' => $liquidation->date,
                'vehicle_id' => $liquidation->vehicle_id
            ]);
        }
        if($type_travel == 'Llegada'){
            $travel->status = 'Finalizado';
            $travel->save();
        }
        $liquidation->travel_id = $travel->id;
        $liquidation->save();
    }
}
