<?php
// /* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Stig Bakken <ssb@php.net>                                    |
// |                                                                      |
// +----------------------------------------------------------------------+
//
// $Id$

require_once 'PEAR/Command/Common.php';
require_once 'PEAR/Registry.php';
require_once 'PEAR/Config.php';

class PEAR_Command_Channels extends PEAR_Command_Common
{
    // {{{ properties

    var $commands = array(
        'list-channels' => array(
            'summary' => 'List Available Channels',
            'function' => 'doList',
            'shortcut' => 'lc',
            'options' => array(),
            'doc' => '
List all available channels for installation.
',
            ),
        'update-channels' => array(
            'summary' => 'Update the Channel List',
            'function' => 'doUpdateAll',
            'shortcut' => 'uc',
            'options' => array(),
            'doc' => '
List all installed packages in all channels.
'
            ),
        'channel-delete' => array(
            'summary' => 'Remove a Channel From the List',
            'function' => 'doDelete',
            'shortcut' => 'cd',
            'options' => array(),
            'doc' => '<channel name>
Delete a channel from the registry.  You may not
remove any channel that has installed packages.
'
            ),
        'channel-add' => array(
            'summary' => 'Add a Channel',
            'function' => 'doAdd',
            'shortcut' => 'ca',
            'options' => array(),
            'doc' => '<channel.xml>
Add a private channel to the channel list.  Note that all
public channels must be synced using update-channels.
'
            ),
        'channel-update' => array(
            'summary' => 'Update an Existing Channel',
            'function' => 'doUpdate',
            'shortcut' => 'ca',
            'options' => array(),
            'doc' => '<channel.xml>
Update a private channel in the channel list.  Note that all
public channels must be synced using update-channels.
'
            ),
        'channel-info' => array(
            'summary' => 'Retrieve Information on a Channel',
            'function' => 'doInfo',
            'shortcut' => 'ci',
            'options' => array(),
            'doc' => '<package>
List the files in an installed package.
'
            ),
        );

    // }}}
    // {{{ constructor

    /**
     * PEAR_Command_Registry constructor.
     *
     * @access public
     */
    function PEAR_Command_Channels(&$ui, &$config)
    {
        parent::PEAR_Command_Common($ui, $config);
    }

    // }}}

    // {{{ doList()
    
    function _sortChannels($a, $b)
    {
        return strnatcasecmp($a->getName(), $b->getName());
    }

    function doList($command, $options, $params)
    {
        $reg = new PEAR_Registry($this->config->get('php_dir'));
        $registered = $reg->getChannels();
        usort($registered, array(&$this, '_sortchannels'));
        $i = $j = 0;
        $data = array(
            'caption' => 'Registered Channels:',
            'border' => true,
            'headline' => array('Channel', 'Server', 'Summary')
            );
        foreach ($registered as $channel) {
            $data['data'][] = array($channel->getName(),
                                      $channel->getServer(),
                                      $channel->getSummary());
        }
        if (count($registered)==0) {
            $data = '(no registered channels)';
        }
        $this->ui->outputData($data, $command);
        return true;
    }
    
    function doUpdateAll($command, $options, $params)
    {
        $reg = new PEAR_Registry($this->config->get('php_dir'));
        $chan = $this->config->get('default_channel');
        $this->config->set('default_channel', 'pear');
        $remote = &new PEAR_Remote($this->config, $reg);
        $channels = $remote->call('channel.list', time());
        $this->config->set('default_channel', $chan);
        if (PEAR::isError($channels)) {
            return $channels;
        }
        if (!is_array($channels) || isset($channels['faultCode'])) {
            return $this->raiseError("Incorrect channel listing returned from pear.php.net");
        }
        if (!count($channels)) {
            $data = 'no updates available';
        }
        include_once 'PEAR/ChannelFile.php';
        foreach ($channels as $channel) {
            $channel = PEAR_ChannelFile::fromArray($channel);
            if (!$channel) {
                continue;
            }
            if ($reg->channelExists($channel->getName())) {
                $this->ui->outputData(array('data' => "Updating channel ". $channel->getName()), $command);
                $reg->updateChannel($channel);
            } else {
                $this->ui->outputData(array('data' => "Adding channel " . $channel->getName()), $command);
                $reg->addChannel($channel);
            }
        }
        return true;
    }
    
