<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Extraction;
use App\Models\Revenue;
use App\Models\Sector;
use App\Traits\ConvertMoneyTrait;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CollectController extends Controller
{
    use ConvertMoneyTrait;

    protected RemoteWebDriver $driver;

    private const CHROME_HOST = 'chrome:3000/webdriver';
    private const CHROME_ARGUMENTS = [
        '--disable-gpu',
        '--headless',
        '--no-sandbox',
        '--disable-dev-shm-usage',
    ];
    private const EXPECTED_COLUMNS = 8;
    private const URL = 'https://pt.wikipedia.org/wiki/Lista_das_maiores_empresas_do_Brasil';

    /**
     * Inicializa o WebDriver Chrome com as configurações pré-definidas.
     */
    public function __construct()
    {
        $options = new ChromeOptions();
        $options->addArguments(self::CHROME_ARGUMENTS);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->driver = RemoteWebDriver::create(self::CHROME_HOST, $capabilities);
    }

    /**
     * Ponto de entrada principal para coletar e processar dados.
     *
     * @return JsonResponse Resposta em JSON com o status da operação.
     */
    public function main(): JsonResponse
    {
        $response = $this->response();

        try {
            $data = $this->extractData();

            $this->saveData($data);

            $response->status = Response::HTTP_OK;
            $response->success = true;
            $response->data = $data;
        } catch (Throwable $e) {
            Log::error($e->getMessage());

            $response->errors = $e->getMessage();
        } finally {
            return response()->json($response, $response->status, $this->headers());
        }
    }

    /**
     * Extrai os dados da URL especificada e processa o conteúdo da tabela.
     *
     * @return array
     */
    private function extractData(): array
    {
        $data = [];

        try {
            $this->driver->get(url(self::URL));

            $table = $this->driver->findElement(WebDriverBy::xpath('//div[@id="bodyContent"]//table[1]'));

            $tbody = $table->findElement(WebDriverBy::tagName('tbody'));

            $rows = $tbody->findElements(WebDriverBy::tagName('tr'));

            foreach ($rows as $row) {
                $cells = $row->findElements(WebDriverBy::tagName('td'));

                if (count($cells) != self::EXPECTED_COLUMNS) continue;

                $texts = array_map(fn($cell) => $cell->getText(), $cells);

                list($id, $ranking, $name, $revenue, $profit, $asset, $value, $sector) = $texts;

                $data[$id] = [
                    'ranking' => (int) $ranking,
                    'name' => $name,
                    'revenue' => $this->convertToFloat($revenue),
                    'profit' => $this->convertToFloat($profit),
                    'asset' => $this->convertToFloat($asset),
                    'value' => $this->convertToFloat($value),
                    'sector' => $sector,
                ];
            }
        } finally {
            $this->driver->quit();
        }

        return $data;
    }

    /**
     * Salva os dados extraídos no banco de dados.
     *
     * @param array $data
     *
     * @return void
     */
    private function saveData(array $data): void
    {
        $extraction = Extraction::create([
            'url' => self::URL
        ]);

        foreach ($data as $value) {
            $sector = $this->getOrCreateSector($value['sector']);

            $company = $this->getOrCreateCompany($sector, $value['name']);

            Revenue::create([
                'extraction_id' => $extraction->id,
                'company_id' => $company->id,
                'ranking' => $value['ranking'],
                'revenue' => $value['revenue'],
                'profit' => $value['profit'],
                'asset' => $value['asset'],
                'value' => $value['value'],
            ]);
        }
    }

    /**
     * Obtém ou cria um setor pelo nome.
     *
     * @param string $name
     *
     * @return Sector
     */
    private function getOrCreateSector(string $name): Sector
    {
        $sector = Sector::firstOrNew(['name' => $name]);

        if (!$sector->exists) $sector->save();

        return $sector;
    }

    /**
     * Obtém ou cria uma empresa e a associa a um setor.
     *
     * @param Sector $sector
     * @param string $name
     *
     * @return Company
     */
    private function getOrCreateCompany(Sector $sector, string $name): Company
    {
        $company = Company::firstOrNew(['name' => $name]);

        if (!$company->exists) {
            $company->sector()->associate($sector);

            $company->save();
        }

        return $company;
    }
}
