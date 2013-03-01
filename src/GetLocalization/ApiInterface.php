<?php
/*
 * This file is part of GetLocalization.
 *
 * (c) 2013 Nicolò Martini
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GetLocalization;

/**
 * Interface ApiInterface
 *
 * Interface for the GetLocalization File Management API
 *
 * http://www.getlocalization.com/library/api/get-localization-file-management-api/
 *
 * @package GetLocalization
 */
interface ApiInterface
{
    public function createMaster($format, $language, $body, $filename);

    public function updateMaster($body, $filename);

    public function listMaster();

    public function getZippedTranslations();

    public function getTranslation($masterfile, $lang);

    public function updateTranslation($masterfile, $lang, $body);
}