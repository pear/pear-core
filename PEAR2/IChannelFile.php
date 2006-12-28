<?php
interface PEAR2_IChannelFile
{
    public function getName();
    public function getPort($mirror = false);
    public function getSSL($mirror = false);
    public function getSummary();
}