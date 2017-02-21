<?php

include __DIR__ . '/../../../vendor/autoload.php';

$localeDir = __DIR__;

$dirs = [
    __DIR__ . '/../'
];

$toTranslate = [
    'Value is required and can\'t be empty'
];

$translatorApiUri = 'https://translate.yandex.net/api/v1.5/tr.json/translate';
$translatorKey = 'trnsl.1.1.20170209T132126Z.979b34845c1404ed.ff6376236a7cb7d04ca03d8267e05fb8cc6fb12c';

$locales = (include __DIR__ . '/../config/module.config.php')['locales'];

$regex = [
    '\/\*translate\*\/(.*?)\/\*translate\*\/',
    '\-\>translate\([\'\"](.*?)[\'\"]\).*'
];

while (false === empty($dirs)) {

    $dir = array_shift($dirs);

    foreach (getFiles($dir) as $innerFile) {
        if (true === is_dir($innerFile)) {
            $dirs[] = $innerFile;
            continue;
        } else {
            foreach ($regex as $regularExpression) {
                preg_match_all('/' . $regularExpression . '/mui', file_get_contents($innerFile), $matches);
                if (null !== @$matches[1]) {
                    $toTranslate = array_unique(array_merge($toTranslate, $matches[1]));
                }
            }
        }
    }

}

$client = new \GuzzleHttp\Client();

foreach ($locales as $locale => $language) {

    list($lang) = explode('_', $locale);

    $pattern = 'msgid ""
msgstr ""
"Project-Id-Version: \n"
"Report-Msgid-Bugs-To: \n"
"Last-Translator: \n"
"Language-Team: %s\n"

';

    file_put_contents($localeDir . '/' .$locale . '.po', sprintf($pattern, $language));

    foreach ($toTranslate as $word) {
        $result = json_decode((string)$client->get(
            $translatorApiUri . '?key=' . $translatorKey
            . '&text=' . htmlentities($word)
            . '&lang=en-' . $lang
        )->getBody())->text[0];

        $poFile = $localeDir . '/' .$locale . '.po';
        $moFile = $localeDir . '/' .$locale . '.mo';

        file_put_contents($poFile, "msgid \"$word\"\nmsgstr \"$result\"\n\n", FILE_APPEND);
        $tranlation = \Gettext\Translations::fromPoFile($poFile);
        $tranlation->toMoFile($moFile);

    }

}

/**
 * @param $root
 * @return array
 */
function getFiles($root)
{
    return array_filter(array_map(
        function ($path) use ($root) {
            return $root . '/' . $path;
        },
        array_filter(
            scandir($root),
            function ($path) {
                return $path !== '.' && $path !== '..';
            }
        )
    ));
}
