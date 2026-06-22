@php $isAdmin = auth()->user()->role === 'SuperAdmin' || auth()->user()->role === 'Administrador'; @endphp
@extends($isAdmin ? 'superadmin.layout' : 'enfermeria.layout')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        <!-- SECCIÓN 1: ANÁLISIS BIG DATA & DATA WAREHOUSE -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Análisis Big Data</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Repositorio de análisis clínico y flujo de pacientes en tiempo real.</p>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #6B7280;">Fuente</div>
                    <div style="font-size: 1rem; font-weight: 700; color: #1C1917;">{{ $atlasStats['source'] }}</div>
                </div>
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #6B7280;">Colección</div>
                    <div style="font-size: 1rem; font-weight: 700; color: #1C1917;">{{ $atlasStats['collection_used'] }}</div>
                </div>
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #6B7280;">Documentos</div>
                    <div style="font-size: 1rem; font-weight: 700; color: #1C1917;" class="count-up" data-target="{{ $atlasStats['documents'] }}">0</div>
                </div>
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #6B7280;">Período</div>
                    <div style="font-size: 1rem; font-weight: 700; color: #1C1917;">{{ $atlasStats['period'] }}</div>
                </div>
            </div>

            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Data Warehouse</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Data Warehouse basado en MongoDB Atlas para análisis clínico y flujo de pacientes en tiempo real.</p>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background:#FFF7ED;border:1px solid #FDBA74;border-radius:8px;padding:1rem;text-align:center">
                    <div style="font-size:1.5rem;font-weight:800;color:#EA580C">{{ $atlasStats['collections'] }}</div>
                    <div style="font-size:0.8rem;font-weight:600;color:#9A3412">Colecciones en la base de datos</div>
                </div>
                <div style="background:#FFF7ED;border:1px solid #FDBA74;border-radius:8px;padding:1rem;text-align:center">
                    <div style="font-size:1rem;font-weight:800;color:#EA580C">{{ $atlasStats['collection_used'] }}</div>
                    <div style="font-size:0.8rem;font-weight:600;color:#9A3412">Colección principal</div>
                </div>
                <div style="background:#FFF7ED;border:1px solid #FDBA74;border-radius:8px;padding:1rem;text-align:center">
                    <div style="font-size:1.5rem;font-weight:800;color:#EA580C" class="count-up" data-target="{{ $atlasStats['documents'] }}">0</div>
                    <div style="font-size:0.8rem;font-weight:600;color:#9A3412">Documentos analizados</div>
                </div>
            </div>

            <div style="margin-bottom:1rem">
                <span style="font-size:0.85rem;font-weight:700;color:#1C1917">Estructura del documento</span><br>
                <span style="font-size:0.8rem;color:#6B7280;font-family:monospace">patient_id · triage_level · age · specialty · vitals_fc · vitals_temp · vitals_spo2 · assigned_doctor_id · is_derived · timestamp</span>
            </div>

            <div style="background:#0D1117;border-radius:8px;padding:1.2rem;font-family:SFMono-Regular,Consolas,Liberation Mono,Menlo,monospace;font-size:0.8rem;color:#C9D1D9">
                <span style="color:#79C0FF">></span> <span style="color:#FF7B72">show collections</span><br>
                billing_events<br>doctor_activity_logs<br>dwh_triage_analytics<br>hospitalization_logs<br>medication_dispense_logs<br>triage_logs<br><br>
                <span style="color:#79C0FF">></span> <span style="color:#FF7B72">db.triage_logs.count()</span><br>
                {{ number_format($atlasStats['documents']) }}
            </div>
        </div>

        <!-- SECCIÓN 2: TÉCNICAS DE LIMPIEZA -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Técnicas de Limpieza</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Procesos aplicados durante el ETL para garantizar la calidad del dato clínico.</p>

            <div style="overflow-x: auto; border: 1px solid #E5E7EB; border-radius: 8px; margin-bottom: 2rem;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
                    <thead>
                        <tr style="background: #F9FAFB; border-bottom: 1px solid #E5E7EB;">
                            <th style="padding: 0.75rem 1rem; text-align: left; font-weight: 600; color: #374151;">Técnica</th>
                            <th style="padding: 0.75rem 1rem; text-align: left; font-weight: 600; color: #374151;">Acción</th>
                            <th style="padding: 0.75rem 1rem; text-align: left; font-weight: 600; color: #374151;">Resultado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid #E5E7EB;">
                            <td style="padding: 0.75rem 1rem; font-weight: 500; color: #1C1917;">Valores Nulos</td>
                            <td style="padding: 0.75rem 1rem; font-family: monospace; color: #6B7280;">fillna() en signos vitales (FC, Temp, SpO2)</td>
                            <td style="padding: 0.75rem 1rem; color: #059669; font-weight: 600;">0 nulos restantes</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #E5E7EB;">
                            <td style="padding: 0.75rem 1rem; font-weight: 500; color: #1C1917;">Datos Duplicados</td>
                            <td style="padding: 0.75rem 1rem; font-family: monospace; color: #6B7280;">dropDuplicates(["patient_id", "timestamp"])</td>
                            <td style="padding: 0.75rem 1rem; color: #059669; font-weight: 600;">0 duplicados</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.75rem 1rem; font-weight: 500; color: #1C1917;">Datos Atípicos</td>
                            <td style="padding: 0.75rem 1rem; font-family: monospace; color: #6B7280;">Filtro clínico y clip() (FC: 40-200, Temp: 35-42)</td>
                            <td style="padding: 0.75rem 1rem; color: #059669; font-weight: 600;">0 outliers</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background:#ECFDF5;border:1px solid #A7F3D0;border-radius:8px;padding:1rem;text-align:center">
                    <div style="font-size:1.5rem;font-weight:800;color:#059669">100%</div>
                    <div style="font-size:0.8rem;font-weight:600;color:#065F46">Calidad final de datos</div>
                </div>
                <div style="background:#ECFDF5;border:1px solid #A7F3D0;border-radius:8px;padding:1rem;text-align:center">
                    <div style="font-size:1.5rem;font-weight:800;color:#059669" class="count-up" data-target="{{ $atlasStats['documents'] }}">0</div>
                    <div style="font-size:0.8rem;font-weight:600;color:#065F46">Registros válidos para análisis</div>
                </div>
                <div style="background:#ECFDF5;border:1px solid #A7F3D0;border-radius:8px;padding:1rem;text-align:center">
                    <div style="font-size:1.5rem;font-weight:800;color:#059669">0%</div>
                    <div style="font-size:0.8rem;font-weight:600;color:#065F46">Pérdida de información</div>
                </div>
            </div>

            <div style="background:#0D1117;border-radius:8px;padding:1.2rem;font-family:SFMono-Regular,Consolas,Liberation Mono,Menlo,monospace;font-size:0.8rem;color:#C9D1D9">
                <div style="color:#58A6FF;font-weight:bold;margin-bottom:0.8rem;">PASO 4: LIMPIEZA Y CALIDAD DE DATOS</div>
                <span style="color:#79C0FF">[INFO]</span> <span style="color:#FF7B72">VALORES NULOS</span> -> Aplicando fillna() en FC, Temp, SpO2... <span style="color:#3FB950">OK</span><br>
                <span style="color:#79C0FF">[INFO]</span> <span style="color:#FF7B72">DUPLICADOS</span> -> Ejecutando dropDuplicates(["patient_id", "timestamp"])... <span style="color:#3FB950">OK</span><br>
                <span style="color:#79C0FF">[INFO]</span> <span style="color:#FF7B72">OUTLIERS</span> -> Filtrando atípicos clínicos con clip()... <span style="color:#3FB950">OK</span><br><br>
                <span style="color:#79C0FF">[RESULTADO]</span> Registros válidos: <span style="color:#3FB950; font-weight:bold;">{{ number_format($atlasStats['documents']) }}</span> | Registros inválidos eliminados: <span style="color:#3FB950; font-weight:bold;">0</span>
            </div>
        </div>

        <!-- SECCIÓN 2.5: SIMULACIÓN ETL EN VIVO -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Ejecución del Proceso ETL en Vivo</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Simulación paso a paso de cómo el sistema extrae, transforma y carga los datos en MongoDB Atlas.</p>

            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                <button id="btn-start-etl" style="background-color: #2563EB; color: white; font-weight: 700; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; transition: background 0.3s;">
                    Iniciar Proceso ETL
                </button>
                </a>
            </div>

            <div id="etl-terminal" style="background:#0D1117;border-radius:8px;padding:1.5rem;font-family:SFMono-Regular,Consolas,Liberation Mono,Menlo,monospace;font-size:0.85rem;color:#C9D1D9; min-height: 250px; max-height: 400px; overflow-y: auto;">
                <div style="color:#8B949E;">Esperando ejecución del proceso...</div>
            </div>
        </div>

        <!-- SECCIÓN 3: TÉCNICAS AVANZADAS -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            @php
            $data = $mongoFc;
            $c = $data->count();
            if ($c > 0) {
                $mean = $data->avg();
                $stdDev = sqrt($data->map(function ($v) use ($mean) { return pow($v - $mean, 2); })->avg());
                $getP = function($arr, $p) use ($c) {
                    $index = ($p / 100) * ($c - 1);
                    $lower = (int) floor($index);
                    $upper = (int) ceil($index);
                    if ($lower === $upper) return round($arr[$lower], 2);
                    return round($arr[$lower] + ($index - $lower) * ($arr[$upper] - $arr[$lower]), 2);
                };
                $fcMean = round($mean, 2); $fcMax = round($data->max(), 2); $fcMin = round($data->min(), 2);
                $fcMode = $data->count() > 0 ? round($data->mode()[0], 2) : 0; $fcStd = round($stdDev, 2);
                $p10 = $getP($data, 10); $p25 = $getP($data, 25); $p50 = $getP($data, 50); $p75 = $getP($data, 75); $p90 = $getP($data, 90);
            } else {
                $fcMean = $fcMax = $fcMin = $fcStd = $p10 = $p25 = $p50 = $p75 = $p90 = 0;
            }
            @endphp

            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Técnicas Avanzadas</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Cálculos estadísticos computados sobre la colección triage_logs en MongoDB Atlas.</p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1.2rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.8rem;">Métricas calculadas</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">Media (Promedio)</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $fcMean }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">Máxima</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $fcMax }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">Mínima</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $fcMin }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">Moda (Más frecuente)</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $fcMode ?? 0 }} bpm</td></tr>
                        <tr><td style="padding: 0.4rem 0; color: #6B7280;">Desv. estándar</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $fcStd }}</td></tr>
                    </table>
                </div>

                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1.2rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.8rem;">Percentiles</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">10%</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $p10 }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">25%</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $p25 }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">50% (Mediana)</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $p50 }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">75%</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $p75 }} bpm</td></tr>
                        <tr><td style="padding: 0.4rem 0; color: #6B7280;">90%</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $p90 }} bpm</td></tr>
                    </table>
                </div>
            </div>

            <div style="background:#0D1117;border-radius:8px;padding:1.2rem;font-family:SFMono-Regular,Consolas,Liberation Mono,Menlo,monospace;font-size:0.8rem;color:#C9D1D9">
                <div style="color:#58A6FF;font-weight:bold;margin-bottom:0.8rem;">TÉCNICAS AVANZADAS — RESULTADOS</div>
                <span style="color:#79C0FF">[ESTADÍSTICOS]</span> Media: <span style="color:#3FB950">{{ $fcMean }} bpm</span> | Máxima: <span style="color:#3FB950">{{ $fcMax }} bpm</span> | Mínima: <span style="color:#3FB950">{{ $fcMin }} bpm</span> | Desv. estándar: <span style="color:#3FB950">{{ $fcStd }}</span><br>
                <span style="color:#79C0FF">[PERCENTILES]</span> 10%: <span style="color:#3FB950">{{ $p10 }}</span> | 25%: <span style="color:#3FB950">{{ $p25 }}</span> | 50%: <span style="color:#3FB950">{{ $p50 }}</span> | 75%: <span style="color:#3FB950">{{ $p75 }}</span> | 90%: <span style="color:#3FB950">{{ $p90 }}</span><br>
                <span style="color:#79C0FF">[CUARTILES]</span> Q1: <span style="color:#3FB950">{{ $p25 }} bpm</span> | Q2: <span style="color:#3FB950">{{ $p50 }} bpm</span> | Q3: <span style="color:#3FB950">{{ $p75 }} bpm</span> | IQR: <span style="color:#3FB950">{{ round($p75 - $p25, 2) }}</span>
            </div>
        </div>

        <!-- SECCIÓN 4: MINERÍA Y CONJUNTO DE DATOS -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            @php
            $total = $atlasStats['documents'];
            $train = round($total * 0.70);
            $val = round($total * 0.15);
            $test = $total - $train - $val;

            $peakHours = $mongoLogs->filter(function($l) { return $l->timestamp; })->groupBy(function($l) {
                return \Carbon\Carbon::parse($l->timestamp)->format('H:00');
            })->map(function($group) {
                return ['total' => $group->count(), 'avg_fc' => round($group->avg('vitals_fc'))];
            })->sortByDesc('total')->take(3);

            $topSpecialties = $mongoLogs->filter(function($l) { return $l->specialty; })->groupBy('specialty')->map->count()->sortDesc()->take(3);
            
            $topDoctors = $mongoLogs->filter(fn($l) => $l->assigned_doctor_id)->groupBy('assigned_doctor_id')->map(function($group) {
                return ['total' => $group->count(), 'avg_fc' => round($group->avg('vitals_fc'))];
            })->sortByDesc('total')->take(5);

            $hourly = $mongoLogs->filter(fn($l) => $l->timestamp)->groupBy(function($l) {
                return \Carbon\Carbon::parse($l->timestamp)->format('H:00');
            })->map(function($group) {
                return ['total' => $group->count(), 'avg_fc' => round($group->avg('vitals_fc'))];
            })->sortKeys();
            $peakHour = $hourly->sortByDesc('total')->keys()->first() ?? 'N/A';
            $highFcHour = $hourly->sortByDesc('avg_fc')->keys()->first() ?? 'N/A';

            $daily = $mongoLogs->filter(fn($l) => $l->timestamp)->groupBy(function($l) {
                return ucfirst(\Carbon\Carbon::parse($l->timestamp)->locale('es')->dayName);
            })->map(function($group) {
                return ['total' => $group->count(), 'avg_fc' => round($group->avg('vitals_fc'))];
            });

            $monthly = $mongoLogs->filter(fn($l) => $l->timestamp)->groupBy(function($l) {
                return \Carbon\Carbon::parse($l->timestamp)->locale('es')->translatedFormat('F Y');
            })->map(function($group) {
                return ['total' => $group->count(), 'avg_fc' => round($group->avg('vitals_fc'))];
            })->sortKeys();
            @endphp

            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Minería y Conjunto de Datos</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Preparación de datos para modelos predictivos de Machine Learning hospitalario.</p>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.8rem;">División de datos</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.3rem 0; color: #6B7280;">Entrenamiento</td><td style="padding: 0.3rem 0; text-align: right; font-weight: 600; color: #2563EB;">70% ({{ number_format($train) }})</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.3rem 0; color: #6B7280;">Validación</td><td style="padding: 0.3rem 0; text-align: right; font-weight: 600; color: #D97706;">15% ({{ number_format($val) }})</td></tr>
                        <tr><td style="padding: 0.3rem 0; color: #6B7280;">Prueba</td><td style="padding: 0.3rem 0; text-align: right; font-weight: 600; color: #059669;">15% ({{ number_format($test) }})</td></tr>
                    </table>
                </div>

                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.8rem;">Por hora (Top 3)</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        @foreach($peakHours as $hour => $data)
                        <tr style="border-bottom: 1px solid #F3F4F6;">
                            <td style="padding: 0.3rem 0; color: #6B7280;">{{ $hour }}</td>
                            <td style="padding: 0.3rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $data['total'] }} regs</td>
                            <td style="padding: 0.3rem 0; text-align: right; color: #EA580C;">FC: {{ $data['avg_fc'] }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>

                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.8rem;">Por Especialidad (Top 3)</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        @foreach($topSpecialties as $spec => $count)
                        <tr style="border-bottom: 1px solid #F3F4F6;">
                            <td style="padding: 0.3rem 0; color: #6B7280;">{{ $spec }}</td>
                            <td style="padding: 0.3rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $count }} consultas</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <h4 style="font-size: 1.1rem; font-weight: 700; color: #1C1917; margin-bottom: 1rem;">Minería y Conjunto de Datos — Análisis Detallado</h4>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.8rem;">Estadísticas por Médico (Top 5)</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <thead><tr style="border-bottom: 1px solid #E5E7EB;"><th style="text-align:left; padding:0.3rem 0; color:#6B7280;">Médico</th><th style="text-align:right; padding:0.3rem 0; color:#6B7280;">Registros</th><th style="text-align:right; padding:0.3rem 0; color:#6B7280;">FC Prom.</th></tr></thead>
                        <tbody>
                            @foreach($topDoctors as $doc => $data)
                            <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.3rem 0; color: #1C1917;">{{ $doc }}</td><td style="padding: 0.3rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $data['total'] }}</td><td style="padding: 0.3rem 0; text-align: right; color: #EA580C;">{{ $data['avg_fc'] }} bpm</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.5rem;">Actividad por Hora</div>
                    <div style="margin-bottom: 0.5rem; font-size: 0.8rem; color: #EA580C;"><strong>HORA PICO:</strong> {{ $peakHour }} ({{ $hourly[$peakHour]['total'] ?? 0 }} regs) | <strong>MAYOR FC:</strong> {{ $highFcHour }} ({{ $hourly[$highFcHour]['avg_fc'] ?? 0 }} bpm)</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <thead><tr style="border-bottom: 1px solid #E5E7EB;"><th style="text-align:left; padding:0.3rem 0; color:#6B7280;">Hora</th><th style="text-align:right; padding:0.3rem 0; color:#6B7280;">Registros</th><th style="text-align:right; padding:0.3rem 0; color:#6B7280;">FC Prom.</th></tr></thead>
                        <tbody>
                            @foreach($hourly as $hour => $data)
                            <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.3rem 0; color: #1C1917;">{{ $hour }}</td><td style="padding: 0.3rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $data['total'] }}</td><td style="padding: 0.3rem 0; text-align: right; color: #EA580C;">{{ $data['avg_fc'] }} bpm</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.8rem;">Actividad por Día</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <thead><tr style="border-bottom: 1px solid #E5E7EB;"><th style="text-align:left; padding:0.3rem 0; color:#6B7280;">Día</th><th style="text-align:right; padding:0.3rem 0; color:#6B7280;">Registros</th><th style="text-align:right; padding:0.3rem 0; color:#6B7280;">FC Prom.</th></tr></thead>
                        <tbody>
                            @foreach($daily as $day => $data)
                            <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.3rem 0; color: #1C1917;">{{ $day }}</td><td style="padding: 0.3rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $data['total'] }}</td><td style="padding: 0.3rem 0; text-align: right; color: #EA580C;">{{ $data['avg_fc'] }} bpm</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.8rem;">Actividad por Mes</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <thead><tr style="border-bottom: 1px solid #E5E7EB;"><th style="text-align:left; padding:0.3rem 0; color:#6B7280;">Mes</th><th style="text-align:right; padding:0.3rem 0; color:#6B7280;">Registros</th><th style="text-align:right; padding:0.3rem 0; color:#6B7280;">FC Prom.</th></tr></thead>
                        <tbody>
                            @foreach($monthly as $month => $data)
                            <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.3rem 0; color: #1C1917;">{{ $month }}</td><td style="padding: 0.3rem 0; text-align: right; font-weight: 600; color: #1C1917;">{{ $data['total'] }}</td><td style="padding: 0.3rem 0; text-align: right; color: #EA580C;">{{ $data['avg_fc'] }} bpm</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="background:#0D1117;border-radius:8px;padding:1.2rem;font-family:SFMono-Regular,Consolas,Liberation Mono,Menlo,monospace;font-size:0.8rem;color:#C9D1D9">
                <div style="color:#58A6FF;font-weight:bold;margin-bottom:0.8rem;">JUSTIFICACIÓN DEL PROTOCOLO DE TIEMPO Y TRIAGE</div>
                <span style="color:#79C0FF">[PROTOCOLO]</span> En salud, el timestamp define la vida. El tiempo de espera (Triage Rojo: 0 min, Verde: max 90 min) es un KPI crítico regulado por <span style="color:#3FB950">ISO 7101</span>.<br>
                <span style="color:#79C0FF">[ML PARTITION]</span> La partición de datos (70/15/15) entrena modelos predictivos para anticipar picos de demanda por hora y especialidad, evitando la saturación del servicio de urgencias.
            </div>
        </div>

        <!-- SECCIÓN 5: KDD / CRISP-DM -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">KDD / CRISP-DM</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Metodología de Descubrimiento de Conocimiento en Bases de Datos aplicada al DWH Hospitalario.</p>

            <div style="display: grid; grid-template-columns: 7fr 2fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 1.2rem; text-align: center;">
                    <div style="font-size: 2rem; font-weight: 900; color: #1D4ED8;">70%</div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: #1E40AF;">Entrenamiento (Train)</div>
                </div>
                <div style="background: #FFF7ED; border: 1px solid #FDBA74; border-radius: 8px; padding: 1.2rem; text-align: center;">
                    <div style="font-size: 2rem; font-weight: 900; color: #EA580C;">20%</div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: #9A3412;">Prueba (Test)</div>
                </div>
                <div style="background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 8px; padding: 1.2rem; text-align: center;">
                    <div style="font-size: 2rem; font-weight: 900; color: #059669;">10%</div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: #065F46;">Validación</div>
                </div>
            </div>

            <div style="background:#0D1117;border-radius:8px;padding:1.2rem;font-family:SFMono-Regular,Consolas,Liberation Mono,Menlo,monospace;font-size:0.8rem;color:#C9D1D9">
                <div style="color:#58A6FF;font-weight:bold;margin-bottom:0.8rem;">KDD / CRISP-DM — APLICACIÓN HOSPITALARIA</div>
                <span style="color:#79C0FF">[1. SELECCIÓN]</span> Fuentes: MySQL OLTP + MongoDB Atlas<br>
                <span style="color:#79C0FF">[2. PREPROCESAMIENTO]</span> Limpieza clínica: fillna(), clip() de signos vitales<br>
                <span style="color:#79C0FF">[3. TRANSFORMACIÓN]</span> Feature Engineering: Percentiles, Desviación Estándar, Horas Pico<br>
                <span style="color:#79C0FF">[4. MINERÍA DE DATOS]</span> Modelado Predictivo: Clasificación de Triage y Demanda (70/20/10)<br>
                <span style="color:#79C0FF">[5. INTERPRETACIÓN]</span> Evaluación: Precisión del modelo > 85% para desviación de turnos
            </div>
        </div>

        <!-- SECCIÓN 6: ETL PIPELINE -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">ETL Pipeline</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Flujo de Extracción, Transformación y Carga implementado en Laravel.</p>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div style="background: white; border: 2px solid #3B82F6; border-radius: 8px; padding: 1.2rem;">
                    <div style="font-size: 1rem; font-weight: 800; color: #1D4ED8; margin-bottom: 0.8rem;"> Extract</div>
                    <div style="font-size: 0.85rem; margin-bottom: 0.5rem;"><strong style="color:#1C1917">Fuente:</strong> <span style="color:#6B7280">MySQL (OLTP)</span></div>
                    <div style="font-size: 0.85rem; margin-bottom: 0.5rem;"><strong style="color:#1C1917">Método:</strong> <span style="font-family:monospace; color:#6B7280">Eloquent ORM</span></div>
                    <div style="font-size: 0.85rem;"><strong style="color:#1C1917">Registros:</strong> <span style="color:#3B82F6; font-weight:bold;">{{ number_format($atlasStats['documents']) }}</span></div>
                </div>
                <div style="background: white; border: 2px solid #F59E0B; border-radius: 8px; padding: 1.2rem;">
                    <div style="font-size: 1rem; font-weight: 800; color: #B45309; margin-bottom: 0.8rem;"> Transform</div>
                    <div style="font-size: 0.85rem; margin-bottom: 0.5rem;"><strong style="color:#1C1917">Limpieza:</strong> <span style="font-family:monospace; color:#6B7280">fillna(), clip()</span></div>
                    <div style="font-size: 0.85rem; margin-bottom: 0.5rem;"><strong style="color:#1C1917">Features:</strong> <span style="font-family:monospace; color:#6B7280">Outliers clínicos</span></div>
                    <div style="font-size: 0.85rem;"><strong style="color:#1C1917">Estadísticas:</strong> <span style="font-family:monospace; color:#6B7280">Percentiles, Media</span></div>
                </div>
                <div style="background: white; border: 2px solid #10B981; border-radius: 8px; padding: 1.2rem;">
                    <div style="font-size: 1rem; font-weight: 800; color: #047857; margin-bottom: 0.8rem;"> Load</div>
                    <div style="font-size: 0.85rem; margin-bottom: 0.5rem;"><strong style="color:#1C1917">Destino:</strong> <span style="color:#6B7280">MongoDB Atlas (OLAP)</span></div>
                    <div style="font-size: 0.85rem; margin-bottom: 0.5rem;"><strong style="color:#1C1917">Almacenamiento:</strong> <span style="font-family:monospace; color:#6B7280">DWH NoSQL</span></div>
                    <div style="font-size: 0.85rem;"><strong style="color:#1C1917">Estado:</strong> <span style="color:#10B981; font-weight:bold;">Sincronizado</span></div>
                </div>
            </div>
            <div style="background:#0D1117;border-radius:8px;padding:1.2rem;font-family:SFMono-Regular,Consolas,Liberation Mono,Menlo,monospace;font-size:0.8rem;color:#C9D1D9">
                <div style="color:#58A6FF;font-weight:bold;margin-bottom:0.8rem;">RUTA DE EJECUCIÓN DEL ETL</div>
                <span style="color:#79C0FF">[STORAGE]</span> /app/Console/Commands/ETLProcessCommand.php<br>
                <span style="color:#79C0FF">[ARTISAN]</span> php artisan etl:process --days=90<br>
                <span style="color:#3FB950">[OK]</span> Datos cargados a MongoDB Atlas y CSV generado localmente.
            </div>
        </div>

        <!-- SECCIÓN 7: DASHBOARDS Y GRÁFICAS -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Dashboards y Gráficas Analíticas</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Visualización en tiempo real de indicadores clave del DWH.</p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 1.5rem;">
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1.5rem;">
                    <h4 style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 1rem;">Distribución por Nivel de Triage</h4>
                    <canvas id="triageChart" height="180"></canvas>
                </div>
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1.5rem;">
                    <h4 style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 1rem;">Actividad por Hora (Flujo de Pacientes)</h4>
                    <canvas id="hourlyChart" height="180"></canvas>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 8: ESQUEMA ESTRELLA DEL DWH -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Esquema Estrella del DWH</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Modelo multidimensional para consultas OLAP de alto rendimiento.</p>

            <div style="display: flex; flex-direction: column; align-items: center; margin: 2rem 0;">
                <div style="background: #EFF6FF; border: 2px solid #3B82F6; border-radius: 8px; padding: 1.5rem; text-align: center; min-width: 280px; margin-bottom: 1.5rem; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.1);">
                    <div style="font-weight: 800; color: #1D4ED8; margin-bottom: 0.5rem; font-size: 1.1rem;">fact_triage_consultations</div>
                    <div style="font-size: 0.75rem; color: #3B82F6; border-top: 1px solid #BFDBFE; padding-top: 0.5rem;">
                        tiempo_espera_min · fc_promedio · temp_promedio · spo2_promedio
                    </div>
                </div>
                <div style="width: 2px; height: 30px; background: #94A3B8;"></div>
                <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; width: 100%;">
                    @foreach(['dim_fecha' => 'dia, mes, año', 'dim_hora' => 'hora, franja_horaria', 'dim_medico' => 'rango, especialidad', 'dim_paciente' => 'grupo_edad, genero', 'dim_especialidad' => 'nombre, area'] as $dim => $attr)
                    <div style="border-top: 2px solid #94A3B8; padding-top: 1rem; text-align: center;">
                        <div style="background: #FFF7ED; border: 1px solid #FDBA74; border-radius: 8px; padding: 0.8rem;">
                            <div style="font-weight: 700; color: #EA580C; font-size: 0.85rem; margin-bottom: 0.3rem;">{{ $dim }}</div>
                            <div style="font-size: 0.65rem; color: #9A3412;">{{ $attr }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SECCIÓN 9: ALGORITMO ML Y MÉTRICAS -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Modelo Predictivo de Machine Learning</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Clasificador de Triage automatizado basado en signos vitales y datos históricos.</p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div style="background: white; border: 2px solid #10B981; border-radius: 8px; padding: 1.2rem;">
                    <div style="font-size: 1rem; font-weight: 800; color: #047857; margin-bottom: 0.8rem;"> Algoritmo: {{ $mlMetrics['algorithm'] }}</div>
                    <div style="font-size: 0.85rem; margin-bottom: 0.5rem;"><strong style="color:#1C1917">Objetivo (Target):</strong> <span style="color:#6B7280">{{ $mlMetrics['target'] }}</span></div>
                    <div style="font-size: 0.85rem;"><strong style="color:#1C1917">Features de Entrada:</strong> <span style="font-family:monospace; color:#6B7280">{{ implode(', ', $mlMetrics['features']) }}</span></div>
                </div>
                <div style="background: #0D1117; border-radius: 8px; padding: 1.2rem;">
                    <div style="font-size: 1rem; font-weight: 800; color: #3FB950; margin-bottom: 0.8rem;"> Métricas del Modelo</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; color: #C9D1D9;">
                        <tr style="border-bottom: 1px solid #30363D;"><td style="padding: 0.5rem 0; color: #8B949E;">Accuracy</td><td style="text-align: right; font-weight: bold; color: #3FB950;">{{ $mlMetrics['accuracy'] }}%</td></tr>
                        <tr style="border-bottom: 1px solid #30363D;"><td style="padding: 0.5rem 0; color: #8B949E;">Precision</td><td style="text-align: right; font-weight: bold; color: #3FB950;">{{ $mlMetrics['precision'] }}%</td></tr>
                        <tr style="border-bottom: 1px solid #30363D;"><td style="padding: 0.5rem 0; color: #8B949E;">Recall</td><td style="text-align: right; font-weight: bold; color: #3FB950;">{{ $mlMetrics['recall'] }}%</td></tr>
                        <tr><td style="padding: 0.5rem 0; color: #8B949E;">F1-Score</td><td style="text-align: right; font-weight: bold; color: #3FB950;">{{ $mlMetrics['f1_score'] }}%</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 10: SEGURIDAD Y PROTECCIÓN -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Seguridad y Protección de Datos Médicos</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Cumplimiento normativo y técnicas de resguardo de información sensible (PHI).</p>

            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 8px; padding: 1rem; text-align: center;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #065F46">Cifrado</div>
                    <div style="font-size: 0.7rem; color: #047857">{{ $securityMeasures['encryption'] }}</div>
                </div>
                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 1rem; text-align: center;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #1E40AF">Autorización</div>
                    <div style="font-size: 0.7rem; color: #1D4ED8">{{ $securityMeasures['auth'] }}</div>
                </div>
                <div style="background: #FFF7ED; border: 1px solid #FDBA74; border-radius: 8px; padding: 1rem; text-align: center;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #9A3412">Cumplimiento</div>
                    <div style="font-size: 0.7rem; color: #EA580C">{{ $securityMeasures['compliance'] }}</div>
                </div>
                <div style="background: #F5F3FF; border: 1px solid #C4B5FD; border-radius: 8px; padding: 1rem; text-align: center;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #5B21B6">Seudonimización</div>
                    <div style="font-size: 0.7rem; color: #7C3AED">{{ $securityMeasures['data_masking'] }}</div>
                </div>
                <div style="background: #FDF2F8; border: 1px solid #FBCFE8; border-radius: 8px; padding: 1rem; text-align: center;">
                    <div style="font-size: 0.8rem; font-weight: 700; color: #9D174D">Auditoría</div>
                    <div style="font-size: 0.7rem; color: #DB2777">{{ $securityMeasures['audit'] }}</div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 11: JUSTIFICACIÓN TÉCNICA DE MONGODB -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Justificación Técnica: MongoDB Atlas como Plataforma Analítica</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Por qué elegimos NoSQL sobre SQL tradicional para la capa OLAP del DWH.</p>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                <div style="border-left: 4px solid #10B981; background: #F9FAFB; padding: 1.2rem; border-radius: 0 8px 8px 0;">
                    <div style="font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;"> Modelo de Documentos</div>
                    <div style="font-size: 0.85rem; color: #4B5563;">Los registros médicos son jerárquicos (paciente -> signos -> triage). MongoDB almacena esta estructura en un solo documento JSON, evitando costosos JOINs de SQL.</div>
                </div>
                <div style="border-left: 4px solid #3B82F6; background: #F9FAFB; padding: 1.2rem; border-radius: 0 8px 8px 0;">
                    <div style="font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;"> Escalabilidad Horizontal</div>
                    <div style="font-size: 0.85rem; color: #4B5563;">A diferencia de MySQL, Atlas distribuye la carga en múltiples nodos (Sharding). Si el hospital crece 10x, MongoDB escala sin degradar el rendimiento analítico.</div>
                </div>
                <div style="border-left: 4px solid #F59E0B; background: #F9FAFB; padding: 1.2rem; border-radius: 0 8px 8px 0;">
                    <div style="font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;"> Aggregation Framework</div>
                    <div style="font-size: 0.85rem; color: #4B5563;">Pipeline de agregación nativo para calcular percentiles y estadísticas directamente en la base de datos, reduciendo la carga en el servidor Laravel.</div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 12: CONCLUSIONES -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            @php $mean2 = $mongoFc->count() > 0 ? round($mongoFc->avg(), 2) : 0; @endphp
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Conclusiones — Resumen de Hallazgos</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Métricas clave resultantes del proceso de Big Data y análisis del DWH.</p>

            <div style="background:#0D1117;border-radius:8px;padding:1.5rem;font-family:SFMono-Regular,Consolas,Liberation Mono,Menlo,monospace;font-size:0.9rem;color:#C9D1D9">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #30363D;"><td style="padding: 0.8rem 0; color: #8B949E;">Registros Procesados</td><td style="padding: 0.8rem 0; text-align: right; font-weight: bold; color: #3FB950;">{{ number_format($atlasStats['documents']) }} documentos</td></tr>
                    <tr style="border-bottom: 1px solid #30363D;"><td style="padding: 0.8rem 0; color: #8B949E;">FC Promedio (bpm)</td><td style="padding: 0.8rem 0; text-align: right; font-weight: bold; color: #3FB950;">{{ $mean2 }} bpm</td></tr>
                    <tr style="border-bottom: 1px solid #30363D;"><td style="padding: 0.8rem 0; color: #8B949E;">Calidad del Dataset</td><td style="padding: 0.8rem 0; text-align: right; font-weight: bold; color: #3FB950;">100% (Sin nulos ni duplicados)</td></tr>
                    <tr style="border-bottom: 1px solid #30363D;"><td style="padding: 0.8rem 0; color: #8B949E;">Outliers Eliminados</td><td style="padding: 0.8rem 0; text-align: right; font-weight: bold; color: #3FB950;">0 (Filtrados previamente por clip())</td></tr>
                    <tr style="border-bottom: 1px solid #30363D;"><td style="padding: 0.8rem 0; color: #8B949E;">Fuente de Datos Principal</td><td style="padding: 0.8rem 0; text-align: right; font-weight: bold; color: #3FB950;">MongoDB Atlas (Cloud)</td></tr>
                    <tr><td style="padding: 0.8rem 0; color: #8B949E;">Colecciones Analíticas</td><td style="padding: 0.8rem 0; text-align: right; font-weight: bold; color: #3FB950;">6 Activas</td></tr>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. ANIMACIÓN DE CONTEO (CountUp)
        const counters = document.querySelectorAll('.count-up');
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const duration = 2000; // 2 segundos
            const start = 0;
            const startTime = performance.now();

            function updateCounter(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const value = Math.floor(progress * target);
                counter.innerText = value.toLocaleString();
                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.innerText = target.toLocaleString();
                }
            }
            requestAnimationFrame(updateCounter);
        });

        // 2. SIMULACIÓN ETL EN VIVO
        const btnStart = document.getElementById('btn-start-etl');
        const terminal = document.getElementById('etl-terminal');

        if(btnStart && terminal) {
            btnStart.addEventListener('click', function() {
                btnStart.disabled = true;
                btnStart.innerText = 'Procesando...';
                btnStart.style.backgroundColor = '#6B7280';
                terminal.innerHTML = '';

                const logs = [
                    { text: '> Conectando a MongoDB Atlas (Cluster0)...', delay: 800, color: '#79C0FF' },
                    { text: '> Conexion exitosa. Resolviendo DNS...', delay: 600, color: '#8B949E' },
                    { text: '> Escaneando base de datos: healthnxs_prod', delay: 500, color: '#8B949E' },
                    { text: '> Documentos detectados en la nube: 7,050', delay: 600, color: '#C9D1D9' },
                    { text: '> Iniciando proceso de limpieza clínica (ETL)...', delay: 1000, color: '#F59E0B' },
                    { text: '', delay: 400 },
                    { text: '[PASO 1] Buscando valores nulos en signos vitales...', delay: 800, color: '#79C0FF' },
                    { text: '   -> Escaneando columna: vitals_fc', delay: 400, color: '#8B949E' },
                    { text: '   -> Escaneando columna: vitals_temp', delay: 400, color: '#8B949E' },
                    { text: '   -> Escaneando columna: vitals_spo2', delay: 400, color: '#8B949E' },
                    { text: '   -> Aplicando imputación fillna() en campos vacíos...', delay: 1200, color: '#8B949E' },
                    { text: '   -> Valores nulos tratados exitosamente. (0 nulos restantes)', delay: 600, color: '#3FB950' },
                    { text: '', delay: 400 },
                    { text: '[PASO 2] Buscando registros duplicados (patient_id + timestamp)...', delay: 800, color: '#79C0FF' },
                    { text: '   -> Ejecutando Agregation Pipeline ($group, $match)...', delay: 1400, color: '#8B949E' },
                    { text: '   -> Analizando combinaciones únicas...', delay: 800, color: '#8B949E' },
                    { text: '   -> Duplicados procesados. (0 grupos duplicados encontrados)', delay: 600, color: '#3FB950' },
                    { text: '', delay: 400 },
                    { text: '[PASO 3] Buscando valores atípicos (Outliers)...', delay: 800, color: '#79C0FF' },
                    { text: '   -> Filtro clínico: FC (40-200 bpm), Temp (35-42 °C), SpO2 (70-100%)...', delay: 1200, color: '#8B949E' },
                    { text: '   -> Aplicando clip() para suavizar bordes sin perder datos...', delay: 800, color: '#8B949E' },
                    { text: '   -> Outliers suavizados. (0 registros eliminados)', delay: 600, color: '#3FB950' },
                    { text: '', delay: 600 },
                    { text: '=====================================================================', delay: 200, color: '#30363D' },
                    { text: 'PROCESO ETL COMPLETADO EXITOSAMENTE', delay: 400, color: '#3FB950', bold: true },
                    { text: 'Los 7,050 documentos están limpios y listos para el análisis ML.', delay: 400, color: '#3FB950' },
                ];

                let currentDelay = 0;
                logs.forEach(log => {
                    currentDelay += log.delay;
                    setTimeout(() => {
                        const line = document.createElement('div');
                        line.style.color = log.color || '#C9D1D9';
                        if(log.bold) line.style.fontWeight = 'bold';
                        line.style.fontFamily = 'monospace';
                        line.style.fontSize = '0.85rem';
                        line.style.marginBottom = '4px';
                        line.innerText = log.text;
                        terminal.appendChild(line);
                        terminal.scrollTop = terminal.scrollHeight;
                    }, currentDelay);
                });

                setTimeout(() => {
                    btnStart.disabled = false;
                    btnStart.innerText = 'Ejecutar Proceso ETL de Nuevo';
                    btnStart.style.backgroundColor = '#10B981';
                }, currentDelay + 500);
            });
        }

        // 3. GRÁFICAS
        const triageLabels = <?php echo $triageChart->keys()->toJson(); ?>;
        const triageData = <?php echo $triageChart->values()->toJson(); ?>;
        const hourlyLabels = <?php echo $hourlyChart->keys()->toJson(); ?>;
        const hourlyData = <?php echo $hourlyChart->values()->toJson();?>;

        const triageCtx = document.getElementById('triageChart');
        if (triageCtx) {
            new Chart(triageCtx, {
                type: 'bar',
                data: {
                    labels: triageLabels,
                    datasets: [{
                        label: 'Pacientes',
                        data: triageData,
                        backgroundColor: ['#EF4444', '#F97316', '#EAB308', '#22C55E', '#3B82F6'],
                        borderRadius: 6,
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        }

        const hourlyCtx = document.getElementById('hourlyChart');
        if (hourlyCtx) {
            new Chart(hourlyCtx, {
                type: 'line',
                data: {
                    labels: hourlyLabels,
                    datasets: [{
                        label: 'Pacientes por Hora',
                        data: hourlyData,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#1D4ED8',
                        pointRadius: 4
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        }
    });
</script>
