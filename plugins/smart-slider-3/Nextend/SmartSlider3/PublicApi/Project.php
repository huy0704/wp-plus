<?php


namespace Nextend\SmartSlider3\PublicApi;


use Nextend\SmartSlider3\Application\ApplicationSmartSlider3;
use Nextend\SmartSlider3\Application\Model\ModelSliders;
use Nextend\SmartSlider3\BackupSlider\ImportSlider;


/**
 * Class Project
 *
 * This class contains the publicly available static methods to interact with projects.
 *
 * @package Nextend\SmartSlider3
 */
class Project {

    /**
     * @param int $projectID
     *
     * @example \Nextend\SmartSlider3\PublicApi\Project::clearCache(6);
     *
     */
    public static function clearCache($projectID) {

        $application = ApplicationSmartSlider3::getInstance();

        $applicationType = $application->getApplicationTypeFrontend();

        $sliderModal = new ModelSliders($applicationType);

        $sliderModal->refreshCache($projectID);
    }

    /**
     * @param string  $pathToFile
     * @param integer $groupID
     *
     * @return bool|int projectID on success. false on failure.
     *
     * @example \Nextend\SmartSlider3\PublicApi\Project::import('/path/to/project.ss3');
     */
    public static function import($pathToFile, $groupID = 0) {

        if (!is_admin()) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        $application = ApplicationSmartSlider3::getInstance();

        $applicationType = $application->getApplicationTypeAdmin();

        $import = new ImportSlider($applicationType);

        $projectID = $import->import($pathToFile, $groupID);

        if ($projectID !== false) {
            return $projectID;
        }

        return false;
    }
}