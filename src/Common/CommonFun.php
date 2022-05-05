<?php
namespace Sang\Basicfun\Common;
use Illuminate\Support\Facades\Storage;
use Cache;
class CommonFun{
	/**
   * 上传图片
   * @param $request
   * @return path
   */
  public static function imgload($dirname,$file)
  {
    try {
      //文件是否上传成功               
      if($file->isValid()){   //判断文件是否上传成功
        $originalName = $file->getClientOriginalName(); //源文件名
        $ext = $file->getClientOriginalExtension();    //文件拓展名
        $type = $file->getClientMimeType(); //文件类型
        $realPath = $file->getRealPath();   //临时文件的绝对路径
        $fileName =$dirname.'/'.date('m').'/'.date('d').'/'.date('His').rand(0000,9999).'.'.$ext;  //新文件名                  
        $bool = Storage::disk('public')->put($fileName,file_get_contents($realPath));   //传成功返回bool值
      }    
      if($fileName){
      	return ['Code'=>0,'data'=>'storage/'.$fileName];
      }else{
        return ['Code'=>1,'data'=>''];
      }
    } catch (\Exception $exception) {
      return ['Code'=>1,'data'=>''];
    }
  }
  /**
   * 保存base64数据为图片
   * @param $request
   * @return path
   */
  public static function getBaseAndSaveImg($dirname,$imgdata)
  {
    try {
      $img = str_replace('data:image/png;base64,','',$imgdata);
      $img = str_replace('data:image/jpeg;base64,','',$imgdata);
      $img = str_replace(' ', '+', $img);
      $data = base64_decode($img);
      $path=$dirname.'/'.date('m').'/'.date('d').'/'.date('His').rand(0000,9999).'.png';  //新图片路径
      //$r=file_get_contents(storage_path('app/public/').$path,$data);
      $r =Storage::disk('public')->put($path,$data);// 生成图片
      if (!$r) {
          return ['Code'=>1,'data'=>''];
      }else{
          return ['Code'=>0,'data'=>'storage/'.$fileName];
      }
    } catch (\Exception $exception) {
      return ['Code'=>1,'data'=>''];
    }
  }
  /**
   * 价格转换为大写
   * @param account
   * @return string
   */
  public static function num_to_rmb($num) {
    $isminus='';
    if((float)$num<0){
      $isminus='负';
    }
    $num=abs((float)$num);
    $c1 = "零壹贰叁肆伍陆柒捌玖";
    $c2 = "分角元拾佰仟万拾佰仟亿";
    //精确到分后面就不要了，所以只留两个小数位
    $num = round($num, 2);
    //将数字转化为整数
    $num = $num * 100;
    if (strlen($num) > 10) {
        return "金额太大，请检查";
    }
    $i = 0;
    $c = "";
    while (1) {
        if ($i == 0) {
        //获取最后一位数字
            $n = substr($num, strlen($num) - 1, 1);
        } else {
            $n = $num % 10;
        }
        //每次将最后一位数字转化为中文
        $p1 = substr($c1, 3 * $n, 3);
        $p2 = substr($c2, 3 * $i, 3);
        if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
            $c = $p1 . $p2 . $c;
        } else {
            $c = $p1 . $c;
        }
        $i = $i + 1;
        //去掉数字最后一位了
        $num = $num / 10;
        $num = (int) $num;
        //结束循环
        if ($num == 0) {
            break;
        }
    }
    $j = 0;
    $slen = strlen($c);
    while ($j < $slen) {
        //utf8一个汉字相当3个字符
        $m = substr($c, $j, 6);
        //处理数字中很多0的情况,每次循环去掉一个汉字“零”
        if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
            $left = substr($c, 0, $j);
            $right = substr($c, $j + 3);
            $c = $left . $right;
            $j = $j - 3;
            $slen = $slen - 3;
        }
        $j = $j + 3;
    }
    //这个是为了去掉类似23.0中最后一个“零”字
    if (substr($c, strlen($c) - 3, 3) == '零') {
        $c = substr($c, 0, strlen($c) - 3);
    }
    $c=$isminus.$c;
    //将处理的汉字加上“整”
    if (empty($c)) {
        return "零元整";
    } else {
        return $c . "整";
    }

  }
  /**
   * 获取随机数
   * @param account
   * @return string
   */
  public static function getrandom($len, $chars=null)
  {
    if (is_null($chars)){
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    } 
    mt_srand(10000000*(double)microtime());
    for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
        $str .= $chars[mt_rand(0, $lc)]; 
    }
    return $str;
  }
  /**
   * 加密字符串
   * @param string $strContent 待加密的字符串内容
   * @param string $key 加密key
   * @return string 返回加密后的字符串，失败返回false
   */
  public static function encrypt($strContent,$key = config('KEY'),$iv =config('IV')){
      $strEncrypted = openssl_encrypt($strContent,"AES-128-CBC", $key,OPENSSL_RAW_DATA, $iv);
      return base64_encode($strEncrypted);
  }

  /**
   * 解密字符串
   * @param string $strEncryptCode加密后的字符串
   * @param string $key 加密key
   * @return string 返回解密后的字符串，失败返回false
   */
  public static function decrypt($strEncryptCode,$key = config('KEY'),$iv =config('IV')){
      $strEncrypted = base64_decode($strEncryptCode);
      return openssl_decrypt($strEncrypted,"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv);
  }
}
