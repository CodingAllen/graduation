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

namespace Google\Service\Contentwarehouse;

class AssistantApiSoftwareCapabilities extends \Google\Collection
{
  protected $collection_key = 'supportedClientOp';
  /**
   * @var AssistantApiAppCapabilities[]
   */
  public $appCapabilities;
  protected $appCapabilitiesType = AssistantApiAppCapabilities::class;
  protected $appCapabilitiesDataType = 'array';
  /**
   * @var AssistantApiAppCapabilitiesDelta[]
   */
  public $appCapabilitiesDelta;
  protected $appCapabilitiesDeltaType = AssistantApiAppCapabilitiesDelta::class;
  protected $appCapabilitiesDeltaDataType = 'array';
  /**
   * @var AssistantApiAppIntegrationsSettings[]
   */
  public $appIntegrationsSettings;
  protected $appIntegrationsSettingsType = AssistantApiAppIntegrationsSettings::class;
  protected $appIntegrationsSettingsDataType = 'map';
  /**
   * @var AssistantApiCarAssistantCapabilities
   */
  public $carAssistantCapabilities;
  protected $carAssistantCapabilitiesType = AssistantApiCarAssistantCapabilities::class;
  protected $carAssistantCapabilitiesDataType = '';
  /**
   * @var AssistantApiClockCapabilities
   */
  public $clockCapabilities;
  protected $clockCapabilitiesType = AssistantApiClockCapabilities::class;
  protected $clockCapabilitiesDataType = '';
  /**
   * @var AssistantApiSupportedConversationVersion
   */
  public $conversationVersion;
  protected $conversationVersionType = AssistantApiSupportedConversationVersion::class;
  protected $conversationVersionDataType = '';
  /**
   * @var AssistantApiCrossDeviceExecutionCapability
   */
  public $crossDeviceExecutionCapabilities;
  protected $crossDeviceExecutionCapabilitiesType = AssistantApiCrossDeviceExecutionCapability::class;
  protected $crossDeviceExecutionCapabilitiesDataType = '';
  /**
   * @var AssistantApiGacsCapabilities
   */
  public $gacsCapabilities;
  protected $gacsCapabilitiesType = AssistantApiGacsCapabilities::class;
  protected $gacsCapabilitiesDataType = '';
  /**
   * @var AssistantApiGcmCapabilities
   */
  public $gcmCapabilities;
  protected $gcmCapabilitiesType = AssistantApiGcmCapabilities::class;
  protected $gcmCapabilitiesDataType = '';
  /**
   * @var AssistantApiLiveTvChannelCapabilities
   */
  public $liveTvChannelCapabilities;
  protected $liveTvChannelCapabilitiesType = AssistantApiLiveTvChannelCapabilities::class;
  protected $liveTvChannelCapabilitiesDataType = '';
  /**
   * @var AssistantApiOemCapabilities
   */
  public $oemCapabilities;
  protected $oemCapabilitiesType = AssistantApiOemCapabilities::class;
  protected $oemCapabilitiesDataType = '';
  /**
   * @var AssistantApiOnDeviceAssistantCapabilities
   */
  public $onDeviceAssistantCapabilities;
  protected $onDeviceAssistantCapabilitiesType = AssistantApiOnDeviceAssistantCapabilities::class;
  protected $onDeviceAssistantCapabilitiesDataType = '';
  /**
   * @var AssistantApiOnDeviceSmartHomeCapabilities
   */
  public $onDeviceSmartHomeCapabilities;
  protected $onDeviceSmartHomeCapabilitiesType = AssistantApiOnDeviceSmartHomeCapabilities::class;
  protected $onDeviceSmartHomeCapabilitiesDataType = '';
  /**
   * @var AssistantApiOnDeviceStorageCapabilities
   */
  public $onDeviceStorageCapabilities;
  protected $onDeviceStorageCapabilitiesType = AssistantApiOnDeviceStorageCapabilities::class;
  protected $onDeviceStorageCapabilitiesDataType = '';
  /**
   * @var string
   */
  public $operatingSystem;
  /**
   * @var AssistantApiLiveTvProvider[]
   */
  public $orderedLiveTvProviders;
  protected $orderedLiveTvProvidersType = AssistantApiLiveTvProvider::class;
  protected $orderedLiveTvProvidersDataType = 'array';
  /**
   * @var AssistantApiSelinaCapabilites
   */
  public $selinaCapabilities;
  protected $selinaCapabilitiesType = AssistantApiSelinaCapabilites::class;
  protected $selinaCapabilitiesDataType = '';
  /**
   * @var AssistantApiSettingsAppCapabilities
   */
  public $settingsAppCapabilities;
  protected $settingsAppCapabilitiesType = AssistantApiSettingsAppCapabilities::class;
  protected $settingsAppCapabilitiesDataType = '';
  /**
   * @var AssistantApiSupportedClientOp[]
   */
  public $supportedClientOp;
  protected $supportedClientOpType = AssistantApiSupportedClientOp::class;
  protected $supportedClientOpDataType = 'array';
  /**
   * @var AssistantApiSupportedFeatures
   */
  public $supportedFeatures;
  protected $supportedFeaturesType = AssistantApiSupportedFeatures::class;
  protected $supportedFeaturesDataType = '';
  /**
   * @var AssistantApiSupportedProtocolVersion
   */
  public $supportedMsgVersion;
  protected $supportedMsgVersionType = AssistantApiSupportedProtocolVersion::class;
  protected $supportedMsgVersionDataType = '';
  /**
   * @var AssistantApiSupportedProviderTypes
   */
  public $supportedProviderTypes;
  protected $supportedProviderTypesType = AssistantApiSupportedProviderTypes::class;
  protected $supportedProviderTypesDataType = '';
  /**
   * @var AssistantApiSurfaceProperties
   */
  public $surfaceProperties;
  protected $surfacePropertiesType = AssistantApiSurfaceProperties::class;
  protected $surfacePropertiesDataType = '';

