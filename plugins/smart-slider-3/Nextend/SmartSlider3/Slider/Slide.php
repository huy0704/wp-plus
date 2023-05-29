<?php


namespace Nextend\SmartSlider3\Slider;


use JRoute;
use Nextend\Framework\Cast;
use Nextend\Framework\Data\Data;
use Nextend\Framework\FastImageSize\FastImageSize;
use Nextend\Framework\Image\Image;
use Nextend\Framework\Image\ImageEdit;
use Nextend\Framework\Misc\Str;
use Nextend\Framework\Parser\Common;
use Nextend\Framework\Parser\Link;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\Plugin;
use Nextend\Framework\Request\Request;
use Nextend\Framework\ResourceTranslator\ResourceTranslator;
use Nextend\Framework\Sanitize;
use Nextend\Framework\Translation\Translation;
use Nextend\Framework\View\Html;
use Nextend\SmartSlider3\Generator\Generator;
use Nextend\SmartSlider3\Renderable\AbstractRenderableOwner;
use Nextend\SmartSlider3\Renderable\Component\AbstractComponent;
use Nextend\SmartSlider3\Renderable\Component\ComponentSlide;

class Slide extends AbstractRenderableOwner {

    /**
     * @var Slider
     */
    protected $sliderObject;
    public $id = 0, $slider = 0, $publish_up = '1970-01-01 00:00:00', $publish_down = '1970-01-01 00:00:00', $published = 1, $first = 0, $slide = '', $ordering = 0, $generator_id = 0;

    protected $frontendFirst = false;

    protected $title = '', $description = '', $thumbnail = '';

    public $parameters;

    /**
     * @var string contains escaped html data
     */
    public $background = '';

    protected $html = '';

    protected $visible = 1;

    public $hasLink = false;

    /**
     * @var bool|Generator
     */
    protected $generator = false;
    protected $variables = array();

    public $index = -1;

    public $publicID = 0;

    public $attributes = array(), $linkAttributes = array(), $showOnAttributes = array();

    public $containerAttributes = array(
        'class' => 'n2-ss-layers-container n2-ss-slide-limiter n2-ow'
    );

    public $classes = '', $style = '';

    public $nextCacheRefresh = 2145916800; // 2038

    /**
     * Slide constructor.
     *
     * @param $slider Slider
     * @param $data   array
     */
    public function __construct($slider, $data) {
        $this->parameters = new Data($data['params'], true);

        $version = $this->parameters->getIfEmpty('version', '0.0.0');
        if (version_compare($version, '3.3.9999', '<')) {
            $this->parameters->set('desktopportraitpadding', '0|*|0|*|0|*|0');
        }

        unset($data['params']);
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        $this->slide = array(
                'type'         => 'slide',
                'layers'       => json_decode($this->slide, true),
                'title'        => $this->title,
                'publish_up'   => $this->publish_up,
                'publish_down' => $this->publish_down,
                'published'    => $this->published,
                'description'  => $this->description,
                'thumbnail'    => $this->thumbnail,
            ) + $this->parameters->toArray();

        if ($version == '0.0.0') {
            /**
             * Required for sample slider city!!!
             */
            $this->fixOldZIndexes($this->slide['layers']);
        }

        $this->sliderObject = $slider;
        $this->renderable   = $slider;
        $this->onCreate();
    }

    private function fixOldZIndexes(&$layers) {
        /**
         * If we do not have version info for the slide, we should do the check for the old zIndexed storage and sort the layers to the new structure.
         */
        if (is_array($layers)) {
            for ($i = 0; $i < count($layers); $i++) {
                if (!isset($layers[$i]['zIndex'])) {
                    if (isset($layers[$i]['style']) && preg_match('/z\-index:[ ]*([0-9]+);/', $layers[$i]['style'], $matches)) {
                        $layers[$i]['zIndex'] = intval($matches[1]);
                    } else {
                        $layers[$i]['zIndex'] = 0;
                    }
                }

                if (isset($layers[$i]['type']) && $layers[$i]['type'] == 'group') {
                    $this->fixOldZIndexes($layers[$i]['layers']);
                }
            }

            if (isset($layers[0]['zIndex'])) {
                usort($layers, array(
                    $this,
                    "sortOldZIndex"
                ));
            }
        }
    }

