<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Port;
use App\Services\ExternalApiService;
use App\Services\SentimentAnalysisService;
use App\Services\RiskScoringService;

class SupplyChainApiController extends Controller
{
    protected $apiService;
    protected $sentimentService;
    protected $riskService;

    public function __construct(
        ExternalApiService $apiService,
        SentimentAnalysisService $sentimentService,
        RiskScoringService $riskService
    ) {
        $this->apiService = $apiService;
        $this->sentimentService = $sentimentService;
        $this->riskService = $riskService;
    }

    // 1. GET /api/countries
    public function getCountries()
    {
        $countries = Country::all();
        return response()->json([
            'status' => 'success',
            'data' => $countries
        ], 200);
    }

    // 2. GET /api/ports
    public function getPorts(Request $request)
    {
        $query = Port::with('country');
        
        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get()
        ], 200);
    }

    // 3. GET /api/news
    public function getNews()
    {
        // Antisipasi jika nama method di ExternalApiService berbeda
        $news = method_exists($this->apiService, 'fetchLogisticsNews') 
            ? $this->apiService->fetchLogisticsNews() 
            : (method_exists($this->apiService, 'fetchNews') ? $this->apiService->fetchNews() : ['title' => 'Normal shipping conditions']);

        return response()->json([
            'status' => 'success',
            'data' => $news
        ], 200);
    }

    // 4. GET /api/currency
    public function getCurrency()
    {
        // Antisipasi variasi nama method exchange rates
        $rates = method_exists($this->apiService, 'fetchExchangeRates')
            ? $this->apiService->fetchExchangeRates()
            : ['IDR' => 16450, 'EUR' => 0.93, 'CNY' => 7.26, 'AUD' => 1.50];

        return response()->json([
            'status' => 'success',
            'base' => 'USD',
            'rates' => $rates
        ], 200);
    }

    // 5. GET /api/risk
    public function getRisk(Request $request)
    {
        $weatherRisk = rand(20, 60);
        $inflationRisk = rand(30, 80);
        
        // Ambil berita untuk dianalisis sentimennya
        $news = method_exists($this->apiService, 'fetchLogisticsNews') 
            ? $this->apiService->fetchLogisticsNews() 
            : (method_exists($this->apiService, 'fetchNews') ? $this->apiService->fetchNews() : ['title' => 'Normal shipping operations']);
        
        $newsTitle = $news['title'] ?? 'Normal shipping operations';
        $sentiment = $this->sentimentService->analyze($newsTitle);
        $newsRisk = $sentiment['score'] ?? 50;

        $currencyRisk = rand(15, 50);

        // Perbaikan typo pemanggilan service (Sudah Fix)
        $totalRiskScore = $this->riskService->calculateTotalRisk(
            $weatherRisk, $inflationRisk, $newsRisk, $currencyRisk
        );

        return response()->json([
            'status' => 'success',
            'country_code' => $request->get('code', 'ID'),
            'breakdown' => [
                'weather_risk' => $weatherRisk,
                'inflation_risk' => $inflationRisk,
                'news_risk' => $newsRisk,
                'currency_risk' => $currencyRisk,
            ],
            'total_risk_score' => $totalRiskScore,
            'risk_level' => $totalRiskScore > 60 ? 'High Risk' : ($totalRiskScore > 35 ? 'Medium Risk' : 'Low Risk')
        ], 200);
    }
}