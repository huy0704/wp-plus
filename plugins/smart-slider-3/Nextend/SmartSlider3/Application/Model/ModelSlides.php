<?php


namespace Nextend\SmartSlider3\Application\Model;


use Nextend\Framework\Data\Data;
use Nextend\Framework\Database\Database;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Misc\Str;
use Nextend\Framework\Model\AbstractModelTable;
use Nextend\Framework\Request\Request;
use Nextend\SmartSlider3\Application\Helper\HelperSliderChanged;
use Nextend\SmartSlider3\Renderable\Component\AbstractComponent;
use Nextend\SmartSlider3\Renderable\Component\ComponentCol;
use Nextend\SmartSlider3\Renderable\Component\ComponentContent;
use Nextend\SmartSlider3\Renderable\Component\ComponentLayer;
use Nextend\SmartSlider3\Renderable\Component\ComponentRow;
use Nextend\SmartSlider3\SlideBuilder\BuilderComponentLayer;
use Nextend\SmartSlider3\SlideBuilder\BuilderComponentSlide;
use Nextend\SmartSlider3\Slider\Slide;
use Nextend\SmartSlider3\Slider\Slider;
use Nextend\SmartSlider3\SmartSlider3Info;
use Nextend\SmartSlider3Pro\Renderable\Component\ComponentGroup;

class ModelSlides extends AbstractModelTable {

    protected function createConnectorTable() {

        return Database::getTable('nextend2_smartslider3_slides');
    }

    public function get($id) {
        return $this->table->findByPk($id);
    }

    public function getAll($sliderid = 0, $where = '') {
        return Database::queryAll('SELECT * FROM ' . $this->getTableName() . ' WHERE slider = ' . $sliderid . ' ' . $where . ' ORDER BY ordering', false, "assoc", null);
    }

    public function createQuickImage($image, $sliderId) {

        $parameters = array(
            'background-type' => 'image',
            'backgroundImage' => $image['image']
        );

        if (!empty($image['alt'])) {
            $parameters['backgroundAlt'] = $image['alt'];
        }

        $slideID = $this->create($sliderId, $image['title'], array(), $image['image'], $parameters, array(
            'description' => $image['description']
        ));
        $this->markChanged($sliderId);

        return $slideID;
    }

    public function createQuickEmptySlide($sliderId) {

        $parameters = array(
            'background-type' => 'color'
        );

        $slideID = $this->create($sliderId, 'Slide', array(), '', $parameters);
        $this->markChanged($sliderId);

        return $slideID;
    }

    public function createQuickStaticOverlay($sliderId) {

        $parameters = array(
            'static-slide' => 1
        );

        $slideID = $this->create($sliderId, n2_('Static overlay'), array(), '', $parameters);
        $this->markChanged($sliderId);

        return $slideID;
    }

    public function createQuickPost($post, $sliderId) {

        $data = new Data($post);

        $title       = $this->removeFourByteChars($data->get('title'));
        $description = $this->removeFourByteChars($data->get('description'));

        $slideBuilder = new BuilderComponentSlide(array(
            'title'                  => $title,
            'description'            => $description,
            'thumbnail'              => $data->get('image'),
            'background-type'        => 'image',
            'backgroundImage'        => $data->get('image'),
            'backgroundImageOpacity' => 20,
            'backgroundColor'        => '000000FF'
        ));

        $slideBuilder->content->set(array(
            'desktopportraitpadding' => '10|*|100|*|10|*|100|*|px',
            'mobileportraitpadding'  => '10|*|10|*|10|*|10|*|px'
        ));

        if ($title) {
            $heading = new BuilderComponentLayer($slideBuilder->content, 'heading');
            $heading->item->set(array(
                'heading' => '{name/slide}',
                'font'    => Base64::encode('{"data":[{"extra":"","color":"ffffffff","size":"48||px","tshadow":"0|*|0|*|0|*|000000ff","afont":"Roboto,Arial","lineheight":"1.5","bold":0,"italic":0,"underline":0,"align":"inherit","letterspacing":"normal","wordspacing":"normal","texttransform":"none"},{"extra":""}]}'),
            ));

        }

        if ($description) {
            $text = new BuilderComponentLayer($slideBuilder->content, 'text');
            $text->set(array(
                'desktopportraitmargin' => '0|*|0|*|20|*|0|*|px',
            ));
            $text->item->set(array(
                'content' => '{description/slide}',
                'font'    => Base64::encode('{"data":[{"extra":"","color":"ffffffff","size":"18||px","tshadow":"0|*|0|*|0|*|000000ff","afont":"Roboto,Arial","lineheight":"1.5","bold":0,"italic":0,"underline":0,"align":"inherit","letterspacing":"normal","wordspacing":"normal","texttransform":"none"},{"extra":""}]}'),
            ));
        }

        $link = $data->get('link');
        if (!empty($link)) {
            $buttonLayer = new BuilderComponentLayer($slideBuilder->content, 'button');
            $buttonLayer->item->set(array(
                'content' => n2_('Read more'),
                'link'    => $link . '|*|_self'
            ));
        }

        $row = $this->convertSlideDataToDatabaseRow($slideBuilder->getData(), $sliderId);

        $slideID = $this->create($row['slider'], $row['title'], $row['slide'], $row['thumbnail'], $row['params'], array(
            'description'  => $row['description'],
            'published'    => $row['published'],
            'publish_up'   => $row['publish_up'],
            'publish_down' => $row['publish_down']
        ));

        $this->markChanged($sliderId);

        return $slideID;
    }

