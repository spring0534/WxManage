<?php
class DB{
	private $host;     //主机
	private $port;     //端口，默认3306
	private $user;     //用户
	private $password; //密码
	private $dbname;   //数据库DB名
	private $mysqli;   //mysqli对象
	private $stmt;     //mysqli_stmt预处理对象
	private $isConnected = false;
	private static $_instance;  //单例模式
	
	/**
	 * 使用用二维数组
	 * 参数 array ( array('192.168.1.121:3306', 'root', 'pass', ''))
	 * @param array $servers
	 */
	private function __construct( $servers){
		$servers = $servers[0];
		$aHost = explode(':', $servers[0]);
		$this->host = $aHost[0];
		$this->port = isset( $aHost[1]) ? $aHost[1] : '3306';
		$this->user = $servers[1];
		$this->password = $servers[2];
		$this->dbname = $servers[3];
	}
	
	public static function getInstance($servers){
		if ( ! (self::$_instance instanceof  self )){
			self::$_instance = new self($servers);
		}
		return self::$_instance ;
	}
	
	/**
	 * 检查并连接数据库
	 * @return mysqli
	 */
	public function connect(){
		if ($this->isConnected) {
			return $this->mysqli;
		}
		class_exists( mysqli) or die('This Lib Requires The Mysqli Extention!');
		$this->mysqli = new mysqli($this->host, $this->user, $this->password, $this->dbname, $this->port);
		$error = mysqli_connect_error();
		if ($error) $this->errorlog( $error);
		$this->isConnected = true;
		$this->mysqli->query("SET SQL_MODE='',CHARACTER_SET_CONNECTION='utf8',CHARACTER_SET_RESULTS='utf8',CHARACTER_SET_CLIENT='binary',NAMES 'utf8'");
		return $this->mysqli;
	}
	/**
	 * 执行sql
	 * @param unknown_type $query
	 */
	public function query( $query){
		$this->connect();
		$result = $this->mysqli->query( $query);
		$error = mysqli_error( $this->mysqli);
		if ( $error) $this->errorlog( $error);
		return $result;
	}
	/**
	 * 返回查询记录数
	 * @param source $result
	 * @return number
	 */
	public function numRows($result=null){ 
		return is_object( $result) ? $result->num_rows : 0;
	}
	/**
	 * 根据SQL查询一条记录
	 * @param string $query 查询语句
	 * @param string $mode  可取三个值：MYSQL_ASSOC、MYSQL_NUM、MYSQL_BOTH
	 * @return array 一维数组
	 */
	public function getOne($query, $mode = MYSQL_BOTH){
		$result = $this->query($query);
		return is_object( $result) ? $result->fetch_array( $mode) : array();
	}
	/**
	 * 根据SQL查询多条记录
	 * @return array 二维数组
	 */
	public function getAll($query, $mode = MYSQL_BOTH){
		$result = $this->query($query);
		if ( ! is_object( $result)) return array();
		$dataList = array();
		while ($row = $result->fetch_array( $mode)) {
			$dataList[] = $row;
		}
		return $dataList;
	}
	/**
	 * 从结果集中获取一条记录
	 * @param source $result
	 * @param string $mode
	 * @return array 一维数组
	 */
	public function fetchArray($result=null,$mode=MYSQL_BOTH){
		if ( ! is_object($result)) return array();
		$row = $result->fetch_array( $mode);
		return is_array($row) ? $row : array();
	}
	/**
	 * 从结果集中获取一条记录，结果为关联数组
	 * @param source $result
	 * @return array 一维数组
	 */
	public function fetchAssoc($result=null){
		if ( ! is_object($result)) return array();
		$row = $result->fetch_assoc();
		return is_array($row) ? $row : array();
	}
	/**
	 * 获取最新插入的记录ID
	 */
	public function insertID(){
	    return $this->mysqli->insert_id;
	}
	/**
	 * sql执行的影响行数
	 */
	public function affectedRows(){
		return $this->mysqli->affected_rows;
	}
	//=============================事务处理章节============================
	public function Start(){
		$this->connect();
		$this->mysqli->autocommit( false);
	}
	public function Commit(){
		$this->mysqli->commit();
		$this->mysqli->autocommit( true);//恢复自动提交
	}
	public function CommitId(){
		$aId = $this->getOne('SELECT LAST_INSERT_ID()', MYSQL_NUM);
		return (int)$aId[0];
	}
	public function Rollback(){
		$this->mysqli->rollback();
	}
	//===========================mysqli 预处理章节============================
	/**
	 * 该方法准备要执行的预处理语句
	 * @return mysqli_stmt
	 */
	public function stmtPrepare( $query){
		if ( ! $query) {
			$this->errorlog('no query');
		}
		$this->connect();
		$this->stmt = $this->mysqli->prepare( $query);
		return $this->stmt;
	}
	//==============================增删改操作================================
	public function insert($tbl, $arrData){
		$sql = "INSERT IGNORE INTO $tbl SET ";
		foreach ((array)$arrData as $key => $value){
			$sql .= "`$key`='" . $this->escape_string($value) . "',";
		}
		$this->query(substr($sql,0,-1));
		return $this->insertID();
	}
	public function update($tbl, $arrData, $where){
		$sql = "update ignore $tbl SET ";
		foreach ((array)$arrData as $key => $value){
			$value = trim($value);
			$flag = strpos($value, '+') ? substr($value, 0, strpos($value, '+'))==$key : (strpos($value, '-') ? substr($value, 0, strpos($value, '-'))==$key : false);
			if($flag){
				$sql .= "$key=$value,";
			} else {
				$sql .= "`$key`='" . $this->escape_string($value) . "',";
			}
		}
		$sql = substr($sql,0,-1);
		$sql .= " where 1 ";
		if(is_array($where) && count($where)){	
			foreach ($where as $key => $value){
				$sql .= " and `$key`='" . $this->escape_string($value) . "'";
			}
			$this->query($sql);
			return $this->affectedRows();
		}
		@oo::oerror()->writeErrorLog('updateTable 第三个参数$where 错误.');
		die('error');
	}
	public function delete($tbl,$where){
		$sql = "delete from $tbl where 1";
		if(is_array($where) && count($where)){	
			foreach ($where as $key => $value){
				$value = $this->escape_string($value);
				if ($key == $value) {
					return false;
				}
				$sql .= " and `$key`='" . $value . "'";
			}	
			$this->query($sql);
			return $this->affectedRows();
		}
		die('error');
	}
	//=============================缓存查询数据============================
	/**
	 * 缓存一行数据
	 */
	 public function getCacheOne($sql, $mode=MYSQL_BOTH, $key=false, $update=0, $expire=86400){
		 $key = $key===false ? md5($sql) : $key;
		 $temp = ocache::cache()->get($key);
		 if( $temp === false || $update){
			 $temp = $this->getOne($sql, $mode);
			 ocache::cache()->set($key, $temp, $expire);
		 }
		 return $temp;
	 }
	 /**
	 * 缓存多行数据
	 */
	 public function getCacheAll($sql, $mode=MYSQL_BOTH, $key=false, $update=0, $expire=86400){
		 $key = $key===false ? md5($sql) : $key;
		 $temp = ocache::cache()->get($key);
		 if( $temp === false || $update){
			 $temp = $this->getAll($sql, $mode);
			 ocache::cache()->set($key, $temp, $expire);
		 }
	 	return $temp;
	 }
	 /**
	  * 关闭数据库
	  */
	 public function close(){
	 	if( $this->isConnected){
	 		$this->isConnected = false;
	 		$this->mysqli->close();
	 	}
	 }
	 //==============================数据过滤================================
	 /**
	  * 安全性检测.调用escape存入的,一定要调unescape取出
	  */
	 public function escape( $string){
	 	return mysql_escape_string( trim($string));
	 }
	 /**
	  * 转义一个字符串用于 query
	  */
	 public function escape_string( $unescaped_string){
	 	return (PHP_VERSION > '5.3.0' ) ? addslashes( trim( $unescaped_string)) : mysql_escape_string( trim( $unescaped_string));
	 }
	 
	 public function unescape( $string){
	 	return stripslashes( $string);
	 }
	//==============================mysql错误日志================================
	private function errorlog( $msg='' ){
		$error = date('Y-m-d H:i:s') . ":\n". mysqli_errno( $this->mysqli) . ":\nmsg:". $msg . ";\n";
		$file = dirname(__FILE__) . '/../data/mysql.txt';
		@file_put_contents($file, "{$error}\n", @filesize($file)<512*1024 ? FILE_APPEND : null);
		die('DB Invalid!!!');
	}

}
