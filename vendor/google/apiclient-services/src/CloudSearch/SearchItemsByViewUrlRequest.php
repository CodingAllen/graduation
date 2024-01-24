<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\CloudSearch;

class SearchItemsByViewUrlRequest extends \Google\Model
{
  /**
   * @var DebugOptions
   */
  public $debugOptions;
  protected $debugOptionsType = DebugOptions::class;
  protected $debugOptionsDataType = '';
  /**
   * @var string
   */
  public $pageToken;
  /**
   * @var string
   */
  public $viewUrl;

  /**
   * @param DebugOptions
   */
  public function setDebugOptions(DebugOptions $debugOptions)
  {
    $this->debugOptions = $debugOptions;
  }
  /**
   * @return DebugOptions
   */
  public function getDebugOptions()
  {
    return $this->debugOptions;
  }
  /**
   * @param string
   */
  public function setPageToken($pageToken)
  {
    $this->pageToken = $pageToken;
  }
  /**
   * @return string
   */
  public function getPageToken()
  {
    return $this->pageToken;
  }
  /**
   * @param string
   */
  public function setViewUrl($viewUrl)
  {
    $this->viewUrl = $viewUrl;
  }
  /**
   * @return string
   */
  public function getViewUrl()
  {
    return $this->viewUrl;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SearchItemsByViewUrlRequest::class, 'Google_Service_CloudSearch_SearchItemsByViewUrlRequest');
