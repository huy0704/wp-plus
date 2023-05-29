<?php


namespace Nextend\SmartSlider3\Application\Model;


use Exception;
use Nextend\Framework\Data\Data;
use Nextend\Framework\Database\Database;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\ContainerInterface;
use Nextend\Framework\Form\Element\Button\ButtonRecordViewer;
use Nextend\Framework\Form\Element\Text\Number;
use Nextend\Framework\Misc\Base64;
use Nextend\Framework\Model\AbstractModelTable;
use Nextend\SmartSlider3\Generator\AbstractGeneratorGroup;
use Nextend\SmartSlider3\Generator\GeneratorFactory;
use Nextend\SmartSlider3\SlideBuilder\BuilderComponentCol;
use Nextend\SmartSlider3\SlideBuilder\BuilderComponentLayer;
use Nextend\SmartSlider3\SlideBuilder\BuilderComponentRow;
use Nextend\SmartSlider3\SlideBuilder\BuilderComponentSlide;

class ModelGenerator extends AbstractModelTable {

    protected function createConnectorTable() {

        return Database::getTable('nextend2_smartslider3_generators');
    }

    private static function getLayout($type) {

        $slideBuilder = new BuilderComponentSlide();

        switch ($type) {
            case 'image':
                $slideBuilder->set(array(
                    'title'           => "{title}",
                    'thumbnail'       => "{thumbnail}",
                    'backgroundImage' => "{image}",
                    'background-type' => 'image'
                ));
                break;

            case 'image_extended':
                $slideBuilder->set(array(
                    'title'           => "{title}",
                    'thumbnail'       => "{thumbnail}",
                    'backgroundImage' => "{image}",
                    'background-type' => 'image'
                ));

                $slideBuilder->content->set(array(
                    'verticalalign'          => 'flex-end',
                    'desktopportraitpadding' => '0|*|0|*|0|*|0|*|px'
                ));
                $row = new BuilderComponentRow($slideBuilder->content);
                $row->set(array(
                    'bgcolor' => '00000080',
                ));
                $col = new BuilderComponentCol($row, '1');
                $col->set(array(
                    'desktopportraitinneralign' => "left"
                ));
                $heading = new BuilderComponentLayer($col, 'heading');
                $heading->set(array(
                    'desktopportraitselfalign' => 'inherit'
                ));
                $heading->item->set(array(
                    'heading' => '{title/1}',
                ));
                break;

            case 'article':
                $slideBuilder->set(array(
                    'title'           => "{title}",
                    'description'     => '{description}',
                    'href'            => '{url}',
                    'thumbnail'       => "{thumbnail}",
                    'backgroundImage' => "{image}",
                    'background-type' => 'image'
                ));

                $slideBuilder->content->set(array(
                    'verticalalign'          => 'flex-end',
                    'desktopportraitpadding' => '0|*|0|*|0|*|0|*|px',
                ));
                $row = new BuilderComponentRow($slideBuilder->content);
                $row->set(array(
                    'bgcolor' => '00000080',
                ));
                $col = new BuilderComponentCol($row, '1');
                $col->set(array(
                    'desktopportraitinneralign' => "left",
                ));
                $heading = new BuilderComponentLayer($col, 'heading');
                $heading->set(array(
                    'desktopportraitselfalign' => 'inherit'
                ));
                $heading->item->set(array(
                    'heading' => '{title}',
                    'font'    => Base64::encode('{"data":[{"extra":"","color":"ffffffff","size":"36||px","tshadow":"0|*|0|*|0|*|000000ff","afont":"Roboto,Arial","lineheight":"1.5","bold":0,"italic":0,"underline":0,"align":"inherit","letterspacing":"normal","wordspacing":"normal","texttransform":"none"},{"extra":""}]}')
                ));
                break;

            case 'product':
                $slideBuilder->set(array(
                    'title'           => "{title}",
                    'description'     => '{description}',
                    'href'            => '{url}',
                    'thumbnail'       => "{thumbnail}",
                    'backgroundImage' => "{image}",
                    'background-type' => 'image'
                ));

                $slideBuilder->content->set(array(
                    'verticalalign'          => 'flex-end',
                    'desktopportraitpadding' => '0|*|0|*|0|*|0|*|px',
                ));
                $row = new BuilderComponentRow($slideBuilder->content);
                $row->set(array(
                    'bgcolor' => '00000080',
                ));
                $col = new BuilderComponentCol($row, '1/2');
                $col->set(array(
                    'desktopportraitinneralign' => "left",
                ));
                $heading = new BuilderComponentLayer($col, 'heading');
                $heading->set(array(
                    'desktopportraitselfalign' => 'inherit'
                ));
                $heading->item->set(array(
                    'heading' => '{title}',
                    'font'    => Base64::encode('{"data":[{"extra":"","color":"ffffffff","size":"36||px","tshadow":"0|*|0|*|0|*|000000ff","afont":"Roboto,Arial","lineheight":"1.5","bold":0,"italic":0,"underline":0,"align":"inherit","letterspacing":"normal","wordspacing":"normal","texttransform":"none"},{"extra":""}]}'),
                ));
                $col2 = new BuilderComponentCol($row, '1/2');
                $col2->set(array(
                    'desktopportraitinneralign' => "right",
                ));
                $text = new BuilderComponentLayer($col2, 'text');
                $text->set(array(
                    'desktopportraitselfalign' => 'inherit'
                ));
                $text->item->set(array(
                    'content' => '{price}',
                    'font'    => Base64::encode('{"data":[{"extra":"","color":"ffffffff","size":"36||px","tshadow":"0|*|0|*|0|*|000000ff","afont":"Roboto,Arial","lineheight":"1.5","bold":0,"italic":0,"underline":0,"align":"inherit","letterspacing":"normal","wordspacing":"normal","texttransform":"none"},{"extra":""}]}'),
                ));

                break;

            case 'event':
                $slideBuilder->set(array(
                    'title'           => "{title}",
                    'description'     => '{description}',
                    'href'            => '{url}',
                    'thumbnail'       => "{thumbnail}",
                    'backgroundImage' => "{image}",
                    'background-type' => 'image'
                ));
                $slideBuilder->content->set(array(
                    'verticalalign'          => 'flex-end',
                    'desktopportraitpadding' => '0|*|0|*|0|*|0|*|px',
                ));
                $row = new BuilderComponentRow($slideBuilder->content);
                $row->set(array(
                    'bgcolor' => '00000080',
                ));
                $col = new BuilderComponentCol($row, '1/2');
                $col->set(array(
                    'desktopportraitinneralign' => "left",
                ));
                $heading = new BuilderComponentLayer($col, 'heading');
                $heading->set(array(
                    'desktopportraitselfalign' => 'inherit'
                ));
                $heading->item->set(array(
                    'heading' => '{title}',
                    'font'    => Base64::encode('{"data":[{"extra":"","color":"ffffffff","size":"36||px","tshadow":"0|*|0|*|0|*|000000ff","afont":"Roboto,Arial","lineheight":"1.5","bold":0,"italic":0,"underline":0,"align":"inherit","letterspacing":"normal","wordspacing":"normal","texttransform":"none"},{"extra":""}]}'),
                ));
                $col2 = new BuilderComponentCol($row, '1/2');
                $col2->set(array(
                    'desktopportraitinneralign' => "right",
                ));
                $heading = new BuilderComponentLayer($col2, 'heading');
                $heading->set(array(
                    'desktopportraitselfalign' => 'inherit'
                ));
                $heading->item->set(array(
                    'heading' => '{start_date}',
                    'font'    => Base64::encode('{"data":[{"extra":"","color":"ffffffff","size":"36||px","tshadow":"0|*|0|*|0|*|000000ff","afont":"Roboto,Arial","lineheight":"1.5","bold":0,"italic":0,"underline":0,"align":"inherit","letterspacing":"normal","wordspacing":"normal","texttransform":"none"},{"extra":""}]}'),
                ));

                break;

            case 'youtube':
                $slideBuilder->set(array(
                    'title'                  => "{title}",
                    'description'            => '{description}',
                    'thumbnail'              => "{thumbnail}",
                    'backgroundColor'        => "ffffff00",
                    'background-type'        => 'color',
                    'desktopportraitpadding' => '0|*|0|*|0|*|0|*|px',
                ));
                
                $youtube = new BuilderComponentLayer($slideBuilder->content, 'youtube');
                $youtube->item->set(array(
                    "youtubeurl" => "{video_url}",
                ));
                break;

            case 'vimeo':
                $slideBuilder->set(array(
                    'title'                  => "{title}",
                    'description'            => '{description}',
                    'thumbnail'              => "{image200x150/1}",
                    'backgroundColor'        => "ffffff00",
                    'background-type'        => 'color',
                    'desktopportraitpadding' => '0|*|0|*|0|*|0|*|px',
                ));

                $vimeo = new BuilderComponentLayer($slideBuilder->content, 'vimeo');
                $vimeo->item->set(array(
                    "vimeourl" => "{url}",
                    'image'    => '{image}'
                ));

                break;

            case 'video_mp4':
                $slideBuilder->set(array(
                    'title'                  => "{name}",
                    'desktopportraitpadding' => '0|*|0|*|0|*|0|*|px',
                ));

                $video = new BuilderComponentLayer($slideBuilder->content, 'video');
                $video->item->set(array(
                    "video_mp4" => "{video}",
                ));
                break;

            case 'social_post':
                $slideBuilder->set(array(
                    'title'           => "{title}",
                    'description'     => '{description}',
                    'href'            => '{url}',
                    'thumbnail'       => "{author_image}",
                    'backgroundColor' => "ffffff00",
                    'background-type' => 'color',
                ));

                $slideBuilder->content->set(array(
                    'verticalalign'          => 'center',
                    'desktopportraitpadding' => '0|*|0|*|0|*|0|*|px',
                    'desktopportraitmargin'  => '0|*|0|*|0|*|0|*|px'
                ));

                $row = new BuilderComponentRow($slideBuilder->content);
                $row->set(array(
                    'bgcolor'                => '00000080',
                    'desktopportraitpadding' => '10|*|10|*|10|*|10|*|px',
                    'desktopportraitmargin'  => '0|*|0|*|0|*|0|*|px'
                ));
                $col = new BuilderComponentCol($row, '1');
                $col->set(array(
                    'desktopportraitinneralign' => "left",
                    'desktopportraitmargin'     => '0|*|0|*|0|*|0|*|px',
                    'desktopportraitpadding'    => '10|*|10|*|10|*|10|*|px'
                ));
                $heading = new BuilderComponentLayer($col, 'heading');
                $heading->set(array(
                    'desktopportraitmargin'    => '0|*|0|*|0|*|0|*|px',
                    'desktopportraitselfalign' => 'inherit'
                ));
                $heading->item->set(array(
                    'heading' => '{message}',
                ));
                $image = new BuilderComponentLayer($col, 'image');
                $image->set(array(
                    'desktopportraitmargin'    => '0|*|0|*|0|*|0|*|px',
                    'desktopportraitselfalign' => 'inherit'
                ));
                $image->item->set(array(
                    'image' => '{author_image}',
                ));
                $button = new BuilderComponentLayer($col, 'button');
                $button->set(array(
                    'desktopportraitmargin'    => '0|*|0|*|0|*|0|*|px',
                    'desktopportraitselfalign' => 'inherit'
                ));
                $button->item->set(array(
                    'content' => '{url_label}',
                ));

                break;

            case 'text':
                $slideBuilder->set(array(
                    'title' => "{title}"
                ));
                $slideBuilder->content->set(array(
                    'verticalalign'          => 'flex-end',
                    'desktopportraitpadding' => '0|*|0|*|0|*|0|*|px',
                ));
                $row = new BuilderComponentRow($slideBuilder->content);
                $row->set(array(
                    'bgcolor' => '00000080',
                ));
                $col = new BuilderComponentCol($row, '1');
                $col->set(array(
                    'desktopportraitinneralign' => "left",
                ));
                $heading = new BuilderComponentLayer($col, 'heading');
                $heading->set(array(
                    'desktopportraitselfalign' => 'inherit'
                ));
                $heading->item->set(array(
                    'heading' => '{title}',
                    'font'    => Base64::encode('{"data":[{"extra":"","color":"ffffffff","size":"36||px","tshadow":"0|*|0|*|0|*|000000ff","afont":"Roboto,Arial","lineheight":"1.5","bold":0,"italic":0,"underline":0,"align":"inherit","letterspacing":"normal","wordspacing":"normal","texttransform":"none"},{"extra":""}]}')
                ));
                break;

            case 'text_generator':
                $slideBuilder->set(array(
                    'title' => "{variable1}"
                ));
                $slideBuilder->content->set(array(
                    'verticalalign'          => 'flex-end',
                    'desktopportraitpadding' => '0|*|0|*|0|*|0|*|px',
                ));
                $row = new BuilderComponentRow($slideBuilder->content);
                $row->set(array(
                    'bgcolor' => '00000080',
                ));
                $col = new BuilderComponentCol($row, '1');
                $col->set(array(
                    'desktopportraitinneralign' => "left",
                ));
                $heading = new BuilderComponentLayer($col, 'heading');
                $heading->set(array(
                    'desktopportraitselfalign' => 'inherit'
                ));
                $heading->item->set(array(
                    'heading' => '{variable1}',
                    'font'    => Base64::encode('{"data":[{"extra":"","color":"ffffffff","size":"36||px","tshadow":"0|*|0|*|0|*|000000ff","afont":"Roboto,Arial","lineheight":"1.5","bold":0,"italic":0,"underline":0,"align":"inherit","letterspacing":"normal","wordspacing":"normal","texttransform":"none"},{"extra":""}]}')
                ));
                break;

            default:
                return $slideBuilder->set(array(
                    'title'           => "title",
                    'description'     => '',
                    'backgroundColor' => "ffffff00",
                    'background-type' => 'color',
                ));
        }

        return $slideBuilder->getData();
    }

