<?php

require 'vendor/autoload.php'; // Include Composer's autoloader

use Google\Cloud\AIPlatform\V1beta1\PredictionServiceClient;
use Google\Cloud\AIPlatform\V1beta1\GenerateContentRequest;
use Google\Cloud\AIPlatform\V1beta1\Part;
use Google\Cloud\AIPlatform\V1beta1\Content;

header('Content-Type: application/json');

// Allow cross-origin requests for development. In production, restrict this to your domain.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Handle OPTIONS request for CORS preflight
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

if (empty($userMessage)) {
    echo json_encode(['error' => 'No message provided']);
    exit();
}

// Replace with your actual API key from Google AI Studio
$apiKey = 'AIzaSyBzytUQBSnBGq_4vpy2YfEqTgMa9RBVtlA';

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

$cvContent = "\nSobre Mí:\n  Soy una persona proactiva, organizada y responsable, con gran capacidad de adaptación y aprendizaje. Disfruto trabajando en equipo y siempre busco superar nuevos retos.\n  Fecha de nacimiento: 17.01.1984\n  Nacionalidad: Española\n  Lugar de nacimiento: Orense\n  Originaria de: Verín\n\n  Proyectos:
    - Miel Pura: Un proyecto web sobre la apicultura y productos de miel. (https://lidiafernandez.pythonanywhere.com/)
    - Planes de Excursiones: Plataforma de gestión de actividades al aire libre. (https://daly2025.ct.ws/)
    - daly2020: Un proyecto web de ejemplo. (https://daly2020.atwebpages.com/)
    - Rehuerta: Un proyecto web sobre huertos urbanos. (https://puskas.ct.ws/)\n\n  Experiencia Laboral:\n    - Empresa X: Desarrollador Web Junior (2022-2023)\n    - Empresa Y: Asistente Administrativo (2019-2021)\n\n  Educación:\n    - Bachillerato Artístico (Politécnico de Vigo)
    - Ciclo Superior de Secretariado
    - Certificado de Profesionalidad Nivel 3 de Programación
    - Curso Selección de personal (2006)
    - Curso Secretariado (Academia Manzaneda, 2005)
    - Curso Formador (Vidisco Sl, 2005)
    - Curso de excelencia telefónica (Femxa, 2008)
    - Curso empleado de oficina (Inem, 2009)
    - Curso atención al cliente y consumidor (Inem, 2008)\n\n  Habilidades:\n    - Programación: HTML, CSS, JavaScript, PHP, Python\n    - Bases de Datos: MySQL, PostgreSQL\n    - Herramientas: Git, Docker, VS Code\n    - Idiomas: Español (nativo), Gallego (nativo), Inglés (B1), Portugués (básico), Francés (básico)\n\n  Contacto:\n    - Email: lydiafdez84@hotmail.com\n    - Teléfono: 628169139\n    - LinkedIn: [tu_perfil_linkedin]\n    - GitHub: [tu_perfil_github]";

$prompt = "Basado en el siguiente contenido de CV, responde a la pregunta del usuario. Si la información no está en el CV, indícalo.\n\nCV: " . $cvContent . "\n\nPregunta del usuario: " . $userMessage;

$data = [
    'contents' => [
        [
            'parts' => [
                ['text' => $prompt]
            ]
        ]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    echo json_encode(['error' => 'Error al conectar con la API de Gemini: ' . curl_error($ch)]);
    exit();
}

$responseData = json_decode($response, true);

if ($httpCode !== 200) {
    $errorMessage = 'Error de la API de Gemini: ' . ($responseData['error']['message'] ?? 'Error desconocido');
    echo json_encode(['error' => $errorMessage]);
    exit();
}

$generatedText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'No se pudo generar una respuesta.';

echo json_encode(['text' => $generatedText]);

?>