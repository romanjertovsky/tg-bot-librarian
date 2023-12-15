<?php

namespace RomanJertovsky\TgBotLibrarian\Library;

/**
 * Здесь всё, что может быть в статье
 */
class Article
{

    private ?string  $title;      // Заголовок (идёт на кнопку)
    private ?string  $text;       // Основной текст (недоступен если премиум)
    private ?string  $image;      // Картинка
    private ?string  $intro;      // Интро без премиум доступа
    private bool    $premium;    // Статья с премиум доступом
    private bool    $test;       // Статья тестовая

    private array $errors;


    /**
     * @param string $sRoute - маршрут к каталогу статьи
     * @param string $jsonFile - имя файла статьи в каталоге (по умолчанию msg.json)
     */
    public function __construct(string $sRoute, string $jsonFile = 'msg.json')
    {

        $articleArray = $this->loadArticleArray($sRoute, $jsonFile);

        $this->title    = $articleArray['title']    ?? null;
        $this->text     = $articleArray['text']     ?? null;
        $this->intro    = $articleArray['intro']    ?? null;
        $this->premium  = $articleArray['premium']  ?? false;
        $this->test     = $articleArray['test']     ?? false;

        // Звезда к премиум-заголовку
        if($this->isPremium())
            $this->title .= '⭐️ ';


        if(isset($articleArray['image'])) {
            $sImagePath = Catalog::makeRoutePath($sRoute) . $articleArray['image'];
            $this->image =  file_exists($sImagePath) ? $sImagePath : null;
        } else {
            $this->image = null;
        }


        // Проверка наличия доп. файла-статьи
        if(isset($articleArray['ext'])) {

            if(Catalog::isRouteExist($sRoute, $articleArray['ext']))
                $this->text .= file_get_contents(
                    Catalog::makeRoutePath($sRoute) . $articleArray['ext']);
            else
                $this->makeErrMsg("Article->__construct: Запрошен несуществующий ext файл: [$sRoute]/[$jsonFile]");

        }


        // Если есть ошибки, и включен debug добавляю сообщение о них в конец text
        if(!empty($this->errors))
            if(env('debug'))
                $this->text .=
                    "\n<code>" . implode("\n", $this->errors) . "</code>";
            else
                $this->text .= $this->loadArticleArray('', 'msg_error.json')['text'];

    }



    /**
     * Метод загружает json статьи и возвращает массив
     * @param string $sRoute
     * @param string $jsonFile
     * @return array
     */
    private function loadArticleArray(string $sRoute, string $jsonFile = 'msg.json'): array
    {

        if (!Catalog::isRouteExist($sRoute, $jsonFile)) {
            $this->makeErrMsg("Article->loadArticleArray: Запрошен несуществующий маршрут: [$sRoute]/[$jsonFile]");
            return [];
        }

        $filePath = Catalog::makeRoutePath($sRoute) . $jsonFile;
        $articleArray = json_decode(file_get_contents($filePath),true);

        if(json_last_error() !== JSON_ERROR_NONE ) {
            $this->makeErrMsg("getArticleArray, ошибка json [$sRoute]/[$jsonFile]: " . json_last_error_msg());
            return [];
        }

        return $articleArray;

    }


    /**
     * @param string $sErrorMessage сообщение об ошибке, будет записано в log_err_postfix
     * но возвращено пользователю будет только в случае debug = true
     * @return void - подробное описание ошибки либо дежурное сообщение из msg_error.json
     */
    private function makeErrMsg(string $sErrorMessage): void
    {
        plogErr($sErrorMessage);
        $this->errors[] = $sErrorMessage;
    }



    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function isPremium(): bool
    {
        return $this->premium;
    }

    public function isTest(): bool
    {
        return $this->test;
    }

}
