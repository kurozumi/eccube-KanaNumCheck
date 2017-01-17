<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2014 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once CLASS_REALDIR . 'SC_FormParam.php';

class plg_KanaNumCheck_SC_FormParam extends SC_FormParam
{
    /**
     * エラーチェック
     * 
     * EC-CUBE本体のcheckError関数ではswith文のdefaultでメッセージが登録されなければ、
     * もっとシンプルにメッセージの拡張できるのですが。
     * 
     * @param type $br
     * @return type
     */
    public function checkError($br = true)
    {
        $arrErr = array();
        foreach ($this->keyname as $index => $key) {
            foreach ($this->arrCheck[$index] as $k => $func) {
                $value = $this->getValue($key);
                switch ($func) {
                    case 'KANANUM_CHECK':
                        $this->recursionCheck($this->disp_name[$index], $func,
                            $value, $arrErr[$key], $this->length[$index]);
                        if (SC_Utils_Ex::isBlank($arrErr[$key])) {
                            unset($arrErr[$key]);
                        }
                        unset($this->arrCheck[$index][$k]);
                        break;
                }
            }
            if (isset($arrErr[$key]) && !$br) {
                $arrErr[$key] = preg_replace("/<br(\s+\/)?>/i", '', $arrErr[$key]);
            }
        }
        
        $arrErr = array_merge($arrErr, parent::checkError($br));
        
        return $arrErr;
    }
}