  /**
   * @param AssistantApiAppCapabilities[]
   */
  public function setAppCapabilities($appCapabilities)
  {
    $this->appCapabilities = $appCapabilities;
  }
  /**
   * @return AssistantApiAppCapabilities[]
   */
  public function getAppCapabilities()
  {
    return $this->appCapabilities;
  }
  /**
   * @param AssistantApiAppCapabilitiesDelta[]
   */
  public function setAppCapabilitiesDelta($appCapabilitiesDelta)
  {
    $this->appCapabilitiesDelta = $appCapabilitiesDelta;
  }
  /**
   * @return AssistantApiAppCapabilitiesDelta[]
   */
  public function getAppCapabilitiesDelta()
  {
    return $this->appCapabilitiesDelta;
  }
  /**
   * @param AssistantApiAppIntegrationsSettings[]
   */
  public function setAppIntegrationsSettings($appIntegrationsSettings)
  {
    $this->appIntegrationsSettings = $appIntegrationsSettings;
  }
  /**
   * @return AssistantApiAppIntegrationsSettings[]
   */
  public function getAppIntegrationsSettings()
  {
    return $this->appIntegrationsSettings;
  }
  /**
   * @param AssistantApiCarAssistantCapabilities
   */
  public function setCarAssistantCapabilities(AssistantApiCarAssistantCapabilities $carAssistantCapabilities)
  {
    $this->carAssistantCapabilities = $carAssistantCapabilities;
  }
  /**
   * @return AssistantApiCarAssistantCapabilities
   */
  public function getCarAssistantCapabilities()
  {
    return $this->carAssistantCapabilities;
  }
  /**
   * @param AssistantApiClockCapabilities
   */
  public function setClockCapabilities(AssistantApiClockCapabilities $clockCapabilities)
  {
    $this->clockCapabilities = $clockCapabilities;
  }
  /**
   * @return AssistantApiClockCapabilities
   */
  public function getClockCapabilities()
  {
    return $this->clockCapabilities;
  }
  /**
   * @param AssistantApiSupportedConversationVersion
   */
  public function setConversationVersion(AssistantApiSupportedConversationVersion $conversationVersion)
  {
    $this->conversationVersion = $conversationVersion;
  }
  /**
   * @return AssistantApiSupportedConversationVersion
   */
  public function getConversationVersion()
  {
    return $this->conversationVersion;
  }
  /**
   * @param AssistantApiCrossDeviceExecutionCapability
   */
  public function setCrossDeviceExecutionCapabilities(AssistantApiCrossDeviceExecutionCapability $crossDeviceExecutionCapabilities)
  {
    $this->crossDeviceExecutionCapabilities = $crossDeviceExecutionCapabilities;
  }
  /**
   * @return AssistantApiCrossDeviceExecutionCapability
   */
  public function getCrossDeviceExecutionCapabilities()
  {
    return $this->crossDeviceExecutionCapabilities;
  }
  /**
   * @param AssistantApiGacsCapabilities
   */
  public function setGacsCapabilities(AssistantApiGacsCapabilities $gacsCapabilities)
  {
    $this->gacsCapabilities = $gacsCapabilities;
  }
  /**
   * @return AssistantApiGacsCapabilities
   */
  public function getGacsCapabilities()
  {
    return $this->gacsCapabilities;
  }
  /**
   * @param AssistantApiGcmCapabilities
   */
  public function setGcmCapabilities(AssistantApiGcmCapabilities $gcmCapabilities)
  {
    $this->gcmCapabilities = $gcmCapabilities;
  }
  /**
   * @return AssistantApiGcmCapabilities
   */
  public function getGcmCapabilities()
  {
    return $this->gcmCapabilities;
  }
  /**
   * @param AssistantApiLiveTvChannelCapabilities
   */
  public function setLiveTvChannelCapabilities(AssistantApiLiveTvChannelCapabilities $liveTvChannelCapabilities)
  {
    $this->liveTvChannelCapabilities = $liveTvChannelCapabilities;
  }
  /**
   * @return AssistantApiLiveTvChannelCapabilities
   */
  public function getLiveTvChannelCapabilities()
  {
    return $this->liveTvChannelCapabilities;
  }
  /**
   * @param AssistantApiOemCapabilities
   */
  public function setOemCapabilities(AssistantApiOemCapabilities $oemCapabilities)
  {
    $this->oemCapabilities = $oemCapabilities;
  }
  /**
   * @return AssistantApiOemCapabilities
   */
  public function getOemCapabilities()
  {
    return $this->oemCapabilities;
  }
  /**
   * @param AssistantApiOnDeviceAssistantCapabilities
   */
  public function setOnDeviceAssistantCapabilities(AssistantApiOnDeviceAssistantCapabilities $onDeviceAssistantCapabilities)
  {
    $this->onDeviceAssistantCapabilities = $onDeviceAssistantCapabilities;
  }
  /**
   * @return AssistantApiOnDeviceAssistantCapabilities
   */
  public function getOnDeviceAssistantCapabilities()
  {
    return $this->onDeviceAssistantCapabilities;
  }
  /**
   * @param AssistantApiOnDeviceSmartHomeCapabilities
   */
  public function setOnDeviceSmartHomeCapabilities(AssistantApiOnDeviceSmartHomeCapabilities $onDeviceSmartHomeCapabilities)
  {
    $this->onDeviceSmartHomeCapabilities = $onDeviceSmartHomeCapabilities;
  }
  /**
   * @return AssistantApiOnDeviceSmartHomeCapabilities
   */
  public function getOnDeviceSmartHomeCapabilities()
  {
    return $this->onDeviceSmartHomeCapabilities;
  }
  /**
   * @param AssistantApiOnDeviceStorageCapabilities
   */
  public function setOnDeviceStorageCapabilities(AssistantApiOnDeviceStorageCapabilities $onDeviceStorageCapabilities)
  {
    $this->onDeviceStorageCapabilities = $onDeviceStorageCapabilities;
  }
  /**
   * @return AssistantApiOnDeviceStorageCapabilities
   */
  public function getOnDeviceStorageCapabilities()
  {
    return $this->onDeviceStorageCapabilities;
  }
  /**
   * @param string
   */
  public function setOperatingSystem($operatingSystem)
  {
    $this->operatingSystem = $operatingSystem;
  }
  /**
   * @return string
   */
  public function getOperatingSystem()
  {
    return $this->operatingSystem;
  }
  /**
   * @param AssistantApiLiveTvProvider[]
   */
  public function setOrderedLiveTvProviders($orderedLiveTvProviders)
  {
    $this->orderedLiveTvProviders = $orderedLiveTvProviders;
  }
  /**
   * @return AssistantApiLiveTvProvider[]
   */
  public function getOrderedLiveTvProviders()
  {
    return $this->orderedLiveTvProviders;
  }
  /**
   * @param AssistantApiSelinaCapabilites
   */
  public function setSelinaCapabilities(AssistantApiSelinaCapabilites $selinaCapabilities)
  {
    $this->selinaCapabilities = $selinaCapabilities;
  }
  /**
   * @return AssistantApiSelinaCapabilites
   */
  public function getSelinaCapabilities()
  {
    return $this->selinaCapabilities;
  }
  /**
   * @param AssistantApiSettingsAppCapabilities
   */
  public function setSettingsAppCapabilities(AssistantApiSettingsAppCapabilities $settingsAppCapabilities)
  {
    $this->settingsAppCapabilities = $settingsAppCapabilities;
  }
  /**
   * @return AssistantApiSettingsAppCapabilities
   */
  public function getSettingsAppCapabilities()
  {
    return $this->settingsAppCapabilities;
  }
  /**
   * @param AssistantApiSupportedClientOp[]
   */
  public function setSupportedClientOp($supportedClientOp)
  {
    $this->supportedClientOp = $supportedClientOp;
  }
  /**
   * @return AssistantApiSupportedClientOp[]
   */
  public function getSupportedClientOp()
  {
    return $this->supportedClientOp;
  }
  /**
   * @param AssistantApiSupportedFeatures
   */
  public function setSupportedFeatures(AssistantApiSupportedFeatures $supportedFeatures)
  {
    $this->supportedFeatures = $supportedFeatures;
  }
  /**
   * @return AssistantApiSupportedFeatures
   */
  public function getSupportedFeatures()
  {
    return $this->supportedFeatures;
  }
  /**
   * @param AssistantApiSupportedProtocolVersion
   */
  public function setSupportedMsgVersion(AssistantApiSupportedProtocolVersion $supportedMsgVersion)
  {
    $this->supportedMsgVersion = $supportedMsgVersion;
  }
  /**
   * @return AssistantApiSupportedProtocolVersion
   */
  public function getSupportedMsgVersion()
  {
    return $this->supportedMsgVersion;
  }
  /**
   * @param AssistantApiSupportedProviderTypes
   */
  public function setSupportedProviderTypes(AssistantApiSupportedProviderTypes $supportedProviderTypes)
  {
    $this->supportedProviderTypes = $supportedProviderTypes;
  }
  /**
   * @return AssistantApiSupportedProviderTypes
   */
  public function getSupportedProviderTypes()
  {
    return $this->supportedProviderTypes;
  }
  /**
   * @param AssistantApiSurfaceProperties
   */
  public function setSurfaceProperties(AssistantApiSurfaceProperties $surfaceProperties)
  {
    $this->surfaceProperties = $surfaceProperties;
  }
  /**
   * @return AssistantApiSurfaceProperties
   */
  public function getSurfaceProperties()
  {
    return $this->surfaceProperties;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AssistantApiSoftwareCapabilities::class, 'Google_Service_Contentwarehouse_AssistantApiSoftwareCapabilities');
