<?php


namespace Nextend\SmartSlider3\Application\Model;


use Exception;
use Nextend\Framework\Database\Database;
use Nextend\Framework\Model\AbstractModelTable;
use Nextend\SmartSlider3\Application\Helper\HelperSliderChanged;
use Nextend\SmartSlider3\SmartSlider3Info;

class ModelSlidersXRef extends AbstractModelTable {

    protected function createConnectorTable() {

        return Database::getTable('nextend2_smartslider3_sliders_xref');
    }

    public function add($groupID, $sliderID) {
        try {
            $this->table->insert(array(
                'group_id'  => $groupID,
                'slider_id' => $sliderID,
                'ordering'  => $this->getMaximalOrderValue($groupID)
            ));

            $helper = new HelperSliderChanged($this);
            $helper->setSliderChanged($sliderID, 1);
            $helper->setSliderChanged($groupID, 1);

            SmartSlider3Info::sliderChanged();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param int $groupID
     *
     * @return array the IDs of the deleted child sliders.
     */
    public function deleteGroup($groupID) {
        $sliders = $this->getSliders($groupID);

        $deletedSliders = array();

        $slidersModel = new ModelSliders($this);
        foreach ($sliders as $slider) {
            $relatedGroups = $this->getGroups($slider['slider_id']);
            if (count($relatedGroups) == 1) {
                if ($slidersModel->trashOrDelete($slider['slider_id'], $groupID) == 'delete') {
                    $deletedSliders[] = $slider['slider_id'];
                }
            }
        }

        $this->table->deleteByAttributes(array(
            'group_id' => $groupID
        ));

        SmartSlider3Info::sliderChanged();

        return $deletedSliders;
    }

    public function deleteSlider($sliderID) {

        $helper = new HelperSliderChanged($this);
        $helper->setSliderChanged($sliderID, 1);

        SmartSlider3Info::sliderChanged();

        return $this->table->deleteByAttributes(array(
            'slider_id' => $sliderID
        ));
    }

    public function deleteXref($groupID, $sliderID) {

        $helper = new HelperSliderChanged($this);
        $helper->setSliderChanged($sliderID, 1);
        $helper->setSliderChanged($groupID, 1);

        SmartSlider3Info::sliderChanged();

        return $this->table->deleteByAttributes(array(
            'group_id'  => $groupID,
            'slider_id' => $sliderID
        ));
    }

    public function getSliders($groupID, $status = '*') {

        if ($status !== '*') {
            $slidersModel = new ModelSliders($this);

            return Database::queryAll("
            SELECT xref.slider_id
            FROM " . $this->getTableName() . " AS xref
            LEFT JOIN " . $slidersModel->getTableName() . " AS sliders ON sliders.id = xref.slider_id
            WHERE xref.group_id = '" . $groupID . "' AND sliders.slider_status LIKE " . Database::quote($status) . "
            ORDER BY xref.ordering ASC");
        }

        return Database::queryAll("
            SELECT slider_id
            FROM " . $this->getTableName() . "
            WHERE group_id = '" . $groupID . "'
            ORDER BY ordering ASC");
    }

    public function getGroupsIDs($sliderID) {
        $ids = array();

        $result = Database::queryAll("
            SELECT group_id
            FROM " . $this->getTableName() . "
            WHERE slider_id = '" . $sliderID . "'
            ORDER BY ordering ASC");

        foreach ($result as $row) {
            $ids[] = $row['group_id'];
        }

        return $ids;
    }

    public function getGroups($sliderID, $status = '*') {
        $slidersModel = new ModelSliders($this);

        $wheres = array("xref.slider_id = '" . $sliderID . "'");

        if ($status !== '*') {
            $wheres[] = "sliders.slider_status LIKE '" . $status . "'";
        }

        $result = Database::queryAll("
            SELECT xref.group_id, sliders.title
            FROM " . $this->getTableName() . " AS xref
            LEFT JOIN " . $slidersModel->getTableName() . " AS sliders ON sliders.id = xref.group_id
            WHERE " . implode(' AND ', $wheres) . "
            ORDER BY xref.group_id ASC");

        if (!empty($result)) {
            return $result;
        }

        return array(
            array(
                "group_id" => 0,
                "title"    => n2_('Dashboard')
            )
        );
    }

    protected function getMaximalOrderValue($groupID) {

        $query  = "SELECT MAX(ordering) AS ordering FROM " . $this->getTableName() . " WHERE group_id = '" . intval($groupID) . "'";
        $result = Database::queryRow($query);

        if (isset($result['ordering'])) return $result['ordering'] + 1;

        return 0;
    }

    /**
     * @param $sliderID
     *
     * @return bool
     */
    public function isSliderAvailableInAnyGroups($sliderID) {
        $allRelatedGroups = $this->getGroups($sliderID);

        $slidersModel = new ModelSliders($this);

        foreach ($allRelatedGroups as $group) {
            if ($group['group_id'] != 0) {
                /*
                 * It is a group
                 */
                $sliderRow = $slidersModel->get($group['group_id']);
                if (isset($sliderRow['slider_status']) && $sliderRow['slider_status'] === 'published') {
                    return true;
                }
            } else {
                /*
                 * It is a slider
                 */
                $sliderRow = $slidersModel->get($sliderID);
                if (isset($sliderRow['slider_status']) && $sliderRow['slider_status'] === 'published') {
                    return true;
                }
            }
        }

        return false;
    }
}