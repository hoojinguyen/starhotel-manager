<?php
namespace Romi\Shared;

interface FileTransferInterface
{
	function connect();
	function login();
	function open();
	function close();
	function mkdir($directory);
	function put($remoteFile, $localFile, $mode = null);
	function get($localFile, $remoteFile, $mode = null);
	function rename($old, $new);
	function delete($path);
	function list($path);
}