<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Core\BlockBreadCrumb;

/**
 * @var $this BlockBreadCrumb
 */
?>

<div class="n2_breadcrumbs">
    <?php
    $breadcrumbs = $this->getBreadCrumbs();
    $length      = count($breadcrumbs);
    foreach ($breadcrumbs as $i => $breadcrumb):
        ?>
        <div class="n2_breadcrumbs__breadcrumb<?php echo $breadcrumb->isActive() ? ' n2_breadcrumbs__breadcrumb--active' : ''; ?>"><?php $breadcrumb->display(); ?></div>
        <?php
        if ($i < $length - 1):
            ?>
            <div class="n2_breadcrumbs__arrow"><i class="ssi_16 ssi_16--breadcrumb"></i></div>
        <?php
        endif;
        ?>
    <?php
    endforeach;
    ?>
</div>
