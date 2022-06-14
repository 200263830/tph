<?php

/**
 * @category    TPH
 * @package     Tph_Onlinedesign
 *
 */

namespace Tph\Onlinedesign\Plugin\Adminhtml;

/**
 * Class Canvaimage
 */
class Canvaimage
{

    public function afterGetColumns($items, $result)
    {
        if (is_array($result)) {
            $newResult['image'] = 'Canva Image';
            foreach ($result as $key => $value) {
                $newResult[$key] = $value;
            }
            $result = $newResult;
        }
        return $result;
    }
}