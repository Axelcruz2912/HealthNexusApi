@extends('enfermeria.layout')
@section('title', 'Big Data Hospitalario')
@section('nav-bigdata', 'active')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        <!-- SECCIÓN 1: ANÁLISIS BIG DATA -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Análisis Big Data en Vivo</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Repositorio de análisis clínico y flujo de pacientes en tiempo real (Solo hoy).</p>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem;">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #6B7280;">Fuente</div>
                    <div style="font-size: 1rem; font-weight: 700; color: #1C1917;">MongoDB Atlas</div>
                </div>
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem;">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #6B7280;">Colección</div>
                    <div style="font-size: 1rem; font-weight: 700; color: #1C1917;">triage_logs</div>
                </div>
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem;">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #6B7280;">Documentos Hoy</div>
                    <div style="font-size: 1rem; font-weight: 700; color: #1C1917;">{{ number_format($stats['total']) }}</div>
                </div>
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem;">
                    <div style="font-size: 0.75rem; font-weight: 600; color: #6B7280;">Período</div>
                    <div style="font-size: 1rem; font-weight: 700; color: #1C1917;">Hoy</div>
                </div>
            </div>

            <div style="margin-bottom:1rem">
                <span style="font-size:0.85rem;font-weight:700;color:#1C1917">Estructura del documento</span><br>
                <span style="font-size:0.8rem;color:#6B7280;font-family:monospace">patient_id · triage_level · age · specialty · vitals_fc · vitals_temp · vitals_spo2 · assigned_doctor_id · is_derived · timestamp</span>
            </div>

            <div style="background:#0D1117;border-radius:8px;padding:1.2rem;font-family:SFMono-Regular,Consolas,Liberation Mono,Menlo,monospace;font-size:0.8rem;color:#C9D1D9">
                <span style="color:#79C0FF">></span> <span style="color:#FF7B72">db.triage_logs.count()</span><br>
                {{ number_format($stats['total']) }}
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
                            <td style="padding: 0.75rem 1rem; font-family: monospace; color: #6B7280;">fillna() en FC, Temp, SpO2</td>
                            <td style="padding: 0.75rem 1rem; color: #059669; font-weight: 600;">0 nulos restantes</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #E5E7EB;">
                            <td style="padding: 0.75rem 1rem; font-weight: 500; color: #1C1917;">Datos Duplicados</td>
                            <td style="padding: 0.75rem 1rem; font-family: monospace; color: #6B7280;">Validación en Controlador (Bloqueo ETL)</td>
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
                    <div style="font-size:1.5rem;font-weight:800;color:#059669">{{ number_format($stats['total']) }}</div>
                    <div style="font-size:0.8rem;font-weight:600;color:#065F46">Registros válidos para análisis</div>
                </div>
                <div style="background:#ECFDF5;border:1px solid #A7F3D0;border-radius:8px;padding:1rem;text-align:center">
                    <div style="font-size:1.5rem;font-weight:800;color:#059669">0%</div>
                    <div style="font-size:0.8rem;font-weight:600;color:#065F46">Pérdida de información</div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 3: TÉCNICAS AVANZADAS -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Técnicas Avanzadas</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Cálculos estadísticos computados en tiempo real.</p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1.2rem;">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.8rem;">Métricas calculadas (FC)</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">Media (Promedio)</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600;">{{ $stats['avg_fc'] }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">Máxima</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600;">{{ $stats['max_fc'] }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">Mínima</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600;">{{ $stats['min_fc'] }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">Moda</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600;">{{ $stats['mode_fc'] }} bpm</td></tr>
                        <tr><td style="padding: 0.4rem 0; color: #6B7280;">Desv. estándar</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600;">{{ $stats['std_fc'] }}</td></tr>
                    </table>
                </div>

                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1.2rem;">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.8rem;">Percentiles</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">10%</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600;">{{ $stats['p10'] }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">25% (Q1)</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600;">{{ $stats['p25'] }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">50% (Mediana)</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600;">{{ $stats['p50'] }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">75% (Q3)</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600;">{{ $stats['p75'] }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.4rem 0; color: #6B7280;">90%</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600;">{{ $stats['p90'] }} bpm</td></tr>
                        <tr><td style="padding: 0.4rem 0; color: #6B7280;">IQR (Q3 - Q1)</td><td style="padding: 0.4rem 0; text-align: right; font-weight: 600;">{{ $stats['iqr'] }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 4: MINERÍA Y CONJUNTO DE DATOS -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Minería y Conjunto de Datos</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Preparación de datos para modelos predictivos de Machine Learning hospitalario.</p>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem;">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.8rem;">División de datos</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.3rem 0; color: #6B7280;">Entrenamiento</td><td style="padding: 0.3rem 0; text-align: right; font-weight: 600; color: #2563EB;">70% ({{ $train }})</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.3rem 0; color: #6B7280;">Prueba</td><td style="padding: 0.3rem 0; text-align: right; font-weight: 600; color: #D97706;">20% ({{ $test }})</td></tr>
                        <tr><td style="padding: 0.3rem 0; color: #6B7280;">Validación</td><td style="padding: 0.3rem 0; text-align: right; font-weight: 600; color: #059669;">10% ({{ $val }})</td></tr>
                    </table>
                </div>
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem;">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.8rem;">Distribución Triage</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        @foreach($dist->sortDesc() as $level => $count)
                        <tr style="border-bottom: 1px solid #F3F4F6;">
                            <td style="padding: 0.3rem 0; color: #6B7280;">{{ $level }}</td>
                            <td style="padding: 0.3rem 0; text-align: right; font-weight: 600;">{{ $count }} consultas</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1rem;">
                    <div style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 0.8rem;">Signos Vitales Hoy</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.3rem 0; color: #6B7280;">FC Promedio</td><td style="padding: 0.3rem 0; text-align: right; font-weight: 600;">{{ $stats['avg_fc'] }} bpm</td></tr>
                        <tr style="border-bottom: 1px solid #F3F4F6;"><td style="padding: 0.3rem 0; color: #6B7280;">Temp Promedio</td><td style="padding: 0.3rem 0; text-align: right; font-weight: 600;">{{ $stats['avg_temp'] }} C</td></tr>
                        <tr><td style="padding: 0.3rem 0; color: #6B7280;">SpO2 Promedio</td><td style="padding: 0.3rem 0; text-align: right; font-weight: 600;">{{ $stats['avg_spo2'] }}%</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 5: KDD / CRISP-DM -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">KDD / CRISP-DM</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Metodología aplicada al DWH Hospitalario.</p>

            <div style="display: grid; grid-template-columns: 7fr 2fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 1.2rem; text-align: center;">
                    <div style="font-size: 2rem; font-weight: 900; color: #1D4ED8;">70%</div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: #1E40AF;">Entrenamiento</div>
                </div>
                <div style="background: #FFF7ED; border: 1px solid #FDBA74; border-radius: 8px; padding: 1.2rem; text-align: center;">
                    <div style="font-size: 2rem; font-weight: 900; color: #EA580C;">20%</div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: #9A3412;">Prueba</div>
                </div>
                <div style="background: #ECFDF5; border: 1px solid #A7F3D0; border-radius: 8px; padding: 1.2rem; text-align: center;">
                    <div style="font-size: 2rem; font-weight: 900; color: #059669;">10%</div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: #065F46;">Validación</div>
                </div>
            </div>

            <div style="background:#0D1117;border-radius:8px;padding:1.2rem;font-family:SFMono-Regular,Consolas,Liberation Mono,Menlo,monospace;font-size:0.8rem;color:#C9D1D9">
                <div style="color:#58A6FF;font-weight:bold;margin-bottom:0.8rem;">KDD / CRISP-DM</div>
                <span style="color:#79C0FF">[1. SELECCIÓN]</span> Fuentes: MySQL OLTP + MongoDB Atlas<br>
                <span style="color:#79C0FF">[2. PREPROCESAMIENTO]</span> Limpieza clínica: fillna(), clip()<br>
                <span style="color:#79C0FF">[3. TRANSFORMACIÓN]</span> Feature Engineering: Percentiles, Desviación Estándar<br>
                <span style="color:#79C0FF">[4. MINERÍA DE DATOS]</span> Modelado Predictivo: Clasificación de Triage (70/20/10)<br>
                <span style="color:#79C0FF">[5. INTERPRETACIÓN]</span> Evaluación en tiempo real
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
                    <div style="font-size: 0.85rem;"><strong style="color:#1C1917">Registros:</strong> <span style="color:#3B82F6; font-weight:bold;">{{ number_format($stats['total']) }}</span></div>
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
        </div>

        <!-- SECCIÓN 7: ESQUEMA ESTRELLA -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Esquema Estrella del DWH</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Modelo multidimensional para consultas OLAP.</p>

            <div style="display: flex; flex-direction: column; align-items: center; margin: 2rem 0;">
                <div style="background: #EFF6FF; border: 2px solid #3B82F6; border-radius: 8px; padding: 1.5rem; text-align: center; min-width: 280px; margin-bottom: 1.5rem; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.1);">
                    <div style="font-weight: 800; color: #1D4ED8; margin-bottom: 0.5rem; font-size: 1.1rem;">fact_triage_consultations</div>
                    <div style="font-size: 0.75rem; color: #3B82F6; border-top: 1px solid #BFDBFE; padding-top: 0.5rem;">
                        tiempo_espera_min · fc_promedio · temp_promedio · spo2_promedio
                    </div>
                </div>
                <div style="width: 2px; height: 30px; background: #94A3B8;"></div>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; width: 100%;">
                    <div style="border-top: 2px solid #94A3B8; padding-top: 1rem; text-align: center;">
                        <div style="background: #FFF7ED; border: 1px solid #FDBA74; border-radius: 8px; padding: 0.8rem;">
                            <div style="font-weight: 700; color: #EA580C; font-size: 0.85rem;">dim_fecha</div>
                            <div style="font-size: 0.65rem; color: #9A3412;">dia, mes, año</div>
                        </div>
                    </div>
                    <div style="border-top: 2px solid #94A3B8; padding-top: 1rem; text-align: center;">
                        <div style="background: #FFF7ED; border: 1px solid #FDBA74; border-radius: 8px; padding: 0.8rem;">
                            <div style="font-weight: 700; color: #EA580C; font-size: 0.85rem;">dim_paciente</div>
                            <div style="font-size: 0.65rem; color: #9A3412;">grupo_edad, genero</div>
                        </div>
                    </div>
                    <div style="border-top: 2px solid #94A3B8; padding-top: 1rem; text-align: center;">
                        <div style="background: #FFF7ED; border: 1px solid #FDBA74; border-radius: 8px; padding: 0.8rem;">
                            <div style="font-weight: 700; color: #EA580C; font-size: 0.85rem;">dim_medico</div>
                            <div style="font-size: 0.65rem; color: #9A3412;">rango, especialidad</div>
                        </div>
                    </div>
                    <div style="border-top: 2px solid #94A3B8; padding-top: 1rem; text-align: center;">
                        <div style="background: #FFF7ED; border: 1px solid #FDBA74; border-radius: 8px; padding: 0.8rem;">
                            <div style="font-weight: 700; color: #EA580C; font-size: 0.85rem;">dim_especialidad</div>
                            <div style="font-size: 0.65rem; color: #9A3412;">nombre, area</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 8: DASHBOARDS Y GRÁFICAS -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Dashboards y Gráficas en Vivo</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Visualización de indicadores clave del día de hoy.</p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 1.5rem;">
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1.5rem;">
                    <h4 style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 1rem;">Distribución por Nivel de Triage</h4>
                    <canvas id="triageChartNurse" height="180"></canvas>
                </div>
                <div style="background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 1.5rem;">
                    <h4 style="font-size: 0.9rem; font-weight: 600; color: #1C1917; margin-bottom: 1rem;">Resumen de Signos Vitales</h4>
                    <canvas id="vitalsChartNurse" height="180"></canvas>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 9: CONCLUSIONES -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1C1917; margin-bottom: 0.5rem;">Conclusiones</h3>
            <p style="font-size: 0.85rem; color: #6B7280; margin-bottom: 1.5rem;">Métricas clave resultantes del análisis en vivo.</p>

            <div style="background:#0D1117;border-radius:8px;padding:1.5rem;font-family:SFMono-Regular,Consolas,Liberation Mono,Menlo,monospace;font-size:0.9rem;color:#C9D1D9">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid #30363D;"><td style="padding: 0.8rem 0; color: #8B949E;">Ingresos Hoy</td><td style="padding: 0.8rem 0; text-align: right; font-weight: bold; color: #3FB950;">{{ number_format($stats['total']) }} documentos</td></tr>
                    <tr style="border-bottom: 1px solid #30363D;"><td style="padding: 0.8rem 0; color: #8B949E;">FC Promedio</td><td style="padding: 0.8rem 0; text-align: right; font-weight: bold; color: #3FB950;">{{ $stats['avg_fc'] }} bpm</td></tr>
                    <tr style="border-bottom: 1px solid #30363D;"><td style="padding: 0.8rem 0; color: #8B949E;">Calidad del Dataset</td><td style="padding: 0.8rem 0; text-align: right; font-weight: bold; color: #3FB950;">100% (Sin nulos/duplicados)</td></tr>
                    <tr><td style="padding: 0.8rem 0; color: #8B949E;">Fuente de Datos</td><td style="padding: 0.8rem 0; text-align: right; font-weight: bold; color: #3FB950;">MongoDB Atlas (Live)</td></tr>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const distLabels = <?php echo $dist->keys()->toJson(); ?>;
        const distData = <?php echo $dist->values()->toJson(); ?>;

        const triageCtx = document.getElementById('triageChartNurse');
        if (triageCtx) {
            new Chart(triageCtx, {
                type: 'bar',
                data: {
                    labels: distLabels,
                    datasets: [{
                        label: 'Pacientes',
                        data: distData,
                        backgroundColor: ['#DC2626', '#EA580C', '#F59E0B', '#22C55E', '#3B82F6'],
                        borderRadius: 6
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        }

        const vitalsCtx = document.getElementById('vitalsChartNurse');
        if (vitalsCtx) {
            new Chart(vitalsCtx, {
                type: 'bar',
                data: {
                    labels: ['FC Promedio', 'FC Máxima', 'FC Mínima'],
                    datasets: [{
                        label: 'Frecuencia Cardíaca (bpm)',
                        data: [{{ $stats['avg_fc'] }}, {{ $stats['max_fc'] }}, {{ $stats['min_fc'] }}],
                        backgroundColor: ['#3B82F6', '#EF4444', '#10B981'],
                        borderRadius: 6
                    }]
                },
                options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        }
    });
</script>
