<?php


namespace Nextend\SmartSlider3\Renderable\Placement;


use Nextend\SmartSlider3\Renderable\Component\AbstractComponent;

class PlacementNormal extends AbstractPlacement {

    public function attributes(&$attributes) {
        $data = $this->component->data;

        $attributes['data-pm'] = 'normal';


        $devices = $this->component->getOwner()
                                   ->getAvailableDevices();

        $desktopPortraitSelfAlign = $data->get('desktopportraitselfalign', 'inherit');
        $desktopPortraitMaxWidth  = intval($data->get('desktopportraitmaxwidth', 0));
        $desktopPortraitHeight    = $data->get('desktopportraitheight', 0);
        $desktopPortraitMargin    = $data->get('desktopportraitmargin');
        if (!empty($desktopPortraitMargin)) {
            $desktopPortraitMargin = $this->component->spacingToPxValue($desktopPortraitMargin);
        } else {
            $desktopPortraitMargin = array(
                0,
                0,
                0,
                0
            );
        }

        foreach ($devices as $device) {
            $margin = $data->get($device . 'margin');
            if (!empty($margin)) {
                $marginValues = $this->component->spacingToPxValue($margin);

                $cssText = array();
                if (($marginValues[0] == 0 && $desktopPortraitMargin[0] != 0) || $marginValues[0] != 0) {
                    $cssText[] = '--margin-top:' . $marginValues[0] . 'px';
                }
                if (($marginValues[1] == 0 && $desktopPortraitMargin[1] != 0) || $marginValues[1] != 0) {
                    $cssText[] = '--margin-right:' . $marginValues[1] . 'px';
                }
                if (($marginValues[2] == 0 && $desktopPortraitMargin[2] != 0) || $marginValues[2] != 0) {
                    $cssText[] = '--margin-bottom:' . $marginValues[2] . 'px';
                }
                if (($marginValues[3] == 0 && $desktopPortraitMargin[3] != 0) || $marginValues[3] != 0) {
                    $cssText[] = '--margin-left:' . $marginValues[3] . 'px';
                }

                $this->component->style->add($device, '', implode(';', $cssText));
            }

            $height = $data->get($device . 'height');
            if ($height === 0 || !empty($height)) {
                if ($height == 0) {
                    if ($desktopPortraitHeight > 0) {
                        $this->component->style->add($device, '', 'height:auto');
                    }
                } else {
                    $this->component->style->add($device, '', 'height:' . $height . 'px');
                }
            }

            $maxWidth = intval($data->get($device . 'maxwidth', -1));
            if ($maxWidth > 0) {
                $this->component->style->add($device, '', 'max-width:' . $maxWidth . 'px');
            } else if ($maxWidth === 0 && $device != 'desktopportrait' && $maxWidth != $desktopPortraitMaxWidth) {
                $this->component->style->add($device, '', 'max-width:none');
            }


            $selfAlign = $data->get($device . 'selfalign', '');

            if ($device == 'desktopportrait') {
                if ($desktopPortraitSelfAlign != 'inherit') {
                    $this->component->style->add($device, '', AbstractComponent::selfAlignToStyle($selfAlign));
                }
            } else if ($desktopPortraitSelfAlign != $selfAlign) {
                $this->component->style->add($device, '', AbstractComponent::selfAlignToStyle($selfAlign));
            }
        }

    }

    public function adminAttributes(&$attributes) {

        $this->component->createDeviceProperty('maxwidth', 0);
        $this->component->createDeviceProperty('margin', '0|*|0|*|0|*|0');
        $this->component->createDeviceProperty('height', 0);
        $this->component->createDeviceProperty('selfalign', 'inherit');
    }
}