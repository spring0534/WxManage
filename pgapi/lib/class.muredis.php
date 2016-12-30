<?php
/**
 * 暂不支持压缩
 * value限制为1G
 * $config['Redis'] = array('127.0.0.1', '6379');  //redis存储.
 * $oo = new muredis( oo::$config['Redis'], false);
 */
class muredis{
	private $oRedis = null; //连接对象
	private $aServer = array(); //地址配置
	private $persist = false; //是否长连接(当前不支持长连接)
	private $connect = false; //是否连接上
	private $connected = false; //是否已经连接过
	private $timeout = 3; //连接超时.秒为单位
	private $prefix = ''; //所有Key前缀
	
	const REDIS_STRING = Redis::REDIS_STRING; //字符串类型
	const REDIS_SET = Redis::REDIS_SET; //SET类型
	const REDIS_LIST = Redis::REDIS_LIST; //LIST类型
	const REDIS_ZSET = Redis::REDIS_ZSET;
	const REDIS_HASH = Redis::REDIS_HASH;
	const REDIS_NOT_FOUND = Redis::REDIS_NOT_FOUND;
	
	const MULTI = Redis::MULTI; //事务类型:保证原子性
	const PIPELINE = Redis::PIPELINE; //事务类型:不保证原子性仅批处理
	