    private function sortOldZIndex($a, $b) {
        if ($a['zIndex'] == $b['zIndex']) {
            return 0;
        }

        return ($a['zIndex'] < $b['zIndex']) ? 1 : -1;
    }

    public function __clone() {
        $this->parameters = clone $this->parameters;
    }

    protected function onCreate() {
        Plugin::doAction('ssSlide', array($this));
    }

    public function initGenerator($extend = array()) {
        if ($this->generator_id > 0) {
            $this->generator = new Generator($this, $this->sliderObject, $extend);
        }
    }

    public function hasGenerator() {
        return !!$this->generator;
    }

    public function isComponentVisible($generatorVisibleVariable) {
        return !empty($generatorVisibleVariable) && $this->hasGenerator();
    }

    /**
     * @return Slide[]
     */
    public function expandSlide() {
        return $this->generator->getSlides();
    }

    public function expandSlideAdmin() {
        return $this->generator->getSlidesAdmin();
    }

    public function fillSample() {
        if ($this->hasGenerator()) {
            $this->generator->fillSample();
        }
    }

    public function setVariables($variables) {
        $this->variables = array_merge($this->variables, (array)$variables);
    }

    public function isFirst() {
        return !!$this->first;
    }

    public function isCurrentlyEdited() {
        return Request::$REQUEST->getInt('slideid') == $this->id;
    }

    public function setIndex($index) {
        $this->index = $index;
    }

    public function setPublicID($publicID) {
        $this->publicID = $publicID;
    }

    /**
     * @return int
     */
    public function getPublicID(): int {
        return $this->publicID;
    }

    public function setFirst() {

        $this->frontendFirst = true;

        $this->attributes['data-first'] = '1';
    }

    public function getFrontendFirst() {
        return $this->frontendFirst;
    }

    public function prepare() {
        $this->variables['slide'] = array(
            'name'        => $this->getTitle(),
            'description' => $this->getDescription()
        );
    }

    public function setSlidesParams() {

        $this->background = $this->sliderObject->features->makeBackground($this);

        $this->addSlideLink();

        $this->attributes['data-slide-duration']  = Cast::floatToString(max(0, $this->parameters->get('slide-duration', 0)) / 1000);
        $this->attributes['data-id']              = $this->id;
        $this->attributes['data-slide-public-id'] = $this->publicID;

        $this->classes .= ' n2-ss-slide-' . $this->id;

        $this->sliderObject->features->makeSlide($this);

        $this->renderHtml();
    }

    protected function addSlideLink() {

        $linkV1 = $this->parameters->getIfEmpty('link', '');
        if (!empty($linkV1)) {
            list($link, $target) = array_pad((array)Common::parse($linkV1), 2, '');
            $this->parameters->un_set('link');
            $this->parameters->set('href', $link);
            $this->parameters->set('href-target', $target);
        }

        $url    = $this->parameters->get('href');
        $target = $this->parameters->get('href-target');

        if (!empty($url) && $url != '#') {
            $url = $this->fill($url);
        }

        if (!empty($url) && $url != '#') {

            if (empty($target)) {
                $target = '_self';
            }

            $url = ResourceTranslator::toUrl($url);


            $url = Link::parse($url, $this->linkAttributes);
            $this->linkAttributes['data-href'] = $url;
        
            $this->linkAttributes['tabindex'] = 0;
            $this->linkAttributes['role']     = 'button';

            $ariaLabel = $this->parameters->get('aria-label');
            if (!empty($ariaLabel)) {
                $this->linkAttributes['aria-label'] = $ariaLabel;
            }

            if (!isset($this->linkAttributes['onclick']) && !isset($this->linkAttributes['data-n2-lightbox'])) {
                if (!empty($target) && $target != '_self') {
                    $this->linkAttributes['data-target'] = $target;
                }
                $this->linkAttributes['data-n2click'] = "url";
            }

            if (!isset($this->linkAttributes['style'])) {
                $this->linkAttributes['style'] = '';
            }
            $this->linkAttributes['data-force-pointer'] = "";

            $this->hasLink = true;
        }
    }

    public function getRawLink() {
        $linkV1 = $this->parameters->getIfEmpty('link', '');
        if (!empty($linkV1)) {
            list($link, $target) = array_pad((array)Common::parse($linkV1), 2, '');

            return $link;
        }

        return $this->parameters->getIfEmpty('href', '');
    }

