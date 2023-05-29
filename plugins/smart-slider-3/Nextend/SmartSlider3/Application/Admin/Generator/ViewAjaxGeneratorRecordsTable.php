<?php


namespace Nextend\SmartSlider3\Application\Admin\Generator;


use Nextend\Framework\View\AbstractViewAjax;

class ViewAjaxGeneratorRecordsTable extends AbstractViewAjax {

    /** @var integer */
    protected $recordGroup = 1;

    /** @var array */
    protected $records;

    public function display() {
        $records = $this->getRecords();

        $headings = array();

        for ($currentGroupIndex = 1; $currentGroupIndex <= $this->getRecordGroup(); $currentGroupIndex++) {
            $headings[] = '#';
            foreach ($records[0][0] as $recordKey => $v) {
                $headings[] = '{' . $recordKey . '/' . $currentGroupIndex . '}';
            }
        }

        $rows = array();

        $i = 0;
        foreach ($records as $recordGroup) {
            foreach ($recordGroup as $record) {
                $rows[$i][] = $i + 1;
                foreach ($record as $recordValue) {
                    if ($recordValue === null) {
                        $rows[$i][] = '';
                    } else {
                        $rows[$i][] = htmlspecialchars($recordValue, ENT_QUOTES, "UTF-8");
                    }
                }
            }
            $i++;
        }

        return array(
            'headings' => $headings,
            'rows'     => $rows
        );
    }

    /**
     * @return int
     */
    public function getRecordGroup() {
        return $this->recordGroup;
    }

    /**
     * @param int $recordGroup
     */
    public function setRecordGroup($recordGroup) {
        $this->recordGroup = $recordGroup;
    }

    /**
     * @return array
     */
    public function getRecords() {
        return $this->records;
    }

    /**
     * @param array $records
     */
    public function setRecords($records) {
        $this->records = $records;
    }
}