    function doInfo($command, $options, $params)
    {
        if (sizeof($params) != 1) {
            return $this->raiseError("No channel specified");
        }
        $reg = new PEAR_Registry($this->config->get('php_dir'));
        $channel = strtolower($params[0]);
        if (!$reg->channelExists($channel)) {
            return $this->raiseError("Channel `$channel' does not exist");
        }
        $info = $reg->channelInfo($channel);
        include_once 'PEAR/ChannelFile.php';
        $chan = PEAR_ChannelFile::fromArray($info);
        if ($chan) {
            $caption = 'Channel ' . $channel . ' Information:';
            $data = array(
                'caption' => $caption,
                'border' => true);
            $data['data'][] = array('Name', $chan->getName());
            $data['data'][] = array('Summary', $chan->getSummary());
            $data['data'][] = array('Server', $chan->getServer());
//            $data['data'][] = array('Package Name Regex', $chan->getPackageNameRegex());
            $this->ui->outputData($data, 'channel-info');
            
            $data['data'] = array();
            $data['caption'] = 'Server Capabilities';
            $data['headline'] = array('Type', 'Version', 'Protocol Name');
            $capabilities = $chan->getProtocols();
            if ($capabilities) {
                foreach ($capabilities as $protocol) {
                    $name = isset($protocol['name']) ? $protocol['name'] : '';
                    $data['data'][] = array($protocol['type'], $protocol['version'], $name);
                }
            } else {
                $data['data'][] = array('No supported protocols');
            }
            $this->ui->outputData($data);
            $data['data'] = array();
            $mirrors = $chan->getMirrors();
            if ($mirrors) {
                foreach ($mirrors as $type => $info) {
                    $data['caption'] = 'Channel ' . $channel . ' ' . $type . ' Mirrors:';
                    unset($data['headline']);
                    foreach ($info as $mirror) {
                        $data['data'][] = array($mirror['name']);
                        $this->ui->outputData($data);
                    }
                    foreach ($info as $mirror) {
                        if ($mirror['provides']) {
                            $data['data'] = array();
                            $data['caption'] = $mirror['name'] . ' Capabilities';
                            $data['headline'] = array('Type', 'Version', 'Protocol Name');
                            foreach ($mirror['provides'] as $protocol) {
                                $name = isset($protocol['name']) ? $protocol['name'] : '';
                                $data['data'][] = array($protocol['type'], $protocol['version'], $name);
                            }
                            $this->ui->outputData($data);
                        } else {
                            $data['data'][] = array('Mirror Capabilities', 'No supported protocols');
                            $this->ui->outputData($data);
                        }
                    }
                }
            }
        } else {
            return $this->raiseError("Serious error: Channel `$params[0]' has a corrupted registry entry");
        }
    }

    // }}}
    
    function doDelete($command, $options, $params)
    {
        if (sizeof($params) != 1) {
            return $this->raiseError('No Channel Specified');
        }
        if (strtolower(trim($params[0])) == 'pear') {
            return $this->raiseError('Cannot delete the PEAR channel');
        }
        $reg = new PEAR_Registry($this->config->get('php_dir'));
        if (!$reg->channelExists($params[0])) {
            return $this->raiseError('Channel `' . $params[0] . '\' does not exist');
        }
        if (PEAR::isError($err = $reg->listPackages($params[0]))) {
            return $err;
        }
        if (count($err)) {
            return $this->raiseError('Channel `' . $params[0] .'\' has installed packages, cannot delete');
        }
        if (!$reg->deleteChannel($params[0])) {
            return $this->raiseError('Channel deletion failed');
        } else {
            $this->ui->outputData('Channel `' . $params[0] . '\' deleted');
        }
    }

    function doAdd($command, $options, $params)
    {
        if (sizeof($params) != 1) {
            return $this->raiseError("No channel file specified");
        }
        $fp = @fopen($params[0], 'r');
        if (!$fp) {
            return $this->raiseError("Cannot open " . $params[0]);
        }
        $contents = '';
        while (!feof($fp)) {
            $contents .= fread($fp, 1024);
        }
        fclose($fp);
        include_once 'PEAR/ChannelFile.php';
        $channel = new PEAR_ChannelFile;
        $channel->fromXmlString($contents);
        $exit = false;
        if (count($errors = $channel->getErrors(true))) {
            foreach ($errors as $error) {
                $this->ui->outputData(ucfirst($error['level'] . ': ' . $error['message']));
                if (!$exit) {
                    $exit = $error['level'] == 'error' ? true : false;
                }
            }
            if ($exit) {
                return $this->raiseError('Invalid channel.xml file');
            }
        }
        $reg = new PEAR_Registry($this->config->get('php_dir'));
        if ($reg->channelExists($channel->getName())) {
            return $this->raiseError('Error: Channel `' . $channel->getName() . "' exists, use channel-update to update entry");
        }
        $ret = $reg->addChannel($channel);
        if (PEAR::isError($ret)) {
            return $ret;
        }
        if (!$ret) {
            return $this->raiseError("Adding Channel `" . $channel->getName() . "' to registry failed");
        }
        $this->ui->outputData('Adding Channel `' . $channel->getName() . '\' succeeded');
    }

    function doUpdate($command, $options, $params)
    {
        if (sizeof($params) != 1) {
            return $this->raiseError("No channel file specified");
        }
        $fp = @fopen($params[0], 'r');
        if (!$fp) {
            return $this->raiseError("Cannot open " . $params[0]);
        }
        $contents = '';
        while (!feof($fp)) {
            $contents .= fread($fp, 1024);
        }
        fclose($fp);
        include_once 'PEAR/ChannelFile.php';
        $channel = new PEAR_ChannelFile;
        $channel->fromXmlString($contents);
        $exit = false;
        if (count($errors = $channel->getErrors(true))) {
            foreach ($errors as $error) {
                $this->ui->outputData(ucfirst($error['level'] . ': ' . $error['message']));
                if (!$exit) {
                    $exit = $error['level'] == 'error' ? true : false;
                }
            }
            if ($exit) {
                return $this->raiseError('Invalid channel.xml file');
            }
        }
        $reg = new PEAR_Registry($this->config->get('php_dir'));
        if (!$reg->channelExists($channel->getName())) {
            return $this->raiseError('Error: Channel `' . $channel->getName() . "' does not exist, use channel-add to add an entry");
        }
        $ret = $reg->updateChannel($channel);
        if (PEAR::isError($ret)) {
            return $ret;
        }
        if (!$ret) {
            return $this->raiseError("Updating Channel `" . $channel->getName() . "' in registry failed");
        }
        $this->ui->outputData('Update of Channel `' . $channel->getName() . '\' succeeded');
    }
}
?>
