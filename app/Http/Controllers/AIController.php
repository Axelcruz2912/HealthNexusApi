<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    public function asistente(Request $request)
    {
        $user = auth()->user();
        $uid = $user->id;

        $misPacientes = \App\Models\Triage::where('assigned_doctor_id', $uid)
            ->whereIn('status', ['En Atencion', 'Hospitalizado'])
            ->orderBy('triage_level', 'asc')
            ->limit(20)
            ->get();

        $criticosSinDoctor = \App\Models\Triage::where('triage_level', 'Rojo')
            ->whereIn('status', ['En Espera', 'En Atencion'])
            ->whereNull('assigned_doctor_id')
            ->count();

        $hospitalizados = \App\Models\Triage::where('status', 'Hospitalizado')->count();
        $camas = \App\Models\Bed::where('status', 'Disponible')->count();
        $ambulancias = \App\Models\Ambulance::where('status', 'En Ruta')->count();

        $alertas = collect();
        foreach ($misPacientes as $p) {
            if ($p->triage_level === 'Rojo') {
                $alertas->push([
                    'tipo' => 'critico',
                    'icono' => 'fa-heart-pulse',
                    'color' => '#DC2626',
                    'titulo' => 'Paciente Critico',
                    'detalle' => $p->patient_name . ' - Triage Rojo - ' . $p->symptoms,
                    'recomendacion' => 'Priorizar atencion inmediata. Considerar UCI.',
                ]);
            }
            if ($p->triage_level === 'Naranja') {
                $alertas->push([
                    'tipo' => 'urgente',
                    'icono' => 'fa-triangle-exclamation',
                    'color' => '#EA580C',
                    'titulo' => 'Paciente Urgente',
                    'detalle' => $p->patient_name . ' - Triage Naranja - ' . $p->symptoms,
                    'recomendacion' => 'Atencion prioritaria en proximos 15 minutos.',
                ]);
            }
        }

        if ($criticosSinDoctor > 0) {
            $alertas->push([
                'tipo' => 'sistema',
                'icono' => 'fa-robot',
                'color' => '#7C3AED',
                'titulo' => 'IA: Criticos sin asignar',
                'detalle' => $criticosSinDoctor . ' pacientes criticos esperando medico',
                'recomendacion' => 'Asignar medicos disponibles inmediatamente. Activar protocolo de emergencia.',
            ]);
        }

        if ($camas < 5 && $hospitalizados > 10) {
            $alertas->push([
                'tipo' => 'sistema',
                'icono' => 'fa-bed-pulse',
                'color' => '#DC2626',
                'titulo' => 'IA: Riesgo de saturacion',
                'detalle' => 'Solo ' . $camas . ' camas disponibles para ' . $hospitalizados . ' hospitalizados',
                'recomendacion' => 'Planificar altas tempranas. Preparar area de expansion. Contactar hospitales vecinos.',
            ]);
        }

        return view('especialidades.asistente-ia', compact(
            'user', 'misPacientes', 'criticosSinDoctor',
            'hospitalizados', 'camas', 'ambulancias', 'alertas'
        ));
    }

    public function consultar(Request $request)
    {
        $request->validate([
            'consulta' => 'required|string|max:1000',
        ]);

        $consulta = $request->consulta;
        $contexto = $request->contexto ?? 'medico';

        $apiKey = config('openai.api_key');

        if (!$apiKey || $apiKey === 'sk-tu-api-key-aqui' || empty(trim($apiKey))) {
            return response()->json([
                'respuesta' => $this->respuestaLocal($consulta),
                'fuente' => 'local',
                'aviso' => 'Modo local - Configure OPENAI_API_KEY en .env para respuestas de IA avanzada',
            ]);
        }

        try {
            $systemPrompt = "Eres un asistente medico especializado del sistema HealthNexus Hospitalario.
Ayudas a medicos especialistas con:
- Analisis de sintomas y posibles diagnosticos
- Recomendaciones de tratamiento basadas en evidencia
- Interpretacion de signos vitales
- Protocolos de emergencia
- Derivaciones y priorizaciones

IMPORTANTE: Siempre indicar que es asistencia y no reemplaza el criterio medico.
Responde en espanol, de forma clara y estructurada.
Usa emojis medicos para facilitar la lectura.";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $consulta],
                ],
                'max_tokens' => 800,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $respuesta = $data['choices'][0]['message']['content'] ?? 'Sin respuesta';

                \App\Models\AuditLog::create([
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'user_role' => auth()->user()->role,
                    'action' => 'Consulta IA Medica',
                    'module' => 'Asistente IA',
                    'ip_address' => $request->ip(),
                    'details' => Str::limit($consulta, 100),
                ]);

                return response()->json([
                    'respuesta' => $respuesta,
                    'fuente' => 'openai',
                ]);
            }

            return response()->json([
                'respuesta' => $this->respuestaLocal($consulta),
                'fuente' => 'local',
                'aviso' => 'Error de API - Usando respuestas locales',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'respuesta' => $this->respuestaLocal($consulta),
                'fuente' => 'local',
                'aviso' => 'Error de conexion - Usando respuestas locales',
            ]);
        }
    }

    private function respuestaLocal($consulta)
    {
        $consulta = strtolower($consulta);

        if (strpos($consulta, 'dolor pecho') !== false || strpos($consulta, 'cardiaco') !== false) {
            return "🫀 **Evaluacion Cardiaca**\n\n" .
                "Sintomas reportados sugieren evaluacion cardiaca:\n\n" .
                "📌 **Acciones inmediatas:**\n" .
                "- Realizar ECG de 12 derivaciones\n" .
                "- Monitorizar signos vitales\n" .
                "- Solicitar troponinas seriadas\n" .
                "- Evaluar factores de riesgo cardiovascular\n\n" .
                "🚨 **Criterios de urgencia:**\n" .
                "- Dolor opresivo + diaforesis\n" .
                "- Irradiacion a miembro superior izquierdo\n" .
                "- Disnea asociada\n\n" .
                "⚠️ *Asistencia IA - No reemplaza criterio medico*";
        }

        if (strpos($consulta, 'neurologico') !== false || strpos($consulta, 'convulsi') !== false || strpos($consulta, 'acv') !== false) {
            return "🧠 **Evaluacion Neurologica**\n\n" .
                "Sintomas sugieren evaluacion neurologica:\n\n" .
                "📌 **Acciones inmediatas:**\n" .
                "- Evaluacion escala Glasgow\n" .
                "- TC craneal urgente\n" .
                "- Monitorizar presion intracraneal\n" .
                "- Evaluar signos de focalidad\n\n" .
                "🚨 **Protocolo ACV:**\n" .
                "- Ventana terapeutica 4.5h\n" .
                "- Evaluar elegibilidad trombolisis\n" .
                "- Activar codigo ictus\n\n" .
                "⚠️ *Asistencia IA - No reemplaza criterio medico*";
        }

        if (strpos($consulta, 'pediat') !== false || strpos($consulta, 'nino') !== false) {
            return "👶 **Evaluacion Pediatrica**\n\n" .
                "Consideraciones pediatricas:\n\n" .
                "📌 **Puntos clave:**\n" .
                "- Ajustar dosis por peso corporal\n" .
                "- Evaluar estado de hidratacion\n" .
                "- Considerar causas especificas por edad\n" .
                "- Valorar signos de alarma pediatricos\n\n" .
                "⚠️ *Asistencia IA - No reemplaza criterio medico*";
        }

        if (strpos($consulta, 'crisi') !== false || strpos($consulta, 'emergencia') !== false || strpos($consulta, 'saturacion') !== false) {
            return "🚨 **Protocolo de Crisis Hospitalaria**\n\n" .
                "Modo Crisis activado:\n\n" .
                "📌 **Acciones inmediatas:**\n" .
                "- Bloquear consultas no urgentes\n" .
                "- Priorizar triage rojo\n" .
                "- Liberar camas de alta temprana\n" .
                "- Alertar todos los medicos A\n" .
                "- Activar farmacia en modo emergencia\n" .
                "- Contactar hospitales vecinos\n\n" .
                "📊 **Metricas actuales del sistema:**\n" .
                "- Pacientes criticos sin asignar\n" .
                "- Nivel de ocupacion por area\n" .
                "- Tiempos de espera\n\n" .
                "⚠️ *Asistencia IA - Activar protocolo institucional*";
        }

        return "🏥 **Asistente Medico HealthNexus**\n\n" .
            "Analisis de la consulta:\n\n" .
            "📌 **Recomendaciones generales:**\n" .
            "- Evaluar signos vitales completos\n" .
            "- Revisar antecedentes del paciente\n" .
            "- Considerar derivacion si excede especialidad\n" .
            "- Documentar hallazgos en sistema\n\n" .
            "💡 Para analisis mas detallado, configure la API de OpenAI en .env\n" .
            "Variables: OPENAI_API_KEY\n\n" .
            "⚠️ *Asistencia IA - No reemplaza criterio medico*";
    }
}
