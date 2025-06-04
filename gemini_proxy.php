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

$cvContent = "";
$cvContent .= "Sobre Mí: ¡Hola! Soy Lydia Fernández, una apasionada Desarrolladora Web. He finalizado mi formación como desarrolladora de aplicaciones con tecnologías web. Me encanta crear soluciones innovadoras y eficientes. Tengo muchas ganas de trabajar y seguir aprendiendo cosas nuevas. Aquí puedes encontrar algunos de mis trabajos y mi información de contacto. Datos de Contacto: Email: lydiafdez84@gmail.com, Teléfono: 628169139, GitHub: Daly2025.";
$cvContent .= "\n\nProyectos: Miel Pura: Un proyecto web sobre la apicultura y productos de miel. Ver Proyecto: https://lidiafernandez.pythonanywhere.com/. Planes de Excursiones: Plataforma de gestión de actividades al aire libre. Ver Proyecto: https://daly2025.ct.ws/. daly2020: Un proyecto web de ejemplo. Ver Proyecto: https://daly2020.atwebpages.com/. Rehuerta: Un proyecto web sobre huertos urbanos. Ver Proyecto: https://puskas.ct.ws/.";
$cvContent .= "\n\nExperiencia Laboral: SOPORTE TECNICO INSTALACIONES en TEX DIGITAL (octubre 2009 - septiembre 2011): Soporte a tecnicos de campo para la resolucion de incidencias y tareas programadas en radioenlaces, solicitud de accesos y repuestos y documentacion asociada a la incidencia. TELEOPERADORA SOPORTE TECNICO VODAFONE en COREMAIN (Octubre 2013 - 2015): Atención telefónica y soporte técnico a clientes de Vodafone particulares. TELEOPERADORA SOPORTE TÉCNICO CORPORATE en SATEC (Marzo 2015 - Diciembre 2017): Atención telefónica y soporte técnico a clientes corporativos para la resolucion de incidencias de red movil y red fija. SOPORTE TECNICO TPV en CISER (Para ABANCA) (Enero 2019 - Enero 2022): Soporte a tecnicos para la instalacion y configuracion de tpv.";
$cvContent .= "\n\nEducación: Certificado de Profesionalidad nivel 3 en desarrollo de aplicaciones web (12.2024 - 06.2025): Desarrollo de aplicaciones web con HTML, CSS, JavaScript y PHP. Bachillerato artistico en IES Politecnico (2001-2003).";
$cvContent .= "\n\nHabilidades: Lenguajes de Programación: JavaScript, Python, Java, Css, Php, Html. Frameworks/Librerías: React, Django, Flask, Spring Boot. Conocimientos de Redes: Cisco, Juniper, Teldat. Programas: Clarify, Abalon, Remedy, Hlr, Spirit... Bases de Datos: MySQL.";

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