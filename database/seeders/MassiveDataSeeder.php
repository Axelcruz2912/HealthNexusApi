<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MassiveDataSeeder extends Seeder
{
    private $nombres = [
        'María García López','Juan Hernández Martínez','Ana López González','Carlos González Rodríguez',
        'Rosa Martínez Pérez','Miguel Pérez Sánchez','Patricia Sánchez Ramírez','Roberto Ramírez Torres',
        'Laura Torres Flores','Fernando Flores Díaz','Carmen Díaz Cruz','Alejandro Cruz Morales',
        'Sofía Morales Jiménez','Ricardo Jiménez Ruiz','Guadalupe Ruiz Díaz','Eduardo Álvarez Romero',
        'Gabriela Romero Moreno','Arturo Moreno Herrera','Verónica Herrera Álvarez','Daniel Álvarez Castillo',
        'Lucía Castillo Vargas','Andrés Vargas Mendoza','Alejandra Mendoza Torres','Sergio Torres Reyes',
        'Mariana Reyes Ortega','Pablo Ortega Gutiérrez','Daniela Gutiérrez Ruiz','Marco Ruiz Mendoza',
        'Isabel Mendoza Flores','Héctor Flores Vega','Natalia Vega Salazar','Óscar Salazar Delgado',
        'Patricia Delgado Ríos','Francisco Ríos Medina','Lorena Medina Navarro','Gustavo Navarro Espinoza',
        'Silvia Espinoza Vega','Raúl Vega Acosta','Claudia Acosta Lara','Jorge Lara Domínguez',
        'Adriana Domínguez Fuentes','Luis Fuentes Guerrero','Teresa Guerrero Castillo','Enrique Castillo Campos',
        'Rosa Campos Luna','Arturo Luna Peña','Margarita Peña Soto','Alfredo Soto Carrillo',
        'Yolanda Carrillo Aguirre','Manuel Aguirre Bravo','Cecilia Bravo Rangel','Armando Rangel Guzmán',
        'Irma Guzmán Villa','Felipe Villa Ramos','Sandra Ramos Estrada','Mario Estrada Velázquez',
        'Lourdes Velázquez Gallegos','Antonio Gallegos Ríos','Patricia Ríos Cervantes','Rodrigo Cervantes Cardona',
        'Elena Cardona Ibarra','Gustavo Ibarra Pacheco','Martha Pacheco Sandoval','Fernando Sandoval Zapata',
        'Ana Zapata Correa','Roberto Correa Lara','Graciela Lara Montes','Eugenio Montes Gómez',
        'Alicia Gómez Medina','Rafael Medina Sánchez','Carolina Sánchez de la Torre','Iván de la Torre Bravo',
        'Leticia Bravo Valencia','César Valencia Domínguez','Rosa Domínguez Alvarado','Hugo Alvarado Espinosa',
        'Mónica Espinosa Varela','Ernesto Varela Rocha','Karina Rocha Palacios','Alberto Palacios Cabrera',
        'Maribel Cabrera Luna','Salvador Luna Meléndez','Nancy Meléndez Escobar','Gerardo Escobar Rincón',
        'Olga Rincón Villanueva','Cristian Villanueva Zúñiga','Rocío Zúñiga Tapia','Javier Tapia Aguilar',
        'Beatriz Aguilar Burgos','Tomás Burgos Montoya','Angélica Montoya Cárdenas','Santiago Cárdenas Valladares',
        'Gloria Valladares Rico','Emilio Rico Tovar','Silvia Tovar Ponce','Raúl Ponce Salinas'
    ];

    private $sintomas = [
        'Dolor torácico opresivo','Disnea progresiva','Cefalea intensa','Fiebre de 39°C',
        'Dolor abdominal agudo','Vómitos persistentes','Diarrea crónica','Hemorragia nasal',
        'Mareo sincopal','Confusión mental','Debilidad generalizada','Dolor lumbar',
        'Tos productiva','Palpitaciones','Edema bilateral','Pérdida de conciencia',
        'Convulsiones','Dolor articular','Erupción cutánea','Disuria','Hematuria',
        'Polifagia','Polidipsia','Poliuria','Pérdida de peso','Insomnio crónico',
        'Ansiedad severa','Trauma craneoencefálico','Fractura expuesta','Reacción alérgica',
        'Dolor precordial','Taquicardia','Parestesias','Odinofagia','Epistaxis',
        'Hemoptisis','Melena','Retención urinaria','Crisis hipertensiva','Hipotensión'
    ];

    private $diagnosticos = [
        'Neumonía adquirida en comunidad'=>'J18.9','Infarto agudo del miocardio'=>'I21.9',
        'Crisis hipertensiva'=>'I16.9','Diabetes mellitus tipo 2 descompensada'=>'E11.65',
        'Apéndice agudo'=>'K35.80','Colecistitis aguda'=>'K81.0','Pancreatitis aguda'=>'K85.9',
        'Cetoacidosis diabética'=>'E10.10','EPOC agudizado'=>'J44.1','Asma bronquial aguda'=>'J45.90',
        'Insuficiencia cardíaca congestiva'=>'I50.9','Fibrilación auricular'=>'I48.91',
        'Trombosis venosa profunda'=>'I82.409','Embolia pulmonar'=>'I26.99',
        'Accidente cerebrovascular'=>'I63.9','Meningitis bacteriana'=>'G00.9','Sepsis'=>'A41.9',
        'Insuficiencia renal aguda'=>'N17.9','Cirrosis hepática descompensada'=>'K74.60',
        'Hemorragia digestiva alta'=>'K92.2','Ulcera péptica perforada'=>'K25.5',
        'Obstrucción intestinal'=>'K56.60','Fractura de cadera'=>'S72.009A',
        'TCE severo'=>'S06.9X9A','Neumotórax'=>'S27.0XXA','Peritonitis'=>'K65.0',
        'Pielonefritis aguda'=>'N10','Dengue hemorrágico'=>'A91','COVID-19 grave'=>'U07.1',
        'Anemia severa'=>'D64.9','Crisis convulsiva'=>'G40.909','Estatus epilépticus'=>'G41.9',
        'Eclampsia'=>'O15.00','Amenaza de aborto'=>'O20.0','Endocarditis infecciosa'=>'I33.0',
        'Pericarditis aguda'=>'I30.9','Miocarditis aguda'=>'I40.9','Gastroenteritis aguda'=>'K52.9',
        'Cistitis aguda'=>'N30.00','Lumbalgia aguda'=>'M54.5','Cefalea tensional'=>'G44.209',
        'Migraña'=>'G43.909','Rinitis alérgica'=>'J30.9','Amigdalitis aguda'=>'J03.90',
        'Otitis media aguda'=>'H66.90','Conjuntivitis aguda'=>'H10.9','Dermatitis'=>'L30.9',
        'Celulitis'=>'L03.90','Absceso cutáneo'=>'L02.9'
    ];

    private $causasMuerte = [
        'Paro cardiorrespiratorio','Choque séptico','Infarto agudo del miocardio',
        'Insuficiencia respiratoria aguda','Accidente cerebrovascular masivo',
        'SDRA','Fallo multiorgánico','Hemorragia intracerebral','Embolia pulmonar masiva',
        'Trauma severo','Neumonía nosocomial','Peritonitis','Sepsis abdominal',
        'Insuficiencia renal terminal','Cirrosis hepática descompensada','Pancreatitis necrotizante'
    ];

    private $hospitales = [
        'Hospital General de México','Hospital Juárez de México','Instituto Nacional de Cardiología',
        'Hospital Ángeles Pedregal','Hospital ABC','Centro Médico La Raza','IMSS T1 Tlatelolco',
        'ISSSTE Centro Médico Nacional','Hospital Español','Hospital Militar Central'
    ];

    public function run()
    {
        $this->command->info('🧹 Limpiando tablas...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('vital_signs')->truncate();
        DB::table('prescriptions')->truncate();
        DB::table('nurse_evolutions')->truncate();
        DB::table('medical_alerts')->truncate();
        DB::table('patient_deaths')->truncate();
        DB::table('derivations')->truncate();
        DB::table('triages')->truncate();
        DB::table('medication_alternatives')->truncate();
        DB::table('medications')->truncate();
        DB::table('beds')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('💊 Creando medicamentos...');
        $this->crearMedicamentos();

        $this->command->info('🛏️ Creando camas...');
        $this->crearCamas();

        $this->command->info('🏥 Creando triages/pacientes...');
        $this->crearPacientes();

        $this->command->info('✅ ¡Completado!');
    }

    private function crearMedicamentos()
    {
        $meds = [
            ['Paracetamol','Acetaminofén','Cuadro Básico','C',5000,500,15.50,'Central',1],
            ['Ibuprofeno','Ibuprofeno','Cuadro Básico','C',3000,300,22.00,'Central',1],
            ['Amoxicilina','Amoxicilina','Generico','C',2000,200,45.00,'Central',1],
            ['Azitromicina','Azitromicina','Patente','C',1500,150,85.00,'Central',1],
            ['Ceftriaxona','Ceftriaxona','Generico','B',800,100,120.00,'Hospitalaria',0],
            ['Amikacina','Amikacina','Generico','B',500,50,95.00,'Hospitalaria',0],
            ['Ciprofloxacino','Ciprofloxacino','Generico','B',2500,250,55.00,'Central',0],
            ['Metronidazol','Metronidazol','Cuadro Básico','C',2000,200,28.00,'Central',1],
            ['Omeprazol','Omeprazol','Generico','C',4000,400,35.00,'Central',1],
            ['Enalapril','Enalapril','Generico','C',2500,250,42.00,'Central',1],
            ['Losartan','Losartan','Patente','C',2000,200,65.00,'Central',1],
            ['Amlodipino','Amlodipino','Generico','C',1800,180,48.00,'Central',1],
            ['Metoprolol','Metoprolol','Generico','B',1500,150,72.00,'Central',0],
            ['Metformina','Metformina','Generico','C',3500,350,25.00,'Central',1],
            ['Insulina Glargina','Insulina','Patente','B',400,50,450.00,'Hospitalaria',0],
            ['Insulina Regular','Insulina','Generico','B',500,50,380.00,'Hospitalaria',0],
            ['Salbutamol','Salbutamol','Cuadro Básico','C',1200,120,95.00,'Central',1],
            ['Prednisona','Prednisona','Cuadro Básico','B',800,80,32.00,'Central',0],
            ['Dexametasona','Dexametasona','Cuadro Básico','B',600,60,55.00,'Hospitalaria',0],
            ['Morfina','Morfina','Controlado','A',200,30,85.00,'Quirófano',0],
            ['Fentanilo','Fentanilo','Controlado','A',150,20,120.00,'Quirófano',0],
            ['Ketamina','Ketamina','Controlado','A',100,15,180.00,'Quirófano',0],
            ['Midazolam','Midazolam','Controlado','A',180,25,65.00,'Quirófano',0],
            ['Diazepam','Diazepam','Controlado','B',300,30,28.00,'Hospitalaria',0],
            ['Heparina','Heparina','Controlado','A',250,30,95.00,'Hospitalaria',0],
            ['Enoxaparina','Enoxaparina','Controlado','A',200,25,250.00,'Hospitalaria',0],
            ['Warfarina','Warfarina','Controlado','A',400,40,55.00,'Central',0],
            ['Alteplasa','Alteplasa','Patente','A',50,10,8500.00,'Hospitalaria',0],
            ['Adrenalina','Epinefrina','Cuadro Básico','A',300,30,45.00,'Urgencias',0],
            ['Noradrenalina','Norepinefrina','Cuadro Básico','A',200,25,68.00,'Urgencias',0],
            ['Amiodarona','Amiodarona','Controlado','A',150,15,95.00,'Urgencias',0],
            ['Atropina','Atropina','Cuadro Básico','A',250,25,35.00,'Urgencias',0],
            ['Naloxona','Naloxona','Cuadro Básico','A',100,15,125.00,'Urgencias',0],
            ['Suero Fisiologico','NaCl 0.9%','Cuadro Básico','C',5000,500,22.00,'Central',1],
            ['Hartmann','Ringer Lactato','Cuadro Básico','C',3000,300,28.00,'Central',1],
            ['Glucosado 5%','Dextrosa 5%','Cuadro Básico','C',2500,250,20.00,'Central',1],
            ['Potasio','KCl','Cuadro Básico','B',800,80,35.00,'Hospitalaria',0],
            ['Calcio','Gluconato Ca','Cuadro Básico','B',400,40,38.00,'Hospitalaria',0],
            ['Bicarbonato','NaHCO3','Cuadro Básico','A',200,20,55.00,'Urgencias',0],
            ['Vitamina K','Fitomenadiona','Cuadro Básico','B',300,30,48.00,'Hospitalaria',0],
            ['Acido Folico','Acido Folico','Cuadro Básico','C',2000,200,12.00,'Central',1],
            ['Omeprazol IV','Omeprazol','Generico','B',600,60,85.00,'Hospitalaria',0],
            ['Pantoprazol','Pantoprazol','Patente','B',500,50,92.00,'Hospitalaria',0],
            ['Ranitidina','Ranitidina','Generico','C',3000,300,18.00,'Central',1],
            ['Cefazolina','Cefazolina','Generico','B',600,60,75.00,'Quirófano',0],
            ['Vancomicina','Vancomicina','Controlado','A',200,25,280.00,'Hospitalaria',0],
            ['Meropenem','Meropenem','Controlado','A',150,20,450.00,'Hospitalaria',0],
            ['Dopamina','Dopamina','Cuadro Básico','A',180,20,52.00,'Urgencias',0],
            ['Dobutamina','Dobutamina','Cuadro Básico','A',150,15,68.00,'Urgencias',0],
            ['Flumazenilo','Flumazenilo','Controlado','A',80,10,180.00,'Urgencias',0],
        ];

        foreach ($meds as $m) {
            DB::table('medications')->insert([
                'name' => $m[0],
                'active_ingredient' => $m[1],
                'type' => $m[2],
                'required_level' => $m[3],
                'stock' => $m[4],
                'min_stock' => $m[5],
                'price' => $m[6],
                'origin' => $m[7],
                'enfermera_can_administer' => $m[8],
            ]);
        }
        $this->command->info('  → ' . count($meds) . ' medicamentos');
    }

    private function crearCamas()
    {
        $tipos = ['General','General','UCI','Quirófano','Pediatría'];
        $estatuses = ['Disponible','Ocupada','Disponible','Disponible','Limpieza'];

        for ($piso = 1; $piso <= 5; $piso++) {
            for ($hab = 1; $hab <= 20; $hab++) {
                for ($cama = 1; $cama <= 2; $cama++) {
                    DB::table('beds')->insert([
                        'floor' => $piso,
                        'room_number' => (string)$hab,
                        'bed_number' => (string)$cama,
                        'type' => $tipos[$piso - 1],
                        'status' => $estatuses[rand(0, count($estatuses) - 1)],
                    ]);
                }
            }
        }
        $this->command->info('  → 200 camas');
    }

    private function crearPacientes()
    {
        $batchSize = 2000;
        $total = 50000;
        $batches = ceil($total / $batchSize);

        $nombres = $this->nombres;
        $sintomas = $this->sintomas;
        $diagKeys = array_keys($this->diagnosticos);
        $diagVals = array_values($this->diagnosticos);
        $niveles = ['Rojo','Naranja','Amarillo','Verde','Azul'];
        $nivelesPeso = [5,15,30,35,15];
        $estados = ['En Espera','En Atención','Hospitalizado','Derivado','Dado de Alta'];
        $areas = ['Urgencias','UCI','Piso','Quirófano','Admisión'];

        $medicos = DB::table('users')->whereIn('role', ['Médico A','Médico B','Médico C'])->pluck('id')->toArray();
        $enfermeras = DB::table('users')->whereIn('role', ['Enfermera A','Enfermera B','Enfermera C'])->pluck('id')->toArray();

        if (empty($medicos)) $medicos = [1];
        if (empty($enfermeras)) $enfermeras = [1];

        for ($b = 0; $b < $batches; $b++) {
            $data = [];
            for ($i = 0; $i < $batchSize; $i++) {
                $rand = rand(1,100); $acum = 0; $nivel = 'Verde';
                foreach ($nivelesPeso as $idx => $p) {
                    $acum += $p;
                    if ($rand <= $acum) { $nivel = $niveles[$idx]; break; }
                }

                $estado = $estados[rand(0,4)];
                $fecha = now()->subDays(rand(0,730));
                $diagIdx = rand(0, count($diagKeys)-1);

                $data[] = [
                    'patient_name' => $nombres[rand(0,count($nombres)-1)],
                    'age' => rand(1,95),
                    'triage_level' => $nivel,
                    'symptoms' => $sintomas[rand(0,count($sintomas)-1)].', '.$sintomas[rand(0,count($sintomas)-1)],
                    'status' => $estado,
                    'assigned_area' => $areas[rand(0,count($areas)-1)],
                    'vitals_ta' => rand(80,160).'/'.rand(50,100),
                    'vitals_fc' => (string)rand(60,120),
                    'vitals_temp' => (string)(rand(360,395)/10),
                    'vitals_spo2' => (string)rand(85,100),
                    'is_derived' => $estado === 'Derivado' ? 1 : 0,
                    'derivation_hospital' => $estado === 'Derivado' ? $this->hospitales[rand(0,count($this->hospitales)-1)] : null,
                    'diagnostico' => rand(0,10)>3 ? $diagKeys[$diagIdx] : null,
                    'cie10' => rand(0,10)>3 ? $diagVals[$diagIdx] : null,
                    'tratamiento' => null,
                    'doctor_notes' => rand(0,10)>5 ? 'Paciente con evolución satisfactoria' : null,
                    'assigned_doctor' => $medicos[rand(0,count($medicos)-1)],
                    'discharge_date' => $estado === 'Dado de Alta' ? $fecha->copy()->addDays(rand(1,10)) : null,
                    'discharge_type' => null,
                    'discharge_doctor_id' => null,
                    'discharge_notes' => null,
                    'created_at' => $fecha,
                    'updated_at' => $fecha,
                ];
            }
            DB::table('triages')->insert($data);
            $this->command->info('  → '.number_format(($b+1)*$batchSize).' triages...');
        }

        // Datos clínicos para los últimos 3000 activos
        $this->command->info('📊 Datos clínicos...');
        $activos = DB::table('triages')->whereIn('status', ['En Atención','Hospitalizado'])->orderBy('id','desc')->limit(3000)->get();
        $medIds = DB::table('medications')->pluck('id')->toArray();
        $bedIds = DB::table('beds')->pluck('id')->toArray();

        foreach ($activos as $p) {
            for ($v = 0; $v < rand(1,4); $v++) {
                DB::table('vital_signs')->insert([
                    'triage_id' => $p->id,
                    'patient_name' => $p->patient_name,
                    'temperature' => rand(360,395)/10,
                    'heart_rate' => rand(60,120),
                    'respiratory_rate' => rand(12,25),
                    'blood_pressure' => rand(80,160).'/'.rand(50,100),
                    'oxygen_saturation' => rand(85,100),
                    'weight' => rand(500,1200)/10,
                    'height' => rand(140,200),
                    'glucose' => rand(70,200),
                    'notes' => null,
                    'recorded_by' => $enfermeras[rand(0,count($enfermeras)-1)],
                    'is_critical' => 0,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->created_at,
                ]);
            }

            if (rand(0,10)>4 && !empty($medIds)) {
                for ($r = 0; $r < rand(1,3); $r++) {
                    DB::table('prescriptions')->insert([
                        'patient_id' => $p->id,
                        'doctor_id' => $p->assigned_doctor ?? $medicos[0],
                        'medication_id' => $medIds[rand(0,count($medIds)-1)],
                        'quantity' => rand(1,30),
                        'status' => ['Pendiente','Autorizada','Denegada','Surtida'][rand(0,3)],
                        'denial_reason' => null,
                        'is_priority' => rand(0,1),
                        'created_at' => $p->created_at,
                        'updated_at' => $p->created_at,
                    ]);
                }
            }

            DB::table('nurse_evolutions')->insert([
                'triage_id' => $p->id,
                'patient_name' => $p->patient_name,
                'nurse_id' => $enfermeras[rand(0,count($enfermeras)-1)],
                'observation' => 'Paciente '.['estable','mejorado','sin cambios'][rand(0,2)].'. Se continúa tratamiento.',
                'intervention' => 'Administración de medicamento',
                'response' => 'Sin reacción adversa',
                'priority' => ['Normal','Urgente','Crítica'][rand(0,2)],
                'created_at' => $p->created_at,
                'updated_at' => $p->created_at,
            ]);

            if ($p->triage_level === 'Rojo') {
                DB::table('medical_alerts')->insert([
                    'vital_sign_id' => null,
                    'triage_id' => $p->id,
                    'patient_name' => $p->patient_name,
                    'type' => 'Crítico',
                    'category' => 'Signos Vitales',
                    'message' => $p->patient_name.': '.$p->symptoms,
                    'is_read' => rand(0,1),
                    'triggered_by' => $enfermeras[rand(0,count($enfermeras)-1)],
                    'target_user_id' => $medicos[rand(0,count($medicos)-1)],
                    'created_at' => $p->created_at,
                    'updated_at' => $p->created_at,
                ]);
            }
        }

        // Defunciones
        $this->command->info('⚰️ Defunciones...');
        $fallecidos = DB::table('triages')->where('status', 'Dado de Alta')->limit(1000)->get();
        $certN = DB::table('patient_deaths')->count() + 1;
        foreach ($fallecidos as $f) {
            DB::table('patient_deaths')->insert([
                'triage_id' => $f->id,
                'doctor_id' => $f->assigned_doctor ?? $medicos[0],
                'bed_id' => !empty($bedIds) ? $bedIds[rand(0,count($bedIds)-1)] : 1,
                'death_time' => $f->created_at,
                'cause_of_death' => $this->causasMuerte[rand(0,count($this->causasMuerte)-1)],
                'immediate_cause' => rand(0,10)>6 ? $this->causasMuerte[rand(0,count($this->causasMuerte)-1)] : null,
                'clinical_summary' => 'Evolución tórpida a pesar de tratamiento.',
                'autopsy_required' => rand(0,10)>8 ? 1 : 0,
                'death_certificate_number' => 'DEF-'.date('Y').'-'.str_pad($certN++,4,'0',STR_PAD_LEFT),
                'notified_family' => ['Sí','No','Pendiente'][rand(0,2)],
                'notes' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Derivaciones
        $this->command->info('🚑 Derivaciones...');
        $derivados = DB::table('triages')->where('status', 'Derivado')->limit(500)->get();
        foreach ($derivados as $d) {
            DB::table('derivations')->insert([
                'triage_id' => $d->id,
                'doctor_id' => $d->assigned_doctor ?? $medicos[0],
                'hospital_destino' => $this->hospitales[rand(0,count($this->hospitales)-1)],
                'motivo' => 'Falta de recursos',
                'status' => 'Pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Todo listo!');
    }
}