    public function createGenerator($sliderId, $params) {

        $data = new Data($params);

        unset($params['type']);
        unset($params['group']);
        unset($params['record-slides']);

        try {
            $generatorId = $this->_create($data->get('type'), $data->get('group'), json_encode($params));


            $source = $this->getGeneratorGroup($data->get('group'))
                           ->getSource($data->get('type'));

            $slideData = self::getLayout($source->getLayout());

            $slideData['record-slides'] = intval($data->get('record-slides', 5));

            $slidesModel = new ModelSlides($this);
            $slideId     = $slidesModel->createSlideWithGenerator($sliderId, $generatorId, $slideData);

            return array(
                'slideId'     => $slideId,
                'generatorId' => $generatorId
            );
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param ContainerInterface $container
     */
    public function renderFields($container) {

        $settings = new ContainerTable($container, 'generator', n2_('Generator settings'));

        $generatorRow = $settings->createRow('generator-row');

        new Number($generatorRow, 'record-slides', n2_('Slides'), 5, array(
            'unit' => n2_x('slides', 'Unit'),
            'wide' => 3,
        ));

        new Number($generatorRow, 'cache-expiration', n2_('Cache expiration'), 24, array(
            'wide' => 3,
            'unit' => n2_('Hours')
        ));

        new ButtonRecordViewer($generatorRow, 'record-viewer');

    }

    /**
     * @param $type
     *
     * @return AbstractGeneratorGroup
     */
    public function getGeneratorGroup($type) {

        return GeneratorFactory::getGenerator($type);
    }

    public function get($id) {
        return Database::queryRow("SELECT * FROM " . $this->getTableName() . " WHERE id = :id", array(
            ":id" => $id
        ));
    }

    public function import($generator) {
        $this->table->insert(array(
            'type'   => $generator['type'],
            'group'  => $generator['group'],
            'params' => $generator['params']
        ));

        return $this->table->insertId();
    }

    private function _create($type, $group, $params) {
        $this->table->insert(array(
            'type'   => $type,
            'group'  => $group,
            'params' => $params
        ));

        return $this->table->insertId();
    }

    public function save($generatorId, $params) {

        $this->table->update(array(
            'params' => json_encode($params)
        ), array('id' => $generatorId));

        return $generatorId;
    }

    public function delete($id) {
        $this->table->deleteByAttributes(array(
            "id" => intval($id)
        ));
    }

    public function duplicate($id) {
        $generatorRow = $this->get($id);
        $generatorId  = $this->_create($generatorRow['type'], $generatorRow['group'], $generatorRow['params']);

        return $generatorId;
    }

    public function getSliderId($generatorId) {

        $slidesModal = new ModelSlides($this);
        $slideData   = Database::queryRow("SELECT slider FROM " . $slidesModal->getTableName() . " WHERE generator_id = :id", array(
            ":id" => $generatorId
        ));

        return $slideData['slider'];
    }
}