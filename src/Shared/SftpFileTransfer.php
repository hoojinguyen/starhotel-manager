<?php

namespace Romi\Shared;

use Romi\Shared\FileTransferInterface;
use phpseclib\Net\SFTP;

require __DIR__ . '/../../vendor/autoload.php';

class SftpFileTransfer implements FileTransferInterface
{

	private $host;
	private $port;
	private $user;
	private $pass;
	private $conn;

	function __construct($host, $port, $user, $pass)
	{
		$this->host = $host;
		$this->port = $port;
		$this->user = $user;
		$this->pass = $pass;
	}

	public function open()
	{
		$this->connect();

		if ($this->login()) {
			// open connection + login success
			// do something()
			return true;
		}

		return false;
	}

	public function close()
	{
		@$this->conn->disconnect();
	}

	public function connect()
	{
		$this->conn = new SFTP($this->host, $this->port);
	}

	public function delete($path)
	{
		$result = false;

		if ($this->open()) {
			if (@$this->conn->delete($path)) {
				// delete success
				$result = true;
			}
		}

		$this->close();

		return $result;
	}

    // sftp not support transfer mode
	public function get($localFile, $remoteFile, $mode = null)
	{
		$result = false;

		if ($this->open()) {
			if (@$this->conn->get($remoteFile, $localFile)) {
				// get success
				$result = true;
			}
		}

		$this->close();

		return $result;
	}

	public function login()
	{
		if (@$this->conn->login($this->user, $this->pass)) {
			// success
			return true;
		} else {
			// fail
			return false;
		}
	}

	public function mkdir($directory)
	{
		$result = false;

		if ($this->open()) {
			if (@$this->conn->mkdir($directory)) {
				// mkdir success
				$result = true;
			}
		}

		$this->close();

		return $result;
	}

	function ftp_mksubdirs($directory)
	{
		$result = false;

		if ($this->open()) {
			$parts = explode('/', $directory);

			foreach ($parts as $part) {
				if (!@$this->conn->chdir($part)) {
					@$this->conn->mkdir($part);
					@$this->conn->chdir($part);
				}
			}

			$result = true;
		}

		$this->close();

		return $result;
	}

	public function chdir($directory)
	{
		$result = false;

		if ($this->open()) {
			if (@$this->conn->chdir($directory)) {
				// mkdir success
				$result = true;
			}
		}

		$this->close();

		return $result;
	}

	public function put($remoteFile, $localFile, $mode = NET_SFTP_LOCAL_FILE)
	{
		$result = false;

		if ($this->open()) {
			if (@$this->conn->put($remoteFile, $localFile, $mode)) {
				// upload success
				$result = true;
			}
		}

		$this->close();

		return $result;
	}

	public function rename($old, $new)
	{
		$result = false;

		if ($this->open()) {
			if (@$this->conn->rename($old, $new)) {
				// rename success
				$result = true;
			}
		}

		$this->close();

		return $result;
	}

	public function list($path)
	{
		$result = false;

		if ($this->open()) {
			$list = @$this->conn->nlist($path);
			$result = array();
			for ($i = 0; $i < count($list); $i++) {
				$file_name = basename($list[$i]);
				if (($file_name != '.') && ($file_name != '..')) {
					$result[] = $file_name;
				}
			}
		}

		$this->close();

		return $result;
	}

}