    public function getRawLinkHref() {
        $linkV1 = $this->parameters->getIfEmpty('link', '');
        if (!empty($linkV1)) {
            list($link, $target) = array_pad((array)Common::parse($linkV1), 2, '');

            return $target;
        }

        return $this->parameters->getIfEmpty('href-target', '_self');
    }

    public function getSlider() {
        return $this->sliderObject;
    }

    public function getAvailableDevices() {
        return array_diff(array_keys($this->sliderObject->features->responsive->mediaQueries), array('all'));
    }

    protected function renderHtml() {
        if (empty($this->html)) {

            AbstractComponent::$isAdmin = $this->sliderObject->isAdmin;

            $mainContainer = new ComponentSlide($this, $this->slide);

            $attributes = array(
                'role'  => 'note',
                'class' => 'n2-ss-slide--focus'
            );

            if (!isset($this->linkAttributes['role']) || $this->linkAttributes['role'] != 'button') {
                $attributes['tabindex'] = '-1';
            }

            $this->html = Html::tag('div', $attributes, Sanitize::remove_all_html($this->getTitle()));
            $this->html .= Html::tag('div', $this->containerAttributes, $mainContainer->render($this->sliderObject->isAdmin));
        }
    }

    public function finalize() {

        if ($this->sliderObject->exposeSlideData['title']) {
            $title = $this->getTitle();
            if (!empty($title)) {
                $this->attributes['data-title'] = Translation::_($title);
            }
        }

        if ($this->sliderObject->exposeSlideData['description']) {
            $description = $this->getDescription();
            if (!empty($description)) {
                $this->attributes['data-description'] = Translation::_($description);
            }
        }

        if ($this->sliderObject->exposeSlideData['thumbnail']) {
            $thumbnail = $this->getThumbnailDynamic();
            if (!empty($thumbnail)) {

                $attributes = Html::addExcludeLazyLoadAttributes(array(
                    'loading' => 'lazy',
                    'style'   => '',
                    'class'   => 'n2-ss-slide-thumbnail'
                ));

                $this->html .= Html::image($this->sliderObject->features->optimize->optimizeThumbnail($thumbnail), esc_attr($this->getThumbnailAltDynamic()), $attributes);
            }
        }

        if ($this->hasLink) {
            $this->attributes['data-haslink'] = 1;
        }

        if (!$this->sliderObject->isAdmin || !$this->underEdit) {
            if (!$this->isVisibleDesktopPortrait()) {
                $this->showOnAttributes['data-hide-desktopportrait'] = 1;
            }
            if (!$this->isVisibleTabletPortrait()) {
                $this->showOnAttributes['data-hide-tabletportrait'] = 1;
            }
            if (!$this->isVisibleMobilePortrait()) {
                $this->showOnAttributes['data-hide-mobileportrait'] = 1;
            }
        }

        $this->attributes += $this->showOnAttributes;
    }

    public function isVisibleDesktopPortrait() {
        return $this->parameters->get('desktopportrait', 1);
    }

    public function isVisibleDesktopLandscape() {
        return $this->parameters->get('desktoplandscape', 1);
    }

    public function isVisibleTabletPortrait() {
        return $this->parameters->get('tabletportrait', 1);
    }

    public function isVisibleTabletLandscape() {
        return $this->parameters->get('tabletlandscape', 1);
    }

    public function isVisibleMobilePortrait() {
        return $this->parameters->get('mobileportrait', 1);
    }

    public function isVisibleMobileLandscape() {
        return $this->parameters->get('mobilelandscape', 1);
    }

    /**
     * @return string contains escaped html data
     */
    public function getHTML() {
        return $this->html;
    }

