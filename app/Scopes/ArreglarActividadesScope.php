<?php


namespace App\Scopes;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ArreglarActividadesScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {

        $builder->select([
            'clients.*',
            'tipo_cliente.nombre as nombre_tipo_cliente',
            'activities.name as activity',
            'activities.id as id_activity',
            'sub_activity.id as id_sub_activity',
            'sub_activity.name as sub_activity',
            'provincia.nombre as nombre_provincia',
            'sectors.sector as nombre_sector',
            DB::raw('row_number() OVER (order by clients.commerce_name)')
        ])
            ->leftjoin('activities', 'activities.id', DB::raw('ANY(clients.activity)'))
            ->leftjoin('sub_activity', 'sub_activity.id', DB::raw('ANY(clients.sub_activity)'))
            ->leftjoin('tipo_cliente', 'tipo_cliente.id', 'clients.id_tipo_cliente')
            ->leftjoin('provincia', 'provincia.id', 'clients.fk_provinc')
            ->leftjoin('sectors', 'sectors.id', 'clients.fk_sector');
    }

    /**
     * Para convertir el string en array
     */
    private function arrayActividades($actividades)
    {

        $reemplazo = ltrim($actividades, '{');
        $reemplazo = rtrim($reemplazo, '}');
        $reemplazo = explode(',', $reemplazo);

        return $reemplazo;
    }

    private function reemplazarActividades($client)
    {
        $reemplazo = $this->arrayActividades($client->activity);
        if (count($reemplazo) >= 1) {
            $actividades = [];
            foreach ($reemplazo as $k) {
                if ($k != null && $k != '') {
                    $m = Activity::find($k);
                    $actividades[] = $m->name;
                }
            }
            $client->activity = implode(',', $actividades);
        } else {
            $client->activity = '';
        }
        return $client;
    }
}
