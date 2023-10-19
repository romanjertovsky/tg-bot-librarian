<?php

namespace RomanJertovsky\TgBotLibrarian;

class Library
{

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
                if(isset($subArticle['title']))
                    $aCurrentDir[$subPath] = $subArticle['title'];
            }

        }

        return $aCurrentDir;
    }



    /**
     * @param $sRoute - маршрут/к/каталогу/сообщения (это НЕ путь к файлу)
     * Пустой '' маршрут - корень!
     * @return string
     */
    private function makeRoutePath($sRoute): string
    {
//        $sRoute = rtrim($sRoute, '/');
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



    // TODO переделать в отдельный объект
    public function getArticleArray($sRoute = ''): array
    {

        if($this->isLibRoteExist($sRoute)) {

            $sFullPath = $this->makeRoutePath($sRoute);

            if(file_exists($sFullPath . 'msg.json')) {

                $articleArray = json_decode(file_get_contents($sFullPath . 'msg.json'), true);

                // Полный путь к картинке в элемент 'image'
                if(!empty($articleArray['image']))
                    if(file_exists($sFullPath . $articleArray['image']))
                        $articleArray['image'] = $sFullPath . $articleArray['image'];
                    else
                        unset($articleArray['image']);

                return $articleArray;

            } else {

                return ['text' => 'Статья есть, но msg файла нет'];

            }

        } else {

            return ['text' => 'Пути не существует'];

        }
        
    }


    public function getWelcome(): string
    {

        if(file_exists(LIB_DIR . 'welcome.json')) {

            $aWelcome = json_decode(file_get_contents(LIB_DIR . 'welcome.json'), true);
            $sWelcome = $aWelcome['text'] ?? 'Добро пожаловать в нашу библиотеку [нет "text" в welcome.json]';

        } else

            $sWelcome = 'Добро пожаловать в нашу библиотеку [нет welcome.json]';

        return $sWelcome;

    }



}