    public function getAsStatic() {

        $mainContainer = new ComponentSlide($this, $this->slide);

        $attributes = array(
            'class' => 'n2-ss-static-slide n2-ow' . $this->classes
        );

        if (!$this->sliderObject->isAdmin || !$this->underEdit) {
            if (!$this->isVisibleDesktopPortrait()) {
                $attributes['data-hide-desktopportrait'] = 1;
            }
            if (!$this->isVisibleDesktopLandscape()) {
                $attributes['data-hide-desktoplandscape'] = 1;
            }
            if (!$this->isVisibleTabletPortrait()) {
                $attributes['data-hide-tabletportrait'] = 1;
            }
            if (!$this->isVisibleTabletLandscape()) {
                $attributes['data-hide-tabletlandscape'] = 1;
            }
            if (!$this->isVisibleMobilePortrait()) {
                $attributes['data-hide-mobileportrait'] = 1;
            }
            if (!$this->isVisibleMobileLandscape()) {
                $attributes['data-hide-mobilelandscape'] = 1;
            }
        }

        return Html::tag('div', $attributes, $mainContainer->render($this->sliderObject->isAdmin));
    }

    public function forceNonStatic() {
        $this->parameters->set('static-slide', 0);
    }

    public function isStatic() {
        if ($this->parameters->get('static-slide', 0)) {
            return true;
        }

        return false;
    }

    private static function splitTokens($input) {
        $tokens       = array();
        $currentToken = "";
        $nestingLevel = 0;
        for ($i = 0; $i < strlen($input); $i++) {
            $currentChar = $input[$i];
            if ($currentChar === "," && $nestingLevel === 0) {
                $tokens[]     = $currentToken;
                $currentToken = "";
            } else {
                $currentToken .= $currentChar;
                if ($currentChar === "(") {
                    $nestingLevel++;
                } else if ($currentChar === ")") {
                    $nestingLevel--;
                }
            }
        }
        if (strlen($currentToken)) {
            $tokens[] = $currentToken;
        }

        return $tokens;
    }

    public function fill($value) {
        if (!empty($this->variables) && !empty($value)) {
            return preg_replace_callback('/{((([a-z]+)\(([^}]+)\))|([a-zA-Z0-9][a-zA-Z0-9_\/]*))}/', array(
                $this,
                'parseFunction'
            ), $value);
        }

        return $value;
    }

    private function parseFunction($match) {
        if (!isset($match[5])) {
            $args = self::splitTokens($match[4]);
            for ($i = 0; $i < count($args); $i++) {
                $args[$i] = $this->parseVariable($args[$i]);
            }

            if (method_exists($this, '_' . $match[3])) {
                return call_user_func_array(array(
                    $this,
                    '_' . $match[3]
                ), $args);
            }

            return $match[0];
        } else {
            return $this->parseVariable($match[5]);
        }
    }

    private function parseVariable($variable) {
        preg_match('/^("|\')(.*)("|\')$/', $variable, $match);
        if (!empty($match)) {
            return $match[2];
        }

        preg_match('/((([a-z]+)\(([^}]+)\)))/', $variable, $match);
        if (!empty($match)) {
            return call_user_func(array(
                $this,
                'parseFunction'
            ), $match);
        } else {
            preg_match('/([a-zA-Z][0-9a-zA-Z_]*)(\/([0-9a-z]+))?/', $variable, $match);
            if ($match) {
                $index = empty($match[3]) ? 0 : $match[3];
                if (is_numeric($index)) {
                    $index = max(1, intval($index)) - 1;
                }

                if (isset($this->variables[$index]) && isset($this->variables[$index][$match[1]])) {
                    return $this->variables[$index][$match[1]];
                } else {
                    return '';
                }
            }

            return $variable;
        }
    }

    private function _fallback($s, $def) {
        if (empty($s)) {
            return $def;
        }

        return $s;
    }

    private function _cleanhtml($s) {
        return strip_tags($s, '<p><a><b><br><i>');
    }

    private function _removehtml($s) {
        return strip_tags($s);
    }

    private function _splitbychars($s, $start = 0, $length = null) {

        return Str::substr($s, $start, $length);
    }

    private function _splitbywords($s, $start, $length) {

        $len = intval(Str::strlen($s));
        if ($len > $start) {
            $posStart = max(0, $start == 0 ? 0 : Str::strpos($s, ' ', $start));
            $posEnd   = max(0, $length > $len ? $len : Str::strpos($s, ' ', $length));
            if ($posEnd == 0 && $length <= $len) $posEnd = $len;

            return Str::substr($s, $posStart, $posEnd);
        } else {
            return '';
        }
    }