    public function createSimpleEditAdd($postData, $sliderId) {

        $data = new Data($postData);

        $title       = $data->get('title', '');
        $description = $data->get('description', '');

        $slideBuilder = new BuilderComponentSlide(array(
            'title'                  => $title,
            'description'            => $description,
            'thumbnailType'          => $data->get('thumbnailType', ''),
            'thumbnail'              => $data->get('backgroundImage', ''),
            'background-type'        => 'image',
            'backgroundImage'        => $data->get('backgroundImage', ''),
            'backgroundImageOpacity' => 100,
            'backgroundColor'        => '000000FF',
            'href'                   => $data->get('href', ''),
            'href-target'            => $data->get('href-target', '')
        ));

        $slideBuilder->content->set(array(
            'desktopportraitpadding' => '10|*|100|*|10|*|100|*|px',
            'mobileportraitpadding'  => '10|*|10|*|10|*|10|*|px'
        ));

        $videoUrl = $data->get('video', '');

        if (!empty($videoUrl)) {
            preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/', $videoUrl, $matches);

            if (!empty($matches)) {
                /**
                 * YouTube
                 */
                $thumbnail = 'https://i.ytimg.com/vi/' . $matches[2] . '/hqdefault.jpg';
                $slideBuilder->set('thumbnail', $thumbnail);


                $youtubeLayer = new BuilderComponentLayer($slideBuilder->content, 'youtube');
                $youtubeLayer->item->set(array(
                    'code'       => $matches[2],
                    'youtubeurl' => $videoUrl,
                    'image'      => $thumbnail
                ));
            } else {

                preg_match('/https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)/', $videoUrl, $matches);
                if (!empty($matches)) {
                    /**
                     * Vimeo
                     */

                    $vimeoLayer = new BuilderComponentLayer($slideBuilder->content, 'vimeo');
                    $vimeoLayer->item->set(array(
                        'vimeourl' => $videoUrl
                    ));
                } else {
                    /**
                     * MP4
                     */

                    $mp4Layer = new BuilderComponentLayer($slideBuilder->content, 'video');
                    $mp4Layer->item->set(array(
                        'video_mp4' => $videoUrl
                    ));
                }
            }
        }

        /*
        if ($title) {
            $heading = new BuilderComponentLayer($slideBuilder->content, 'heading');
            $heading->item->set(array(
                'heading' => '{name/slide}'
            ));
        }
        */

        $row = $this->convertSlideDataToDatabaseRow($slideBuilder->getData(), $sliderId);

        $slideID = $this->create($row['slider'], $row['title'], $row['slide'], $row['thumbnail'], $row['params'], array(
            'description'  => $row['description'],
            'published'    => $row['published'],
            'publish_up'   => $row['publish_up'],
            'publish_down' => $row['publish_down']
        ));

        $this->markChanged($sliderId);

        return $slideID;
    }

