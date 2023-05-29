<?php


namespace Nextend\SmartSlider3\BackupSlider;


use Nextend\Framework\Asset\AssetManager;
use Nextend\Framework\Asset\Builder\BuilderCss;
use Nextend\Framework\Asset\Builder\BuilderJs;
use Nextend\Framework\Asset\Predefined;
use Nextend\Framework\Data\Data;
use Nextend\Framework\Filesystem\Filesystem;
use Nextend\Framework\Image\ImageManager;
use Nextend\Framework\Misc\Zip\Creator;
use Nextend\Framework\Model\Section;
use Nextend\Framework\PageFlow;
use Nextend\Framework\Pattern\MVCHelperTrait;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\Url\Url;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use Nextend\SmartSlider3\Application\Model\ModelGenerator;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\Application\Model\ModelSlidersXRef;
use Nextend\SmartSlider3\Application\Model\ModelSlides;
use Nextend\SmartSlider3\Renderable\Component\ComponentCol;
use Nextend\SmartSlider3\Renderable\Component\ComponentContent;
use Nextend\SmartSlider3\Renderable\Component\ComponentLayer;
use Nextend\SmartSlider3\Renderable\Component\ComponentRow;
use Nextend\SmartSlider3\Slider\SliderParams;
use Nextend\SmartSlider3\Slider\SliderType\SliderTypeFactory;
use Nextend\SmartSlider3\Widget\AbstractWidget;
use Nextend\SmartSlider3\Widget\WidgetGroupFactory;
use Nextend\SmartSlider3Pro\Renderable\Component\ComponentGroup;

class ExportSlider {

    use MVCHelperTrait;

    private $uniqueCounter = 1;

    /**
     * @var BackupData
     */
    private $backup;
    private $sliderId = 0;

    public $images = array(), $visuals = array();

    private $files, $usedNames = array(), $imageTranslation = array();

    public function __construct($MVCHelper, $sliderId) {

        $this->setMVCHelper($MVCHelper);
        $this->sliderId = $sliderId;
    }

