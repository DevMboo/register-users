<?php

namespace App\View;

class View
{

    protected static $component;
    protected static $template;
    protected static $title;

    public static function render($component, $data = [], $template = "pages/views/index.html")
    {
        self::$component = $component;
        self::$template = $template;

        $templateContent = self::getFileContent(self::$template);
        
        $templateContent = self::processComponents($templateContent);

        $componentContent = self::getFileContent("pages/views/components/" . self::$component);
        $componentContent = self::replacePlaceholders($componentContent, $data);
        $componentContent = self::processComponents($componentContent);

        $finalContent = str_replace('@render(content)', $componentContent, $templateContent);
        $finalContent = self::processTitle($finalContent, self::$title); // Aqui usamos self::$title

        echo $finalContent;

        return new static; // Permite encadear outros métodos
    }

    private static function processTitle($template, $title) 
    {
        if ($title) {
            return str_replace('@title', htmlspecialchars($title, ENT_QUOTES, 'UTF-8'), $template);
        } else {
            return str_replace('@title', htmlspecialchars(getenv('APP_NAME'), ENT_QUOTES, 'UTF-8'), $template);
        }
    }

    public static function title($title)
    {
        self::$title = $title; // Aqui também usamos self::$title
        return new static; // Permite encadear
    }

    private static function getFileContent($filePath)
    {
        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }
        return "";
    }

    private static function replacePlaceholders($content, $data)
    {
        foreach ($data as $key => $value) {
            $placeholder = "{{" . $key . "}}";
            $content = str_replace($placeholder, htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $content);
        }
        return $content;
    }

    private static function processComponents($content)
    {
        $pattern = '/@component\(\s*"([^"]+)"\s*(?:,\s*(\[[^\]]*\]))?\)/';

        $content = preg_replace_callback($pattern, function ($matches) {
            $path = $matches[1];
            $dataArray = [];

            if (isset($matches[2])) {
                $dataArray = self::parseDataArray($matches[2]);
            }

            return self::component($path, $dataArray);
        }, $content);

        return $content;
    }


    private static function parseDataArray($dataString)
    {
        $dataString = str_replace("'", '"', $dataString);
        $dataString = preg_replace('/(\w+)\s*=>/', '"$1":', $dataString);

        return json_decode($dataString, true) ?: [];
    }

    public static function component($path, $data = [])
    {
        $componentContent = self::getFileContent("pages/components/" . $path . '.html');

        return self::replacePlaceholders($componentContent, $data);
    }

}