    public function import($row, $sliderId) {

        if (!$row['params']->has('version')) {
            /**
             * We must set the missing empty version to allow upgrade of the old slides
             */
            $row['params']->set('version', '');
        }

        return $this->create($sliderId, $row['title'], $row['slide'], $row['thumbnail'], $row['params']->toArray(), array(
            'description'  => $row['description'],
            'published'    => $row['published'],
            'publish_up'   => $row['publish_up'],
            'publish_down' => $row['publish_down'],
            'first'        => $row['first'],
            'ordering'     => $row['ordering'],
            'generator_id' => $row['generator_id']
        ));
    }

    private function create($sliderID, $title, $layers, $thumbnail, $params = array(), $optional = array()) {

        if (!isset($optional['ordering'])) {
            $optional['ordering'] = $this->getNextOrdering($sliderID);
        }

        if (!isset($params['version'])) {
            $params['version'] = SmartSlider3Info::$version;
        }

        $data = array_merge(array(
            'description'  => '',
            'first'        => 0,
            'published'    => 1,
            'publish_up'   => '1970-01-01 00:00:00',
            'publish_down' => '1970-01-01 00:00:00',
            'generator_id' => 0
        ), $optional, array(
            'title'     => $title,
            'slide'     => json_encode($layers, JSON_UNESCAPED_SLASHES),
            'thumbnail' => $thumbnail,
            'params'    => json_encode($params, JSON_UNESCAPED_SLASHES),
            'slider'    => $sliderID
        ));

        $this->table->insert($data);

        return $this->table->insertId();
    }

    /**
     * @param      $sliderId
     * @param int  $generatorID
     * @param      $slide
     *
     * @return bool
     */
    public function createSlideWithGenerator($sliderId, $generatorID, $slide) {

        $row = $this->convertSlideDataToDatabaseRow($slide, $sliderId);

        $slideId = $this->create($row['slider'], $row['title'], $row['slide'], $row['thumbnail'], $row['params'], array(
            'description'  => $row['description'],
            'published'    => $row['published'],
            'publish_up'   => $row['publish_up'],
            'publish_down' => $row['publish_down'],
            'generator_id' => $generatorID
        ));

        $this->markChanged($sliderId);

        return $slideId;
    }

    /**
     * @param int    $slideID
     * @param string $slide
     * @param string $guides
     *
     * @return bool
     */
    public function save($slideID, $slide, $guides) {

        $slideData           = json_decode(Base64::decode($slide), true);
        $slideData['guides'] = $guides;

        $row = $this->convertSlideDataToDatabaseRow($slideData);

        $this->table->update(array(
            'title'        => $row['title'],
            'slide'        => json_encode($row['slide'], JSON_UNESCAPED_SLASHES),
            'description'  => $row['description'],
            'thumbnail'    => $row['thumbnail'],
            'published'    => $row['published'],
            'publish_up'   => $row['publish_up'],
            'publish_down' => $row['publish_down'],
            'params'       => json_encode($row['params'], JSON_UNESCAPED_SLASHES)
        ), array('id' => $slideID));

        $this->markChanged(Request::$REQUEST->getInt('sliderid'));

        return true;
    }

    public function saveSimple($slideID, $title, $description, $params) {

        $this->table->update(array(
            'title'       => $title,
            'description' => $description,
            'params'      => json_encode($params, JSON_UNESCAPED_SLASHES)
        ), array('id' => $slideID));
    }

    /**
     * Updates the params field of the slide;
     *
     * @param $id
     * @param $params
     */
    public function updateSlideParams($id, $params) {

        $this->table->update(array(
            'params' => json_encode($params)
        ), array('id' => $id));

    }

    public function delete($id) {

        $slide = $this->get($id);

        if ($slide['generator_id'] > 0) {
            $slidesWithSameGenerator = $this->getAll($slide['slider'], 'AND generator_id = ' . intval($slide['generator_id']));
            if (count($slidesWithSameGenerator) == 1) {
                $generatorModel = new ModelGenerator($this);
                $generatorModel->delete($slide['generator_id']);
            }
        }

        $this->table->deleteByAttributes(array(
            "id" => intval($id)
        ));

        $this->markChanged($slide['slider']);

    }

