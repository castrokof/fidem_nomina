<?php
// app/Services/CitasApiService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class CitasApiService
{
    protected $baseUrl;
    protected $email;
    protected $password;

    public function __construct()
    {
        $this->baseUrl = config('services.citas_api.base_url') 
            ?: env('CITAS_API_BASE_URL', 'http://172.200.35.22:8004/api');
        
        $this->email = config('services.citas_api.email') 
            ?: env('CITAS_API_EMAIL', 'sistemas@clinicafidem.com');
        
        $this->password = config('services.citas_api.password') 
            ?: env('CITAS_API_PASSWORD', 'LorecaS1508***');
    }

    /**
     * Obtiene el token de acceso
     * Respuesta API: {"res": true, "token": "xxx"}
     */
    public function getToken()
    {
        return Cache::remember('citas_api_token', 240, function () {
            $url = $this->baseUrl . '/acceso';
            
            Log::info("Solicitando token API: {$url}");
            
            $response = Http::timeout(30)->post($url, [
                'email' => $this->email,
                'password' => $this->password
            ]);

            if (!$response->successful()) {
                $errorMsg = "Error HTTP " . $response->status() . ": " . $response->body();
                Log::error($errorMsg);
                throw new Exception($errorMsg);
            }

            $data = $response->json();

            if (!is_array($data)) {
                throw new Exception("Respuesta de autenticación no es JSON válido");
            }

            // ✅ Estructura confirmada: {"res": true, "token": "xxx"}
            if (isset($data['token']) && is_string($data['token']) && !empty($data['token'])) {
                Log::info("Token obtenido exitosamente");
                return $data['token'];
            }

            Log::error("Token no encontrado. Respuesta: " . json_encode($data));
            throw new Exception("Token no encontrado en respuesta de autenticación");
        });
    }

    /**
     * Obtiene citas del API con filtros de fecha
     * Respuesta API: {"success": true, "data": [...]}
     */
    public function getCitasPorRango($fechaInicio, $fechaFin)
    {
        $token = $this->getToken();

        Log::info("Consultando citas: {$fechaInicio} a {$fechaFin}");

        $response = Http::withToken($token)
            ->timeout(240)
            ->post($this->baseUrl . '/citas', [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin'    => $fechaFin,
            ]);

        if (!$response->successful()) {
            $errorMsg = "Error HTTP " . $response->status() . " al obtener citas: " . $response->body();
            Log::error($errorMsg);
            throw new Exception($errorMsg);
        }

        $data = $response->json();

        if (!is_array($data)) {
            return [];
        }

        // ✅ Estructura confirmada: {"success": true, "data": [...]}
        if (isset($data['data']) && is_array($data['data'])) {
            Log::info("Citas obtenidas: " . count($data['data']));
            return $data['data'];
        }

        // Fallback: si viene array directo
        if (isset($data[0]) || empty($data)) {
            return $data;
        }

        Log::warning("Estructura de citas no reconocida: " . json_encode(array_keys($data)));
        return [];
    }

    public function clearTokenCache()
    {
        Cache::forget('citas_api_token');
    }

    
    public function getCitaPorIdRegistro($idRegistro)
{
    $token = $this->getToken();
    
    Log::info("Consultando cita específica (POST): ID_REGISTRO={$idRegistro}");
    
    $url = $this->baseUrl . '/citas';
    
    $postData = json_encode([
        'id_registro' => $idRegistro,
    ]);
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
            'Content-Length: ' . strlen($postData)
        ],
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        Log::error("Error cURL cita específica: " . $error);
        return null;
    }
    
    if ($httpCode !== 200) {
        Log::error("Error HTTP {$httpCode} en cita específica: " . $response);
        return null;
    }
    
    $data = json_decode($response, true);
    
    if (!is_array($data)) {
        return null;
    }
    
    // Si viene {"success": true, "data": {...}}
    if (isset($data['data']) && is_array($data['data'])) {
        // Si es un solo registro
        if (isset($data['data']['ID_REGISTRO'])) {
            return $data['data'];
        }
        // Si es array de un elemento
        if (isset($data['data'][0])) {
            return $data['data'][0];
        }
    }
    
    // Si viene directo el objeto
    if (isset($data['ID_REGISTRO'])) {
        return $data;
    }
    
    return null;
}
}