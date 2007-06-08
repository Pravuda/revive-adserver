<?php

class OA_UpgradePostscript
{
    var $oUpgrade;

    function OA_UpgradePostscript()
    {

    }

    function execute($aParams)
    {
        $this->oUpgrade = & $aParams[0];
        if (!$this->configMax())
        {
            return false;
        }
        return true;
    }

    function configMax()
    {
        if ($this->oUpgrade->oConfiguration->isMaxConfigFile())
        {
            if (!$this->oUpgrade->oConfiguration->replaceMaxConfigFileWithOpenadsConfigFile())
            {
                $this->oUpgrade->oLogger->logError('Failed to replace MAX configuration file with Openads configuration file');
                $this->oUpgrade->message = 'Failed to replace MAX configuration file with Openads configuration file';
                return false;
            }
            $this->oUpgrade->oLogger->log('Replaced MAX configuration file with Openads configuration file');
            $this->oUpgrade->oConfiguration->setMaxInstalledOff();
            $this->oUpgrade->oConfiguration->writeConfig();
        }
        if (!$this->oUpgrade->oVersioner->removeMaxVersion())
        {
            $this->oUpgrade->oLogger->logError('Failed to remove MAX application version');
            $this->oUpgrade->message = 'Failed to remove MAX application version';
            return false;
        }
        $this->oUpgrade->oLogger->log('Removed MAX application version');
        $this->oUpgrade->oConfiguration->setupConfigPriority('');
        if (!$this->oUpgrade->oConfiguration->writeConfig())
        {
            $this->oUpgrade->oLogger->logError('Failed to set the randmax priority value');
            $this->oUpgrade->message = 'Failed to set the randmax priority value';
            return false;
        }
        return true;
    }

}

?>