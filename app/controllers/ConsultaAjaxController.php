<?php
class ConsultaAjaxController extends Controller{    
    public function buscar_dni() {
        $dni = $_GET['dni'];
        $token = 'apis-token-15310.8bbaAe16t6MfPH8MAg80V5yeUXaHhSPV'; // Poner Token

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.apis.net.pe/v2/reniec/dni?numero=' . $dni,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Referer: https://apis.net.pe/consulta-dni-api',
                'Authorization: Bearer ' . $token
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {
            echo $response;
        } else {
            echo json_encode(['error' => 'No se pudo consultar el DNI']);
        }
    }

    public function validar_correo_real1() {
        try {
            if (!isset($_GET['correo'])) {
                echo json_encode(['success' => false, 'message' => 'Correo no recibido']);
                return;
            }

            $email = $_GET['correo'];
            //$apiKey = '1e62b3d6633e4f9daaf6cb9df3ab32e3'; Quedan 52
            $apiKey = 'a555cca58d304c67b75c92e84a599c6c'; // Quedan 99
            $certPath = __DIR__ . '/../certs/cacert.pem';

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://emailvalidation.abstractapi.com/v1/?api_key=$apiKey&email=" . urlencode($email),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CAINFO => $certPath,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 15
            ]);

            $response = curl_exec($curl);
            $error = curl_error($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($error) {
                throw new Exception("Error de conexión: $error");
            }

            $data = json_decode($response, true);

            // Validar estructura de respuesta
            if ($httpCode != 200 || json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Respuesta inválida de la API");
            }

            // Determinar validez
            $esValido = $data['is_valid_format']['value'] &&
                $data['deliverability'] === 'DELIVERABLE';

            echo json_encode([
                'success' => true,
                'valid' => $esValido,
                'message' => $esValido
                    ? 'Correo válido y entregable ✅'
                    : 'Correo no válido o no existente ❌'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error en validación: ' . $e->getMessage()
            ]);
        }
    }

    public function buscar_proveedor() {
        $ruc = $_GET['ruc'];
        $token = 'apis-token-15310.8bbaAe16t6MfPH8MAg80V5yeUXaHhSPV'; // Poner Token

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.apis.net.pe/v2/sunat/ruc?numero=' . $ruc,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Referer: http://apis.net.pe/api-ruc',
                'Authorization: Bearer ' . $token
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {
            echo $response;
        } else {
            echo json_encode(['error' => 'No se pudo consultar el RUC']);
        }
    }

}