    /**
     * @param int      $id
     * @param bool     $maintainOrdering
     * @param bool|int $targetSliderId
     *
     * @return int The new slide ID;
     */
    public function copyTo($id, $maintainOrdering = false, $targetSliderId = false) {
        $row = $this->get($id);
        unset($row['id']);

        $row['first'] = 0;

        if ($targetSliderId === false || $row['slider'] == $targetSliderId) {
            /**
             * Copy the slide to the same slider
             */

            $this->shiftSlideOrdering($row['slider'], $row['ordering']);
        } else {
            /**
             * Copy the slide to another slider
             */
            $row['slider'] = $targetSliderId;

            if (!$maintainOrdering) {
                $row['ordering'] = 0;
            }
        }

        if (!empty($row['generator_id'])) {
            $generatorModel      = new ModelGenerator($this);
            $row['generator_id'] = $generatorModel->duplicate($row['generator_id']);
        }

        $row['slide'] = json_encode(AbstractComponent::translateUniqueIdentifier(json_decode($row['slide'], true)), JSON_UNESCAPED_SLASHES);

        $this->table->insert($row);

        $id = $this->table->insertId();

        $this->markChanged($row['slider']);

        return $id;
    }

    public function setTitle($id, $title) {
        $slide = $this->get($id);

        $this->table->update(array(
            "title" => $title
        ), array(
            "id" => $id
        ));

        $this->markChanged($slide['slider']);
    }

    public function first($id) {
        $slide = $this->get($id);

        $this->table->update(array(
            "first" => 0
        ), array(
            "slider" => $slide['slider']
        ));

        $this->table->update(array(
            "first" => 1
        ), array(
            "id" => $id
        ));

        $this->markChanged($slide['slider']);
    }

    public function publish($id) {

        $this->markChanged(Request::$REQUEST->getInt('sliderid'));

        return $this->table->update(array(
            "published" => 1
        ), array("id" => intval($id)));
    }

    public function unPublish($id) {
        $this->table->update(array(
            "published" => 0
        ), array(
            "id" => intval($id)
        ));

        $this->markChanged(Request::$REQUEST->getInt('sliderid'));

    }

    public function convertToSlide($id) {
        $slide = $this->get($id);

        $data = new Data($slide['params'], true);
        $data->set('static-slide', 0);

        $this->table->update(array(
            "params" => $data->toJSON()
        ), array(
            "id" => intval($id)
        ));

        $this->markChanged($slide['slider']);
    }

    public function deleteBySlider($sliderid) {

        $slides = $this->getAll($sliderid);
        foreach ($slides as $slide) {
            $this->delete($slide['id']);
        }
        $this->markChanged($sliderid);
    }

    /**
     * @param $sliderid
     * @param $ids
     *
     * @return bool|int
     */
    public function order($sliderid, $ids) {
        if (is_array($ids) && count($ids) > 0) {
            $i = 0;
            foreach ($ids as $id) {
                $id = intval($id);
                if ($id > 0) {
                    $this->table->update(array(
                        'ordering' => $i + 1,
                    ), array(
                        "id"     => $id,
                        "slider" => $sliderid
                    ));

                    $i++;
                }
            }

            $this->markChanged($sliderid);

            return $i;
        }

        return false;
    }

    private function markChanged($sliderid) {

        $helper = new HelperSliderChanged($this);
        $helper->setSliderChanged($sliderid, 1);
    }

    public function convertDynamicSlideToSlides($slideId) {
        $slideData = $this->get($slideId);
        if ($slideData['generator_id'] > 0) {
            $sliderObj = new Slider($this, $slideData['slider'], array(), true);
            $rootSlide = new Slide($sliderObj, $slideData);
            $rootSlide->initGenerator(array());
            $slides = $rootSlide->expandSlide();

            $this->shiftSlideOrdering($slideData['slider'], $slideData['ordering'], count($slides));

            $firstUsed = false;
            $i         = 1;
            foreach ($slides as $slide) {
                $row                = $slide->getRow();
                $row['title']       = Str::substr($row['title'], 0, 200);
                $row['description'] = Str::substr($row['description'], 0, 2000);
                // set the proper ordering
                $row['ordering'] += $i;
                if ($row['first']) {
                    // Make sure to mark only one slide as start slide
                    if ($firstUsed) {
                        $row['first'] = 0;
                    } else {
                        $firstUsed = true;
                    }
                }
                $this->table->insert($row);
                $i++;
            }

            Database::query("UPDATE {$this->getTableName()} SET published = 0, first = 0 WHERE id = :id", array(
                ":id" => $slideData['id']
            ));

            return count($slides);
        } else {
            return false;
        }
    }

