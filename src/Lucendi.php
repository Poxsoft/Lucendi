<?php

namespace Lucendi;

use Exception;
use Symfony\Component\HttpFoundation\Request;

class Lucendi
{
    protected $apiURL = 'https://www.larapox.com/';

    protected $apiKey;
    protected $apiSecret;
    protected $apiUser;
    protected $headers;
    protected $envValues = [];
    
    protected $endBusinessPoint = '/api/business';
    protected $endFreelancerPoint = '/api/freelancer';
    protected $endPluginPoint = '/api/plugin';

    public function __construct()
    {
        $envContent = file_get_contents(base_path('.env'));
        $variables = ['LARAPOX_APP_KEY', 'LARAPOX_APP_SECRET', 'LARAPOX_APP_USERNAME'];
        foreach ($variables as $variable) { if (preg_match("/^$variable=(.*)$/m", $envContent, $matches)) { $this->envValues[$variable] = trim($matches[1]); }else{ $this->envValues[$variable] = false; } }
        $this->apiKey =  htmlspecialchars($this->envValues['LARAPOX_APP_KEY'], ENT_QUOTES, 'UTF-8');
        $this->apiSecret = htmlspecialchars($this->envValues['LARAPOX_APP_SECRET'], ENT_QUOTES, 'UTF-8');
        $this->apiUser = htmlspecialchars($this->envValues['LARAPOX_APP_USERNAME'], ENT_QUOTES, 'UTF-8');
        if (empty($this->apiURL)) { throw new \Exception("The API URL is incorrect or not provided properly."); }else{ if (empty($this->apiKey) || empty($this->apiSecret) || empty($this->apiUser)) { throw new \Exception("The API keys are not configured correctly."); } }
        $this->headers = [ 'Authorization: Bearer ' . htmlspecialchars($this->apiKey, ENT_QUOTES, 'UTF-8'), 'Content-Type: application/json' ];
    }    
    
    public function checkEndPoint($endpoint)
    {
        $url = $this->apiURL . ltrim($endpoint, '/');
        if (!empty($this->envValues)) { $query = http_build_query($this->envValues); $url .= '?' . $query; }
        if (strpos($url, 'https') !== 0) { throw new \Exception("Insecure connection, HTTPS is required."); }
        return $url;
    }

    public function curlExecute($url,$transfer,$timeout,$nobody,$header){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $transfer);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_NOBODY, $nobody); 
        if($header != null){ curl_setopt($ch, CURLOPT_HTTPHEADER, $header); }
        $response = curl_exec($ch);
        if (curl_errno($ch)) { throw new Exception('Error: ' . curl_error($ch)); }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode < 200 || $httpCode >= 400) { throw new Exception("The endpoint is unavailable or inaccessible. HTTP code: " . $httpCode); }
        if($header != null){
            $decoded = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) { throw new \Exception("Error decoding the JSON response."); }
            return $decoded; 
        }        
    }

    public function getData(){ try { $responseBusiness = self::getBusiness(); $responseFreelancer = self::getFreelancer(); $responsePlugins = self::getPlugins(); return response()->json([ "business" => $responseBusiness, "freelancer" => $responseFreelancer, "plugins" => $responsePlugins ]); } catch (\Exception $e) { return response()->json(['Error' => $e->getMessage()], 500); } }
    public function getBusiness(){ return self::curlExecute(self::checkEndPoint($this->endBusinessPoint),true,10,true,$this->headers); }
    public function getFreelancer(){ return self::curlExecute(self::checkEndPoint($this->endFreelancerPoint),true,10,true,$this->headers); }
    public function getPlugins(){ return self::curlExecute(self::checkEndPoint($this->endPluginPoint),true,10,true,$this->headers); }
}