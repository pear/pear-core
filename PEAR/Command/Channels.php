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
            'shortcut' => 'cde',
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
public channels should be synced using "update-channels".
Parameter may be either a local file or remote URL to a
channel.xml.
'
            ),
        'channel-update' => array(
            'summary' => 'Update an Existing Channel',
            'function' => 'doUpdate',
            'shortcut' => 'cu',
            'options' => array(
                'force' => array(
                    'shortopt' => 'f',
                    'doc' => 'will force download of new channel.xml if an existing channel name is used',
                    ),
),
            'doc' => '[<channel.xml>|<channel name>]
Update a channel in the channel list directly.  Note that all
public channels should be synced using "update-channels".
Parameter may be a local or remote channel.xml, or the name of
an existing channel.
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
        'channel-alias' => array(
            'summary' => 'Specify an alias to a channel name',
            'function' => 'doAlias',
            'shortcut' => 'cha',
            'options' => array(),
            'doc' => '<channel> <alias>
Specify a specific alias to use for a channel name.
The alias may not be an existing channel name or
alias.
'
            ),
        'channel-discover' => array(
            'summary' => 'Initialize a Channel from its server',
            'function' => 'doDiscover',
            'shortcut' => 'di',
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
        $reg = &$this->config->getRegistry();
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
        $reg = &$this->config->getRegistry();
        $chan = $this->config->get('default_channel');
        if ($chan != 'pear.php.net') {
            $this->ui->outputData('WARNING: default channel is not pear.php.net');
        }
        $remote = &$this->config->getRemote();
        $channels = $remote->call('channel.listAll');
        if (PEAR::isError($channels)) {
            return $channels;
        }
        if (!is_array($channels) || isset($channels['faultCode'])) {
            return $this->raiseError("Incorrect channel listing returned from channel '$chan'");
        }
        if (!count($channels)) {
            $data = 'no updates available';
        }
        include_once 'PEAR/ChannelFile.php';
        foreach ($channels as $info) {
            $save = $channel = $info[0];
            $server = $info[1];
            if ($reg->channelExists($channel, true)) {
                $this->ui->outputData("Updating channel '$channel'");
                $test = $reg->getChannel($channel, true);
                if (!$test) {
                    $this->ui->outputData("Channel '$channel' is corrupt in registry!");
                    $lastmodified = null;
                } else {
                    //$test->setServer($server);
                    //$reg->updateChannel($test);
                    $lastmodified = $test->lastModified();
                    
                }
                $this->config->set('default_channel', $channel);
                PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
                $info = $remote->call('channel.update', $lastmodified);
                PEAR::popErrorHandling();
                if (PEAR::isError($info)) {
                    $this->ui->outputData($info->getMessage());
                    continue;
                }
                if (!$info) {
                    $this->ui->outputData("Channel '$channel' is up-to-date");
                    continue;
                }
                $channelinfo = new PEAR_ChannelFile;
                $channelinfo->fromXmlString($info);
                if ($channelinfo->getErrors()) {
                    $this->ui->outputData("Downloaded channel data from channel '$channel' " . 
                        'is corrupt, skipping');
                    continue;
                }
                $channel = $channelinfo;
                if ($channel->getName() != $save) {
                    $this->ui->outputData('ERROR: Security risk - downloaded channel ' .
                        'definition file for channel "'
                        . $channel->getName() . ' from channel "' . $save .
                        '".  To use anyway, use channel-update');
                    continue;
                }
                $reg->updateChannel($channel);
            } else {
                $this->ui->outputData("Adding new channel '$channel'");
                if ($reg->isAlias($channel)) {
                    $temp = &$reg->getChannel($channel);
                    $temp->setAlias($temp->getName(), true); // set the alias to the channel name
                    if ($reg->channelExists($temp->getName())) {
                        $this->ui->outputData('ERROR: existing channel "' . $temp->getName() .
                            '" is aliased to "' . $channel . '" already and cannot be ' .
                            're-aliased to "' . $temp->getName() . '" because a channel with ' .
                            'that name or alias already exists!  Please rename manually and try ' .
                            'again.');
                        continue;
                    }
                }
                $test = new PEAR_ChannelFile;
                $test->setName($channel);
                $test->setServer($server);
                $test->setSummary($channel);
                $test->addProtocol('xml-rpc', '1.0', 'channel.update');
                $reg->addChannel($test);
                $this->config->set('default_channel', $channel);
                PEAR::pushErrorHandling(PEAR_ERROR_RETURN);
                $info = $remote->call('channel.update');
                PEAR::popErrorHandling();
                if (PEAR::isError($info)) {
                    $this->ui->outputData(array('data' => $info->getMessage()), $command);
                    continue;
                }
                $channelinfo = new PEAR_Channelfile;
                $channelinfo->fromXmlString($info);
                if ($channelinfo->getErrors()) {
                    $this->ui->outputData("Downloaded channel data from channel '$channel'" .
                        ' is corrupt, skipping');
                    continue;
                }
                $channel = $channelinfo;
                if ($channel->getName() != $save) {
                    $this->ui->outputData('ERROR: Security risk - downloaded channel ' .
                        'definition file for channel "'
                        . $channel->getName() . ' from channel "' . $save .
                        '".  To use anyway, use channel-update');
                    continue;
                }
                $reg->addChannel($channel);
            }
        }
        $this->config->set('default_channel', $chan);
        return true;
    }
    
    function doInfo($command, $options, $params)
    {
        if (sizeof($params) != 1) {
            return $this->raiseError("No channel specified");
        }
        $reg = &$this->config->getRegistry();
        $channel = strtolower($params[0]);
        if (!$reg->channelExists($channel)) {
            return $this->raiseError("Channel `$channel' does not exist");
        }
        $chan = $reg->getChannel($channel);
        if ($chan) {
            $caption = 'Channel ' . $channel . ' Information:';
            $data = array(
                'caption' => $caption,
                'border' => true);
            $data['data'][] = array('Name', $chan->getName());
            if ($chan->getAlias() != $chan->getName()) {
                $data['data'][] = array('Alias', $chan->getAlias());
            }
            $data['data'][] = array('Summary', $chan->getSummary());
            $data['data'][] = array('Xmlrpc Server', $chan->getServer('xmlrpc'));
            $data['data'][] = array('SOAP Server', ($a = $chan->getServer('soap')) ? $a : '(none)');
            $validate = $chan->getValidationPackage();
            $data['data'][] = array('Validation Package Name', $validate['name']);
            $data['data'][] = array('Validation Package Version', $validate['version']);
            $this->ui->outputData($data, 'channel-info');
            
            $data['data'] = array();
            $data['caption'] = 'Server Capabilities';
            $data['headline'] = array('Type', 'Version', 'Function Name');
            $capabilities = $chan->getFunctions('xmlrpc');
            $soaps = $chan->getFunctions('soap');
            if ($capabilities || $soaps) {
                if ($capabilities) {
                    foreach ($capabilities as $protocol) {
                        $data['data'][] = array('xmlrpc', $protocol['attribs']['version'],
                            $protocol['_content']);
                    }
                }
                if ($soaps) {
                    foreach ($soaps as $protocol) {
                        $data['data'][] = array('soap', $protocol['attribs']['version'],
                            $protocol['_content']);
                    }
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
                        if (isset($mirror['protocols']['xmlrpc'])) {
                            $data['data'] = array();
                            $data['caption'] = $mirror['name'] . ' Xml-rpc Functions';
                            $data['headline'] = array('Version', 'Name');
                            foreach ($mirror['protocols']['xmlrpc']['functions'] as $protocol) {
                                $data['data'][] = array($protocol['version'], $protocol['name']);
                            }
                            $this->ui->outputData($data);
                        }
                        if (isset($mirror['protocols']['soap'])) {
                            $data['data'] = array();
                            $data['caption'] = $mirror['name'] . ' SOAP Functions';
                            $data['headline'] = array('Version', 'Name');
                            foreach ($mirror['protocols']['soap']['functions'] as $protocol) {
                                $data['data'][] = array($protocol['version'], $protocol['name']);
                            }
                            $this->ui->outputData($data);
                        }
                        if (!isset($mirror['protocols']['xmlrpc']) && !isset($mirror['protocols']['soap'])) {
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
        $reg = &$this->config->getRegistry();
        if (($channel = $reg->channelName($params[0])) == 'pear.php.net') {
            return $this->raiseError('Cannot delete the pear.php.net channel');
        }
        if (!$reg->channelExists($channel)) {
            return $this->raiseError('Channel `' . $channel . '\' does not exist');
        }
        if (PEAR::isError($err = $reg->listPackages($channel))) {
            return $err;
        }
        if (count($err)) {
            return $this->raiseError('Channel `' . $channel .
                '\' has installed packages, cannot delete');
        }
        if (!$reg->deleteChannel($channel)) {
            return $this->raiseError('Channel deletion failed');
        } else {
            $this->config->deleteChannel($channel);
            $this->ui->outputData('Channel `' . $channel . '\' deleted');
        }
    }

    function doAdd($command, $options, $params)
    {
        if (sizeof($params) != 1) {
            return $this->raiseError('channel-add: no channel file specified');
        }
        $fp = @fopen($params[0], 'r');
        if (!$fp) {
            return $this->raiseError('channel-add: cannot open "' . $params[0] . '"');
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
                return $this->raiseError('channel-add: invalid channel.xml file');
            }
        }
        $reg = &$this->config->getRegistry();
        if ($reg->channelExists($channel->getName())) {
            return $this->raiseError('channel-add: Channel "' . $channel->getName() .
                '" exists, use channel-update to update entry');
        }
        $ret = $reg->addChannel($channel);
        if (PEAR::isError($ret)) {
            return $ret;
        }
        if (!$ret) {
            return $this->raiseError('channel-add: adding Channel "' . $channel->getName() .
                '" to registry failed');
        }
        $this->config->setChannels($reg->listChannels());
        $this->config->writeConfigFile();
        $this->ui->outputData('Adding Channel "' . $channel->getName() . '" succeeded');
    }

    function doUpdate($command, $options, $params)
    {
        $reg = &$this->config->getRegistry();
        if (sizeof($params) != 1) {
            return $this->raiseError("No channel file specified");
        }
        if ((!file_exists($params[0]) || is_dir($params[0]))
              && $reg->channelExists(strtolower($params[0]))) {
            $c = $reg->getChannel(strtolower($params[0]));
            $this->ui->outputData('Retrieving channel.xml from remote server');
            $chan = $this->config->get('default_channel');
            $this->config->set('default_channel', strtolower($params[0]));
            $remote = &$this->config->getRemote();
            // if force is specified, use a timestamp of "1" to force retrieval
            $lastmodified = isset($options['force']) ? 1 : $c->lastModified();
            $contents = $remote->call('channel.update', $lastmodified);
            if (PEAR::isError($contents)) {
                return $contents;
            }
            if (!$contents) {
                $this->ui->outputData("Channel $params[0] channel.xml is up to date");
                return;
            }
            include_once 'PEAR/ChannelFile.php';
            $channel = new PEAR_ChannelFile;
            $channel->fromXmlString($contents);
            if (!$channel->getErrors()) {
                // security check: is the downloaded file for the channel we got it from?
                if ($channel->getName() != strtolower($params[0])) {
                    if (isset($options['force'])) {
                        return $this->raiseError('WARNING: downloaded channel definition file' .
                            ' for channel "' . $channel->getName() . '" from channel "' .
                            strtolower($params[0]) . '"');
                    } else {
                        return $this->raiseError('ERROR: downloaded channel definition file' .
                            ' for channel "' . $channel->getName() . '" from channel "' .
                            strtolower($params[0]) . '"');
                    }
                }
            }
        } else {
            if (strpos($params[0], '://')) {
                $downloader = &$this->getDownloader();
                require_once 'System.php';
                $tmpdir = System::mktemp(array('-d'));
                PEAR::staticPushErrorHandling(PEAR_ERROR_RETURN);
                $loc = $downloader->downloadHttp($params[0], $this->ui, $tmpdir);
                PEAR::staticPopErrorHandling();
                if (PEAR::isError($loc)) {
                    return $this->raiseError("Cannot open " . $params[0]);
                } else {
                    $contents = implode('', file($loc));
                }
            } else {
                $fp = @fopen($params[0], 'r');
                if (!$fp) {
                    return $this->raiseError("Cannot open " . $params[0]);
                }
                $contents = '';
                while (!feof($fp)) {
                    $contents .= fread($fp, 1024);
                }
                fclose($fp);
            }
            include_once 'PEAR/ChannelFile.php';
            $channel = new PEAR_ChannelFile;
            $channel->fromXmlString($contents);
        }
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
        if (!$reg->channelExists($channel->getName())) {
            return $this->raiseError('Error: Channel "' . $channel->getName() .
                '" does not exist, use channel-add to add an entry');
        }
        $ret = $reg->updateChannel($channel);
        if (PEAR::isError($ret)) {
            return $ret;
        }
        if (!$ret) {
            return $this->raiseError('Updating Channel "' . $channel->getName() .
                '" in registry failed');
        }
        $this->config->setChannels($reg->listChannels());
        $this->config->writeConfigFile();
        $this->ui->outputData('Update of Channel "' . $channel->getName() . '" succeeded');
    }

    function &getDownloader()
    {
        $a = new PEAR_Downloader($this->ui, array(), $this->config);
        return $a;
    }

    function doAlias($command, $options, $params)
    {
        $reg = &$this->config->getRegistry();
        if (sizeof($params) == 1) {
            return $this->raiseError("No channel alias specified");
        }
        if (sizeof($params) != 2) {
            return $this->raiseError(
                "Invalid format, correct is: channel-alias channel alias");
        }
        
    }

    function doDiscover($command, $options, $params)
    {
        $reg = &$this->config->getRegistry();
        if (sizeof($params) != 1) {
            return $this->raiseError("No channel server specified");
        }
        if ($reg->channelExists($params[0])) {
            if ($reg->isChannelAlias($params[0])) {
                return $this->raiseError("A Channel alias named \"$params[0]\" " .
                    'already exists, aliasing channel "' . $reg->channelName($params[0])
                    . '"');
            } else {
                return $this->raiseError("Channel \"$params[0]\" is already initialized");
            }
        }
        $this->pushErrorHandling(PEAR_ERROR_RETURN);
        $err = $this->doAdd($command, $options, array($params[0] . '/channel.xml'));
        $this->popErrorHandling();
        if (PEAR::isError($err)) {
            return $this->raiseError("Discovery of channel \"$params[0]\" failed");
        }
        $this->ui->outputData("Discovery of channel \"$params[0]\" succeeded");
    }
}
?>