    public function create($saveAsFile = false) {
        $this->backup = new BackupData();
        $slidersModel = new ModelSliders($this);
        if ($this->backup->slider = $slidersModel->get($this->sliderId)) {

            $zip = new Creator();

            if (empty($this->backup->slider['type'])) {
                $this->backup->slider['type'] = 'simple';
            }
            self::addImage($this->backup->slider['thumbnail']);

            $this->backup->slider['params'] = new SliderParams($this->backup->slider['id'], $this->backup->slider['type'], $this->backup->slider['params'], true);

            if ($this->backup->slider['type'] == 'group') {
                $xref = new ModelSlidersXRef($this);

                $sliders = $xref->getSliders($this->backup->slider['id'], 'published');
                foreach ($sliders as $k => $slider) {
                    $export = new self($this->MVCHelper, $slider['slider_id']);

                    $fileName = $export->create(true);

                    $zip->addFile(file_get_contents($fileName), 'sliders/' . $k . '.ss3');
                    unlink($fileName);
                }
            } else {
                $slidesModel          = new ModelSlides($this);
                $this->backup->slides = $slidesModel->getAll($this->backup->slider['id']);


                $sliderType = SliderTypeFactory::getType($this->backup->slider['type']);
                $sliderType->export($this, $this->backup->slider);

                /** @var AbstractWidget[] $enabledWidgets */
                $enabledWidgets = array();

                $widgetGroups = WidgetGroupFactory::getGroups();

                $params = $this->backup->slider['params'];
                foreach ($widgetGroups as $groupName => $group) {
                    $widgetName = $params->get('widget' . $groupName);
                    if ($widgetName && $widgetName != 'disabled') {
                        $widget = $group->getWidget($widgetName);
                        if ($widget) {
                            $enabledWidgets[$groupName] = $widget;
                        }
                    }
                }

                foreach ($enabledWidgets as $k => $widget) {
                    $params->fillDefault($widget->getDefaults());

                    $widget->prepareExport($this, $params);
                }

                for ($i = 0; $i < count($this->backup->slides); $i++) {
                    $slide = $this->backup->slides[$i];
                    self::addImage($slide['thumbnail']);
                    $slide['params'] = new Data($slide['params'], true);

                    self::addImage($slide['params']->get('backgroundImage'));
                    self::addImage($slide['params']->get('ligthboxImage'));

                    if ($slide['params']->has('link')) {
                        // Compatibility fix for the old SS3 import files
                        self::addLightbox($slide['params']->get('link'));
                    }
                    if ($slide['params']->has('href')) {
                        self::addLightbox($slide['params']->get('href'));
                    }

                    $layers = json_decode($slide['slide'], true);

                    $this->prepareLayer($layers);


                    if (!empty($slide['generator_id'])) {
                        $generatorModel             = new ModelGenerator($this);
                        $this->backup->generators[] = $generatorModel->get($slide['generator_id']);
                    }
                }

            }

            $this->images  = array_unique($this->images);
            $this->visuals = array_unique($this->visuals);

            foreach ($this->images as $image) {
                $this->backup->NextendImageManager_ImageData[$image] = ImageManager::getImageData($image, true);
                if ($this->backup->NextendImageManager_ImageData[$image]) {
                    self::addImage($this->backup->NextendImageManager_ImageData[$image]['tablet']['image']);
                    self::addImage($this->backup->NextendImageManager_ImageData[$image]['mobile']['image']);
                } else {
                    unset($this->backup->NextendImageManager_ImageData[$image]);
                }
            }

            $this->images = array_unique($this->images);

            $usedNames = array();
            foreach ($this->images as $image) {
                $file = ResourceTranslator::toPath($image);
                if (Filesystem::fileexists($file)) {
                    $fileName = strtolower(basename($file));
                    while (in_array($fileName, $usedNames)) {
                        $fileName = $this->uniqueCounter . $fileName;
                        $this->uniqueCounter++;
                    }
                    $usedNames[] = $fileName;

                    $this->backup->imageTranslation[$image] = $fileName;
                    $zip->addFile(file_get_contents($file), 'images/' . $fileName);
                }
            }

            foreach ($this->visuals as $visual) {
                $this->backup->visuals[] = Section::getById($visual);
            }

            $zip->addFile(serialize($this->backup), 'data');

            if (!$saveAsFile) {
                PageFlow::cleanOutputBuffers();
                header('Content-disposition: attachment; filename*=UTF-8\'\'' . rawurlencode($this->backup->slider['title'] . '.ss3'));
                header('Content-type: application/zip');
                // PHPCS - contains binary zip data, so nothing to escape
                echo $zip->file(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                PageFlow::exitApplication();
            } else {
                $file   = $this->sliderId . '-' . preg_replace('/[^a-zA-Z0-9_-]/', '', $this->backup->slider['title']) . '.ss3';
                $folder = Platform::getPublicDirectory();
                $folder .= '/export/';
                if (!Filesystem::existsFolder($folder)) {
                    Filesystem::createFolder($folder);
                }
                Filesystem::createFile($folder . $file, $zip->file());

                return $folder . $file;
            }
        }
    }

    /**
     * @param array $layers
     */
    public function prepareLayer($layers) {
        foreach ($layers as $layer) {

            if (isset($layer['type'])) {
                switch ($layer['type']) {
                    case 'content':
                        ComponentContent::prepareExport($this, $layer);
                        break;
                    case 'row':
                        ComponentRow::prepareExport($this, $layer);
                        break;
                    case 'col':
                        ComponentCol::prepareExport($this, $layer);
                        break;
                    case 'group':
                        $this->prepareLayer($layer['layers']);
                        break;
                    default:
                        ComponentLayer::prepareExport($this, $layer);
                }
            } else {
                ComponentLayer::prepareExport($this, $layer);
            }
        }
    }

    public function createHTML($isZIP = true) {
        Platform::setIsAdmin(false); //Some features are disabled on the admin area

        $this->files = array();
        PageFlow::cleanOutputBuffers();
        AssetManager::createStack();

        Predefined::frontend(true);

        ob_start();


        $applicationTypeFrontend = ApplicationSmartSlider3::getInstance()
                                                          ->getApplicationTypeFrontend();

        $applicationTypeFrontend->process('slider', 'display', false, array(
            'sliderID' => $this->sliderId,
            'usage'    => 'Export as HTML'
        ));

        $slidersModel = new ModelSliders($this);
        $slider       = $slidersModel->get($this->sliderId);
        $sliderHTML   = ob_get_clean();
        $headHTML     = '';

        $css = AssetManager::getCSS(true);
        foreach ($css['url'] as $url) {
            $headHTML .= Html::style($url, true, array(
                    'media' => 'screen, print'
                )) . "\n";
        }

        array_unshift($css['files'], ResourceTranslator::toPath('$ss3-frontend$/dist/normalize.min.css'));

        foreach ($css['files'] as $file) {
            if (file_exists($file)) {
                $headHTML .= $this->addCSSFile($file);
            } else {
            }
        }

        if ($css['inline'] != '') {
            $headHTML .= Html::style($css['inline']) . "\n";
        }

        $js = AssetManager::getJs(true);

        if ($js['globalInline'] != '') {
            $headHTML .= Html::script($js['globalInline']) . "\n";
        }

        foreach ($js['url'] as $url) {
            $headHTML .= Html::scriptFile($url) . "\n";
        }
        foreach ($js['files'] as $file) {
            $path = 'js/' . basename($file);

            if (file_exists($file)) {
                $this->files[$path] = file_get_contents($file);
            } else {
            }
            $headHTML .= Html::scriptFile($path) . "\n";
        }

        if ($js['inline'] != '') {
            $headHTML .= Html::script($js['inline']) . "\n";
        }

        $sliderHTML = preg_replace_callback('/(src|srcset)=["|\'](.*?)["|\']/i', array(
            $this,
            'replaceHTMLImage'
        ), $sliderHTML);

        $sliderHTML = preg_replace_callback('/url\(\s*([\'"]|(&#039;))?(\S*\.(?:jpe?g|gif|png))([\'"]|(&#039;))?\s*\)[^;}]*?/i', array(
            $this,
            'replaceHTMLBGImage'
        ), $sliderHTML);

        $sliderHTML = preg_replace_callback('/(data-href)=["|\'](.*?)["|\']/i', array(
            $this,
            'replaceHTMLImage'
        ), $sliderHTML);

        $sliderHTML = preg_replace_callback('/(srcset)=["|\'](.*?)["|\']/i', array(
            $this,
            'replaceHTMLRetinaImage'
        ), $sliderHTML);

        $sliderHTML = preg_replace_callback('/(data-n2-lightbox-urls)=["|\'](.*?)["|\']/i', array(
            $this,
            'replaceLightboxImages'
        ), $sliderHTML);

        $sliderHTML = preg_replace_callback('/(data-n2-lightbox)=["|\'](.*?)["|\']/i', array(
            $this,
            'replaceHTMLImageHrefLightbox'
        ), $sliderHTML);

        $headHTML = preg_replace_callback('/"([^"]*?\.(jpg|png|gif|jpeg|webp|svg|mp4))"/i', array(
            $this,
            'replaceJSON'
        ), $headHTML);

        $headHTML = preg_replace_callback('/(--n2bgimage:URL\(")(.*?)("\);)/i', array(
            $this,
            'replaceCssBGImage'
        ), $headHTML);

        $this->files['index.html'] = "<!doctype html>\n<html lang=\"en\">\n<head>\n<meta charset=\"utf-8\">\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge, chrome=1\">\n<title>" . $slider['title'] . "</title>\n" . $headHTML . "</head>\n<body>\n" . $sliderHTML . "</body>\n</html>";

        if (!$isZIP) {
            return $this->files;
        }

        $zip = new Creator();
        foreach ($this->files as $path => $content) {
            $zip->addFile($content, $path);
        }
        PageFlow::cleanOutputBuffers();
        header('Content-disposition: attachment; filename*=UTF-8\'\'' . rawurlencode($slider['title'] . '.zip'));
        header('Content-type: application/zip');
        // PHPCS - contains binary zip data, so nothing to escape
        echo $zip->file(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        Platform::setIsAdmin(false); // Restore admin area

        PageFlow::exitApplication();
    }

    private static function addProtocol($image) {
        if (substr($image, 0, 2) == '//') {
            return Url::addScheme($image);
        }

        return $image;
    }

    public function replaceHTMLImage($found) {
        $path = Filesystem::absoluteURLToPath(self::addProtocol($found[2]));

        if (substr($path, 0, 5) === "data:") {
            return $found[0];
        }

        if (strpos($path, Filesystem::getBasePath()) !== 0) {
            $imageUrl = Url::relativetoabsolute($path);
            $path     = Filesystem::absoluteURLToPath($imageUrl);
        }

        if ($path == $found[2]) {
            return $found[0];
        }
        if (Filesystem::fileexists($path)) {
            if (!isset($this->imageTranslation[$path])) {
                $fileName = strtolower(basename($path));
                while (in_array($fileName, $this->usedNames)) {
                    $fileName = $this->uniqueCounter . $fileName;
                    $this->uniqueCounter++;
                }
                $this->usedNames[]                  = $fileName;
                $this->files['images/' . $fileName] = file_get_contents($path);
                $this->imageTranslation[$path]      = $fileName;
            } else {
                $fileName = $this->imageTranslation[$path];
            }

            return str_replace($found[2], 'images/' . $fileName, $found[0]);
        } else {
            return $found[0];
        }
    }

    public function replaceHTMLImageHrefLightbox($found) {
        return $this->replaceHTMLImage($found);
    }

    public function replaceLightboxImages($found) {
        $images = explode(',', $found[2]);
        foreach ($images as $k => $image) {
            $images[$k] = $this->replaceHTMLImage(array(
                $image,
                '',
                $image
            ));
        }

        return 'data-n2-lightbox-urls="' . implode(',', $images) . '"';
    }

    public function replaceHTMLRetinaImage($found) {
        $srcset = $found[0];

        $replacedImages = array();
        $explodedSrcs   = explode(',', $found[2]);
        foreach ($explodedSrcs as $explodedSrc) {
            $exploded         = explode(' ', $explodedSrc);
            $replacedImage    = $this->replaceHTMLImage(array(
                $exploded[0],
                '',
                $exploded[0]
            ));
            $replacedImages[] = $replacedImage . (count($exploded) > 1 ? ' ' . $exploded[1] : '');

        }
        if (!empty($replacedImages)) {
            $srcset = $found[1] . '="' . implode(',', $replacedImages) . '"';
        }

        return $srcset;
    }

    public function replaceHTMLBGImage($found) {
        $path = $this->replaceHTMLImage(array(
            $found[3],
            '',
            $found[3]
        ));

        return str_replace($found[3], $path, $found[0]);
    }

    public function replaceCssBGImage($found) {
        if (substr($found[2], 0, 9) !== 'http:\/\/' && substr($found[2], 0, 10) !== 'https:\/\/' && substr($found[2], 0, 5) !== 'data:') {
            return $found[1] . '..\/' . $found[2] . $found[3];
        }

        return $found[0];
    }

    public function replaceJSON($found) {
        $image = ResourceTranslator::toUrl(str_replace('\\/', '/', $found[1]));
        $path  = $this->replaceHTMLImage(array(
            $image,
            '',
            $image
        ));

        return str_replace($found[1], str_replace('/', '\\/', $path), $found[0]);
    }

    public function addImage($image) {
        if (!empty($image)) {
            $this->images[] = $image;
        }
    }

    public function addLightbox($url) {
        preg_match('/^([a-zA-Z]+)\[(.*)]/', $url, $matches);
        if (!empty($matches)) {
            if ($matches[1] == 'lightbox') {
                $data = json_decode($matches[2]);
                if ($data) {
                    foreach ($data->urls as $image) {
                        $this->addImage($image);
                    }
                }
            }
        }
    }

    public function addVisual($id) {
        if (is_numeric($id) && $id > 10000) {
            $this->visuals[] = $id;
        }
    }

    private $basePath;
    private $baseUrl;

    private function addCSSFile($file) {

        return $this->addCSSFileWithContent($file, file_get_contents($file));
    }

    private function addCSSFileWithContent($file, $fileContent) {
        $path = 'css/' . basename($file);

        $this->basePath = dirname($file);
        $this->baseUrl  = Filesystem::pathToAbsoluteURL($this->basePath);

        $fileContent = preg_replace_callback('#url\([\'"]?([^"\'\)]+)[\'"]?\)#', array(
            $this,
            'replaceCSSImage'
        ), $fileContent);

        $this->files[$path] = $fileContent;

        return Html::style($path, true, array(
                'media' => 'screen, print'
            )) . "\n";
    }

    public function replaceCSSImage($matches) {
        if (substr($matches[1], 0, 5) == 'data:') return $matches[0];
        if (substr($matches[1], 0, 4) == 'http') {
            $exploded = explode('?', $matches[1]);

            $path = ResourceTranslator::toPath(ResourceTranslator::urlToResource($exploded[0]));
            if (strpos($path, $this->basePath) === 0) {
                $exploded = explode('?', $matches[1]);

                return str_replace($matches[1], 'assets/' . $this->addFile($path) . '?' . $exploded[1], $matches[0]);
            } else {
                return $matches[0];
            }
        }
        if (substr($matches[1], 0, 2) == '//') return $matches[0];

        $exploded = explode('?', $matches[1]);

        $path = realpath($this->basePath . '/' . $exploded[0]);
        if ($path === false) {
            return 'url(' . str_replace(array(
                    'http://',
                    'https://'
                ), '//', $this->baseUrl) . '/' . $matches[1] . ')';
        }

        return str_replace($matches[1], 'assets/' . $this->addFile($path), $matches[0]);
    }

    protected function addFile($path) {
        $path = Filesystem::convertToRealDirectorySeparator($path);

        if (!isset($this->imageTranslation[$path])) {
            $fileName = strtolower(basename($path));
            while (in_array($fileName, $this->usedNames)) {
                $fileName = $this->uniqueCounter . $fileName;
                $this->uniqueCounter++;
            }
            $this->usedNames[]                      = $fileName;
            $this->files['css/assets/' . $fileName] = file_get_contents($path);
            $this->imageTranslation[$path]          = $fileName;
        } else {
            $fileName = $this->imageTranslation[$path];
        }

        return $fileName;
    }
}