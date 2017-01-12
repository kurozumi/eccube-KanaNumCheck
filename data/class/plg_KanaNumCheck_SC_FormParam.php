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
        $arrErr = parent::checkError($br);

        foreach ($this->keyname as $index => $key) {
            foreach ($this->arrCheck[$index] as $func) {
                $value = $this->getValue($key);
                switch ($func) {
                    case 'EXIST_CHECK':
                    case 'NUM_CHECK':
                    case 'EMAIL_CHECK':
                    case 'EMAIL_CHAR_CHECK':
                    case 'ALNUM_CHECK':
                    case 'GRAPH_CHECK':
                    case 'KANA_CHECK':
                    case 'URL_CHECK':
                    case 'IP_CHECK':
                    case 'SPTAB_CHECK':
                    case 'ZERO_CHECK':
                    case 'ALPHA_CHECK':
                    case 'ZERO_START':
                    case 'FIND_FILE':
                    case 'NO_SPTAB':
                    case 'DIR_CHECK':
                    case 'DOMAIN_CHECK':
                    case 'FILE_NAME_CHECK':
                    case 'MOBILE_EMAIL_CHECK':
                    case 'MAX_LENGTH_CHECK':
                    case 'MIN_LENGTH_CHECK':
                    case 'NUM_COUNT_CHECK':
                    case 'KANABLANK_CHECK':
                    case 'SELECT_CHECK':
                    case 'FILE_NAME_CHECK_BY_NOUPLOAD':
                    case 'NUM_POINT_CHECK':
                    case 'KANANUM_CHECK':
                        $this->recursionCheck($this->disp_name[$index], $func,
                            $value, $arrErr[$key], $this->length[$index]);
                        if (SC_Utils_Ex::isBlank($arrErr[$key])) {
                            unset($arrErr[$key]);
                        }
                        break;
                    // 小文字に変換
                    case 'CHANGE_LOWER':
                        $this->toLower($key);
                        break;
                    // ファイルの存在チェック
                    case 'FILE_EXISTS':
                        if ($value != '' && !file_exists($this->check_dir . $value)) {
                            $arrErr[$key] = '※ ' . $this->disp_name[$index] . 'のファイルが存在しません。<br>';
                        }
                        break;
                    // ダウンロード用ファイルの存在チェック
                    case 'DOWN_FILE_EXISTS':
                        if ($value != '' && !file_exists(DOWN_SAVE_REALDIR . $value)) {
                            $arrErr[$key] = '※ ' . $this->disp_name[$index] . 'のファイルが存在しません。<br>';
                        }
                        break;
                    default:
                        // これがなければエラーメッセージを拡張できる。
                        //$arrErr[$key] = "※※　エラーチェック形式($func)には対応していません　※※ <br>";
                        break;
                }
            }

            if (isset($arrErr[$key]) && !$br) {
                $arrErr[$key] = preg_replace("/<br(\s+\/)?>/i", '', $arrErr[$key]);
            }
        }

        return $arrErr;
    }
}
