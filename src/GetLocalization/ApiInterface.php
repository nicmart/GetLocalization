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
    /**
     * @param string $format    The format of the master file
     * @param string $language  The language og the master file
     * @param string $filePath  The path of the local file to upload as master file
     *
     * @return mixed
     */
    public function createMaster($format, $language, $filePath);

    /**
     * @param string $filePath  The path of the local file to upload as master file
     * @return mixed
     */
    public function updateMaster($filePath);

    /**
     * List master files
     *
     * @return mixed
     */
    public function listMaster();

    /**
     * Download all translations in zip format
     *
     * @return mixed
     */
    public function getZippedTranslations();

    /**
     * @param string $masterfile    The name of the masterfile
     * @param string $lang          The lang of the translation
     * @return string mixed         The content of the translation
     */
    public function getTranslation($masterfile, $lang);

    /**
     * @param string $masterfile    The name of the masterfile
     * @param string $lang          The lang of the translation being uploaded
     * @param string $filePath      The path of the local translation file
     * @return mixed
     */
    public function updateTranslation($masterfile, $lang, $filePath);
}