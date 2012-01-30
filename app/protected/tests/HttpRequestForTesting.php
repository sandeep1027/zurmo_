<?php
    /*********************************************************************************
     * Zurmo is a customer relationship management program developed by
     * Zurmo, Inc. Copyright (C) 2011 Zurmo Inc.
     *
     * Zurmo is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License version 3 as published by the
     * Free Software Foundation with the addition of the following permission added
     * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
     * IN WHICH THE COPYRIGHT IS OWNED BY ZURMO, ZURMO DISCLAIMS THE WARRANTY
     * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
     *
     * Zurmo is distributed in the hope that it will be useful, but WITHOUT
     * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
     * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
     * details.
     *
     * You should have received a copy of the GNU General Public License along with
     * this program; if not, see http://www.gnu.org/licenses or write to the Free
     * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
     * 02110-1301 USA.
     *
     * You can contact Zurmo, Inc. with a mailing address at 113 McHenry Road Suite 207,
     * Buffalo Grove, IL 60089, USA. or at email address contact@zurmo.com.
     ********************************************************************************/

    /**
     * Override class is used specifically by the
     * testing framework to provide an exception
     * during a redirect call.
     */
    class HttpRequestForTesting extends CHttpRequest
    {
        public function redirect($url, $terminate = true, $statusCode = 302)
        {
            throw new RedirectException($url);
        }

        /**
         * Override returns a fake Uri. Request URI is not relevant when doing
         * command line unit tests.
         */
        public function getRequestUri()
        {
            return '/app/test/index.php?r=somewhereForTheTest'; // Not Coding Standard
        }

        /**
         * Override for testing since you cannot set headers during testing.
         * @see CHttpRequest::sendFile()
         */
        public function sendFile($fileName, $content, $mimeType = null, $terminate = true)
        {
            echo 'Testing download.';
            Yii::app()->end(0, false);
        }
    }
?>