    private function _findimage($s, $index) {
        $index = isset($index) ? intval($index) - 1 : 0;
        preg_match_all('/(<img.*?src=[\'"](.*?)[\'"][^>]*>)|(background(-image)??\s*?:.*?url\((["|\']?)?(.+?)(["|\']?)?\))/i', $s, $r);
        if (isset($r[2]) && !empty($r[2][$index])) {
            $s = $r[2][$index];
        } else if (isset($r[6]) && !empty($r[6][$index])) {
            $s = trim($r[6][$index], "'\" \t\n\r\0\x0B");
        } else {
            $s = '';
        }

        return $s;
    }

    private function _findlink($s, $index) {
        $index = isset($index) ? intval($index) - 1 : 0;
        preg_match_all('/href=["\']?([^"\'>]+)["\']?/i', $s, $r);
        if (isset($r[1]) && !empty($r[1][$index])) {
            $s = $r[1][$index];
        } else {
            $s = '';
        }

        return $s;
    }

    private function _removevarlink($s) {
        return preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', '', $s);
    }

    private function _removelinebreaks($s) {
        return preg_replace('/\r?\n|\r/', '', $s);
    }

    public function getTitle($isAdmin = false) {

        return $this->fill($this->title);
    }

    public function getDescription() {
        return $this->fill($this->description);
    }

    public function getRawTitle() {
        return $this->title;
    }

    public function getRawDescription() {
        return $this->description;
    }

    public function getBackgroundImage() {
        return $this->fill($this->parameters->get('backgroundImage'));
    }

    public function getThumbnail() {

        return ResourceTranslator::toUrl($this->getThumbnailRaw());
    }

    public function getThumbnailRaw() {
        $image = $this->thumbnail;
        if (empty($image)) {
            return $this->getBackgroundImage();
        }

        return $this->fill($image);
    }

    public function getThumbnailDynamic() {
        $image = $this->thumbnail;
        if (empty($image)) {
            $image = $this->parameters->get('backgroundImage');
        }

        return $this->fill($image);
    }

    public function getThumbnailAltDynamic() {
        $alt = $this->fill($this->parameters->get('thumbnailAlt'));
        if (empty($alt)) {
            $alt = $this->getTitle();
        }

        return $alt;
    }

    public function getLightboxImage() {
        $image = $this->fill($this->parameters->get('ligthboxImage'));
        if (empty($image)) {
            $image = $this->getBackgroundImage();
        }

        return ResourceTranslator::toUrl($image);
    }

    public function getRow() {
        $this->fillParameters();

        return array(
            'title'        => $this->getTitle(),
            'slide'        => $this->getFilledLayers(),
            'description'  => $this->getDescription(),
            'thumbnail'    => ResourceTranslator::urlToResource($this->getThumbnail()),
            'published'    => $this->published,
            'publish_up'   => $this->publish_up,
            'publish_down' => $this->publish_down,
            'first'        => $this->first,
            'params'       => $this->parameters->toJSON(),
            'slider'       => $this->slider,
            'ordering'     => $this->ordering,
            'generator_id' => 0
        );
    }

    public function fillParameters() {
        $this->parameters->set('backgroundImage', $this->fill($this->parameters->get('backgroundImage')));
        $this->parameters->set('backgroundAlt', $this->fill($this->parameters->get('backgroundAlt')));
        $this->parameters->set('backgroundTitle', $this->fill($this->parameters->get('backgroundTitle')));
        $this->parameters->set('backgroundVideoMp4', $this->fill($this->parameters->get('backgroundVideoMp4')));
        $this->parameters->set('backgroundColor', $this->fill($this->parameters->get('backgroundColor')));
        $this->parameters->set('href', $this->fill($this->parameters->get('href')));
    }

    private function getFilledLayers() {
        $layers = $this->slide['layers'];
        if (!$this->underEdit) {
            $layers = AbstractComponent::translateUniqueIdentifier($layers);
        }

        $this->fillLayers($layers);

        return json_encode($layers);
    }

    public function setNextCacheRefresh($time) {
        $this->nextCacheRefresh = min($this->nextCacheRefresh, $time);
    }

    public function setVisibility($visibility) {
        $this->visible = $visibility;
    }

