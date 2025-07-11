<?php

/**
 * Класс Game реализует исключительно техническую часть игры:
 *  - главный цикл
 *  - последовательность запусков логики
 *  - контрольные проверки
 *  - сохранение и загрузка состояния
 *
 * При этом, класс Game не реализует конкретно бизнес-логику игры!
 */
class Game
{
    public $market;
    public $turnNumber;
    public $scenario;
    public $history = [];
    public $state_filename = 'state.ser';

    public function __construct()
    {
        $this->turnNumber = 1;
        $this->scenario = new Scenario();
        $this->market = new Market(new Factors());
    }

    public function loadState()
    {
        if (!file_exists($this->state_filename)) {
            // this is first initial start - skip loading from file
            return true;
        }
        $r = file_get_contents($this->state_filename);
        if ($r === false) {
            throw new Exception("Error reading the state file " . $this->state_filename);
        }
        $data = unserialize($r);
        if ($data === null) {
            throw new Exception("State unserialize failed" . $r);
        }
        if (!isset($data["turnNumber"])) {
            throw new Exception("State unserialize failed: not isset turnNumber" . $r);
        }
        if (!isset($data["market"])) {
            throw new Exception("State unserialize failed: not isset market" . $r);
        }
        if (!isset($data["history"])) {
            throw new Exception("State unserialize failed: not isset history" . $r);
        }
        $this->history = $data["history"];
        $this->turnNumber = $data["turnNumber"];
        $this->market = $data["market"];

        return true;
    }

    public function saveState()
    {
        $data = [
            'turnNumber' => $this->turnNumber,
            'market' => $this->market,
            'history' => $this->history,
        ];
        $r = file_put_contents($this->state_filename, serialize($data));
        if ($r === false) {
            throw new Exception("Error writing the state to the file  " . $this->state_filename);
        }
        return true;
    }

    public function updateCompanies()
    {
        //
    }

    public function updateHistory()
    {
        $this->history[$this->turnNumber] = clone $this->market;
    }

    public function main()
    {
        // загружаем состояние игры: номер хода, объект Market и список компаний
        $this->loadState();

        // запуск сценария для данного номера хода
        // сценарий может изменить параметры Рынка и Параметры каждой компании
        $this->scenario->run($this->market, $this->turnNumber);

        // по каждой компании загружаем данные ее хода
        // если загрузка невозможна хотя бы для одной компании - то не продолжаем дальше цикл
        $this->market->loadCompaniesTurnData();

        // Обновляем вычислимые поля Market на основе текущего списка компаний и их значений
        $this->market->updateComputableValues();

        // Вычисляем новое состояние каждой компании
        // В каждом объекте компанни будут заполнены атрибуты:
        //  - attr - состояние перед ходом
        //  - turn - как сходила компания
        //  - calc - вычислимые поля
        //  - nattr - новое состояние компании
        $this->market->updateCompanies();

        // Сохраняем в историю текущее состояние игры под номером хода
        $this->updateHistory();

        Logger::log("TURN = " . $this->turnNumber);
        Logger::companies(
            $this->market->companies,
            [],
            $this->market->toArray('m_')
        );

        // Подготавливаем состояние игры к следующему ходу
        $this->turnNumber++;
        $this->market->cleanUp();
        $this->saveState();
    }
}