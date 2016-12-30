<?php
defined( 'IN_WEB' ) or die( 'Include Error!' );

/**
 * @name	日志
 * @version	1.0.0
 */
class ModelLogs extends tables{
	/**
	 * 写记录
	 * @param String/Array $params 要记录的数据
	 * @param String $fname 文件名.该记录会保存到 data 目录下
	 * @param Int $fsize 文件大小M为单位.默认为1M
	 * @param Bool $isudplog 是否通过udp记录日志，如果要记录IP，此参数传字符'ip'
	 * @return null
	 */
	public function debug( $params, $fname = 'debug.txt', $fsize = 1, $isudplog = true ){
		is_scalar( $params ) or ($params = var_export( $params, true )); //是简单数据
		if( !$params ){
			return false;
		}
		if( $isudplog && PRODUCTION_SERVER ){
			$udp = array( $fname, max( 1, $fsize ) * 1024 * 1024, $params );
			if( $isudplog === 'ip' ) $udp[3] = 1;//要记IP
			if( $isudplog === 'bak' ) $udp[3] = 2;//要自动备份
			$content = implode( '+_+', $udp );
			$sSocket = @socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
			@socket_sendto( $sSocket, $content, strlen( $content ), 0, oo::$config['udploghost'] ? oo::$config['udploghost'] : '127.0.0.1', '55531' );
			return true;
		}
		clearstatcache();
		$file = PATH_DAT . $fname . '.php';
		$dir = dirname( $file );
		if( !is_dir( $dir ) ) mkdir( $dir, 0775, true );
		$size = file_exists( $file ) ? @filesize( $file ) : 0;
		$flag = $size < max( 1, $fsize ) * 1024 * 1024; //标志是否附加文件.文件控制在1M大小
		if( !$flag && $isudplog === 'bak' ){//文件超过大小自动备份
			$bak = $dir . '/bak/';
			if( !is_dir( $bak ) ) mkdir( $bak, 0775, true );
			$fname = explode( '/', $fname );
			$fname = $fname[count( $fname ) - 1];
			$bak .= $fname . '-' . date( 'YmdHis' ) . '.php';
			copy( $file, $bak );
		}
		$prefix = $size && $flag ? '' : "<?php (isset(\$_GET['p']) && (md5('&%$#'.\$_GET['p'].'**^')==='8b1b0c76f5190f98b1110e8fc4902bfa')) or die();?>\n"; //有文件内容并且非附加写
		@file_put_contents( $file, $prefix . $params . "\n", $flag ? FILE_APPEND : null  );
	}
	
}