    public function isVisible() {

        if (!$this->visible) {
            return false;
        }

        if ($this->publish_down != '1970-01-01 00:00:00') {
            $publish_down = strtotime($this->publish_down);

            if ($publish_down) {
                if ($publish_down > Platform::getTimestamp()) {
                    $this->setNextCacheRefresh($publish_down);
                } else {
                    return false;
                }
            }
        }

        if ($this->publish_up != '1970-01-01 00:00:00') {
            $publish_up = strtotime($this->publish_up);

            if ($publish_up) {
                if ($publish_up > Platform::getTimestamp()) {
                    $this->setNextCacheRefresh($publish_up);

                    return false;
                }
            }
        }

        return true;
    }

    public function getSlideStat() {
        if ($this->hasGenerator()) {
            return $this->generator->getSlideStat();
        }

        return '1/1';
    }

    public function getGeneratorLabel() {
        $source = $this->generator->getSource();
        if (!$source) {
            return n2_('Not found');
        }

        return $source->getLabel();

    }

    public function getElementID() {
        return $this->getSlider()->elementId;
    }

    public function addScript($script, $name = false) {
        $this->sliderObject->addScript($script, $name);
    }

    public function isScriptAdded($name) {
        return $this->sliderObject->isScriptAdded($name);
    }

    public function addLess($file, $context) {
        $this->sliderObject->addLess($file, $context);
    }

    public function addCSS($css) {
        $this->sliderObject->addCSS($css);
    }

    public function addDeviceCSS($device, $css) {
        $this->sliderObject->addDeviceCSS($device, $css);
    }

    public function addFont($font, $mode, $pre = null) {
        return $this->sliderObject->addFont($font, $mode, $pre);
    }

    public function addStyle($style, $mode, $pre = null) {
        return $this->sliderObject->addStyle($style, $mode, $pre);
    }

    public function addImage($imageUrl) {
        $this->sliderObject->addImage($imageUrl);
    }

    public function isAdmin() {
        return $this->sliderObject->isAdmin;
    }

    public function isLazyLoadingEnabled() {
        return $this->sliderObject->features->lazyLoad->isEnabled;
    }

    public function optimizeImageWebP($src) {

        return array();
    }

    public function renderImage($item, $src, $attributes = array(), $pictureAttributes = array()) {

        /**
         * @see https://bugs.chromium.org/p/chromium/issues/detail?id=1181291
         */
        if (!$this->frontendFirst) {
            $attributes['loading'] = 'lazy';
        }

        $imageUrl = ResourceTranslator::toUrl($src);

        FastImageSize::initAttributes($src, $attributes);

        $attributes = Html::addExcludeLazyLoadAttributes($attributes);

        $attributes['src'] = $imageUrl;
        $this->addImage($imageUrl);

        return Html::tag('img', $attributes, false);
    }

    public function getThumbnailType() {
        return $this->parameters->get('thumbnailType', 'default');
    }

    public function renderThumbnailImage($width, $height, $attributes = array()) {

        $src = $this->getThumbnailRaw();

        if (empty($src)) {
            return '<img src="data:," alt style="visibility:hidden;">';
        }

        $attributes['src']     = ResourceTranslator::toUrl($src);
        $originalThumbnailSize = FastImageSize::getSize($src);
        if ($originalThumbnailSize) {
            $attributes['width']  = $originalThumbnailSize['width'];
            $attributes['height'] = $originalThumbnailSize['height'];
        }
        $attributes['loading'] = 'lazy';

        $attributes = Html::addExcludeLazyLoadAttributes($attributes);

        $sources = array();

        $imagePath = ResourceTranslator::toPath($src);
        if (isset($imagePath[0])) {
            $optimizeThumbnail = $this->sliderObject->params->get('optimize-thumbnail-scale', 0);

            if ($optimizeThumbnail) {
                $optimizedThumbnailUrl  = $this->sliderObject->features->optimize->optimizeThumbnail($attributes['src']);
                $attributes['src']      = $optimizedThumbnailUrl;
                $optimizedThumbnailSize = FastImageSize::getSize(ResourceTranslator::urlToResource($optimizedThumbnailUrl));
                if ($optimizedThumbnailSize) {
                    $attributes['width']  = $optimizedThumbnailSize['width'];
                    $attributes['height'] = $optimizedThumbnailSize['height'];
                }
            }

        }


        $sources[] = Html::tag('img', $attributes, false);

        return HTML::tag('picture', Html::addExcludeLazyLoadAttributes(), implode('', $sources));
    }
}