    public static function prepareSample(&$layers) {
        for ($i = 0; $i < count($layers); $i++) {

            if (isset($layers[$i]['type'])) {
                switch ($layers[$i]['type']) {
                    case 'content':
                        ComponentContent::prepareSample($layers[$i]);
                        break;
                    case 'row':
                        ComponentRow::prepareSample($layers[$i]);
                        break;
                    case 'col':
                        ComponentCol::prepareSample($layers[$i]);
                        break;
                    default:
                        ComponentLayer::prepareSample($layers[$i]);
                }
            } else {
                ComponentLayer::prepareSample($layers[$i]);
            }
        }
    }

    public function convertSlideDataToDatabaseRow($slideData, $sliderID = false) {

        $slideData['version'] = SmartSlider3Info::$version;

        $publish_up = '1970-01-01 00:00:00';
        if (isset($slideData['publish_up'])) {
            if ($slideData['publish_up'] != '0000-00-00 00:00:00') {
                $publish_up = date('Y-m-d H:i:s', strtotime($slideData['publish_up']));
            } else {
                $publish_up = '1970-01-01 00:00:00';
            }
        }

        $publish_down = '1970-01-01 00:00:00';
        if (isset($slideData['publish_down'])) {
            if ($slideData['publish_down'] != '0000-00-00 00:00:00') {
                $publish_down = date('Y-m-d H:i:s', strtotime($slideData['publish_down']));
            } else {
                $publish_down = '1970-01-01 00:00:00';
            }
        }

        $row = array(
            'title'        => $slideData['title'],
            'slide'        => '',
            'description'  => $slideData['description'],
            'thumbnail'    => $slideData['thumbnail'],
            'published'    => (isset($slideData['published']) ? $slideData['published'] : 1),
            'publish_up'   => $publish_up,
            'publish_down' => $publish_down
        );

        if ($sliderID !== false) {
            $row['slider'] = $sliderID;
        }

        $row['slide'] = $slideData['layers'];

        if (isset($slideData['first'])) {
            $row['first'] = intval($slideData['first']);
        }

        if (isset($slideData['generator_id']) && $slideData['generator_id'] > 0) {
            $row['generator_id'] = intval($slideData['generator_id']);
        }

        unset($slideData['title']);
        unset($slideData['layers']);
        unset($slideData['description']);
        unset($slideData['thumbnail']);
        unset($slideData['published']);
        unset($slideData['first']);
        unset($slideData['publish_up']);
        unset($slideData['publish_down']);
        unset($slideData['ordering']);
        unset($slideData['generator_id']);

        $row['params'] = $slideData;

        return $row;
    }

    private function removeFourByteChars($text) {
        return preg_replace('/[\x{10000}-\x{10FFFF}]/u', "\xEF\xBF\xBD", $text);
    }

    /**
     * @param int $sliderID
     *
     * @return int
     */
    private function getNextOrdering($sliderID) {

        $query  = "SELECT MAX(ordering) AS ordering FROM " . $this->getTableName() . " WHERE slider = :id";
        $result = Database::queryRow($query, array(
            ":id" => intval($sliderID)
        ));

        if (isset($result['ordering'])) {
            return $result['ordering'] + 1;
        }

        return 1;
    }

    /**
     * @param int $sliderID
     * @param int $offset
     * @param int $slidesCount
     */
    private function shiftSlideOrdering($sliderID, $offset, $slidesCount = 1) {

        // Shift the afterwards slides with the slides count
        Database::query("UPDATE {$this->getTableName()} SET ordering = ordering + " . $slidesCount . " WHERE slider = :sliderid AND ordering > :ordering", array(
            ":sliderid" => intval($sliderID),
            ":ordering" => intval($offset)
        ), '');
    }
}