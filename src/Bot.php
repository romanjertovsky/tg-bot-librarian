<?php

namespace RomanJertovsky\TgBotLibrarian;


/*
 * В этом классе - вся логика работы бота
 */
class Bot
{

    private TgPost      $oTgPost;
    private Library     $oLibrary;
    private MessageIn   $oMessageIn;
    private MessageOut  $oMessageOut;
    private Subscriber  $oSubscriber;

    private array $mainKeyboard = [
        'main_menu' => 'Главное меню 📋',
        'premium'   => '⭐️ Hi-level доступ',
        'creators'  => 'Создатели 👨‍💻',
        'call_back' => 'Обратная связь ✉️',
    ];


     public function __construct()
    {

        $this->oTgPost        = new TgPost();
        $this->oLibrary       = new Library();
        $this->oMessageIn     = new MessageIn();

        if(is_null($this->oMessageIn->getUsername()))
            errorDie('username не может быть пустым');

        $this->oMessageOut    = new MessageOut();
        $this->oSubscriber    = new Subscriber($this->oMessageIn->getUsername());

    }


    public function run(): void
    {

        if($this->oMessageIn->isCallback()) {
        // Если нажата inline-кнопка

            $this->processCallback();

        } elseif (!empty($this->oMessageIn->getText())) {
        // Если отправлено текстовое сообщение

            $this->processTextMessage();

        } else {


            plogErr('В Bot->run() не выбран подходящий вариант!');

        }

        $this->oMessageOut->setChatId($this->oMessageIn->getChatId());
        $this->oTgPost->sendMessage($this->oMessageOut);

    }


    /**
     * Процесс ответа на колбэки
     * @return void
     */
    private function processCallback(): void
    {

        // Подготавливаю маршрут и загружаю массив статьи
        $sRoute = $this->oMessageIn->getCallbackData();
        $aArticle = $this->oLibrary->getArticleArray($sRoute);

        // Если статья премиум, а подписчик нет
        if(isset($aArticle['premium']) &&
            !$this->oSubscriber->isPremium()
        ) {
            // Беру из статьи "интро"
            $sIntro = $aArticle['intro'];
            // Перезаписываю массив статьи промо-сообщением
            $aArticle = $this->oLibrary->getArticleArray('', 'msg_premium.json');
            // Добавляю "интро" перед текстом промо-сообщения
            $aArticle['text'] = $sIntro . PHP_EOL . PHP_EOL . $aArticle['text'];
        }

        // Устанавливаю в сообщение текст и картинку, если она есть
        $this->oMessageOut->setText($aArticle['text']);
        $this->oMessageOut->setImage($aArticle['image'] ?? null);

        // Загружаю дерево подкаталогов статьи
        $aDirTitles = $this->oLibrary->getDirTitles($sRoute);

        // Если статья не из корня, то добавляю в дерево кнопку "Назад"
        if($sRoute != '/') {
            $sBackWay = mb_substr($sRoute, 0,strripos($sRoute, '/'));
            $sBackWay = $sBackWay ?: '/';
            $aDirTitles[$sBackWay] = "⬅️ Назад";
        }

        // Устанавливаю inline-клавиатуру для подкаталогов
        $this->oMessageOut->setInlineKeyboardFromDirs($aDirTitles);
        
    }


    /**
     * Процесс ответа на текстовые сообщения
     * @return void
     */
    private function processTextMessage(): void
    {

        switch ($this->oMessageIn->getText()) {

            // /start
            case '/start':

                $this->processMainKeyboard();

                $this->oMessageOut->setText(
                    $this->oLibrary->getArticleArray('', 'msg_welcome.json')['text']
                );

                break;

            // Главное меню
            case $this->mainKeyboard['main_menu']:

                $aArticle = $this->oLibrary->getArticleArray('');

                $this->oMessageOut->setText($aArticle['text']);
                $this->oMessageOut->setImage($aArticle['image']);

                $aDirTitles = $this->oLibrary->getDirTitles('');
                $this->oMessageOut->setInlineKeyboardFromDirs($aDirTitles);

                break;

            // Подключить премиум
            case $this->mainKeyboard['premium']:

                $this->oMessageOut->setText('Тут будет описание получения премиум-доступа');

                break;

            // Создатели
            case $this->mainKeyboard['creators']:

                $this->oMessageOut->setText('Инфа о создателях');

                break;

            // Обратная связь
            case $this->mainKeyboard['call_back']:

                $this->oMessageOut->setText('Инфа, как оставить отзыв');

                break;

            // Остальные сообщения
            default:

                $this->oMessageOut->setText('`Don\'t know o_O`');

                break;


        }

    }


    /**
     * Установка клавиатуры-главного меню
     * @return void
     */
    private function processMainKeyboard(): void
    {

        $this->oMessageOut->setKeyboard([
            [
                [
                    'text'  => $this->mainKeyboard['main_menu']
                ]
            ],
            [
                [
                    'text'  => $this->mainKeyboard['premium']
                ],
                [
                    'text'  => $this->mainKeyboard['creators'],
                ],
                [
                    'text'  => $this->mainKeyboard['call_back'],
                    //'url' => 'https://YOUR/BUTTON/URL'
                ],
            ]
        ]);

    }

}