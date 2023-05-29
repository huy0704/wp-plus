<?php


namespace Nextend\SmartSlider3\Application\Admin\Sliders;

use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Grouping;
use Nextend\Framework\Form\Element\Message\Notice;
use Nextend\Framework\Form\Element\OnOff;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Select\SelectFile;
use Nextend\Framework\Form\Element\Token;
use Nextend\Framework\Form\Element\Upload;
use Nextend\Framework\Form\Form;
use Nextend\Framework\Platform\Platform;
use Nextend\Framework\View\AbstractView;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\TopBarMain\BlockTopBarMain;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonBack;
use Nextend\SmartSlider3\Application\Admin\Layout\Block\Forms\Button\BlockButtonImport;
use Nextend\SmartSlider3\Application\Admin\Layout\LayoutDefault;
use Nextend\SmartSlider3\Application\Admin\TraitAdminUrl;

class ViewSlidersImport extends AbstractView {

    use TraitAdminUrl;

    /**
     * @var LayoutDefault
     */
    protected $layout;

    /**
     * @var int
     */
    protected $groupID;

    public function display() {

        $this->layout = new LayoutDefault($this);

        $this->layout->addBreadcrumb(n2_('Import project'), '', $this->getUrlImport());

        $this->displayTopBar();

        $this->displayHeader();

        $this->layout->render();

    }

    protected function displayTopBar() {

        $topBar = new BlockTopBarMain($this);

        $buttonImport = new BlockButtonImport($this);
        $buttonImport->addClass('n2_button--inactive');
        $buttonImport->addClass('n2_slider_import');
        $topBar->addPrimaryBlock($buttonImport);

        $buttonBack = new BlockButtonBack($this);
        $buttonBack->setUrl($this->getUrlDashboard());
        $buttonBack->addClass('n2_slider_import_back');
        $topBar->addPrimaryBlock($buttonBack);

        $this->layout->setTopBar($topBar->toHTML());
    }

    protected function displayHeader() {

        $this->layout->addContent($this->render('Import'));
    }


    public function renderForm() {

        $form = new Form($this, 'slider');

        new Token($form->getFieldsetHidden());

        $settings = new ContainerTable($form->getContainer(), 'import-slider', n2_('Import project'));


        $row1 = $settings->createRow('import-row-1');

        $instructions = n2_('You can upload *.ss3 files which were exported by Smart Slider 3.') . '<br>';
        new Notice($row1, 'instructions', n2_('Instruction'), $instructions);


        $row2 = $settings->createRow('import-row-2');

        new OnOff($row2, 'upload_or_local', n2_('Local import'), 0, array(
            'relatedFieldsOff' => array(
                'sliderupload-grouping'
            ),
            'relatedFieldsOn'  => array(
                'sliderlocal-import-grouping'
            )
        ));


        $uploadGrouping = new Grouping($row2, 'upload-grouping');

        new Upload($uploadGrouping, 'import-file', n2_('Upload file'));
        new Notice($uploadGrouping, 'instructions', '', sprintf(n2_('Your server\'s upload filesize limitation is %s, so if your file is bigger, use the local import.'), @ini_get('post_max_size')));


        $localImportGrouping = new Grouping($row2, 'local-import-grouping');

        new SelectFile($localImportGrouping, 'local-import-file', n2_('File'), '', 'ss3');

        new Notice($localImportGrouping, 'instructions', '', sprintf(n2_('Files with %1$s.ss3%2$s extension are listed from: %3$s'), '<i>', '</i>', Platform::getPublicDirectory()));

        new OnOff($localImportGrouping, 'delete', n2_('Delete file'), 0, array(
            'tipLabel'       => n2_('Delete file'),
            'tipDescription' => n2_('Removes the selected .ss3 file from your sever after the import.'),
        ));


        $row3 = $settings->createRow('import-row-3');

        new OnOff($row3, 'restore', n2_('Restore slider'), 0, array(
            'tipLabel'       => n2_('Restore'),
            'tipDescription' => n2_('The imported slider will have the same ID as the original export has. If you have a slider with the same ID, it will be overwritten.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1728-export-import-slider#import'
        ));

        new Select($row3, 'image-mode', n2_('Image mode'), 'clone', array(
            'options'        => array(
                'clone'    => n2_('Clone'),
                'update'   => n2_('Old site url'),
                'original' => n2_('Original')
            ),
            'tipLabel'       => n2_('Image mode'),
            'tipDescription' => n2_('You can choose how the slide images are loaded.'),
            'tipLink'        => 'https://smartslider.helpscoutdocs.com/article/1728-export-import-slider#image-mode'
        ));

        $form->render();
    }

    /**
     * @return int
     */
    public function getGroupID() {
        return $this->groupID;
    }

    /**
     * @param int $groupID
     */
    public function setGroupID($groupID) {
        $this->groupID = $groupID;
    }
}