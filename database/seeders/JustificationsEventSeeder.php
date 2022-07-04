<?php

namespace Database\Seeders;

use App\Models\JustificationEvent;
use Illuminate\Database\Seeder;

class JustificationsEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $justifications_events = [
            'Vacaciones' => 'Se restará un día de vacaciones del empleado y se agregará un 25% más de tiempo como parte proporcional de prima vacacional. Si no cuanta con mínimo 1 día, no se marcará como vacaciones.',
            'Falta justificada'=> 'Si justifica la falta, no se verán afectados los bonos del empleado, a excepción del bono de productividad',
            'Incapacidad'=> 'Se cubrirá el sesenta por ciento del día. Asegúrese de recibir una constancia que acredite la incapacidad',
        ];

        foreach ($justifications_events as $justification_event => $description ) {
            JustificationEvent::create([
                'name' => $justification_event,
                'description' => $description,
            ]);
        }
    }
}
