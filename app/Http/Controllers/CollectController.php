<?php

namespace App\Http\Controllers;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Log;
use Throwable;

class CollectController extends Controller
{
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

    public function __construct()
    {
        $options = new ChromeOptions();
        $options->addArguments(self::CHROME_ARGUMENTS);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->driver = RemoteWebDriver::create(self::CHROME_HOST, $capabilities);
    }

    /**
     * Coleta os dados da página e retorna uma lista.
     *
     * @return array Lista de empresas coletadas.
     */
    public function index(): array
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

                $data[$id] = compact('ranking', 'name', 'revenue', 'profit', 'asset', 'value', 'sector');
            }
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        } finally {
            $this->driver->quit();

            return $data;
        }
    }
}
