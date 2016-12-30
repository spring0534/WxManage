/**
 * js截取字符串，中英文都能用
 * @param str: 需要截取的字符串
 * @param len: 需要截取的长度
 */
function cutstr(str,len){
   	var str_length = 0,
   		str_len = 0,
  		str_cut = new String(),
  		a = '';
  	str_len = str.length;
  	for(var i = 0; i < str_len; i++){
        a = str.charAt(i);
        str_length++;
        if(escape(a).length > 4){
         	//中文字符的长度经编码之后大于4
         	str_length++;
     	}
     	str_cut = str_cut.concat(a);
     	if(str_length>=len){
         	return str_cut;
     	}
    }
    //如果给定字符串小于指定长度，则返回源字符串；
    if(str_length <= len){
     	return str;
    }
}
/**
  *返回字符串长度 一个中文字符算2个长度
  */
function getStrLength(str){
  var str_length = 0,
      str_len = 0,
      a = '';
    str_len = str.length;
    for(var i = 0; i < str_len; i++){
        a = str.charAt(i);
        str_length++;
        if(escape(a).length > 4){
            //中文字符的长度经编码之后大于4
            str_length++;
        }
    }
    return str_length;
}

/**
 * js map对象
 */
function Map() {
    var struct = function(key, value) {
        this.key = key;
        this.value = value;
    }
    var put = function(key, value){
        for (var i = 0; i < this.arr.length; i++) {
            if ( this.arr[i].key === key ) {
                this.arr[i].value = value;
                return;
            }
        }
        this.arr[this.arr.length] = new struct(key, value);
    }
    var get = function(key) {
        for (var i = 0; i < this.arr.length; i++) {
            if ( this.arr[i].key === key ) {
                return this.arr[i].value;
            }
        }
        return null;
    }
    var remove = function(key) {
        var v;
        for (var i = 0; i < this.arr.length; i++) {
            v = this.arr.pop();
            if ( v.key === key ) {
                    continue;
            }
            this.arr.unshift(v);
        }
    }
    var size = function() {
        return this.arr.length;
    }
    var isEmpty = function() {
        return this.arr.length <= 0;
    }
    this.arr = new Array();
    this.get = get;
    this.put = put;
    this.remove = remove;
    this.size = size;
    this.isEmpty = isEmpty;
}

