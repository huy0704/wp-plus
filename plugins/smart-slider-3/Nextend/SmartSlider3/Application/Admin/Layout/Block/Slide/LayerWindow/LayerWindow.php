<?php

namespace Nextend\SmartSlider3\Application\Admin\Layout\Block\Slide\LayerWindow;

/**
 * @var $this BlockLayerWindow
 */
?>

<div id="n2-ss-layer-window" class="n2_ss_layer_window  n2_form--dark">
    <div class="n2_ss_layer_window__crop">
        <div class="n2_ss_layer_window__title">

            <div class="n2_ss_layer_window__title_nav n2_ss_layer_window__title_nav_left">
            </div>

            <div class="n2_ss_layer_window__title_inner"></div>

            <div class="n2_ss_layer_window__title_nav n2_ss_layer_window__title_nav_right">
            </div>
        </div>

        <div class="n2_ss_layer_window__tab_buttons">
            <?php
            foreach ($this->getTabs() as $tab):
                ?>
                <div class="n2_ss_layer_window__tab_button" data-related-tab="<?php echo esc_attr($tab->getName()); ?>">
                    <div class="n2_ss_layer_window__tab_button_icon">
                        <i class="<?php echo esc_attr($tab->getIcon()); ?>"></i>
                    </div>
                    <div class="n2_ss_layer_window__tab_button_label">
                        <?php
                        echo esc_html($tab->getLabel());
                        ?>
                    </div>
                </div>
            <?php
            endforeach;
            ?>
        </div>

        <div class="n2_ss_layer_window__tab_container n2_container_scrollable">
            <?php
            foreach ($this->getTabs() as $tab):
                ?>
                <div class="n2_ss_layer_window__tab" data-tab="<?php echo esc_attr($tab->getName()); ?>">
                    <?php
                    $tab->display();
                    ?>
                </div>
            <?php
            endforeach;
            ?>
        </div>

        <?php
        //$this->renderForm();
        ?>
    </div>
</div>