	public function __construct( $aServer, $persist=false){
		$this->aServer = $aServer;
		$this->persist = $persist;
		if (!class_exists( 'Redis')) { //强制使用
			die( 'This Lib Requires The Redis Extention!');
		}
	}
	/**
	 * 连接.每个实例仅连接一次
	 * @return Boolean
	 */
	private function connect(){
		if ( ! $this->connected){
			$this->connected = true; //标志已经连接过一次
			try {
				$this->oRedis = new Redis();
				$this->connect = $this->persist ? $this->oRedis->pconnect($this->aServer[0], $this->aServer[1], $this->timeout) : $this->oRedis->connect($this->aServer[0], $this->aServer[1], $this->timeout); //TRUE on success, FALSE on error.
				//$this->oRedis->setOption(Redis::OPT_PREFIX, $this->prefix); //Key前缀
				$this->oRedis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY); //使用内建函数序列化.Redis::SERIALIZER_NONE不序列化.Redis::SERIALIZER_IGBINARY二进制序列化
			}catch (RedisException $e){ //连接失败,记录
				$this->errorlog('Connect', $e->getCode(), $e->getMessage());
			}
		}
		return $this->connect;
	}
	/**
	 * 设置.true成功false失败
	 * @param String $key
	 * @param Mixed $value
	 * @param Mixed $expire   过期时间
	 * @return Boolean
	 */
	public function set($key, $value){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->set( $key, $value);
	}
	/**
	 * 设置带过期时间的值
	 * @param String $key
	 * @param Mixed $value
	 * @param int $expire 过期时间.默认24小时
	 * @return Boolean
	 */
	public function setex($key, $value, $expire=86400){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->setex( $key, $expire, $value);
	}
	/**
	 * 添加.存在该Key则返回false.
	 * @param String $key
	 * @param Mixed $value
	 * @return Boolean
	 */
	public function setnx($key, $value){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->setnx( $key, $value);
	}
	/**
	 * 原子递增1.不存在该key则基数为0.注意因为serialize的关系不能在set方法的key上再执行此方法
	 * @param String $key
	 * @return false/int 返回最新的值
	 */
	public function incr( $key){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->incr( $key);
	}
	/**
	 * 原子递加指定的数.不存在该key则基数为0,注意$value可以为负数.返回的结果也可能是负数
	 * @param String $key
	 * @param int $value
	 * @return false/int 返回最新的值
	 */
	public function incrBy($key, $value = 0){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->incrBy( $key, (int)$value);
	}
	/**
	 * 原子递减1.不存在该key则基数为0.可以减成负数
	 * @param String $key
	 * @return false/int 返回最新的值
	 */
    public function decr( $key){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->decr( $key);
    }
    /**
	 * 原子递减指定的数.不存在该key则基数为0,注意$value可以是负数(负负得正就成递增了).可以减成负数
	 * @param String $key
	 * @param int $value
	 * @return false/int 返回最新的值
	 */
    public function decrBy($key, $value){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->decrBy( $key, (int)$value);
	}
	/**
	 * 获取.不存在则返回false
	 * @param String $key
	 * @return false/Mixed
	 */
	public function get( $key){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->get( $key);
	}
	/**
     * 先获取该key的值,然后以新值替换掉该key.该key不存在则添加同时返回false
     * @param String $key
     * @param Mixed $value
     * @return Mixed/false
     */
    public function getSet($key, $value){
    	if( ! $this->connect()){
			return false;
		}
		
		return $this->oRedis->getSet($key, $value);
    }
    /**
     * 从存储器中随机获取一个key
     * @return String
     */
    public function randomKey(){
    	if( ! $this->connect()){
			return '';
		}
		return $this->oRedis->randomKey();
    }
    /**
     * 选择数据库
     * @param int $dbindex 0-...
     * @return Boolean
     */
    public function select( $dbindex){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->select( $dbindex);
    }
    /**
     * 把某个key转移到另一个db中
     * @param String $key
     * @param int $dbindex 0-...
     * @return Boolean 当前db中没有该key或者...
     */
    public function move($key, $dbindex){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->move($key, $dbindex);
    }
    /**
     * 重命名某个Key.注意如果目的key存在将会被覆盖
     * @param String $srcKey
     * @param String $dstKey
     * @return Boolean 源key和目的key相同或者源key不存在...
     */
    public function renameKey($srcKey, $dstKey){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->renameKey($srcKey, $dstKey);
    }
    /**
     * 重命名某个Key.和renameKey不同: 如果目的key存在将不执行
     * @param String $srcKey
     * @param String $dstKey
     * @return Boolean 源key和目的key相同或者...
     */
    public function renameNx($srcKey, $dstKey){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->renameNx($srcKey, $dstKey);
    }
    /**
     * 设置某个key过期时间.只能设置一次
     * @param String $key
     * @param int $expire 过期秒数
     * @return Boolean
     */
    public function setTimeout($key, $expire){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->setTimeout($key, $expire);
    }
    /**
     * 设置某个key在特定的时间过期
     * @param String $key
     * @param int $timestamp 时间戳
     * @return Boolean
     */
    public function expireAt($key, $timestamp){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->expireAt($key, $timestamp);
    }
	/**
	 * 批量获取.注意: 如果某键不存在则对应的值为false
	 * @param Array $keys
	 * @return Array 原顺序返回 
	 */
	public function getMultiple( $keys){
		if( (! is_array( $keys)) || (! count( $keys)) || (! $this->connect())){
			return array();
		}
		return $this->oRedis->getMultiple( $keys);
	}
	/**
	 * List章节 无索引序列 把元素加入到队列左边(头部).如果不存在则创建一个队列.返回该队列当前元素个数/false
	 * 注意对值的匹配要考虑到serialize.array(1,2)和array(2,1)是不同的值
	 * @param String $key
	 * @param Mixed $value
	 * @return false/Int. 如果连接不上或者该key已经存在且不是一个队列
	 */
	public function lPush($key, $value){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->lPush($key, $value);
	}
	/**
	 * 往一个已存在的队列左边加元素.返回0(如果队列不存在)或最新的元素个数
	 * @param String $key
	 * @param Mixed $value
	 * @return false/Int. 如果连接不上或者该key不存在或者不是一个队列
	 */
	public function lPushx($key, $value){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->lPushx($key, $value);
	}
	/**
	 * 把元素加入到队列右边(尾部).如果不存在则创建一个队列.返回该队列当前元素个数/false
	 * @param String $key
	 * @param Mixed $value
	 * @return false/int 如果连接不上或者该key已经存在且不是一个队列
	 */
	public function rPush($key, $value){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->rPush($key, $value);
	}
	/**
	 * 往一个已存在的队列右边加元素.返回0(如果队列不存在)或最新的元素个数
	 * @param String $key
	 * @param Mixed $value
	 * @return false/Int. 如果连接不上或者该key不存在或者不是一个队列
	 */
	public function rPushx($key, $value){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->rPushx($key, $value);
	}
	/**
	 * 弹出(返回并清除)队列头部(最左边)元素
	 * @param String $key
	 * @return Mixed/false
	 */
	public function lPop( $key){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->lPop( $key);
	}
	/**
	 * 弹出队列尾部(最右边)元素
	 * @param String $key
	 * @return Mixed/false
	 */
	public function rPop( $key){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->rPop( $key);
	}
	/**
	 * 情况形如lPop方法.只要其中一个列表存在且有值则立即返回.否则等待对应的秒数直到有相应的列表加入为止(慎用)
	 * 大致用途就是:监听N个列表,只要其中有一个列表有数据就立即返回该列表左边的数据
	 * @param String/Array $keys
	 * @param int $timeout
	 * @return Array array('列表键名', '列表最左边的值')
	 */
	public function blPop($keys, $timeout){
		if( ! $this->connect()){
			return array();
		}
		try{
			$value = $this->oRedis->blPop( $keys, $timeout);
		}catch (Exception $e){
			$value = array();
		}
		return is_array( $value) ? $value : array();
	}
	/**
	 * 情况形如rPop方法.这里指定一个延时只要其中一个列表存在且有值则立即返回.否则等待对应的秒数直到有相应的列表加入为止(慎用)
	 * 参考:blPop
	 * @param String/Array $keys
	 * @param int $timeout
	 * @return Array array('列表键名', '列表最右边的值')
	 */
	public function brPop($keys, $timeout){
		if( ! $this->connect()){
			return array();
		}
		try{
			$value = $this->oRedis->brPop( $keys, $timeout);
		}catch (Exception $e){
			$value = array();
		}
		return is_array( $value) ? $value : array();
	}
	/**
	 * 返回队列里的元素个数.不存在则为0.不是队列则为false
	 * @param String $key
	 * @return int/false
	 */
	public function lSize( $key){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->lSize( $key);
	}
	/**
	 * 控制队列只保存某部分,即:删除队列的其余部分
	 * @param String $key
	 * @param int $start
	 * @param int $end
	 * @return Boolean 不是一个队列或者不存在...
	 */
	public function lTrim($key, $start, $end){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->lTrim($key, $start, $end);
	}
	/**
	 * 获取队列的某个元素
	 * @param String $key
	 * @param int $index 0第一个1第二个...-1最后一个-2倒数第二个
	 * @return Mixed/false 没有则为空字符串或者false
	 */
	public function lGet($key, $index){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->lGet($key, $index);
	}
	/**
	 * 修改队列中指定$index的元素
	 * @param String $key
	 * @param int $index
	 * @param Mixed $value
	 * @return Boolean 该$index不存在或者该key不是一个队列为false
	 */
	public function lSet($key, $index, $value){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->lSet($key, $index, $value);
	}
	/**
	 * 取出队列的某一段.不存在则返回空数组
	 * @param String $key
	 * @param String $start 相当于$index:第一个为0...最后一个为-1
	 * @param String $end
	 * @return Array
	 */
	public function lGetRange($key, $start, $end){
		if( ! $this->connect()){
			return array();
		}
		return $this->oRedis->lGetRange($key, $start, $end);
	}
	/**
	 * 删掉队列中的某些值
	 * @param String $key
	 * @param Mixed $value 要删除的值.可以是复杂数据,但要考虑serialize
	 * @param int $count 去掉的个数,>0从左到右去除;0为去掉所有;<0从右到左去除
	 * @return Boolean/int 删掉的值
	 */
	public function lRemove($key, $value, $count=0){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->lRemove($key, $value, $count);
	}
	/**
	 * 在队列的某个特定值前/后面插入元素(如果有多个相同特定值则确定为左边起第一个)
	 * @param String $key
	 * @param int $direct 0往后面插入1往前面插入
	 * @param Mixed $pivot
	 * @param Mixed $value
	 * @return false/int 列表当前元素个数或者-1表示元素不存在或不是列表
	 */
	public function lInsert($key, $direct, $pivot, $value){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->lInsert($key, $direct?Redis::BEFORE:Redis::AFTER, $pivot, $value);
	}
	/**
	 * 给该key添加一个唯一值.相当于制作一个没有重复值的数组
	 * @param String $key
	 * @param Mixed $value
	 * @return Boolean 该值存在或者该键不是一个集合
	 */
	 public function sAdd($key, $value){
	 	if( ! $this->connect()){
			return false;
		}
	 	return $this->oRedis->sAdd($key, $value);
	 }
	/**
	 * 获取某key对象个数
	 * @param String $key 
	 * @return int 不存在则为0
	 */
    public function sSize( $key){
    	if( ! $this->connect()){
			return 0;
		}
    	return $this->oRedis->sSize( $key);
    }
    /**
     * 随机弹出一个值.
     * @param String $key
     * @return Mixed/false
     */
    public function sPop( $key){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->sPop( $key);
    }
    /**
     * 随机取出一个值.与sPop不同,它不删除值
     * @param String $key
     * @return Mixed/false
     */
    public function sRandMember( $key){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->sRandMember( $key);
    }
    /**
     * 返回所给key列表都有的那些值,相当于求交集
     * $keys Array 
     * @return Array 如果某集合不存在或者某键非集合返回空数组
     */
    public function sInter( $keys){
    	if( ! $this->connect()){
			return array();
		}
		return is_array( $result = $this->oRedis->sInter( $keys)) ? $result : array();
    }
    /**
     * 把所给$keys列表都有的那些值存到$key指定的数组中.相当于执行sInter操作然后再存到另一个数组中
     * $key String 要存到的数组key 注意该数组如果存在会被覆盖
     * $keys Array 
     * @return false/int 新集合的元素个数或者某key不存在为false
     */
    public function sInterStore($key, $keys){
    	if( ! $this->connect()){
			return 0;
		}
		return call_user_func_array(array($this->oRedis,'sInterStore'), array_merge(array($key), $keys));
    }
    /**
     * 返回所给key列表所有的值,相当于求并集
     * @param Array $keys
     * @return Array
     */
    public function sUnion( $keys){
    	if( ! $this->connect()){
			return array();
		}
		return is_array( $result = $this->oRedis->sUnion( $keys)) ? $result : array();
    }
    /**
     * 把所给key列表所有的值存储到另一个数组
     * @param String $key
     * @param Array $keys
     * @return int/false 并集(新集合)的数量
     */
    public function sUnionStore($key, $keys){
    	if( ! $this->connect()){
			return 0;
		}
		return call_user_func_array(array($this->oRedis,'sUnionStore'), array_merge(array($key), (array)$keys));
    }
    /**
     * 返回所给key列表想减后的集合,相当于求差集
     * @param Array $keys 注意顺序,前面的减后面的
     * @return Array
     */
    public function sDiff( $keys){
    	if( ! $this->connect()){
			return array();
		}
		return is_array($result = $this->oRedis->sDiff( $keys)) ? $result : array();
    }
    /**
     * 把所给key列表差集存储到另一个数组
     * @param String $key 要存储的目的数组
     * @param Array $keys
     * @return int/false 差集的数量
     */
    public function sDiffStore($key, $keys){
    	if( ! $this->connect()){
			return 0;
		}
		return call_user_func_array(array($this->oRedis,'sDiffStore'), array_merge(array($key), (array)$keys));
    }
    /**
     * 删除该集合中对应的值 
     * @param String $key
     * @param String $value
	 * @return Boolean 没有该值返回false
	 */
    public function sRemove($key, $value){
    	if( ! $this->connect()){
			return false;
		}
    	return $this->oRedis->sRemove($key, $value);
    }
    /**
     * 把某个值从一个key转移到另一个key
     * @param String $srcKey
     * @param String $dstKey
     * @param Mixed $value
     * @return Boolean 源key不存在/目的key不存在/源值不存在->false
     */
    public function sMove($srcKey, $dstKey, $value){
    	if( ! $this->connect()){
			return false;
		}
    	return $this->oRedis->sMove($srcKey, $dstKey, $value);
    }
    /**
     * 判断该数组中是否有对应的值
     * @param String $key
     * @param String $value
	 * @return Boolean 集合不存在或者值不存在->false
	 */
    public function sContains($key, $value){
    	if( ! $this->connect()){
			return false;
		}
    	return $this->oRedis->sContains($key, $value);
    }
    /**
     * 获取某数组所有值
     * @param String $key
	 * @return Array 顺序是不固定的
	 */
    public function sMembers( $key){
    	if( ! $this->connect()){
			return array();
		}
    	return is_array($result = $this->oRedis->sMembers( $key)) ? $result : array();
    }
    /**
     * 有序队列.添加一个指定了索引值的元素(默认索引值为0).元素在集合中存在则更新对应$score
     * @param String $key
     * @param int $score 索引值
     * @param Mixed $value
     * @return false/int 成功加入的个数
     */
    public function zAdd($key, $score, $value){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->zAdd($key, $score, $value);
    }
    /**
     * 获取指定单元的数据
     * @param String $key
     * @param int $start
     * @param int $end
     * @param Boolean $withscores 是否返回索引值.如果是则返回[值=>索引]的数组.如果要返回索引值,存入的时候$value必须是标量
     * @return Array
     */
    public function zRange($key, $start, $end, $withscores=false){
    	if( ! $this->connect()){
			return array();
		}
		return is_array($result = $this->oRedis->zRange($key, $start, $end, $withscores)) ? $result : array();
    }
    /**
     * 获取指定单元的反序排列的数据
     * @param String $key
     * @param int $start
     * @param int $end
     * @param Boolean $withscores 是否返回索引值.如果是则返回值=>索引的数组
     * @return Array
     */
    public function zRevRange($key, $start, $end, $withscores=false){
    	if( ! $this->connect()){
			return array();
		}
		return is_array($result = $this->oRedis->zRevRange($key, $start, $end, $withscores)) ? $result : array();
	}
	/**
	 * 获取指定条件下的集合
	 * @param unknown_type $key
	 * @param unknown_type $start 最小索引值
	 * @param unknown_type $end 最大索引值
	 * @param Array $options array('withscores'=>true,limit=>array($offset, $count))
	 * @return Array
	 */
    public function zRangeByScore($key, $start, $end, $options){
    	if( ! $this->connect()){
			return array();
		}
		return is_array($result = $this->oRedis->zRangeByScore($key, $start, $end, $options)) ? $result : array();
    }
    /**
	 * 获取指定条件下的反序排列集合
	 * @param unknown_type $key
	 * @param unknown_type $start 最小索引值
	 * @param unknown_type $end 最大索引值
	 * @param Array $options array('withscores'=>true,limit=>array($offset, $count))
	 * @return Array
	 */
    public function zRevRangeByScore($key, $start, $end, $options){
    	if( ! $this->connect()){
			return array();
		}
		return is_array($result = $this->oRedis->zRevRangeByScore($key, $start, $end, $options)) ? $result : array();
	}
	/**
	 * 返回指定索引值区域内的元素个数
	 * @param unknown_type $key
	 * @param unknown_type $start 最小索引值
	 * @param unknown_type $end 最大索引值
	 * @return int
	 */
    public function zCount($key, $start, $end){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->zCount($key, $start, $end);
    }
    /**
     * 删除指定索引值区域内的所有元素
     * @param unknown_type $key
     * @param unknown_type $start 最小索引值
     * @param unknown_type $end 最大索引值
     * @return int
     */
    public function zDeleteRangeByScore($key, $start, $end){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->zDeleteRangeByScore($key, $start, $end);
    }
    /**
     * 删除指定排序范围内的所有元素
     * @param int $start 排序起始值
     * @param int $end
     * @return int
     */
    public function zDeleteRangeByRank($key, $start, $end){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->zDeleteRangeByRank($key, $start, $end);
	}
	/**
	 * 获取集合元素个数
	 * @param unknown_type $key
	 * @return int
	 */
    public function zSize( $key){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->zSize( $key);
    }
    /**
     * 获取某集合中某元素的索引值
     * @param unknown_type $key
     * @param unknown_type $member
     * @return int/false 没有该值为false
     */
    public function zScore($key, $member){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->zScore( $key, $member);
    }
    /**
     * 获取指定元素的排序值
     * @param unknown_type $key
     * @param unknown_type $member
     * @return int/false 不存在为false
     */
    public function zRank($key, $member){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->zRank( $key, $member);
    }
    /**
     * 获取指定元素的反向排序值
     * @param unknown_type $key
     * @param unknown_type $member
     * @return int/false 不存在为false
     */
    public function zRevRank($key, $member){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->zRevRank( $key, $member);
    }
    /**
     * 给指定的元素累加索引值.元素不存在则会被添加
     * @param unknown_type $key
     * @param unknown_type $value 要加的索引值量 
     * @param unknown_type $member
     * @return int 该元素最新的索引值
     */
    public function zIncrBy($key, $value, $member){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->zIncrBy( $key, $value, $member);
    }
    /**
     * 得到一个并集存储到新的集合中
     * @param unknown_type $keyOutput 新集合名
     * @param Array $arrayZSetKeys 需要合并的集合 array('key1', 'key2')
     * @param Array $arrayWeights 对应集合中索引值要放大的倍数  array(5, 2)表示第一个集合中的索引值*5,第二个集合中的索引值*2,然后再合并
     * @param String $aggregateFunction 如果有相同元素,则取索引值的方法: "SUM", "MIN", "MAX"
     * @return int 新集合的元素个数
     */
    public function zUnion($keyOutput, $arrayZSetKeys, $arrayWeights, $aggregateFunction){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->zUnion( $keyOutput, $arrayZSetKeys, $arrayWeights, $aggregateFunction);
    }
    /**
     * 得到一个交集存储到新的集合中
     * @param unknown_type $keyOutput 新集合名
     * @param Array $arrayZSetKeys 需要合并的集合 array('key1', 'key2')
     * @param Array $arrayWeights 对应集合中索引值要放大的倍数  array(5, 2)表示第一个集合中的索引值*5,第二个集合中的索引值*2,然后再合并
     * @param String $aggregateFunction 如果有相同元素,则取索引值的方法: "SUM", "MIN", "MAX"
     * @return int 新集合的元素个数
     */
    public function zInter($keyOutput, $arrayZSetKeys, $arrayWeights, $aggregateFunction){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->zInter( $keyOutput, $arrayZSetKeys, $arrayWeights, $aggregateFunction);
    }
    /**
     * 
     * 哈希单个存储 
     * @param unknown_type $key  key唯一
     * @param unknown_type $item  其中一个项
     * @param unknown_type $value  该项的值
     */
    public function hSet($key, $item, $value){
    	if( ! $this->connect()){
			return false;
		}
		$ret = true;
		if ($this->oRedis->hSet($key, $item, $value) === false) {
			$ret = false;
		}
		return $ret;
    }
    /**
     * 
     * 获取哈希列表里的某项
     * @param unknown_type $key唯一
     * @param unknown_type $item项
     */
    public function hGet($key, $item){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->hGet($key, $item);
    }
    public function hLen(){
    }
    public function hDel($key){
    }
    public function hKeys(){
    }
    public function hVals(){
    }
    /**
     * 
     * 获取整哈希
     * @param unknown_type $key
     */
    public function hGetAll( $key){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->hGetAll($key);
    }
    public function hExists(){
    }
    public function hIncrBy(){
    }
    public function hMget(){
    }
    /**
     * 
     * 同时设置多个项 
     * @param unknown_type $key
     * @param unknown_type $aItemValue array('name' => 'zp','sex'=>'man')
     */
    public function hMset($key, $aItemValue){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->hMset($key, $aItemValue);
    }
    /**
     * 往值后面追加字符串.不存在则创建
     * @param String $key
     * @param String $value
     * @return int 最新值的长度
     */
	public function append($key, $value){
		if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->append( $key, $value);
	}
	/**
	 * 获取字符串的一部分.此方法仅针对append加的字符串有意义
	 * @param int $start
	 * @param int $end
	 * @return String 不存在则为''
	 */
	public function getRange($key, $start, $end){
		if( ! $this->connect()){
			return '';
		}
		return $this->oRedis->getRange($key, $start, $end);
	}
	/**
	 * 从$offset开始替换后面的字符串.$offset从0开始
	 * @param unknown_type $key
	 * @param unknown_type $offset
	 * @param unknown_type $value
	 * @return int 字符串最新的长度
	 */
	public function setRange($key, $offset, $value){
		if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->setRange($key, $offset, $value);
	}
	/**
	 * 返回值的长度
	 * @param unknown_type $key
	 * @return int
	 */
	public function strlen( $key){
		if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->strlen( $key);
	}
	/**
	 * 返回排序后的数据或者存储的元素个数
	 * $options = array('by' => 'some_pattern_*',
	    'limit' => array(0, 1),
	    'get' => 'some_other_pattern_*' or an array of patterns,
	    'sort' => 'asc' or 'desc',
	    'alpha' => TRUE,
	    'store' => 'external-key')
	 *@return Array/int
	 */
	public function sort($key, $options){
		if( ! $this->connect()){
			return array();
		}
		return $this->oRedis->sort( $key, $options);
	}
	public function sortAsc(){
	}
	public function sortAscAlpha(){
	}
	public function sortDesc(){
	}
	public function sortDescAlpha(){
	}
	/**
	 * 移除某key的过期时间使得永不过期
	 * @return Boolean 没有设置过期时间或者没有该Key
	 */
	public function persist( $key){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->persist( $key);
	}
	/**
	 * 启动后台回写至硬盘
	 * @return Boolean
	 */
	public function bgrewriteaof(){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->bgrewriteaof();
	}
	/**
	 * 转换从DB角色
	 * @param String $host 从DB地址
	 * @param String $port 从DB端口
	 * @return Boolean
	 */
	public function slaveof($host, $port){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->slaveof($host, $port);
	}
	/**
	 * 开始一个事务处理
	 * @param int $mode 事务类型1保证原子性2不保证
	 *$ret = $redis->multi()
				    ->set('key1', 'val1')
				    ->get('key1')
				    ->set('key2', 'val2')
				    ->get('key2')
				    ->exec();
				$ret == array(
				    0 => TRUE,
				    1 => 'val1',
				    2 => TRUE,
				    3 => 'val2');

	 */
	public function multi( $mode=1){
		if( ! $this->connect()){
			return new stdClass();
		}
		return $this->oRedis->multi($mode==1 ? self::MULTI : self::PIPELINE);
	}
	/**
	 * 回滚事务
	 * @return Boolean
	 */
	public function discard(){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->discard();
	}
	/**
	 * 提交事务
	 * @return Mixed 返回事务中各方法的返回值.如果采用了watch锁而值被改或者没有任何执行,则强制返回空数组
	 */
	public function exec(){
		if( ! $this->connect()){
			return array();
		}
		return is_array( $result = $this->oRedis->exec()) ? $result : array();
	}
	public function pipeline(){
	}
	/**
	 * 被动锁定某个/某些key.用于事务处理中:如果被锁定的key在提交事务前被改了则事务提交失败
	 * @return Boolean
	 */
	public function watch( $keys){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->watch($keys);
	}
	/**
	 * 解锁所有被锁key
	 * @return Boolean
	 */
	public function unwatch(){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->unwatch();
	}
	/**
	 * 未确定
	 */
	public function publish(){
	}
	/**
	 * 未确定
	 */
	public function subscribe(){
	}
	public function unsubscribe(){
	}
	public function open(){
	}
	public function lLen(){
	}
	public function getBit($key, $offset){
	}
	public function setBit($key, $offset, $value){
	}
	public function hSetNx(){
	}
	/**
	 * 获取环境
	 * @param String $option
	 * @return Mixed
	 */
	public function getOption( $option){
		if( ! $this->connect()){
			return '';
		}
		return $this->oRedis->getOption($option);
	}
	public function setOption(){
	}
	public function popen(){
	}
    /**
     * 删除对应的值
     * @param String $key
     * @param Mixed $value
     * @return Boolean/int 删除元素的个数(0/1)
     */
    public function zDelete($key, $value){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->zDelete($key, $value);
    }
    /**
     * 返回服务器信息
     * @return Array
     */
    public function info(){
    	if( ! $this->connect()){
			return array();
		}
		return $this->oRedis->info();
    }
    /**
     * 返回某key剩余的时间.单位是秒
     * @param String $key
     * @return int/false -1为没有设置过期时间
     */
    public function ttl( $key){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->ttl( $key);
    }
    /**
     * 批量设置
     * @param Array $pairs 索引数组,索引为key,值为...
     * @return Boolean
     */
    public function mset( $pairs){
    	if( (! $this->connect()) || ( !is_array( $pairs ) ) ){
			return false;
		}
		return $this->oRedis->mset( $pairs);
    }
    /**
     * 批量添加.如果某key存在则为false并且其他key也不会被保存
     * @param Array $pairs 索引数组,索引为key,值为...
     * @return Boolean
     */
	public function msetnx( $pairs){
		if( (! $this->connect()) || ( !is_array( $pairs ) ) ){
			return false;
		}
		return $this->oRedis->msetnx( $pairs);
	}
	/**
	 * 批量获取数据
	 * @param Array $pairs 数组，其value为KEY组合
	 * @return Mixed 如果成功，返回与KEY对应位置的VALUE组成的数组
	 */
	public function mget( $pairs){
		if( (! $this->connect()) || ( !is_array( $pairs ) ) ){
			return array();
		}
		return is_array( $result = $this->oRedis->mget( $pairs )) ? $result : array();
	}
    /**
     * 从源队列尾部弹出一项加到目的队列头部.并且返回该项
     * @param String $srcKey
     * @param String $dstKey
     * @return Mixed/false
     */
    public function rpoplpush($srcKey, $dstKey){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->rpoplpush($srcKey, $dstKey);
    }
	/**
	 * 判断key是否存在
	 * @param String $key
	 * @return Boolean
	 */
	public function exists( $key){
		if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->exists( $key); //BOOL: If the key exists, return TRUE, otherwise return FALSE.
	}
    /**
     * 获取符合匹配的key.仅支持正则中的*通配符.如->getKeys('*')
     * @param String $pattern
     * @return Array
     */
    public function getKeys( $pattern){
    	if( ! $this->connect()){
			return array();
		}
		return is_array($result = $this->oRedis->getKeys( $pattern)) ? $result : array();
    }
    /**
     * 删除某key/某些key
     * @param String/Array $keys
     * @return int 被删的个数
     */
    public function delete( $keys){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->delete( $keys);
    }
    /**
     * 返回当前key数量
     * @return int
     */
    public function dbSize(){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->dbSize();
    }
    /**
     * 密码验证.密码明文传输
     * @param String $password
     * @return Boolean
     */
    public function auth( $password){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->auth( $password);
    }
    /**
     * 强制把内存中的数据写回硬盘
     * @return Boolean 如果正在回写则返回false
     */
    public function save(){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->save();
    }
    /**
     * 执行一个后台任务: 强制把内存中的数据写回硬盘
     * @return Boolean 如果正在回写则返回false
     */
    public function bgSave(){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->bgSave();
    }
    /**
     * 返回最后一次写回硬盘的时间
     * @return int 时间戳
     */
    public function lastSave(){
    	if( ! $this->connect()){
			return 0;
		}
		return $this->oRedis->lastSave();	
    }
    /**
     * 返回某key的数据类型
     * @param String $key
     * @return int 存在于: REDIS_* 中
     */
    public function type( $key){
    	if( ! $this->connect()){
			return self::REDIS_NOT_FOUND;
		}
		return $this->oRedis->type( $key);
    }
    /**
     * 清空当前数据库.谨慎执行
     * @return Boolean
     */
    public function flushDB(){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->flushDB();
    }
    /**
     * 清空所有数据库.谨慎执行
     * @return Boolean
     */
    public function flushAll(){
    	if( ! $this->connect()){
			return false;
		}
		return $this->oRedis->flushAll();
    }
    /**
	 * 获取连接信息
	 * @return String +PONG
	 */
	public function ping(){
		if(! $this->connect()){
			return false;
		}
		return $this->oRedis->ping();
	}
	/**
	 * 关闭非持久连接
	 */
	public function close(){
		$this->connect && $this->oRedis->close() && ( $this->connected = false);
	}
	private function errorlog($keys, $code, $msg){
		$error = date('H:i:s').":\n".$code.";\nkeys:".var_export($keys, true).";\nmsg:{$msg}\n";
		
		$file = dirname(__FILE__) . '/../data/muredis.txt';
		@file_put_contents($file, "{$error}\n", @filesize($file)<512*1024 ? FILE_APPEND : null);
		die('Redis Invalid!!!');
	}
}