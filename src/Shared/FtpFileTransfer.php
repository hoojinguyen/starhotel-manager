<?php

namespace Romi\Shared;

use Romi\Shared\FileTransferInterface;

class FtpFileTransfer implements FileTransferInterface
{

	private $host;
	private $port;
	private $user;
	private $pass;
	private $pasv;
	private $conn;

	function __construct($host, $port, $user, $pass, $pasv = true)
	{
		$this->host = $host;
		$this->port = $port;
		$this->user = $user;
		$this->pass = $pass;
		$this->pasv = $pasv;
	}

	public function open()
	{
		$this->connect();

		if ($this->login()) {
 			// open connection + login success
 			// do something()
			if (@ftp_pasv($this->conn, $this->pasv)) {
				return true;
			}
		}

		return false;
	}

	public function close()
	{
		@ftp_close($this->conn);
	}

	public function connect()
	{
		$this->conn = @ftp_connect($this->host, $this->port);
	}

	public function delete($path)
	{
		$result = false;

		if ($this->open()) {
			if (@ftp_delete($this->conn, $path)) {
				// delete success
				$result = true;
			}
		}

		$this->close();

		return $result;
	}

	public function get($localFile, $remoteFile, $mode = FTP_ASCII)
	{
		$result = false;

		if ($this->open()) {
			if (@ftp_get($this->conn, $localFile, $remoteFile, $mode)) {
				// get success
				$result = true;
			}
		}

		$this->close();

		return $result;
	}

	public function login()
	{
		if (@ftp_login($this->conn, $this->user, $this->pass)) {
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
			if (@ftp_mkdir($this->conn, $directory)) {
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
				if (!@ftp_chdir($this->conn, $part)) {
					@ftp_mkdir($this->conn, $part);
					@ftp_chdir($this->conn, $part);
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
			if (@ftp_chdir($this->conn, $directory)) {
				// upload success
				$result = true;
			}
		}

		$this->close();

		return $result;
	}

	public function put($remoteFile, $localFile, $mode = FTP_ASCII)
	{
		$result = false;

		if ($this->open()) {
			if (@ftp_put($this->conn, $remoteFile, $localFile, $mode)) {
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
			if (@ftp_rename($this->conn, $old, $new)) {
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
			$list = @ftp_nlist($this->conn, $path);
			$result = array();
			for ($i = 0; $i < count($list); $i++) {
				$file_name = @basename($list[$i]);
				if (($file_name != '.') && ($file_name != '..')) {
					$result[] = $file_name;
				}
			}
		}

		$this->close();

		return $result;
	}
}
