<?php

namespace RomanJertovsky\TgBotLibrarian;

/*
 * Класс для работы с библиотекой.
 * В библиотеке каждый каталог представляет собой набор данных для генерации сообщения.
 *
 * Route - маршрут (для колбэков), каталоги разделены '/', в конце разделителя нет/
 * Пустой Route означает корневой каталог
 *
 * Path - путь каталогу в файловой системе, каталоги разделены 'DIRECTORY_SEPARATOR'
 * начинается с корневого каталога, заканчивается разделителем
 *
 */
class Library
{

    /**
     * @param $sRoute
     * @return array
     */
    public function getDirTitles($sRoute): array
    {

        $sRoute = ltrim($sRoute, '/');

        if($this->isLibRoteExist($sRoute))
            $sPath = $this->makeRoutePath($sRoute);
        else
            return [];

        $aScanDir = scandir($sPath);

        $aCurrentDir = [];

        foreach ($aScanDir as $item) {

            if($item === '.' || $item === '..')
                continue;

            if(is_dir($sPath . $item)) {
                $subPath = (empty($sRoute) ? '' : $sRoute . '/') . $item;
                $subArticle = $this->getArticleArray($subPath);
                if(isset($subArticle['title'])) {

                    $aCurrentDir[$subPath] =
                        (isset($subArticle['premium']) ? '⭐️ ' : '') . $subArticle['title'];
                }
            }

        }

        return $aCurrentDir;
    }



    /**
     * Возвращает полный путь к каталогу сообщения из маршрута,
     * заменяя '/' разделителями каталогов
     * @param $sRoute - маршрут/к/каталогу/сообщения (это НЕ путь к файлу)
     * Пустой '' маршрут - корень!
     * @return string - 'LIB_DIR/маршрут/к/каталогу/сообщения/' или ''
     */
    private function makeRoutePath($sRoute): string
    {
        return
            LIB_DIR .
            str_replace('/', DIRECTORY_SEPARATOR, $sRoute) .
            (empty($sRoute) ? '' : DIRECTORY_SEPARATOR);
    }



    /**
     * Проверка существования пути по маршруту
     * @param $sRoute
     * @return bool
     */
    public function isLibRoteExist($sRoute): bool
    {
        $sFullPath = $this->makeRoutePath($sRoute);
        return is_dir($sFullPath);
    }


    /**
     * @param $sRoute
     * @param $jsonFile - имя файла-статьи, если нужно другое
     * @return string[]
     */
    public function getArticleArray($sRoute = '', $jsonFile = 'msg.json'): array
    {

        if($this->isLibRoteExist($sRoute)) {

            // Полный путь к каталогу библиотеки
            $sFullPath = $this->makeRoutePath($sRoute);

            // Если в каталоге есть нужный json
            if(file_exists($sFullPath . $jsonFile)) {

                $articleArray = json_decode(file_get_contents($sFullPath . $jsonFile), true);

                // Ошибка JSON
                if (json_last_error() !== 0)
                    return $this->makeErrMsg('getArticleArray: ошибка JSON: ' . json_last_error_msg());

                // Проверка наличия изображения
                if(isset($articleArray['image'])) {

                    // Если изображение есть в каталоге
                    if(file_exists($sFullPath . $articleArray['image']))
                        // Есть: подстановка полного пути
                        $articleArray['image'] = $sFullPath . $articleArray['image'];
                    else
                        // Нет: удаление элемента массива
                        unset($articleArray['image']);

                }

                // Проверка наличия файла-статьи
                if(isset($articleArray['ext'])) {

                    // Если файл есть в каталоге
                    if(file_exists($sFullPath . $articleArray['ext']))
                        // Есть, загрузка содержимого в text
                        $articleArray['text'] .= file_get_contents($sFullPath . $articleArray['ext']);
                    else
                        // Нет: удаление элемента массива
                        unset($articleArray['ext']);

                }

                return $articleArray;

            } else {

                return $this->makeErrMsg("ОШИБКА getArticleArray: путь $sRoute есть, но msg.json нет.");

            }

        } else {

            return  $this->makeErrMsg("ОШИБКА getArticleArray: пути $sRoute не существует в дереве каталогов.");

        }
        
    }


    /**
     * @param string $sErrorMessage сообщение об ошибке, будет записано в log_err_postfix
     * но возвращено пользователю будет только в случае debug = true
     * @return string[] - подробное описание ошибки либо дежурное сообщение из msg_error.json
     */
    private function makeErrMsg(string $sErrorMessage): array
    {
        plogErr($sErrorMessage);

        if(env('debug'))
            return ['text' => $sErrorMessage];
        else
            return $this->getArticleArray('', 'msg_error.json');
    }

}